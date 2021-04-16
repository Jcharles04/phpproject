<?php

use apputils\Util;

include '../__app.php';

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
	
		<header>
				<a href='../index.php' class='link'>Acceuil</a>
		</header>
        <div id="replyCom">
                <form action="./replyComVal.php" method="POST" enctype="multipart/form-data">
                    <label for="textarea">Envie de réagir ?</label>
                    <textarea placeholder="" name="textarea"><?=h('')?></textarea>
                    <input id="comId" name="comId" type="hidden" value="<?=$_SESSION['user']['id']?>"/>
                    <input id="thisCom" name="comId" type="hidden" value="<?=$res['id']?>"/>
                    <button type="submit" id="submit" value="Envoyer">Envoyez</button>
                </form>
            </div>
        <section>
            <div id="responseCom">
                <?php  renderResponse($res) ?>
            </div>
        </section>
    </body>
<html>
 

<?php 
function renderResponse($comment, $level = 0) {
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
		<?php if ($_SESSION['user']['id'] == $comment['User_id']): ?>
				<form action='./com/modifyCom.php' method="GET">
					<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
					<input class='modify' type='submit' value='Modifier'/>
				</form>
		<?php endif; ?>	
		<?php if ($_SESSION['user']['id'] == $comment['User_id'] || $_SESSION['user']['Moderator'] == 1): ?>	
				<form action='./com/deleteCom.php' method="POST">
					<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
					<input class='delete' type='submit' value='Delete'/>
				</form>
		<?php endif; ?>
		<?php if (array_key_exists('NbOfResponse', $comment) && count($comment['NbOfResponse']) >= 5): ?>	
				<form action='./com/responsesCom.php' method="GET">
					<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
					<input class='NbOfResponse' type='submit' value='Afficher les réponses plus anciennes'/>
				</form>
		<?php endif; ?>
		<?php if ($_SESSION['user']['Moderator'] == 1 &&  $comment['checkedByAdmin'] == 1): ?>	
				<p>Check</p>
		<?php elseif  ($_SESSION['user']['Moderator'] == 1 &&  $comment['checkedByAdmin'] == 0): ?>
				<p>A vérifier</p>		
		<?php endif; ?>
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
