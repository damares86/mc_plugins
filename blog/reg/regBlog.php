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
        <form id="postForm" class="form-horizontal row-fluid" action="core/mngBlog.php" method="post"  enctype="multipart/form-data" onsubmit="return postForm()">
        <input type="hidden" name="operation" value="<?= $operation ?>" />
        <input type="hidden" name="author" value="<?= $user_name ?>" />

                <?php 
        $post->id = $idToMod;

		     

                if($operation=="mod"){ 
                    ?>
                    <input type="hidden" name="idToMod" value="<?= $idToMod ?>" />
                    <?php 
                } 
                $post->showById();
                $catNames="";
                $catArr=array();
                if($operation=="add"){
                    if(!isset($_REQUEST['more'])){
                        $post->destroyCheckSessVar();
                    }
                    $catNames=$_SESSION['blog_select_cat'];
                    foreach($catNames as $names){
                        $categories->category_name=$names;
                        $categories->showByName();
                        $catArr[]=$categories->id;
                    }
                }else if($operation=="mod"&&!isset($_REQUEST['more'])){
                    $post->destroyCheckSessVar();
                    $post->modCheckSessVar();
                    $category_id= $_SESSION['blog_select_cat'];
                    $catArr=explode(",",$category_id);
                }

                                                
                ?>

                <div class="control-group">
                    <label class="control-label" for="title"><?=$regpost_posttitle?></label>
                    <div class="controls">
                        <input type="text" id="title" name="title" placeholder="<?=$regpost_posttitle_ph?>" value="<?= $_SESSION['blog_title']?>" class="span8">
                        
                    </div>
                </div>
                <br>
        
                <div class="control-group">
                    <label class="control-label" for="myMultiselect" ><?=$regpost_cat?></label>
                    <div class="controls">
                        <div id="myMultiselect" class="multiselect">
                            <div id="mySelectLabel" class="selectBox" onclick="toggleCheckboxArea()">
                                <select class="form-select">
                                <option><?=$regpost_no_cat?></option>
                                </select>
                                <div class="overSelect"></div>
                            </div>
                            <div id="mySelectOptions">
                                <?php
                                
                                $stmt = $categories->showAllList();
                                        
                                $total_rows = $categories->countAll();
                                
                                // ciclare gli id delle categorie

                                foreach($stmt as $row){

                                    $checked="";
                                    
                                    if(in_array($row['id'], $catArr)){
                                        $checked="checked";
                                    }else{
                                        $checked="";
                                    }
                                    ?>
                                    <label for="<?=$row['id']?>"><input type="checkbox"  name="select_cat[]" id="<?=$row['id']?>" onchange="checkboxStatusChange()" value="<?=$row['category_name']?>" <?=$checked?> /> <?=$row['category_name']?></label>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

           <br>
           <?php
            $img=$post->main_img;
           ?>
            <div class="control-group">
                <label class="control-label" for="file"><?=$regpost_img?></label>
                <div class="controls">
                    <input type="file" id="myfile" name="myfile">
                    <?php
                        $picture=$_SESSION['blog_img'];
                    ?>
                    <input type="hidden" name="old_img" value="<?= $picture ?>" />

                    <br><br>
                    <?php

                    if($_SESSION['blog_old_img']){
                    ?>
                    <?=$regpage_actual?> &nbsp;<img src="../uploads/img/<?=$picture?>"  style="max-width:200px;">
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
            <textarea id="editor1" name="editor" rows="10">
                <?=$_SESSION['blog_editor']?>        
            </textarea>
            
            <br>

            <h4><?=$regpost_content?></h4>
            <textarea id="editor2" name="editor2" rows="10">
                <?=$_SESSION['blog_editor2']?>        
            </textarea>
            <br>
                 <input type="submit" class="btn btn-primary" name="subReg" value="<?=$txt_submit?>">
        </form>

        

    </div>
</div>
</div>
</div>