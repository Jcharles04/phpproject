<?php

use apputils\Util;

include '__app.php';

require_once __DIR__ . '/Database/db.php';
require_once __APPDIR__ . '/apputils/Util.php';
//unset($_SESSION['login_error']);

$comId = $_GET['comId'];
$userId = $_SESSION['user']['id'];



$thisCom = getOneCom($comId);
if (!$thisCom) { //
    $_SESSION['getOneCom_error'] = "Problême avec ce commentaire !";
    header('Location: ./index.php');
} else {
    ?>
        <!DOCTYPE html>

        <html>
            <head>
                <meta charset="utf-8">
                <link rel="stylesheet" href="./style.css" media="screen" />
            </head>
            <div id="postCom">
                <form action="./modifyComVal.php" method="POST" enctype="multipart/form-data">
                    <label for="textarea">Que voulez-vous changer ?</label>
                    <textarea placeholder="" name="textarea"><?=h($thisCom['Text'])?></textarea>
                    <?php if ($thisCom['ImgUrl'] != NULL): ?>
                        <label for="file">Une autre image à partager?</label>
                        <img src="<?=h(Util::APP_URL('/upload/', $thisCom['ImgUrl']))?>"/>
                        <button type="submit" id="delete-image" name="delete-image" value="1">Supprimer image</button>
					<?php endif; ?>	
                    <input id="delete-image" name="delete-image" type="hidden" value="0"/>
                    <input id="comId" name="comId" type="hidden" value="<?=$thisCom['id']?>"/>
                    <input type="file" name="file"/>
                    <button type="submit" id="submit" value="Envoyer">Envoyez</button>
                </form>
            </div>
        <html>
<?php   
}
?>