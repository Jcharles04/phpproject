<?php

include_once '__app.php';
require_once __APPDIR__ . '/Database/db.php';

//unset($_SESSION['signin_error']);

$UserId = $_POST['UserId'];
$comId = $_POST['comId'];

if (!$_SESSION["user"]) {
	header('Location: ./log/login.php');
  	exit();
      
} elseif ($_SESSION['user']['Moderator'] == 0 && $_SESSION['user']['id'] != $UserId) {
    $_SESSION['getOneCom_error'] = "Problême avec ce commentaire !";
    header('Location: ../index.php');

} else {
    try {
        deleteCom($comId);
        header('Location: ../index.php'); 
    }
    catch (Exception $e) {
        error_log($e);
        $_SESSION['signin_error'] = $e->getMessage();
        header('Location: ../index.php');
    }
}




?>