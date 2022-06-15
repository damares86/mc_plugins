<?php
require "../../admin/template/inc/header_portfolio.php";

?>
            <div id="bottomContainer">
                <?php
                $file = basename($_SERVER['PHP_SELF']);
                $page_class = pathinfo($file, PATHINFO_FILENAME);
                
                $portfolio->project_title=$page_name;
                $portfolio->showByTitle();


                ?>
                <div id="content">
                <?php
                if (isset($_SESSION['loggedin'])) {
                   
                    ?>
                <div class="text-right">
                    
                    <a href="admin/index.php?man=portfolio&op=edit&idToMod=<?=$portfolio->id?>" class="btn btn-primary btn-sm"><b><?=$pro_edit?></b></a>
                </div>
                    <?php
                }
                ?>
                    <div class="row">
                        <div class="col-12 pt-5 text-center">
                            <h2><?=$portfolio->project_title?></h2>
                        </div>
                    </div>
                    <div class="row px-5">
                        <div class="col-12 img_portfolio">
                            <img src="img/<?=$portfolio->main_img?>">
                        </div>
                    </div>
                    <div class="row p-5">                    
                        <div class="col-lg-8 p-3">
                            <p><?=$portfolio->description?></p>
                            <i class="solid fa-circle-calendar"></i>
                        </div>
                        <div class="col-lg-4 project_data p-5">
                            <p><b><?=$pro_client?>:</b><br> <?=$portfolio->client?></p>
                            <p><b><?=$pro_comp?>:</b><br> <?=$portfolio->completed?></p>
                            <?php
                            $categories_portfolio->id=$portfolio->category;
                            $categories_portfolio->showById();
                            ?>
                            <p><b><?=$pro_cat?>:</b><br> <?=$categories_portfolio->category_name?></p>
                            <p><a href="<?=$portfolio->link?>" class="button icon solid fa-arrow-circle-right"><?=$pro_goto?></a></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 pt-5 text-center">
                            <p><a href="../../portfolio.php" class="button icon solid fa-arrow-circle-left"><?=$pro_back?></a></p>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

<?php
require "../../admin/template/inc/footer.php";
?>