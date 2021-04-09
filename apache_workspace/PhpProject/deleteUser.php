<?php

include '__app.php';

require_once __APPDIR__ . '/Database/db.php';

//unset($_SESSION['signin_error']);

$userId = $_SESSION['user']['id'];


try {
    deleteUser($userId);
    header('Location: ./index.php'); 
}
catch (Exception $e) {
    error_log($e);
    $_SESSION['signin_error'] = $e->getMessage();
    header('Location: ./index.php');
}

?>