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
                <form class='form' action="./loginuser.php" method="POST">
                    <h1>Connexion</h1>
                
                    <?php 
                    if ($_SESSION['login_error']) {
                        ?><div class="messages">
                            <p class="error"><?=h($_SESSION['login_error'])?></p>
                        </div><?php
                    }
                    ?>
                    <div class='login-box'>
                        <label><b>Adresse Mail : </b></label>
                        <input type="email" placeholder="Entrez votre email" name="email" required>
                    </div>
                    <div class='login-box'>
                        <label><b>Mot de passe : </b></label>
                        <input type="password" placeholder="Entrez le mot de passe" name="password" required>
                    </div>
                    <input type="submit"  id='submit' value='Envoyer' >
                    <div class='link'>
                        <a href="./signin.php">Pas encore de compte ?</a>
                    </div>
                </form>
                
            </div>
        </div>
    </body>
</html>

