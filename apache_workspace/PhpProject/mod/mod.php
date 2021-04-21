<?php

use apputils\Util;

include_once '__app.php';;

require_once __APPDIR__ . '/Database/db.php';
require_once __APPDIR__ . '/apputils/Util.php';

if ($_SESSION["user"]['Moderator'] == 0) {
	header('Location: ../index.php');
  	exit();
}

?>

<!DOCTYPE html>

<html>
	<head>
       	<meta charset="utf-8">
        <link rel="stylesheet" href="<?=Util::CACHE_URL('./assets/style.css') ?>" media="screen" />
    </head>
	<body>
        <div id='main'> 
            <header>
                <h2>Groupomania</h2>
                <a href="../index.php" class='link'>Acceuil</a>
                <h3>Hello, <?=h($_SESSION['user']['Name']) ?></h3>
                <div>
                    <a href='./log/logout.php' class='link'>Déconnexion</a>
                    <a href='./log/deleteUser.php' class='link'>Supprimer votre compte</a>
                </div>
		    </header>
        
            <section class='container check'>
                <h2>Dernière vérification des messages</h2>
                <form action='./checkCom.php' method="POST">
                    <input id="admin" name="admin" type="hidden" value="1">
                    <input class='response' type='submit' value='Cliquez ici pour valider tous les commentaires'/>
                </form>
                <?=moderationDate();?>
            </section>

            <section class='container '>
                <h2>Liste des utilisateurs</h2>
                <div class='array'>
                    <table>
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Service</th>
                                <th>Suppression</th>
                                <th>Date Suppression</th>
                            </tr>
                        </thead>
                        <?=getAllUser(); ?>
                    </table>
                </div>	
            </section> 
        </div>
	</body>
</html>

<?php 

function checkModeration($date){
    $newdate = new DateTime($date['ModerationDate'])
?>  
    <p>Modération faite  le: <?php echo date_format($newdate, '\L\e d-m-Y \à H:i')?> par: <?=$date['Name']?> <?=$date['FirstName']?> </p>
<?php
}
?>

<?php 

function renderUsers($users){
    $date = new DateTime($users['Suppression'])
?>  
    <tbody>
        <tr>
            <td><?=$users['id']?></td>
            <td><?=$users['Name']?></td>
            <td><?=$users['FirstName']?></td>
            <td><?=$users['Mail']?></td>
            <td><?=$users['Service']?></td>
            <?php if ($users['Suppression'] == NULL): ?>
                <td>
                    <form action="./deleteUser.php" method="POST">
                        <input id="userId" name="userId" type="hidden" value='<?=$users['id']?>'/>
                        <button type='submit' value=''>X</button>
                    </form>
                </td>
            <?php else : ?>
                <td>
                    <form action="./backUser.php" method="POST">
                        <input id="userId" name="userId" type="hidden" value='<?=$users['id']?>'/>
                        <button type='submit' value=''>O</button>
                    </form>
                </td>
            <?php endif; ?>
            <?php if ($users['Suppression'] != NULL): ?>
                <td><?php echo date_format($date, '\L\e d-m-Y \à H:i') ; ?></td>
            <?php else : ?>   
                <td>/</td>
		    <?php endif; ?>	
        </tr>
    </tbody>
<?php
}
?>




