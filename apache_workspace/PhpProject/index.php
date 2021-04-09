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
			<div>
				<a href='./logout.php' class='link'>Déconnexion</a>
				<a href='./deleteUser.php' class='link'>Supprimer votre compte</a>
			</div>
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
					$comments = $conn->query('SELECT * FROM commentaire WHERE Suppression IS NULL')->fetchAll();
					$commentsById = [];
					foreach($comments as $comment){
						$commentsById[$comment->id] = $comment;
					}
					foreach ($comments as $id => $comment) {
						if($comment->ReplyTo_id != NULL) {
							$commentsById[$comment->ReplyTo_id]->children[] = $comment;
							if ($unset_children) {
								unset($comments[$id]);
							}
						}
					}
					var_dump($id);
					var_dump($comments);
					var_dump($comment);
					foreach ($comments as $comment) {
					?>
						<div class='card'>
							<div class='comId'><?php echo $comment['id'];?></div>
							<div class='userId'><?php echo $comment['User_id'];?></div>
							<div class='date'><?php echo $comment['DateCreation']; ?></div>
							<?php if ($comment['ImgUrl'] != null): ?>
								<img src="<?=Util::APP_URL('/upload/', $comment['ImgUrl']) ?>"/>
							<?php endif; ?>	
							<div class='text'><?php echo $comment['Text']; ?></div>
							<div class='like'><?php echo $comment['Like']; ?></div>
							<div class='reponse'><?php echo $comment['ReplyTo_id']; ?></div>
							<?php if ($_SESSION['user']['id'] == $comment['User_id']): ?>
									<form action='./modifyCom.php' method="GET">
										<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
										<input class='modify' type='submit' value='Modifier'/>
									</form>
									<form action='./deleteCom.php' method="POST">
										<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
										<input class='delete' type='submit' value='Delete'/>
									</form>
							<?php endif; ?>	
						</div>
					<?php
					}
					?>
			</div>
		</section>

<!-- <?php
	if (FALSE) {
?>
		<div>toto</div>
<?php
	} else {
?>
		<div>lulu</div>
<?php
	}
	$toto = 'toto qeszfqsdf';
	ob_start();
?>
<div>
	<p>p1</p>
	<p>p2</p>
	<p>p3 <?=$toto?></p>
</div>

<?php
$lulu = ob_get_clean();

echo $lulu;
echo ''
?> -->

	</body>
</html>
