<?php

use apputils\Util;

include '__app.php';

require_once __DIR__ . '/Database/db.php';
require_once __APPDIR__ . '/apputils/Util.php';
//unset($_SESSION['login_error']);

$comId = $_POST['comId'];
$isSup = $_POST['delete-image'];

if($isSup == 1){
    $file = NULL;
    supImg($file, $comId);
    header('Location: ./index.php');

} else if(isset($_FILES['file'])){
    $tmpName = $_FILES['file']['tmp_name'];
    $name = $_FILES['file']['name'];
    $size = $_FILES['file']['size'];
    $error = $_FILES['file']['error'];

    $tabExtension = explode('.', $name);
    $extension = strtolower(end($tabExtension));
    //Tableau des extensions que l'on accepte
    $extensions = ['jpg', 'png', 'jpeg', 'gif'];
    //Taille max que l'on accepte
    $maxSize = 1000000;
    if(in_array($extension, $extensions) && $size <= $maxSize && $error == 0){
        $uniqueName = uniqid('', true);
        //uniqid génère quelque chose comme ca : 5f586bf96dcd38.73540086
        $file = $uniqueName.".".$extension;
        //$file = 5f586bf96dcd38.73540086.jpg
        $uploadDir = __DIR__ . '/upload/';
        $uploadfile = $uploadDir . basename($file);
        move_uploaded_file($tmpName, './upload/'.$file);

    } else {
        $file = NULL;
    }
    $textarea = $_POST['textarea'];
    error_log("postCom with $textarea / $file");

    if(!empty($file or $textarea)){

        try {
            modifyCom($file, $textarea, $comId);
            echo "Commentaire enregistré";
            header('Location: ./index.php'); 
        }
        catch (Exception $e) {
            error_log($e);
            $_SESSION['modifyComVal_error'] = $e->getMessage();
            header('Location: ./index.php');
        }
    } else {
        $_SESSION['postCom_error'] = "Un problême est survenu !";
            header('Location: ./index.php');
    }

} else{
    echo "Une erreur est survenue";
    header('Location: ./index.php');
}  

?>
