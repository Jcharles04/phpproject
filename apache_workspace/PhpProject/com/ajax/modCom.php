<?php

use apputils\Util;

require '__app.php';

require_once __APPDIR__ . '/Database/db.php';
require_once __DIR__ . '/../component/modcom.php';
//unset($_SESSION['login_error']);

$comId = $_GET['comId'];
$userId = $_SESSION['user']['id'];

$thisCom = getOneCom($comId);
if (!$thisCom) {
    http_response_code(400);
    die();
} else {
    modCom($thisCom);
}
?>