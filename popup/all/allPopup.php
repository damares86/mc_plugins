<?php
    require "core/config.php";

    $stmt = $popup->showAllPopup($from_record_num, $records_per_page);
    
    $total_rows=$popup->countAllPopup();

?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?=$popup_title?></h1>

                    </div><div class="row">

<!-- Content Column -->
<div class="col-lg-12 mb-4">

    <!-- Project Card Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?=$popup_box_title?></h6>
        </div>
        <div class="card-body">
            <a href="index.php?man=popup&op=add" class="btn btn-success btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text"><?=$popup_add?></span>
            </a>
            <br>
            <br>

        
        <?php

    if($total_rows>0){

        ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col"><?=$popup_tab_title?></th>
                    <th scope="col"><?=$popup_onpage?></th>
                    <th scope="col"><?=$popup_link?></th>
                    <th scope="col"><?=$txt_edit?></th>
                    <th scope="col"><?=$txt_delete?></th>
                </tr>
            </thead>
            <tbody>
            <?php
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);
        ?>
            <tr>
                <td><?=$title?></td>
                <?php
                $str=$pagename;
                $page_title = ucfirst($pagename);
                $page_title = str_replace('_', ' ', $page_title);
                ?>
                <td><?=$page_title?></td>
                <?php
                ?>
                <td><a href="../<?=$str?>.php"><?=$allpage_view?></a></td>
                <td>
                    <?php
                    if($no_mod==0||$page_name=="index"){
                        ?>

                <a href="index.php?man=popup&op=edit&idToMod=<?=$row["id"]?>&type=popup" class="btn btn-warning btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-pen"></i>
                            </span>
                            <span class="text"><?=$txt_edit?></span>
                        </a>   
                    <?php
                    }
                    ?>
                </td>

                <td>
                    <?php
                        if($no_mod==0){
                    ?>

                        <a href="#" class="btn btn-danger btn-icon-split" data-toggle="modal" data-target="#delete<?=$row['id']?>">
                            <span class="icon text-white-50">
                                <i class="fas fa-trash"></i>
                            </span>
                            <span class="text"><?=$txt_delete?></span>
                        </a> 
                        <?php
                    }
                    ?>
                
                        <!-- Delete Modal-->
                        <div class="modal fade" id="delete<?=$row['id']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                      aria-hidden="true">
                      <div class="modal-dialog" role="document">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel"><b><?=$txt_modal_title?></b></h5>
                                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">×</span>
                                  </button>
                              </div>
                              <div class="modal-body"><?=$popup_modal_text?></div>
                              <div class="modal-footer">
                                  <button class="btn btn-secondary" type="button" data-dismiss="modal"><?=$txt_cancel?></button>
                                  <a class="btn btn-primary" href="core/mngPopup.php?idToDel=<?=$row["id"]?>">Ok</a>
                              </div>
                          </div>
                      </div>
                  </div>
                <?php
                }
    
                ?>
            </td>
            </tr>


            </tbody>
        </table>
        <?php
        // paging buttons
        include_once 'inc/paging.php';
    } else{
        echo "<div class='alert alert-danger'>$popup_nopopup</div>";
    }


?>

    </div>
</div>
</div>
</div>