<?php

use apputils\Util;

include '__app.php';

require_once __APPDIR__ . '/Database/db.php';
require_once __APPDIR__ . '/apputils/Util.php';

if (!$_SESSION["user"]) {
	header('Location: ./login.php');
  	exit();
}



?>

<!DOCTYPE html>

<html>
	<head>
       	<meta charset="utf-8">
        <link rel="stylesheet" href="./style.css" media="screen" />
    </head>
	<body>
	
		<header>
			<h1>Hello, <?=htmlspecialchars_decode($_SESSION['user']['Name'])?></h1>
			<a href='./logout.php' class='link'>Déconnexion</a>
		</header>
		
		<section class='container one'>
			<div id="postCom">
                <form action="./postComVerify.php" method="POST" enctype="multipart/form-data">
					<label for="textarea">Exprimez-vous !</label>
                    <textarea placeholder="Racontez nous votre journée..." name="textarea"></textarea>
					<label for="file">Une image à partager?</label>
                	<input type="file" name="file">
                    <button type="submit" id="submit" value="Envoyer">Envoyez</button>
                </form>
        	</div>
		</section>
		<section class='container two'>
			<div class='commentary'>
				<?php 
					try{
						$conn;
					} catch(PDOException $e){
						die('Erreur connexion : '.$e->getMessage());
					}
					$response = $conn->query('SELECT * FROM commentaire WHERE Suppression IS NULL');
					while ($donnees = $response->fetch()){
					?>
						<div class='card'>
							<div class='comId'><?php echo $donnees['id'];?></div>
							<div class='userId'><?php echo $donnees['User_id'];?></div>
							<div class='date'><?php echo $donnees['DateCreation']; ?></div>
							<?php if ($donnees['ImgUrl'] != null): ?>
								<img src="<?=Util::APP_URL('/upload/', $donnees['ImgUrl']) ?>"/>
							<?php endif; ?>	
							<div class='text'><?php echo $donnees['Text']; ?></div>
							<div class='like'><?php echo $donnees['Like']; ?></div>
							<div class='reponse'><?php echo $donnees['ReplyTo_id']; ?></div>
							<?php if ($_SESSION['user']['id'] == $donnees['User_id']): ?>
									<form action='./modifyCom.php' method="GET">
										<input id="comId" name="comId" type="hidden" value="<?=$donnees['id']?>">
										<input class='modify' type='submit' value='Modifier'/>
									</form>
									<form action='./deleteCom.php' method="POST">
										<input class='delete' type='submit' value='Delete'/>
									</form>;
							<?php endif; ?>	
						</div>
					<?php
					}
					$response->closeCursor();
					?>
			</div>
		</section>
	</body>
</html>
