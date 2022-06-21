<?php

$operation = "add";
$titoloForm = $regpost_title_add;

$postToMod="";
$idToMod="";
$category_id="";

if(filter_input(INPUT_GET,"idToMod")){
    $idToMod = filter_input(INPUT_GET,"idToMod");
    $titoloForm=$regpost_title_edit;
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
        <div class="card-header py-3">
        </div>
        <div class="card-body">
        <a href="#" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#infoPost">
                        <span class="icon text-white-50">
                            <i class="fas fa-fw fa-question"></i>
                        </span>
                        <span class="text"><?=$regpost_info?></span>
                    </a> <br>
             <!-- Info Modal-->
             <div class="modal fade" id="infoPost" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel"><b><?=$regpost_info?></b></h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body guide">
                                   <?=$regpost_desc?>
 
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary" type="button" data-dismiss="modal"><?=$txt_close?></button>
                                </div>
                            </div>
                        </div>
                    </div>
            <br>
           
            <br>
        <form id="postForm" class="form-horizontal row-fluid" action="core/mngPost.php" method="post"  enctype="multipart/form-data" onsubmit="return postForm()">
        <input type="hidden" name="operation" value="<?= $operation ?>" />
                <?php 
        $post->id = $idToMod;
      

                if($operation=="mod"){ 
                    ?>
                    <input type="hidden" name="idToMod" value="<?= $idToMod ?>" />
                    <?php 
                } 
                $post->showById();
                $category_id= $post->category_id;
                
                ?>

                <div class="control-group">
                    <label class="control-label" for="title"><?=$regpost_posttitle?></label>
                    <div class="controls">
                        <input type="text" id="title" name="title" placeholder="<?=$regpost_posttitle_ph?>" value="<?=$post->title?>" class="span8">
                        
                    </div>
                </div>
        
                <div class="control-group">
                    <label for="category_id"><?=$regpost_cat?></label>
                    <?php
                        $stmt = $categories->showAllList();
                        $total_rows = $categories->countAll();
                    
                        ?>
                    <select name="category_id">
                        <?php
                    
                    
                        
                    
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                
                            extract($row);
                        
                            $selected = "";
                            if ($id == $category_id) {
                                $selected = "selected";
                            }
                            echo "<option value='{$id}' $selected>{$category_name}</option>";

                        }

                        ?>
                    </select>
                </div>
           <br>
           <?php
            $img=$post->main_img;
           ?>
            <div class="control-group">
                <label class="control-label" for="file"><?=$regpost_img?></label>
                <div class="controls">
                    <input type="file" id="myfile" name="myfile">
                    <br><br>
                    <?php
                    if($operation=="mod"){
                    ?>
                    <?=$regpage_actual?> &nbsp;<img src="../uploads/img/<?=$img?>"  style="max-width:200px;">
                    <?php
                    }
                    ?>
                </div>
            </div>
   <br>

            <?php
                $display="";
                $checked="";
                if(!$post->gall||$post->gall=="none"){
                    $display="none";
            }else{
                $display="block";
                $checked="checked";
            }

        ?>
        <script>
            function show(i) {
                document.getElementById(i).style.visibility='visible';
            }
        </script>
        <script>
            $(document).ready(function(){
            $("#gall").click(function(){
                $("#addGall").toggle();
            });
            });
        </script>
        <input type="checkbox" name="selectGall" value="1" id="gall" <?=$checked?>>  <?=$regpost_add_gall?>
        <div class="control-group">
<div id="addGall" class="border-top border-bottom py-3"  style="display:<?=$display?>">
            <?=$regpost_gall?>
            <?php
            $dir_gall="../misc/gallery/img/";
            $dir_root="../misc/gallery/";

            function is_dir_empty($dir) {
                if (!is_readable($dir)) return null; 
                  return (count(scandir($dir)) == 2);
             }

            if( !is_dir($dir_gall) || is_dir_empty($dir_gall) ||is_dir_empty(($dir_root)) ){
                    echo "<div class='col'><div class='alert alert-danger'>$gall_nogall</div></div>";
                }else{
                    ?>
                    <select name="gall">
                        <?php
                        echo "<option value='none'>none</option>";

            foreach (glob("../misc/gallery/img/*") as $file) {
                $folder=pathinfo($file, PATHINFO_FILENAME);
                $gallery= str_replace("_"," ", $folder);
                $gallery=ucfirst($gallery);
                
                $selected ="";
                if($post->gall==$folder){
                    $selected="selected";
                }
                echo "<option value'$folder' $selected>$gallery</option>";
            ?>

        <?php
            }
        }
        
        ?>
        </select>

</div>   
        </div>
            <br>
            <h4><?=$regpost_summary?></h4>
            <textarea id="summernote" name="editor" rows="10">
                <?=$post->summary?>        
            </textarea>
            
            <br>

            <h4><?=$regpost_content?></h4>
            <textarea id="summernote2" name="editor2" rows="10">
                <?=$post->content?>        
            </textarea>
            <br>
                 <input type="submit" class="btn btn-primary" name="subReg" value="<?=$txt_submit?>">
        </form>

        

    </div>
</div>
</div>
</div>