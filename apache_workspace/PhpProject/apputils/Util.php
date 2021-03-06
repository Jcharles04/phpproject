<?php
namespace apputils;

use \DateTime;

//include './__app.php';

class Util
{

    private static $APP_ROOT;

    private static $APP_URL;
    
    /**
     * Static initialization. Called on include, not meant to be called any other time
     */
    public static function __init__()
    {
        date_default_timezone_set('Europe/Paris');
        self::$APP_ROOT = realpath(__APPDIR__);
        $droot = realpath($_SERVER['DOCUMENT_ROOT']);
        $drl = strlen($droot);
        $arl = strlen(self::$APP_ROOT);
        if ($arl > $drl && substr(self::$APP_ROOT, 0, $drl) === $droot) {
            $url = substr(self::$APP_ROOT, $drl);
            $url = preg_replace('/\\\\/', '/', $url);
            if (strlen($url) < 1) {
                $url = '/';
            }
            if (substr($url, 0, 1) != '/') {
                $url = '/' . $url;
            }
            if (substr($url, strlen($url) - 1, 1) != '/') {
                $url = $url . '/';
            }
            self::$APP_URL = $url;
        } else {
            self::$APP_URL = '/';
        }
    }

    /**
     * Get application root (real path without trailing /)
     * @return string - Application real filesystem path
     */
    public static function APP_ROOT()
    {
        return self::$APP_ROOT;
    }

    /**
     * Make an application-relative URL server-relative : insert application directory at front
     * <p>Several parts can be passed and they will be made into a sound path with / separating each part
     * and double-slashes inbetween eliminated.</p>
     * <p>Always has a leading / and preserves trailing / if passed in last argument.</p>
     * <p>If arguments are empty and application path is document root, returns a single /.</p>
     * @param string ...$relative - application-relative path parts 
     * @return string - server-relative URL
     */
    public static function APP_URL(...$relative)
    {
        $url = Util::encodeUrlPath(self::$APP_URL);
        $relCount = count($relative);
        for ($i = 0; $i < $relCount; $i++) {
            if($relative[$i] == null || !strlen($relative[$i])){
                $relative[$i] = '';
            }
            if (substr($url, strlen($url) - 1, 1) != '/') {
                $url .= '/';
            }
            if ($i+1 == $relCount) {
                $url .= Util::encodeUrlPath(preg_replace('/^\/+/', '', $relative[$i]));
            } else {
                $url .= Util::encodeUrlPath(preg_replace('/^\/+/', '', $relative[$i]), false);
            }
        }
            
//         foreach ($relative as $rel) {
//             if ($rel == null || !strlen($rel)) {
//                 $rel = '';
//             }
//             if (substr($url, strlen($url) - 1, 1) != '/') {
//                 $url .= '/';
//             }
//             $url .= Util::encodeUrlPath(preg_replace('/^\/+/', '', $rel));
//         }
        return $url;
    }
    


    /**
     * encode path as url, ignoring slashes (/)
     * @param string $path
     * @return string
     */
    public static function encodeUrlPath($path, $searchAnchorQuery = true) {
        if ($searchAnchorQuery) {
            $pos = strrpos($path, '#');
            if ($pos !== false){
                $anchor = substr($path, $pos+1);
                $path = substr($path, 0, $pos);
            }
            $pos = strrpos($path, '?');
            if ($pos !== false){
                $query = substr($path, $pos+1);
                $path = substr($path, 0, $pos);
            }
        }
        $query = null;
        $anchor = null;
        $path = implode('/', array_map('rawurlencode', explode('/', $path)));
        if($query) {
            $path .= '?' . $query;
        }
        if($anchor) {
            $path .= '#' . $anchor;
        }
        return $path;
    }
    
    /**
     * Computes server-relative URL just like <code>Util::APP_URL</code>, but also appends to filename
     * the last modification time of pointed file (if it exists) in format: <code>-@YYYYMMddHHmmss@-</code>.
     * Combined with URL-rewriting, this is meant to prevent clients from using cached versions of static files
     * if they have been modified by changing in-page links to these files accordingly.
     * @param string ...$relative - application-relative path parts 
     * @return string - server-relative URL with file name containing last modification date
     */
    public static function CACHE_URL(...$relative)
    {
        //Call APP_URL(...$relative)
        $url = call_user_func_array('self::APP_URL', func_get_args());
        //Absolute file path
        $pth = $_SERVER['DOCUMENT_ROOT'] . $url;
        //Get last modification date in format 'YYYYMMDDHHmmss'
        $ftime = @filemtime($pth);
        if ($ftime === false) {
            error_log(error_get_last()['message']);
            return $url;
        }
        $time = new DateTime('now');
        $time->setTimestamp($ftime);
        $time = $time->format('YmdHis');
        return Util::setRequestParameter($url, 'nocache-version', $time);
        // //Insert timestamp into file name with syntax -@YYYYMMDDHHmmss@-
        // $add = preg_replace('/(\w)(\.[^\/]*)?$/', '$1-@' . $time . '@-$2', $url);
        // if ($add === null) {
        //     error_log(print_r(error_get_last(), TRUE));
        //     return $url;
        // }
        // return $add;
    }
    
