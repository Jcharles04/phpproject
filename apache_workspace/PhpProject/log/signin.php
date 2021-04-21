<?php 
include_once '__app.php';
?>

<!DOCTYPE html>

<html>
    <head>
       <meta charset="utf-8">
        <link rel="stylesheet" href="../assets/style.css" media="screen" type="text/css" />
    </head>
    <body>
        <div id="main">
            <div class='login'>
                <form class='form' action="./signinuser.php" method="POST">
                    <h1>Inscription</h1>
                    
                    <?php 
                    if ($_SESSION['signin_error']) {
                        ?><div class="messages">
                            <p class="error"><?=h($_SESSION['signin_error'])?></p>
                        </div>
                    <?php
                    }
                    ?>
                    <div class='login-box'>
                        <label><b>Nom : </b></label>
                        <input type="text" placeholder="Entrez votre nom" name="name" required>
                    </div>   
                    <div class='login-box'>
                        <label><b>Prénom : </b></label>
                            <input type="text" placeholder="Entrez votre prénom" name="firstname" required>
                    </div>        
                    <div class='login-box'>
                        <label><b>Service : </b></label>
                        <input type="text" placeholder="Votre service (ex: compta...)" name="serv" required>
                    </div>   
                    <div class='login-box'>
                        <label><b>Adresse Mail : </b></label>
                        <input type="email" placeholder="Entrez votre email" name="email" required>
                    </div>    
                    <div class='login-box'>    
                        <label><b>Mot de passe : </b></label>
                        <input type="password" placeholder="Entrez le mot de passe" name="password" required>
                    </div>
                    <div class='login-box'>
                        <input type="submit" id='submit' value='Envoyer' >
                    </div>
                    <div class='login-box'>
                        <a href="./login.php">Déjà un compte ?</a>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
