
<div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"><?=$backup_title?></h1>

                    </div><div class="row">

<!-- Content Column -->
<div class="col-lg-12 mb-4">

    <!-- Project Card Example -->
    <div class="card shadow mb-4">
        <div class="card-header"></div>
        <div class="card-body">
  <div class="row">
      <div class="col">
          <h3><?=$backup_box_title?></h3>
          <p><?=$backup_box_text?></p>
          <br>
       <form class="form-horizontal row-fluid" action="core/mngBackup.php" method="POST" enctype="multipart/form-data">
         <div class="control-group">
                <div class="controls">
                    
                    <input type="submit" class="btn btn-primary" name="subReg" value="Backup">
                    
                    
                </div>
            </div>
        </form>
        </div>
      <div class="col guide">
        <?=$backup_desc?>
      </div>
  </div>
    </div>
        </div>

    </div>
</div>
