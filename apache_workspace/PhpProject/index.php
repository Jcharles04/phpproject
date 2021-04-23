<?php

use apputils\Util;

include '__app.php';

require_once __APPDIR__ . '/Database/db.php';
require_once __APPDIR__ . '/apputils/Util.php';
require_once __APPDIR__ . '/com/component/rendercom.php';


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
				<button id='button'><i class="fas fa-user-alt"></i></button>
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


