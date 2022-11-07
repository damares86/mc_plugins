<?php
if($msg=="popupDelSucc"){
    ?>
    <div class="alert alert-warning">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <?=$al_popupDelSucc?>
    </div>

    <?php    
} else if($msg=="popupDelErr"){
    ?>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <?=$al_popupDelErr?>
    </div>

    <?php    
} else if($msg=="popupSucc"){
    ?>
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <?=$al_popupSucc?>
    </div>

    <?php    
} else if($msg=="popupErr"){
    ?>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <?=$al_popupErr?>
    </div>

    <?php    
} else if($msg=="popupModSucc"){
    ?>
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <?=$al_popupModSucc?>
    </div>

    <?php    
} else if($msg=="popupModErr"){
    ?>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <?=$al_popupModErr?>
    </div>

    <?php    
} else

?>