<?php


// require '../vendor/autoload.php';		// If installed via composer
// $debug = new \bdk\Debug(array(
// 	'collect' => true,
// 	'output' => true,
// ));


// loading class
include("../../class/Database.php");
include("../../class/Plugins.php");
include("../../class/Page.php");
include("../../class/Home.php");
include("../../class/Menu.php");


$database = new Database();
$db = $database->getConnection();

$plugins = new Plugins($db);
$page = new Page($db);
$home = new Home($db);
$menu = new Menu($db);

require "config.php";

$plugins->plugin_name = $plugin_name;
$plugins->page_exist = $page_exist;
$plugins->description=$description;
$plugins->icon=$sidebar_icon;
$plugins->title=$sidebar_title;
$plugins->sub_show_title=$sidebar_sub_show_title;
$plugins->sub_show_link=$sidebar_sub_show_link;

$op = filter_input(INPUT_GET,"op");

if($op=="del"){

    $plugins->plugin_name=$plugin_name;

    $name = ucfirst($plugin_name);

    if($plugins->delete()){
        
        $error=0;

        if(is_file("../../inc/func/allPopup.php")){
            if(!unlink("../../inc/func/allPopup.php")){
                $error++;
            }
        }
        
        if(is_file("../../class/Popup.php")){
            if(!unlink("../../class/Popup.php")){
                $error++;
            }
        }

        if(is_file("../../core/mngPopup.php")){
            if(!unlink("../../core/mngPopup.php")){
                $error++;
            }
        }

        if(is_file("../../inc/func/regPopup.php")){
            if(!unlink("../../inc/func/regPopup.php")){
                $error++;
            }
        }

        if(is_file("../../locale/en/popup_en.php")){
            if(!unlink("../../locale/en/popup_en.php")){
                $error++;
            }
        }

        if(is_file("../../locale/it/popup_it.php")){
            if(!unlink("../../locale/it/popup_it.php")){
                $error++;
            }
        }

        if(is_file("../../inc/alert/popup_alert.php")){
            if(!unlink("../../inc/alert/popup_alert.php")){
                $error++;
            }
        }

        $db->query("DROP TABLE `popup`");

        $home->name_function="popup";
        $home->delete();
        
        unlink("../../inc/class_initialize.php");

        header("Location: ../../index.php?man=plugins&op=show&msg=pluginDelSucc");
        exit;
        
        }else{
            header("Location: ../../index.php?man=plugins&op=show&msg=pluginDelErr");
            exit;
        }

    } else if($op=="add"){

        if($plugins->create()){
            
            $error=0;

            // ALL
            if(copy('all/allPopup.php', '../../inc/func/allPopup.php')){
                chmod('../../inc/func/allPopup.php',0777);
            }else{
                $error++;
            }

            // CLASS
            if(copy('class/Popup.php', '../../class/Popup.php')){
                chmod('../../class/Popup.php',0777);
            }else{
                $error++;
            }

            // MNG
            if(copy('mng/mngPopup.php', '../../core/mngPopup.php')){
                chmod('../../core/mngPopup.php',0777);
            }else{
                $error++;
            }


            // REG
            if(copy('reg/regPopup.php', '../../inc/func/regPopup.php')){
                chmod('../../inc/func/regPopup.php',0777);
            }else{
                $error++;
            }


            // LOCALE EN
            if(copy('locale/en/popup_en.php', '../../locale/en/popup_en.php')){
                chmod('../../locale/en/popup_en.php',0777);
                }else{
                    $error++;
                }

            // LOCALE IT
            if(copy('locale/it/popup_it.php', '../../locale/it/popup_it.php')){
                chmod('../../locale/it/popup_it.php',0777);
                }else{
                    $error++;
                }

            
            // ALERT
            if(copy('alert/popup_alert.php', '../../inc/alert/popup_alert.php')){
                chmod('../../inc/alert/popup_alert.php',0777);
                }else{
                    $error++;
                }

            $db->query("CREATE TABLE IF NOT EXISTS popup
                ( id INT ( 5 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                content TEXT NOT NULL,
                pagename VARCHAR(255) NOT NULL)");

            // LOCAL

            unlink("../../inc/class_initialize.php");
            if($error==0){
                header("Location: ../../index.php?man=plugins&op=show&msg=pluginSucc");
                exit;
            }else{
                header("Location: ../../index.php?man=plugins&op=show&msg=pluginErr");
                exit;
            }

        }else{
            header("Location: ../../index.php?man=plugins&op=show&msg=pluginErr");
            exit;
        }
    }

