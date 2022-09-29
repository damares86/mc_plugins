<?php
    require "core/config.php";

	$database = new Database();
	$db = $database->getConnection();

	$portfolio = new Portfolio($db);
	$cat = new Categories_Portfolio($db);
    
    $stmt = $portfolio->showAll($from_record_num, $records_per_page);
    
    $total_rows=$portfolio->countAll();

?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?=$port_title?></h1>

                    </div><div class="row">

<!-- Content Column -->
<div class="col-lg-12 mb-4">

    <!-- Project Card Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?=$port_box_title?></h6>
        </div>
        <div class="card-body">
            <a href="index.php?man=portfolio&op=add" class="btn btn-success btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text"><?=$port_add?></span>
            </a>
            <br>
            <br>

        
        <?php

    if($total_rows>0){

        ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col"><?=$port_project_title ?></th>
                    <th scope="col"><?=$port_completed?></th>
                    <th scope="col"><?=$port_category?></th>
                    <th scope="col"><?=$port_view ?></th>
                    <th scope="col"><?=$txt_edit?></th>
                    <th scope="col"><?=$txt_delete?></th>
                </tr>
            </thead>
            <tbody>
            <?php
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);
        $str=$project_title;
        $str = preg_replace('/\s+/', '_', $str);			
        $str = strtolower($str);

        // $categories_portfolio->id=$category;
        // $categories_portfolio->showById();

        ?>
            <tr>
                <td><?=$project_title?></td>
                <td><?=$completed?></td>
                <td>
                    - <?php
                            $catArr=explode(",",$category);
                            
                            foreach($catArr as $row1){
                                $categories_portfolio->id = $row1['id'];
                                $categories_portfolio->showById();                                        
                                $category_name= $categories_portfolio->category_name;
                                echo "$category_name - ";
                            }
                        ?>                
                </td>
                <td><a href="../misc/portfolio/<?=$str?>.php"><?=$port_link?></a></td>
               
                <td>
                <a href="index.php?man=portfolio&op=edit&idToMod=<?=$row["id"]?>" class="btn btn-warning btn-icon-split">
                            <span class="icon text-white-50">
                                <i class="fas fa-pen"></i>
                            </span>
                            <span class="text"><?=$txt_edit?></span>
                        </a>   
                <td>
                        <a href="#" class="btn btn-danger btn-icon-split" data-toggle="modal" data-target="#delete<?=$id?>">
                            <span class="icon text-white-50">
                                <i class="fas fa-trash"></i>
                            </span>
                            <span class="text"><?=$txt_delete?></span>
                        </a> 
                
                        </td>
                        <!-- Delete Modal-->
                        <div class="modal fade" id="delete<?=$id?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel"><b><?=$txt_modal_title?></b></h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body"><?=$port_modal_text?></div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal"><?=$txt_cancel?></button>
                                    <a class="btn btn-primary" href="core/mngPortfolio.php?idToDel=<?=$id?>">Ok</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
           
                ?>
            </tr>
                          


            </tbody>
        </table>
        <?php
        // paging buttons
        include_once 'inc/paging.php';
    } else{
        echo "<div class='alert alert-danger'>$port_noproject</div>";
    }


?>

    </div>
</div>
</div>
</div>