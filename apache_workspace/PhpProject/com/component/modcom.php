<?php

use apputils\Util;

include_once '__app.php';

require_once __APPDIR__ . '/apputils/Util.php';

function modCom($thisCom) {
    ?>

    <div class="modCom">
        <form class='form-wrapper' action="./modifyComVal.php" method="POST" enctype="multipart/form-data">
            <div class='field'> 
                <div class='img-field'>   
                    <?php if ($thisCom['ImgUrl'] != NULL): ?>
                        <div class='three'>
                            <label for="file">Une autre image à partager?</label>
                        </div>
                        <div class='four'>
                            <img src="<?=h(Util::APP_URL('/upload/', $thisCom['ImgUrl']))?>"/>
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
                        <textarea id="text" name="text" rows="15" cols="33"><?=h($thisCom['Text'])?></textarea>
                    </div>
                </div>  
            </div>    	
            <input id="delete-image" name="delete-image" type="hidden" value="0"/>
            <input id="comId" name="comId" type="hidden" value="<?=$thisCom['id']?>"/>
            <div class='six'>
                <input type="file" name="file"/>
            </div>
            <div class='seven'>
                <button type="submit" id="submit" value="Envoyer">Envoyez</button>
            </div>
        </form>
    </div>

    <?php
}