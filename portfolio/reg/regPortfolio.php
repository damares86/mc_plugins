<?php

$operation = "add";
$titoloForm = $regport_title_add;

$postToMod="";
$idToMod="";
$category_id="";

if(filter_input(INPUT_GET,"idToMod")){
    $idToMod = filter_input(INPUT_GET,"idToMod");
    $titoloForm= $regport_title_edit;
    $operation="mod";
}
$settings = new Settings ($db);
$stmt = $settings->showSettings();

?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?=$titoloForm?></h1>

                    </div><div class="row">

<!-- Content Column -->
<div class="col-lg-12 mb-4">

    <!-- Project Card Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
        <?php
        $portfolio->id = $idToMod;

        $portfolio->showById();
        ?>

        </div>
        <div class="card-body">

        <form id="postForm" class="form-horizontal row-fluid" action="core/mngPortfolio.php" method="post" onsubmit="return postForm()"  enctype="multipart/form-data">
        <input type="hidden" name="operation" value="<?= $operation ?>" />
                <?php 
      

                if($operation=="mod"){ 
                    ?>
                    <input type="hidden" name="idToMod" value="<?= $idToMod ?>" />
                    <?php 
                } 
                
                ?>
            <div class="control-group">
             
                <div class="controls">
                    <?php
                  if($operation=="mod"){
                            ?>
                    <strong><?= $portfolio->project_title ?></strong>
                    <input type="hidden" name="project_title" value="<?= $portfolio->project_title ?>" />
                    <?php
                       } else {
                    ?>

                    <input type="text" id="project_title" name="project_title" placeholder="<?=$regport_name_placeholder?>" value="<?= $portfolio->project_title ?>" class="span8">

                    <?php
                       }
                    ?>
                     
                </div>
            </div>
            
            <br>
            <div class="control-group">
            <label for="category"><?=$regport_category?></label>
            <?php
            $cat = new Categories_Portfolio($db);
                $stmt = $cat->showAllList();
                $total_rows = $cat->countAll();
              
                ?>
            <select name="category">
                <?php
               
               
                
               
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
           
                    extract($row);
                  
                    $selected = "";
                    if ($id == $portfolio->category) {
                        $selected = "selected";
                    }
                    echo "<option value='{$id}' $selected>{$category_name}</option>";

                }

                ?>
            </select>
            </div>
            <br>
            <div class="control-group">
                <label class="control-label" for="myfile"><?=$regport_img?></label>
                <div class="controls">
                    <input type="file" id="myfile" name="myfile">
                    <?php
                    if($operation=="mod"){
            $main_img=$portfolio->main_img;
       ?>
                        
                        <br><br>
                    <?=$regport_actual?><img src="../misc/portfolio/img/<?=$main_img?>"  style="max-width:200px;">
                    <?php
                    }
                    ?>
                </div>
            </div>
<br><br>
    <div class="control-group">
        <label class="control-label" for="client"><?=$regport_client?></label>
        <div class="controls">

            <input type="text" id="client" name="client" placeholder="<?=$regport_client_placeholder?>" class="span8" value="<?= $portfolio->client ?>">
                
        </div>
    </div>
<br>
    <div class="control-group">
        <label class="control-label" for="completed"><?=$regport_completed?></label>
        <div class="controls">
            <input type="date" class="fspan8" id="completed" placeholder="<?=$regport_completed_placeholder?>" name="completed" value="<?= $portfolio->completed ?>">
                
        </div>
    </div>
<br>
    <div class="control-group">
        <label class="control-label" for="link"><?=$regport_link ?></label>
        <div class="controls">

            <input type="text" id="link" name="link" placeholder="<?=$regport_link_placeholder?>" class="span8" value="<?= $portfolio->link ?>">
                
        </div>
    </div>


<br>
            <h3><?=$regport_description?></h3>

            <textarea id="summernote" name="editor" rows="10">   <?=$portfolio->description?></textarea>
            <br>
          
                 <input type="submit" class="btn btn-primary" name="subReg" value="<?=$txt_submit?>">
        </form>
        
        
    </div>
</div>
</div>
</div>