    /**
     * Get current page URL including protocol, server, port, path and query
     * @return string
     */
    public static function CURRENT_URL() {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
    
    /**
     * Redirect from HTTP response header
     * @param string $destination
     */
    public static function redirect($destination = null) {
        if ($destination) $destination = trim($destination);
        if (!$destination || !strlen($destination)) {
            $destination = self::APP_URL();
        }
        $destination = preg_replace( '/[^[:print:]]/u', '',$destination);
        header("Location: $destination");
    }

    /**
     * Grab string data from an external url with a POST request.
     * @param string $url
     * @param string $postData
     * @return string - remote server's response
     */
    public static function stringFromPostRequest($url, $postData)
    {
        try {
            self::setMaxExecutionTimePhpScript('40');
            $curl_connection = curl_init($url);
            // set options
            curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($curl_connection, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
            curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
            // set header
            curl_setopt($curl_connection, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Connection: Keep-Alive'
            ));
            // set data to be posted
            curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $postData);
            // perform our request
            $res = curl_exec($curl_connection);
            $respCode = curl_getinfo($curl_connection, CURLINFO_HTTP_CODE);
            if ($respCode < 200 || $respCode > 299) {
                throw new \Exception("Error getting result from $url:" . PHP_EOL . "HTTP return code $respCode");
            }
            if ($res === FALSE) {
                throw new \Exception("Error getting result from $url:" . PHP_EOL . curl_error($curl_connection));
            }
            return $res;
        } finally {
            // close the connection
            curl_close($curl_connection);
            self::setMaxExecutionTimePhpScript();
        }
    }
    
    /**
     * Make a string trimmed (remove leading and trailing spaces); make it null if it's empty then.
     * Takes its argument by reference and actually changes its value. Return is only for convenience.
     * @param string $strValue
     * @return string|null $strValue after transformation
     */
    public static function trimOrNull(&$strValue) {
        if ($strValue === null) {
            return null;
        }
        $strValue = trim($strValue);
        if (!strlen($strValue)) {
            $strValue = null;
        }
        return $strValue;
    }
    
    /**
     * Remove accents from string
     * @param string $str
     * @return string
     */
    public static function removeAccents($str) {
        try {
            $replace = array(
                '??'=>'-', '??'=>'-', '??'=>'-', '??'=>'-',
                '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'A', '??'=>'Ae',
                '??'=>'B',
                '??'=>'C', '??'=>'C', '??'=>'C',
                '??'=>'E', '??'=>'E', '??'=>'E', '??'=>'E', '??'=>'E',
                '??'=>'G',
                '??'=>'I', '??'=>'I', '??'=>'I', '??'=>'I', '??'=>'I',
                '??'=>'L',
                '??'=>'N', '??'=>'N',
                '??'=>'O', '??'=>'O', '??'=>'O', '??'=>'O', '??'=>'O', '??'=>'Oe',
                '??'=>'S', '??'=>'S', '??'=>'S', '??'=>'S',
                '??'=>'T',
                '??'=>'U', '??'=>'U', '??'=>'U', '??'=>'Ue',
                '??'=>'Y',
                '??'=>'Z', '??'=>'Z', '??'=>'Z',
                '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'a', '??'=>'ae', '??'=>'ae', '??'=>'ae', '??'=>'ae',
                '??'=>'b', '??'=>'b', '??'=>'b', '??'=>'b',
                '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'c', '??'=>'ch', '??'=>'ch',
                '??'=>'d', '??'=>'d', '??'=>'d', '??'=>'d', '??'=>'d', '??'=>'d', '??'=>'D', '??'=>'d',
                '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e', '??'=>'e',
                '??'=>'f', '??'=>'f', '??'=>'f',
                '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g', '??'=>'g',
                '??'=>'h', '??'=>'h', '??'=>'h', '??'=>'h', '??'=>'h', '??'=>'h', '??'=>'h', '??'=>'h',
                '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'i', '??'=>'ij', '??'=>'ij',
                '??'=>'j', '??'=>'j', '??'=>'j', '??'=>'j', '??'=>'ja', '??'=>'ja', '??'=>'je', '??'=>'je', '??'=>'jo', '??'=>'jo', '??'=>'ju', '??'=>'ju',
                '??'=>'k', '??'=>'k', '??'=>'k', '??'=>'k', '??'=>'k', '??'=>'k', '??'=>'k',
                '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l', '??'=>'l',
                '??'=>'m', '??'=>'m', '??'=>'m', '??'=>'m',
                '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n', '??'=>'n',
                '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'o', '??'=>'oe', '??'=>'oe', '??'=>'oe',
                '??'=>'p', '??'=>'p', '??'=>'p', '??'=>'p',
                '??'=>'q',
                '??'=>'r', '??'=>'r', '??'=>'r', '??'=>'r', '??'=>'r', '??'=>'r', '??'=>'r', '??'=>'r', '??'=>'r',
                '??'=>'s', '??'=>'s', '??'=>'s', '??'=>'s', '??'=>'s', '??'=>'s', '??'=>'s', '??'=>'s', '??'=>'s', '??'=>'sch', '??'=>'sch', '??'=>'sh', '??'=>'sh', '??'=>'ss',
                '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '??'=>'t', '???'=>'tm',
                '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'u', '??'=>'ue',
                '??'=>'v', '??'=>'v', '??'=>'v',
                '??'=>'w', '??'=>'w', '??'=>'w',
                '??'=>'y', '??'=>'y', '??'=>'y', '??'=>'y', '??'=>'y', '??'=>'y',
                '??'=>'y', '??'=>'z', '??'=>'z', '??'=>'z', '??'=>'z', '??'=>'z', '??'=>'z', '??'=>'z', '??'=>'zh', '??'=>'zh'
            );
            return strtr($str, $replace);
        } catch (\Exception $err) {
            return $str;
        }
    }
    
