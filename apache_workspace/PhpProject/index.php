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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script type="text/javascript" src="./script/index.js"></script>
		<script type="text/javascript" src="./jquery-ui-1.12.1/jquery-ui.js"></script>
		<link rel="stylesheet"  href="./assets/fontawesome/css/all.css" />
		<link rel="stylesheet" href="<?=Util::CACHE_URL('/jquery-ui-1.12.1/jquery-ui.css') ?>" media="screen" />
        <link rel="stylesheet" href="<?=Util::CACHE_URL('/assets/style.css') ?>" media="screen" />
    </head>
	<body>
		<main>
			<header>
				<h2>Groupomania</h2>
				<h3>Hello, <?=h($_SESSION['user']['Name']) ?></h3>
				<button id='button'>Profile</button>
					<div id='toggle' style='display: none'>
						<ul>
							<li><?php if($_SESSION['user']['Moderator'] == 1): ?>
									<a href='./mod/mod.php' class='link'>admins</a>
								<?php endif ?>
							<li><a href='./log/logout.php' class='link'>Déconnexion</a></li>
							<li><a href='./log/deleteUser.php' class='link'>Supprimer votre compte</a></li>
						</ul>
					</div>
			</header>
		
			<section class='container'>
				<div id="postCom">
					<form action="./com/postComVerify.php" method="POST" enctype="multipart/form-data">
						<label for="text">Exprimez-vous !</label>
						<label for="name"></label>
						<input type="text" id="text" name="text"  minlength="0" maxlength="250" size="25" placeholder="Racontez nous votre journée...">
						<label for="file">Une image à partager?</label>
						<input type="file" name="file">
						<button type="submit" id="submit" class='button' value="Envoyer">Envoyez</button>
					</form>
				</div>
			</section>
			<section>
				<div class='comment'>
					<?php foreach (getAllCom() as $comment) renderComment($comment) ?>
				</div>
			</section>
		</main>		
	</body>
</html>

<?php 
function renderComment($comment, $level = 0) {
	$date = new DateTime($comment['CreationDate'])
?>
	<div class='card level-<?=$level?> wrapper' id='comment-<?=$comment['id']?>'>
		<div class='head'>

				<div class='name one'><?php echo $comment['FirstName']?></div>
				<div class='date two'><?php echo date_format($date, 'd-m, H:i')?></div>


				<div class='service three'>Service : </p></div>
				<div class='descService four'><?php echo $comment['Service'];?></div>


				<?php if ($_SESSION['user']['id'] == $comment['User_id']): ?>
						<form class='mod six' action='./com/modifyCom.php' method="GET">
							<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
							<input class='button' type='submit' value='Modifier'/>
						</form>
				<?php endif; ?>	
				<?php if ($_SESSION['user']['id'] == $comment['User_id'] || $_SESSION['user']['Moderator'] == 1): ?>	
						<form class='del seven' action='./com/deleteCom.php' method="POST">
							<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
							<input class='button' type='submit' value='Delete'/>
						</form>
				<?php endif; ?>
				<?php if ($_SESSION['user']['Moderator'] == 1 &&  $comment['checkedByAdmin'] == 1): ?>	
						<div class='check five'>Check</div>
				<?php elseif  ($_SESSION['user']['Moderator'] == 1 &&  $comment['checkedByAdmin'] == 0): ?>
						<div class='check five'>A vérifier</div>		
				<?php endif; ?>

		</div>
		<div class='main'>
			<?php if ($comment['ImgUrl'] != null): ?>
				<img class='img nine' src="<?=Util::APP_URL('/upload/', $comment['ImgUrl']) ?>"/>
			<?php endif; ?>	
			<div class='text height'><?php echo $comment['Text'] ?></div>
		</div>
		<div class='bottom'>
		<div class='nbOfLike twelve'>Nombre de likes: <?=$comment['likes'] ?></div>
			<?php if (array_key_exists('NbOfResponse', $comment) ): ?>	
					<button class='toggle nbOfResponse eleven'>
						<span><?=$comment['NbOfResponse']?> commentaires</span>
					</button>
			<?php endif; ?>
		</div>

		<div class='footer'>
		<?php if($comment['myLike'] == 0): ?>
				<form class='like twelve' action='./com/like.php' method="POST">
					<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
					<input  class='button far fa-thumbs-up' type='submit' value='Like <?php $comment['likes'] ?>' />
					<i class="far fa-thumbs-up"></i>
				</form>
			<?php else : ?>
				<form class='like twelve' action='./com/unLike.php' method="POST">
					<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
					<input class='button' type='submit' value='Vous avez liké'/>
				</form>
			<?php endif; ?>
			<?php if ($comment['ReplyTo_id'] == NULL):?>
			<button class='toggle response thirteen'>
				<span>Répondre</span>
			</button>
			<?php endif?>
		</div>
		<div class="show" style='display:none'>
			<?php if ($comment['ReplyTo_id'] == NULL):?>
				<div class='responseTo' >
					<form action="./com/replyComVal.php" method="POST" enctype="multipart/form-data">
							<input type="text" id="text" name="text"  minlength="0" maxlength="250" size="25" placeholder="Envie de réagir ?" required/>
							<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
							<button type="submit" id="submit" class='button' value="Envoyer">Envoyez</button>
					</form>
				</div>
			<?php endif; ?>
			<?php if (array_key_exists('replies', $comment) && count($comment['replies'])):?>
				<div class="replies">
					<?php foreach($comment['replies'] as $reply) {
						renderComment($reply, $level + 1);
					}?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	
	
<?php
}
?>
<!--
<aside hidden>
	<div class="modCom">
		<form class='form-wrapper' action="./modifyComVal.php" method="POST" enctype="multipart/form-data">
			<div class='field'> 
				<div class='img-field'>   
					<?php if ($comment['ImgUrl'] != NULL): ?>
						<div class='three'>
							<label for="file">Une autre image à partager?</label>
						</div>
						<div class='four'>
							<img src="<?=h(Util::APP_URL('/upload/', $comment['ImgUrl']))?>"/>
						</div>
						<div class='five'>
							<button type="submit" id="delete-image" name="delete-image" value="1">Supprimer image</button>
						</div>
					<?php endif; ?>
				</div> 
				<div class='text-field'> 
					<div class='one'>
						<label for="textarea">Que voulez-vous changer ?</label>
					</div>
					<div class='two'>
						<textarea id="text" name="text" rows="15" cols="33"><?=h($comment['Text'])?></textarea>
					</div>
				</div>  
			</div>    	
			<input id="delete-image" name="delete-image" type="hidden" value="0"/>
			<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>"/>
			<div class='six'>
				<input type="file" name="file"/>
			</div>
			<div class='seven'>
				<button type="submit" id="submit" value="Envoyer">Envoyez</button>
			</div>
		</form>
	</div>
</aside>



<form class='response thirteen' action='./com/replyCom.php' method="POST">
				<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
				<input class='button' type='submit' value='Répondre'/>
			</form>


<div class='nbOfLike twelve'><p>Nombre de likes: <?=$comment['likes'] ?><p></div>
<?php if (array_key_exists('NbOfResponse', $comment) ): ?>	
					<form class='nbOfResponse eleven' action='./com/responsesCom.php' method="GET">
						<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
						<input class='button com' type='submit' value='<?=$comment['NbOfResponse']?> commentaires'/>
					</form>
			<?php endif; ?>
		</div>