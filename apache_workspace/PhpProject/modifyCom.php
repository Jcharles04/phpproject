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
    <div id="postCom">
        <form action="./modifyCom.php" method="POST" enctype="multipart/form-data">
            <label for="textarea">Que voulez-vous changer ?</label>
            <textarea placeholder="" name="textarea"><?=h($thisCom['Text'])?></textarea>
            <label for="file">Une autre image à partager?</label>
            <img src="<?=h(Util::APP_URL('/upload/', $thisCom['ImgUrl']))?>"/>
            <input type="file" name="file"/>
            <button type="submit" id="submit" value="Envoyer">Envoyez</button>
        </form>
    </div> 
    <?php
}
?>