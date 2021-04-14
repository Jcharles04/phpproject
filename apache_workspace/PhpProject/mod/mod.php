<?php

use apputils\Util;

include '../__app.php';;

require_once __APPDIR__ . '/Database/db.php';
require_once __APPDIR__ . '/apputils/Util.php';

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
		
		<section class='container '>
            <div class='array'>
                <table>
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Service</th>
                            <th>Suppression</th>
                        </tr>
                    </thead>
                    <?=getAllUser(); ?>
                </table>
            </div>	
		</section>
	</body>
</html>

<?php 

function renderUsers($users){
?>  
    <tbody>
        <tr>
            <td><?=$users['id']?></td>
            <td><?=$users['Name']?></td>
            <td><?=$users['FirstName']?></td>
            <td><?=$users['Mail']?></td>
            <td><?=$users['Service']?></td>
            <td>
                <form action="../log/deleteUser.php" method="POST">
                    <input id="userId" name="userId" type="hidden" value='<?=$users['id']?>'/>
                    <button type='submit'  value=''>X</button>
                </form>
            </td>
        </tr>
    </tbody>
<?php
}
?>
