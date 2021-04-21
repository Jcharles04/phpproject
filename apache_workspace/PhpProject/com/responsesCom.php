<?php

use apputils\Util;

include_once '__app.php';

require_once __DIR__ . '/../Database/db.php';
require_once __APPDIR__ . '/apputils/Util.php';

$comId = $_GET['comId'];
$res = responsesCom($comId);

?>



<!DOCTYPE html>

<html>
	<head>
       	<meta charset="utf-8">
        <link rel="stylesheet" href="<?=Util::CACHE_URL('./assets/style.css') ?>" media="screen" />
    </head>
	<body>
		<div class='main'>
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
			<section class='container'>
				<div id="replyCom">
					<form action="./replyComVal.php" method="POST" enctype="multipart/form-data">
						<label for="textarea">Envie de réagir ?</label>
						<textarea placeholder="Envie de réagir ?" rows="2" cols="50" name="textarea"></textarea>
						<input id="comId" name="comId" type="hidden" value="<?=$_SESSION['user']['id']?>"/>
						<input id="thisCom" name="comId" type="hidden" value="<?=$res['id']?>"/>
						<button type="submit" id="submit" value="Envoyer">Envoyez</button>
					</form>
				</div>
			</section>
			<section>
				<div id="responseCom" class='comment'>
					<?php  renderResponse($res) ?>
				</div>
			</section>
		</div>
	</body>
<html>
 

<?php 
function renderResponse($comment, $level = 0) {
	$date = new DateTime($comment['CreationDate'])
?>
	<div class='card level-<?=$level?> wrapper'>
		<div class='head'>

				<div class='name one'><p><?php echo $comment['FirstName']?></p></div>
				<div class='date two'><?php echo date_format($date, '\L\e d-m-Y \à H:i')?></div>


				<div class='service three'><p>Service : </p></div>
				<div class='descService four'><p><?php echo $comment['Service'];?></p></div>


				<?php if ($_SESSION['user']['id'] == $comment['User_id']): ?>
						<form class='mod six' action='./modifyCom.php' method="GET">
							<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
							<input class='button' type='submit' value='Modifier'/>
						</form>
				<?php endif; ?>	
				<?php if ($_SESSION['user']['id'] == $comment['User_id'] || $_SESSION['user']['Moderator'] == 1): ?>	
						<form class='del seven' action='./deleteCom.php' method="POST">
							<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
							<input class='button' type='submit' value='Delete'/>
						</form>
				<?php endif; ?>
				<?php if ($_SESSION['user']['Moderator'] == 1 &&  $comment['checkedByAdmin'] == 1): ?>	
						<div class='check five'><p>Check</p></div>
				<?php elseif  ($_SESSION['user']['Moderator'] == 1 &&  $comment['checkedByAdmin'] == 0): ?>
						<div class='check five'><p>A vérifier</p></div>		
				<?php endif; ?>

		</div>
		<div class='main'>
			<?php if ($comment['ImgUrl'] != null): ?>
				<img class='img nine' src="<?=Util::APP_URL('/upload/', $comment['ImgUrl']) ?>"/>
			<?php endif; ?>	
			<div class='text height'><?php echo $comment['Text'] ?></div>
		</div>

		<div class='bottom'>
		<div class='nbOfLike twelve'><p>Nombre de likes: <?=$comment['likes'] ?><p></div>
			<?php if (array_key_exists('NbOfResponse', $comment) && $comment['NbOfResponse'] > 5): ?>	
					<form class='nbOfResponse eleven' action='./responsesCom.php' method="GET">
						<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
						<input class='button com' type='submit' value='<?=$comment['NbOfResponse']?> commentaires'/>
					</form>
			<?php endif; ?>
		</div>

		<div class='footer'>
		<?php if($comment['myLike'] == 0): ?>
				<form class='like twelve' action='./like.php' method="POST">
					<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
					<input  class='button' type='submit' value='Like<?php $comment['likes'] ?>' />
				</form>
			<?php else : ?>
				<form class='like twelve' action='./unLike.php' method="POST">
					<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
					<input class='button' type='submit' value='Vous avez liké'/>
				</form>
			<?php endif; ?>
			<?php if ($_SESSION['user']['id'] != $comment['User_id'] && $comment['ReplyTo_id'] == NULL): ?>
					<form class='response thirteen' action='./replyCom.php' method="POST">
						<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
						<input class='button' type='submit' value='Répondre'/>
					</form>
			<?php endif; ?>	
		</div>

	</div>
	<?php if (array_key_exists('replies', $comment) && count($comment['replies'])):?>
			<div class="replies">
				<?php foreach($comment['replies'] as $reply) {
						renderResponse($reply, $level + 1);
				}?>
			</div>
	<?php endif; ?>
<?php
}
?>