    /**
     * Composes a URL from a base URL (https://server:port/basepath) and a relative URL (/a/b/c)
     * into a full URL (https://server:port/basepath/a/b/c). Puts slashes where necessary, avoiding double slashes
     * @param string $base - base URL (stem containing at least protocol and domain) - uses "https://localhost/" if not provided -
     *                          Ignored if $relative is already absolute, i.e. begins with http:// or https://
     * @param string $relative - relative URL to compose with base
     */
    public static function fullUrl($base, $relative) {
        if ($base == null || !strlen($base)) {
            $base = 'https://localhost/';
        }
        if ($relative == null || !strlen($relative)) {
            $relative = '';
        }
        if (preg_match('/http(s)?:\/\/.*/i', $relative)) {
            return $relative;
        }
        if (substr($base, strlen($base) - 1, 1) != '/') {
            $base .= '/';
        }
        
        return $base . preg_replace('/^\/+/', '', $relative);
    }
    
    /**
     * Sets a request parameter in a given url. Adds query string or appends to it, or replaces existing value. Escapes to URL
     * @param string $url
     * @param string $parameter
     * @param string $value
     * @return $url with request parameter set
     */
    public static function setRequestParameter($url, $parameter, $value) {
        if (!$parameter)
            return $url;
        $parameter = urlencode($parameter);
        if ($value)
            $value = urlencode($value);
        else
            $value = '';
        
        $chr = '?';
        $pos = strpos($url, $chr);
        if ($pos > 0) {
            $chr = '&';
            $q = substr($url, $pos + 1);
            if (strlen($q)) {
                $spl = preg_split('/&/', $q);
                for ($i = 0; $i < count($spl); $i++) {
                    $qp = $spl[$i];
                    $pspl = preg_split('/=/', $qp);
                    if (!count($pspl))
                        continue;
                    if ($pspl[0] == $parameter) {
                        array_splice($spl, $i--);
                    }
                }
                $url = substr($url, 0, $pos) . '?' . join('&', $spl);
                if (!count($spl)) {
                    $chr = '';
                }
            } else {
                $chr = '';
            }
        }
        return "$url$chr$parameter=$value";
    }
    
    /**
     * Set the maximum execution time of a php script.
     * <p>Can only augment it except '0' for infinite time of execution.</p>
     * <p>if $newtime is null, reset to the default value (30 seconds)</p>
     * @param string $newTime
     */
    public static function setMaxExecutionTimePhpScript ($newTime = null) {
        $newTime = self::trimOrNull($newTime);
        if($newTime == null)
            $newTime = '30';
        $old = ini_get('max_execution_time');
        $old = intval($old ? intval($old) : 0);
        $intnew = intval($newTime);
        if ($intnew == 0 || $old < $intnew)
            ini_set('max_execution_time', $newTime);
    }
    
    public static function error500($message) {
        if (!$message)
            $message = 'Internal server errror';
        $message = preg_replace('/\R+/', ' ', $message);
        header("HTTP/1.0 500 $message");
        exit();
    }
    
    /**
     * Retrieves the domain name from the URL passed as a parameter,
     * returns the URL if this domain name matches the site, otherwise NULL
     * @param string $url
     * @return string|NULL
     */
    public static function domainNamesCheck($url) {
        
        $dnSearch = $_SERVER['HTTP_HOST'];
        
        if (!$dnSearch)
            return null;
        $u = strstr($url, '//');
        if ($u && strlen($u) > 2) {
            $dnFind = explode('/', $u)[2];
        }
        if ($dnFind != $dnSearch) {
            $url = null;
        }
        return $url;
    }
    
    public static function getRefererUrl() {
        return self::domainNamesCheck($_SERVER['HTTP_REFERER']);
    }
    
}

Util::__init__();

