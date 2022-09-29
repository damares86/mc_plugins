<?php
require "admin/template/inc/header.php";

require "admin/core/config.php";


$id_cat="";
$total_rows="";

if(filter_input(INPUT_GET,"cat")){
    $id_cat=filter_input(INPUT_GET,"cat");
    $stmt = $portfolio->showByCatId($id_cat,$from_record_num, $records_per_page);
    $total_rows=$portfolio->countSelected();
}else{           
    $stmt = $portfolio->showAll($from_record_num, $records_per_page);
    $total_rows=$portfolio->countAll();
}

?>
            <div id="bottomContainer">
                <div id="content">
                    <div id="features-wrapper">
                            <div class="container">
                                <div class="row mb-5">
                                    <div class="col-12 text-center p-0"><h2>Portfolio</h2></div>
                                    <div class="col-12 text-left py-0">
                                    <a href="portfolio.php" class="button <?php
                                                if(filter_input(INPUT_GET,"cat")){
                                                    echo "inactive";
                                                }else{
                                                    echo "active";
                                                }
                                                $rows_all=$portfolio->countAll();
                                                ?>">All (<?=$rows_all?>)</a>
                                        <?php
                                            $stmt1=$categories_portfolio->showAllList();
                                            while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)){
            
                                                extract($row1);
                                                $actual_id=$row1['id'];
                                                $stmt2=$portfolio->showCat();
                                                $total=0;
                                                while($row2=$stmt2->fetch(PDO::FETCH_ASSOC)){
                                                    extract($row2);

                                                    $catArr=explode(",",$row2['category']);
                                                    if(in_array($actual_id,$catArr)){
                                                        $total++;
                                                    }
                                                }


                                                ?>
                                        <a href="?cat=<?=$row1['id']?>" id="<?=$row1['id']?>" class="button <?php
                                                if($id_cat==$row1['id']){
                                                    echo "active";
                                                }else{
                                                    echo "inactive";
                                                }
                                       ?>"><?=$row1['category_name']?> (<?=$total?>)</a>
                                        <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                <?php

                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            
                                        extract($row);
                                        $categories->id = $row['category'];	
                                        $catArr=explode(",",$row['category']);

                                        $str=$project_title;
                                        $str = preg_replace('/\s+/', '_', $str);			
			                            $str = strtolower($str);

                                 ?>
                                 <div class="col-4 col-12-medium">

                                 <!-- Box -->
                                     <section class="box feature">
                                         <a href="misc/portfolio/<?=$str?>.php" class="image featured"><img src="misc/portfolio/img/<?=$main_img?>" alt="" /></a>
                                         <div class="inner">
                                             <header>
                                                 <h2><?=$project_title?></h2>
                                                 <p><?=$client?></p>
                                             </header>
                                             <p class="text-center"><a href="misc/portfolio/<?=$str?>.php" class="button icon solid fa-arrow-circle-right">More</a></p>
                                         </div>
                                     </section>

                                </div>
                                <?php
                                }
                      
                                ?>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                 <?php
                                // paging buttons
                                include_once 'admin/inc/paging.php';
                                ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
require "admin/template/inc/footer.php";
?>