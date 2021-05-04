<?php

use apputils\Util;

include_once '__app.php';

require_once __DIR__ . '/../Database/db.php';
require_once __APPDIR__ . '/apputils/Util.php';
require_once __DIR__ . '/component/modcom.php';
//unset($_SESSION['login_error']);

$comId = $_GET['comId'];
$userId = $_SESSION['user']['id'];


$thisCom = getOneCom($comId);
if ($_SESSION['user']['Moderator'] == 0 && $_SESSION['user']['id'] != $thisCom['User_id']) {
    $_SESSION['getOneCom_error'] = "Problême avec ce commentaire !";
    header('Location: ../index.php');

} elseif (!$thisCom) { 
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
            <header>
                <h2>Groupomania</h2>
                <a href="../index.php">Acceuil</a>
                <h3>Hello, <?=h($_SESSION['user']['Name']) ?></h3>
                <?php if($_SESSION['user']['Moderator'] == 1): ?>
                    <a href='../mod/mod.php' class='link'>Page des admins</a>
                <?php endif ?>
                <div>
                    <a href='./log/logout.php' class='link'>Déconnexion</a>
                    <a href='./log/deleteUser.php' class='link'>Supprimer votre compte</a>
                </div>
		    </header>
            <?php modCom($thisCom); ?>
        <html>
<?php   
}
?>