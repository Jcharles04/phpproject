<?php 
include '../__app.php';
?>

<!DOCTYPE html>

<html>
    <head>
       <meta charset="utf-8">
        <link rel="stylesheet" href="../assets/style.css" media="screen" type="text/css" />
    </head>
    <body>
        <div id="main">
            <form action="./loginuser.php" method="POST">
                <h1>Connexion</h1>
            
            	<?php 
            	if ($_SESSION['login_error']) {
            	    ?><div class="messages">
            	    	<p class="error"><?=h($_SESSION['login_error'])?></p>
            	    </div><?php
            	}
            	?>
                <label><b>Adresse Mail</b></label>
                <input type="email" placeholder="Entrez votre email" name="email" required>

                <label><b>Mot de passe</b></label>
                <input type="password" placeholder="Entrez le mot de passe" name="password" required>

                <input type="submit" id='submit' value='Envoyer' >
            </form>
            <a href="./signin.php">Pas encore de compte ?</a>
        </div>
    </body>
</html>

