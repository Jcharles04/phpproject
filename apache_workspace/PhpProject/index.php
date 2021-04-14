<?php

use apputils\Util;

include '__app.php';

require_once __APPDIR__ . '/Database/db.php';
require_once __APPDIR__ . '/apputils/Util.php';

if (!$_SESSION["user"]) {
	header('Location: ./log/login.php');
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
			<h1>Hello, <?=htmlspecialchars_decode($_SESSION['user']['Name'])?></h1>
			<?php if($_SESSION['user']['Moderator'] != 0): ?>
				<a href='./mod/mod.php' class='link'>Liste des utilisateurs</a>
			<?php endif ?>
			<div>
				<a href='./log/logout.php' class='link'>Déconnexion</a>
				<a href='./log/deleteUser.php' class='link'>Supprimer votre compte</a>
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
			<div class='comment'>
				<?=getAllCom(); ?>
			</div>
		</section>
	</body>
</html>

<?php 
function renderComment($comment, $level = 0) {
	$date = new DateTime($comment['CreationDate'])
?>
	<div class='card level-<?=$level?>'>
		<div class='firstName'><?php echo $comment['FirstName'];?></div>
		<div class='service'><p>Service : </p><?php echo $comment['Service'];?></div>
		<div class='date'><?php echo date_format($date, '\L\e d-m-Y \à H:i') ; ?></div>
		<?php if ($comment['ImgUrl'] != null): ?>
			<img src="<?=Util::APP_URL('/upload/', $comment['ImgUrl']) ?>"/>
		<?php endif; ?>	
		<div class='text'><?php echo $comment['Text']; ?></div>
		<?php if($comment['myLike'] == 0): ?>
			<form action='./com/like.php' method="POST">
				<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
				<input class='like' type='submit' value='Like <?php echo ($comment['likes']); ?>' />
				<p>Nombre de likes: <?php echo ($comment['likes']); ?><p>
			</form>
		<?php else : ?>
			<form action='./com/unLike.php' method="POST">
				<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
				<input class='like' type='submit' value='Vous avez liké'/>
				<p>Nombre de likes: <?php echo ($comment['likes']); ?><p>
			</form>
		<?php endif; ?>	
		<div class='response'></div>
		<?php if ($_SESSION['user']['id'] != $comment['User_id'] && $comment['ReplyTo_id'] == NULL): ?>
				<form action='./com/replyCom.php' method="POST">
					<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
					<input class='response' type='submit' value='Répondre'/>
				</form>
		<?php endif; ?>	
		<?php if ($_SESSION['user']['id'] == $comment['User_id']): ?>
				<form action='./com/modifyCom.php' method="GET">
					<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
					<input class='modify' type='submit' value='Modifier'/>
				</form>
		<?php endif; ?>	
		<?php if ($_SESSION['user']['id'] == $comment['User_id'] || $_SESSION['user']['Moderator'] != 0): ?>	
				<form action='./com/deleteCom.php' method="POST">
					<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
					<input class='delete' type='submit' value='Delete'/>
				</form>
		<?php endif; ?>
		</div>
		<?php if (array_key_exists('replies', $comment) && count($comment['replies'])):?>
			<div class="replies">
				<?php foreach($comment['replies'] as $reply) {
					renderComment($reply, $level + 1);
				}?>
			</div>
		<?php endif; ?>
<?php
	}
?>
