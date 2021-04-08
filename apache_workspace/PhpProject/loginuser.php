<?php

include '__app.php';

require_once __DIR__ . '/Database/db.php';

unset($_SESSION['login_error']);

$userName = $_POST['email'];
$password = $_POST['password'];
error_log("Login with $userName / $password");

try {

    $userData = login($userName, $password);
    
    if (!$userData) { //Login échoué
        $_SESSION['login_error'] = "Nom d'utilisateur ou mdp inconnu !";
        header('Location: ./login.php');
    } else {
        $_SESSION['user'] = $userData;
        header('Location: ./');
    }
    
}
catch (Exception $e) {
    error_log($e);
    $_SESSION['login_error'] = $e->getMessage();
    header('Location: ./login.php');
}

?>