<?php

$operation = "add";
$titoloForm = $regcat_title_add;

$catToMod="";
$idToMod="";

if(filter_input(INPUT_GET,"idToMod")){
    $idToMod = filter_input(INPUT_GET,"idToMod");
    $titoloForm=$regcat_title_edit;
    $operation="mod";
}


?>


<div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?=$titoloForm?></h1>

                    </div><div class="row">

<!-- Content Column -->
<div class="col-lg-12 mb-4">

    <!-- Project Card Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
        <form class="form-horizontal row-fluid" action="core/mngCat.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="operation" value="<?=$operation?>" />

        <?php 
      
        // $categories = new Categories($db);
        $categories->id = $idToMod;
        

      
           if($operation=="mod"){ 
                    ?>
                    <input type="hidden" name="idToMod" value="<?= $idToMod ?>" />
                <?php 
                } 
        $categories->showById();

        ?>
            <div class="control-group">
                <label class="control-label" for="category_name"><?=$regcat_name?></label>
                <div class="controls">
                    <input type="text" id="category_name" name="category_name" placeholder="<?=$regcat_name_ph?>" value="<?=$categories->category_name?>" class="span8">
                     
                </div>
            </div>
          <br>
            <div class="control-group">
                <div class="controls">
                   
                    <input type="submit" class="btn btn-primary" name="subReg" value="<?=$txt_submit?>">

                </div>
            </div>
        </form>

    </div>
</div>
</div>
</div>
