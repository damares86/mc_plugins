<?php

if($msg=="quoteDelSucc"){
    ?>
    <div class="alert alert-warning">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <?=$al_quoteDelSucc?>
    </div>

    <?php    
} else if($msg=="quoteDelErr"){
    ?>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <?=$al_quoteDelErr?>
    </div>

    <?php    
} else if($msg=="quoteMissing"){
    ?>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <?=$al_quoteMissing?>
    </div>

    <?php    
} else if($msg=="quoteSucc"){
    ?>
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <?=$al_quoteSucc?>
    </div>

    <?php    
} else if($msg=="quoteErr"){
    ?>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <?=$al_quoteErr?>
    </div>

    <?php    
} else  

?>