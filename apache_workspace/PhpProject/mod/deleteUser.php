<?php

include '../__app.php';

require_once __DIR__ . '/../Database/db.php';

//unset($_SESSION['signin_error']);

$userId = $_POST['id'];

try {
    deleteUser($userId);
    if($_SESSION['user']['Moderator'] == 0) {
        session_start();
        unset($_SESSION);
        session_destroy();
        session_write_close();
        header('Location: ../index.php');
        die;
    } else {
        header('Location: ./mod.php'); 
    }
        
    
}
catch (Exception $e) {
    error_log($e);
    $_SESSION['signin_error'] = $e->getMessage();
    header('Location: ../index.php');
}

?>