<?php

require '../../admin/phpDebug/src/Debug/Debug.php';   			// if not using composer

$debug = new \bdk\Debug(array(
    'collect' => true,
    'output' => true,
));

session_start();

// loading class

if(!is_file("../../admin/class/Database.php")){
    header("Location: ../../admin/inc/dbdata.php");
    exit;
}
spl_autoload_register('autoloader');

require "../../admin/inc/version.php";

function autoloader($class){
    include("../../admin/class/$class.php");
}

$database = new Database();
$db = $database->getConnection();

$files=glob("../../admin/class/*.php", GLOB_BRACE);
rsort($files); 

if(!is_file('../../admin/inc/class_initialize.php')){
$file_handle = fopen('../../admin/inc/class_initialize.php', 'w');
fwrite($file_handle, '<?php');
fwrite($file_handle, "\n");
foreach ($files as $filename) {
    $nomefile = pathinfo($filename);
    $file=$nomefile['filename'];
    $file_var = strtolower($file);
    fwrite($file_handle, '$'.$file_var.' = new '.$file.'($db);');
    fwrite($file_handle, "\n");
}
fwrite($file_handle,"?>");
chmod('../../admin/inc/class_initialize.php',0777);

}

include "../../admin/inc/class_initialize.php";


$stmt=$settings->showSettings();

$stmt1=$settings->showLangAndName();
$lang=$settings->dashboard_language;
foreach (glob("../../admin/locale/$lang/*.php") as $file){
    require "$file";
}

// prendo il nome del file (con estensione)
$file = basename($_SERVER['PHP_SELF']);

$post_title="";
if(filter_input(INPUT_GET,"id")){
    $post_id=filter_input(INPUT_GET,"id");
    
    $post->id=$post_id;
    $post->showById();
    $post_title = $post->title;
}


$page_name="";
$page_class="";
if($file=="login.php"){
    if (isset($_SESSION['loggedin'])) {
        header('Location: ../../admin/');
        exit;
    }
}

// mi prendo solo il nome senza l'estensione
$page_name = pathinfo($file, PATHINFO_FILENAME);
$page_class = pathinfo($file, PATHINFO_FILENAME);
// rimuovo gli _ (underscore) che ho messo nel nome file
$page_name=str_replace("_"," ", $page_name);
// metto la prima lettera maiuscola
$page_name=ucfirst($page_name);
$root="../../";
$lang="";


while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    
    extract($row);
    $theme=$row['theme'];
    $lang=$row['dashboard_language'];
    
    ?>

<!doctype html>
<html>
    <head>
        <!--
            ==========================================================================
            
            Mini Cms is a project by damares86 (https://github.com/damares86/mini_cms)
            
            ==========================================================================
        -->
		<meta charset="utf-8">
		<title><?=$page_name?> - <?=$site_name?></title>
        <link rel="icon" href="<?=$root?>assets/<?= $theme ?>/img/favicon.ico">
        <?php
          
          
          $page->page_name='Portfolio';
          $stmt=$page->showByNameDefault();
      
          $img=$page->img;
          
          
          ?>
        <link href='<?=$root?>admin/template/inc/layout.css' rel='stylesheet' type='text/css'>

		<?php

require "$root/assets/".$theme."/inc/scripts.php";
// require "../../admin/inc/func/check.php";
if(($file=="login.php")||($file=="contact.php")){
    require "$root/assets/".$theme."/inc/recaptcha.php";
}
?>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
	</head>
    
	<body>
        <?php
            $style="";
            if(isset($_SESSION['loggedin'])){
                $style="style='margin-top:1.8em'";
                ?>
        <div id="adminBar">
            <a href="<?=$root?>admin"><?=$goToAdmin?></a>
            &nbsp; - &nbsp;
            <a href="<?=$root?>admin/core/logout.php">Logout</a>
        </div>
        <?php
            }
        ?>
        <div id="siteContainer" <?=$style?>>
            <div id="topContainer">
            <header>
                <?php
                    require "../../assets/".$theme."/inc/header.php";
                ?>  
                </header>
                <?php
                    if($page->header==1){
                    ?>
                <div id="banner-wrapper">
                <div id="banner-wrapper">
                <?php    
                if(pathinfo($page->img, PATHINFO_EXTENSION)){
                ?>
                    <div id="banner" class="box container" style="background-image: url(<?=$root?>uploads/img/<?=$img?>);">
						<div class="row">
                            <div class="col-7 col-12-medium">
                            <?php
                            if($page->use_name==1){
                            ?>
								<h2><?=$site_name?></h2>
                            <?php
                            }
                            
                            if($page->use_desc==1){
                            ?>

								<p><?=$site_description?></p>
                            <?php
                            }
                            ?>
							</div>
						</div>
					</div>
                <?php
                }else{
                        $gallery_name_visual=$page->img;
                        $folder_visual= str_replace(" ","_", $gallery_name_visual);
                        $folder_visual=strtolower($folder_visual);
                ?>
                        <script>
                            $('#myVisualCarousel').carousel({
                                interval: 2000,
                                cycle: true
                            })
                        </script>

                            <div id="#myVisualCarousel" class="carousel slide" data-ride="carousel">
                                <ol class="carousel-indicators">
                                <?php

                                $dirCarousel="misc/gallery/img/$folder_visual/";

                                $idx=0;
                                foreach (glob($dirCarousel."*") as $file) {
                                    
                                    $active="";
                                    if($idx==0){
                                    $active="class=\"active\"";
                                    }
                                
                                ?>
                                <li data-target="#myVisualCarousel" data-slide-to="<?=$idx?>" <?=$class?>></li>
                                <?php

                                    $idx++;
                                }
                                ?>
                                </ol>
                                <div class="carousel-inner">

                                <?php
                                $idx=0;
                                foreach (glob($dirCarousel."*") as $file) {
                                    $img=pathinfo($file, PATHINFO_FILENAME);
                                    $ext=pathinfo($file, PATHINFO_EXTENSION);
                                    $imgName=$img.".".$ext;

                                    $active="";
                                    if($idx==0){
                                    $active="active";
                                    }

                                    $numberArr=array('first','second','third','fourth','fifth','sixth','seventh','eighth','ninth','tenth');

                                    $number=$numberArr[$idx];

                                    ?>
                                <div class="carousel-item <?=$active?>">
                                    <img class="<?=$number?>-slide" src="<?=$dirCarousel?>/<?=$imgName?>" alt="<?=$number?> slide">
                                </div>
                                <?php
                                $idx++;
                                }
                                ?>
                                
                                </div>
                                
                            </div>
                <?php
                $idx=0;
                }
                ?>
				</div>
                    <?php
                    }
                ?> 
            </div>
            <?php
}
?>