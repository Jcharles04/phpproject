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
            <form action="./signinuser.php" method="POST">
                <h1>Inscription</h1>
                
                <?php 
            	if ($_SESSION['signin_error']) {
            	    ?><div class="messages">
            	    	<p class="error"><?=h($_SESSION['signin_error'])?></p>
            	    </div><?php
            	}
            	?>
            
                <label><b>Nom</b></label>
                <input type="text" placeholder="Entrez votre nom" name="name" required>
                
				<label><b>Prénom</b></label>
                <input type="text" placeholder="Entrez votre prénom" name="firstname" required>
                
                <label><b>Service</b></label>
                <input type="text" placeholder="Votre service (ex: comptabilité)" name="serv" required>
                
                <label><b>Adresse Mail</b></label>
                <input type="email" placeholder="Entrez votre email" name="email" required>
                
                <label><b>Mot de passe</b></label>
                <input type="password" placeholder="Entrez le mot de passe" name="password" required>

                <input type="submit" id='submit' value='Envoyer' >
            </form>
            <a href="./login.php">Déjà un compte ?</a>
        </div>
    </body>
</html>
