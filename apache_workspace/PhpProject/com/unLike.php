<?php

use apputils\Util;

include '../__app.php';

require_once __DIR__ . '/../Database/db.php';
require_once __APPDIR__ . '/apputils/Util.php';


$comId = $_POST['comId'];
$userId = $_SESSION['user']['id'];

try {
    dropLike($comId ,$userId);
    header('Location: ../index.php'); 
}
catch (Exception $e) {
    error_log($e);
    $_SESSION['sendReply_error'] = $e->getMessage();
    header('Location: ../index.php');
}
?>