<?php


include_once '__app.php';

require_once __DIR__ . './component/rendercom.php';
require_once __DIR__ . '/../Database/db.php';
require_once __APPDIR__ . '/apputils/Util.php';


$comId = $_POST['comId'];
$userId = $_SESSION['user']['id'];

$ajax = array_key_exists('ajax', $_POST);

try {
    sendLike($comId ,$userId);
        if ($ajax) {
            $comment = modifyThisCom($comId);
            renderComment($comment);
        } else {
            header('Location: ../index.php'); 
        }
}
catch (Exception $e) {
    error_log($e);
    $_SESSION['sendReply_error'] = $e->getMessage();
    header('Location: ../index.php');
}
?>