<?php

include_once '__app.php';

require_once __DIR__ . '/../Database/db.php';

//unset($_SESSION['postCom_error']);

$userId = $_SESSION['user']['id'];

if(isset($_FILES['file'])){
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
        $uploadDir = __APPDIR__ . '/upload/';
        $uploadfile = $uploadDir . basename($file);

        move_uploaded_file($tmpName, '../upload/'.$file);
    } else {
        $file = NULL;
    }

    $textarea = $_POST['text'];

    error_log("postCom with $textarea / $file");

    if(!empty($file or $textarea)){

        try {
            postCom($userId, $file, $textarea);
            echo "Commentaire enregistré";
            header('Location: ../index.php'); 
        }
        catch (Exception $e) {
            error_log($e);
            $_SESSION['postCom_error'] = $e->getMessage();
            header('Location: ../index.php');
        }
    } else {
        $_SESSION['postCom_error'] = "Un problême est survenu !";
            header('Location: ../index.php');
    }

} else{
    echo "Une erreur est survenue";
}  

?>
