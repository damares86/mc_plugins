<?php
    require "core/config.php";
    $quotes="";
    if(is_file("inc/quotes.json")){
        $json_file = 'inc/quotes.json';
        $data = file_get_contents($json_file);
        $quotes = json_decode($data, true);
        $count=count($quotes);
    }
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?=$quote_title?></h1>

                    </div><div class="row">

<!-- Content Column -->
<div class="col-lg-12 mb-4">

    <!-- Project Card Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><?=$quote_box_title?></h6>
        </div>
        <div class="card-body">
        <form class="form-horizontal row-fluid" action="core/mngQuote.php" method="POST">
            <div class="control-group">
                <label class="control-label" for="quote"><?=$quote_quoteAdd?></label>
                <div class="controls">
                    <input type="text" id="quote" name="quote" placeholder="<?=$quote_quoteAdd_ph?>" value="" class="span8">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="author"><?=$quote_authorAdd?></label>
                <div class="controls">
                    <input type="text" id="author" name="author" placeholder="<?=$quote_authorAdd_ph?>" value="" class="span8">
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                   
                    <input type="submit" class="btn btn-primary" name="subReg" value="<?=$txt_submit?>">

                </div>
            </div>
        </form>
        
        <br>
        <?php

    if(!empty($quotes)){
        ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col"><?=$quote_authorAdd?></th>
                    <th scope="col"><?=$quote_quoteAdd?></th>
                    <th scope="col"><?=$txt_delete?></th>
                </tr>
            </thead>
            <tbody>
            <?php
            for($i=0;$i<$count;$i++){
            ?>
            <tr>
                <td><?=$quotes[$i]['author']?></td>
                <td><?=$quotes[$i]['quote']?></td>

                <td>
                    <a href="#" class="btn btn-danger btn-icon-split" data-toggle="modal" data-target="#delete<?=$quotes[$i]['id'] ?>">
                        <span class="icon text-white-50">
                            <i class="fas fa-trash"></i>
                        </span>
                        <span class="text"><?=$txt_delete?></span>
                    </a>            
                </td>
            </tr>
                    <!-- Delete Modal-->
                 <div class="modal fade" id="delete<?=$quotes[$i]['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel"><b><?=$txt_modal_title?></b></h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body"><?=$quote_modal_text?></div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal"><?=$txt_cancel?></button>
                                    <a class="btn btn-primary" href="core/mngQuote.php?idToDel=<?=$quotes[$i]['id'] ?>">Ok</a>
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
    } else{
        echo "<div class='alert alert-danger'>$quote_noquote</div>";
    }


?>

    </div>
</div>
</div>
</div>