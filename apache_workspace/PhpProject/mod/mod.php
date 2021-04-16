<?php

use apputils\Util;

include '../__app.php';;

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
	
		<header>
				<a href='../index.php' class='link'>Acceuil</a>
		</header>
		
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
        <section>
            <h2>Dernière vérification des messages</h2>
            <form action='./checkCom.php' method="POST">
                <input id="admin" name="admin" type="hidden" value="1">
                <input class='response' type='submit' value='Cliquez ici pour valider tous les commentaires'/>
			</form>
            <?=moderationDate();?>
        </section>
	</body>
</html>

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
                    <form action="./ldeleteUser.php" method="POST">
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

<?php 

function checkModeration($date){
    $newdate = new DateTime($date['ModerationDate'])
?>  
    <p>Modération faite  le: <?php echo date_format($newdate, '\L\e d-m-Y \à H:i')?> par: <?=$date['Name']?> <?=$date['FirstName']?> </p>
<?php
}
?>


