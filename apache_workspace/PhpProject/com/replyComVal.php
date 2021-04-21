<?php

use apputils\Util;

include_once '__app.php';

require_once __DIR__ . '/../Database/db.php';
require_once __APPDIR__ . '/apputils/Util.php';
//unset($_SESSION['login_error']);

$comId = $_POST['comId'];
$userId = $_SESSION['user']['id'];
$text = $_POST['text'];

try {
    sendReply($userId, $text, $comId);
    header('Location: ../index.php'); 
}
catch (Exception $e) {
    error_log($e);
    $_SESSION['sendReply_error'] = $e->getMessage();
    header('Location: ../index.php');
}
?>