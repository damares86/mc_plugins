<?php
    require "core/config.php";

    $stmt = $categories->showAll($from_record_num, $records_per_page);

    $total_rows=$categories->countAll();

?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?=$cat_title?></h1>

                    </div><div class="row">

<!-- Content Column -->
<div class="col-lg-12 mb-4">

    <!-- Project Card Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?=$cat_box_title?></h6>
        </div>
        <div class="card-body">
            <a href="index.php?man=cat&op=add" class="btn btn-success btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text"><?=$cat_add ?></span>
            </a>
            <br>
            <br>
       
  

        <?php

if($total_rows>0){
    ?>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col"><?=$cat_name?></th>
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
                <td><?=$category_name?></td>

      
                       
                        <td>
                        <a href="index.php?man=cat&op=edit&idToMod=<?=$row["id"] ?>" class="btn btn-warning btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-pen"></i>
                            </span>
                            <span class="text"><?=$txt_edit?></span>
                        </a>   
                        </td>
                        
                        <td>
                        <a href="#" class="btn btn-danger btn-icon-split" data-toggle="modal" data-target="#delete<?=$row['id']?>">
                            <span class="icon text-white-50">
                                <i class="fas fa-trash"></i>
                            </span>
                            <span class="text"><?=$txt_delete?></span>
                        </a>    
                    
                        </td>
                    </tr>
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
                                <div class="modal-body"><?=$cat_modal_text?></div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal"><?=$txt_cancel?></button>
                                    <a class="btn btn-primary" href="core/mngCat.php?idToDel=<?=$row["id"] ?>">Ok</a>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php
                }
                ?>


            </tbody>
        </table>
        <?php
        // paging buttons
        include_once 'inc/paging.php';
    }
      
    // tell the user there are no products
    else{
        echo "<div class='alert alert-danger'>$cat_nocat</div>";
    }


?>
      </div>
    </div>
</div>
</div>  