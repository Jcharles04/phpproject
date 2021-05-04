<?php

use apputils\Util;

include '__app.php';
require_once __APPDIR__ . '/apputils/Util.php';

function renderComment($comment, $level = 0) {
	$date = new DateTime($comment['CreationDate'])
?>
	<div class='card level-<?=$level?> wrapper' id='comment-<?=$comment['id']?>'>
		<div class='head'>

                <div class='zero'>
                        <div class='ProfilPicture'></div>
                </div>

                <div class='one'>
                    <div class='name'><?= $comment['FirstName']?></div>
                    <div class='date'><?php echo date_format($date, 'd-m, H:i')?></div>
                </div>

                <div class='two'>
                    <div class='service'>Service :</div>
                    <div class='descService '><?php echo $comment['Service'];?></div>
                </div>

                <div class='three'>
                    <?php if ($_SESSION['user']['id'] == $comment['User_id']): ?>
                            <form class='mod' action='./com/modifyCom.php' method="GET">
                                <input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
                                <button class='button' type='submit' value='Modifier'><i class="fas fa-ellipsis-h"></i></button>
                            </form>
                    <?php endif; ?>	

                    <?php if ($_SESSION['user']['id'] == $comment['User_id'] || $_SESSION['user']['Moderator'] == 1): ?>	
                            <form class='del' action='./com/deleteCom.php' method="POST">
                                <input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
                                <input id="UserId" name="UserId" type="hidden" value="<?=$comment['User_id']?>">
                                <button class='button' type='submit' value='Delete'><i class="fas fa-trash-alt"></i></button>
                            </form>
                    <?php endif; ?>
                    <?php if ($_SESSION['user']['Moderator'] == 1 &&  $comment['checkedByAdmin'] == 1): ?>	
                            <div class='check'><i class="far fa-check-circle"></i></div>
                    <?php elseif  ($_SESSION['user']['Moderator'] == 1 &&  $comment['checkedByAdmin'] == 0): ?>
                            <div class='check five'><i class="fas fa-flag"></i></div>		
                    <?php endif; ?>
                </div>
		</div>


		<div class='main'>
			<div class='visible'>
                <?php if ($comment['ImgUrl'] != null): ?>
                <div class='img'>
                    <img  id='img' src="<?=Util::APP_URL('/upload/', $comment['ImgUrl']) ?>"/>
                </div>
                <?php endif; ?>	
                <?php if ($comment['Text'] != NULL): ?>
				    <div class='text' contenteditable=false ><?php echo $comment['Text'] ?></div>
                <?php else : ?>
                    <div class='text' contenteditable=false style='display:none;'><?php echo $comment['Text'] ?></div>
                <?php endif; ?>	
			</div>
			<form class='inVisible' style='display:none' action="./com/modifyComVal.php" method="POST" enctype="multipart/form-data">
                <div class='buttonMod'>    
                    <div class='img-field'>   
                        <?php if ($comment['ImgUrl'] != NULL): ?>
                            <div class='five'>
                                <button type="submit" id="deleteImage" name="deleteImage" value="1">Supprimer image</button>
                            </div>
                        <?php endif; ?>
                    </div>	
                        <input id="deleteImage" name="deleteImage" type="hidden" value="0"/>
                        <input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>"/>
                    <div class='six'>
                        <label for="file">Changer d'image ?</label>
                        <input type="file" name="file" class='upload'/>
                    </div>
                    <div class='seven'>
                        <button type="submit" id="submit" class='submitMod' value="Envoyer">Valider</button>
                    </div>
                </div>
			</form>
		</div>


		<div class='bottom'>
		<div class='nbOfLike twelve'><i class="far fa-thumbs-up"></i> <?=$comment['likes'] ?></div>
			<?php if (array_key_exists('NbOfResponse', $comment) ): ?>	
					<button class='toggle nbOfResponse eleven'>
						<?=$comment['NbOfResponse']?> commentaires
					</button>
			<?php endif; ?>
		</div>

		<div class='footer'>
		<?php if($comment['myLike'] == 0): ?>
				<form class='like twelve' action='./com/like.php' method="POST">
					<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
					<button  class='button submitLike' type='submit' value='<?php $comment['likes']?>'>J'aime</button>
				</form>
			<?php else : ?>
				<form class='like twelve' action='./com/unLike.php' method="POST">
					<input id="comId" name="comId" type="hidden" value="<?=$comment['id']?>">
					<button class='button submitDisLike' type='submit' value='Vous avez liké'>J'ai liké</button>
				</form>
			<?php endif; ?>
			<?php if ($comment['ReplyTo_id'] == NULL):?>
			<button class='toggle response thirteen'><i class="fas fa-reply"></i></button>
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
			<?php if (array_key_exists("replies", $comment) && count($comment["replies"])):?>
				<div class="replies">
					<?php foreach($comment["replies"] as $reply) {
						renderComment($reply, $level + 1);
					}?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	
	
<?php
}