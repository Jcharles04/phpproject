<?php

use apputils\Util;

include '../__app.php';

require_once __DIR__ . '/../Database/db.php';
require_once __APPDIR__ . '/apputils/Util.php';


$userId = $_POST['userId'];

try {
    backUser($userId);
    header('Location: ./mod.php'); 
}
catch (Exception $e) {
    error_log($e);
    $_SESSION['sendReply_error'] = $e->getMessage();
    header('Location: ./mod.php'); 
}
?>