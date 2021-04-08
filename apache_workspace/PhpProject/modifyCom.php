<?php

include '__app.php';

require_once __DIR__ . '/Database/db.php';

//unset($_SESSION['login_error']);

$comId = $_POST['comId'];
$userId = $_SESSION['user']['id'];

try {

    getOneCom($comId);
    var_dump($comId);
    
    
}
catch (Exception $e) {
    error_log($e);
    $_SESSION['login_error'] = $e->getMessage();
    header('Location: ./login.php');
}

?>