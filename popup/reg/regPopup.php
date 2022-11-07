<?php
$operation = "add";
$titoloForm = $popup_add_title;

$popToMod="";
$idToMod="";

if(filter_input(INPUT_GET,"idToMod")){
    $idToMod = filter_input(INPUT_GET,"idToMod");
    $titoloForm= $popup_mod_title;
    $operation="mod";
}

$all_pages=$page->showAllPages();

if($operation=="add"){
    if(!isset($_REQUEST['more'])){
        $popup->destroyPopupSessVar();
    }
}else if($operation=="mod"&&!isset($_REQUEST['more'])){
        $popup->destroyPopupSessVar();
        $popup->id_popup=$idToMod;
        $popup->showPopupById();
        $popup-> modPopupSessVar();
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
 
           
            <br>

        <form id="postForm" class="form-horizontal row-fluid" action="core/mngPopup.php" method="post" enctype="multipart/form-data">

            <input type="hidden" name="operation" value="<?= $operation ?>" />
               
            <input type="hidden" name="idToMod" value="<?= $idToMod ?>" />
            
            <div class="control-group">
                <label class="control-label">
                <?=$popup_tab_title?>
                </label>
                <div class="controls">
<input type="text" id="title" name="title" placeholder="<?=$popup_title_ph?>" value="<?=$_SESSION['sess_popup_title']?>" class="span8">                     
</div>
            </div>
            
        <div class="control-group">
            <span style="font-weight:bold; font-size: 1.5em;"><?=$popup_content?></span> &nbsp; &nbsp;<br><br>
            <!-- TEXT BOX -->
            <div class="t1 box1">
                <textarea id="editor1" name="editor1" rows="10">   <?=$_SESSION['sess_popup_editor']?></textarea>
                <br>
            </div>
        </div>
        <div class="control-group">            
            <label class="control-label">
                <?=$popup_onpage?>
            </label>
            <div class="controls">
                <select name="pagename">
                <?php
                    $count=count($all_pages);
                    for($i=0;$i<$count;$i++){
                        $selected="";
                        $pagename=$all_pages[$i];
                        $filename=strtolower($pagename);
                        $filename=str_replace(" ","_",$filename);
                        if($filename==$_SESSION['sess_popup_page_name']){
                            $selected="selected";
                        }
                ?>

                    <option value='<?=$filename?>' <?=$selected?>><?=$pagename?></option>;

                <?php
                    }
                ?>
                </select>
            </div>
        </div>
            <br>
                 <input type="submit" class="btn btn-primary" name="subReg" value="<?=$txt_submit?>">
        </form>
        
        
    </div>
</div>
</div>
</div>