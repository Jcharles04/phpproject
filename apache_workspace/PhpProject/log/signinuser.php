<?php
include_once '__app.php';

require_once __DIR__ . '/../Database/db.php';

unset($_SESSION['signin_error']);

$name = $_POST['name'];
$firstname = $_POST['firstname'];
$service = $_POST['serv'];
$mail = $_POST['email'];
$password =$_POST['password'];
$hash = password_hash($password, PASSWORD_DEFAULT);

error_log("signin with $name / $firstname / $service / $mail / $hash");

try {
    signin($name, $firstname, $service, $mail, $hash);
    header('Location: ./login.php'); 
}
catch (Exception $e) {
    error_log($e);
    $_SESSION['signin_error'] = $e->getMessage();
    header('Location: ./signin.php');
}

?>