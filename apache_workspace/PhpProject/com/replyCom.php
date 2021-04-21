<?php

use apputils\Util;

include_once '__app.php';

require_once __DIR__ . '/../Database/db.php';
require_once __APPDIR__ . '/apputils/Util.php';
//unset($_SESSION['login_error']);

$comId = $_POST['comId'];
$userId = $_SESSION['user']['id'];



$thisCom = getOneCom($comId);
if (!$thisCom) { //
    $_SESSION['getOneCom_error'] = "Problême avec ce commentaire !";
    header('Location: ../index.php');
} else {
    ?>
        <!DOCTYPE html>

        <html>
            <head>
                <meta charset="utf-8">
                <link rel="stylesheet" href="<?=Util::CACHE_URL('./assets/style.css') ?>" media="screen" />
            </head>
            <div class="card">
                    <p><?=h($thisCom['Text'])?></p>
                    <?php if ($thisCom['ImgUrl'] != NULL): ?>
                        <img src="<?=h(Util::APP_URL('/upload/', $thisCom['ImgUrl']))?>"/>
					<?php endif; ?>	
                </form>
            </div>
            <div id="replyCom">
                <form action="./replyComVal.php" method="POST" enctype="multipart/form-data">
                    <label for="textarea">Envie de réagir ?</label>
                    <textarea placeholder="" name="textarea"><?=h('')?></textarea>
                    <input id="comId" name="comId" type="hidden" value="<?=$userId?>"/>
                    <input id="thisCom" name="comId" type="hidden" value="<?=$thisCom['id']?>"/>
                    <button type="submit" id="submit" value="Envoyer">Envoyez</button>
                </form>
            </div>
        <html>
<?php   
}
?>