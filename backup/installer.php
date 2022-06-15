<?php

require '../../phpDebug/src/Debug/Debug.php';   			// if not using composer

$debug = new \bdk\Debug(array(
    'collect' => true,
    'output' => true,
));


// loading class
include("../../class/Database.php");
include("../../class/Plugins.php");
include("../../class/Page.php");
include("../../class/Home.php");


$database = new Database();
$db = $database->getConnection();

$plugins = new Plugins($db);
$page = new Page($db);
$home = new Home($db);

require "config.php";

$plugins->plugin_name = $plugin_name;
$plugins->link = $link;
$plugins->page_exist = $page_exist;
$plugins->second_page = $second_page;
$plugins->description=$description;
$plugins->icon=$sidebar_icon;
$plugins->title=$sidebar_title;
$plugins->sub_show_title=$sidebar_sub_show_title;

$op = filter_input(INPUT_GET,"op");

if($op=="del"){

    $plugins->plugin_name=$plugin_name;

    $name = ucfirst($plugin_name);

    if($plugins->delete()){
        
        $error=0;

        if(is_file("../../inc/alert/backup_alert.php")){
            if(!unlink("../../inc/alert/backup_alert.php")){
                $error++;
            }
        }

        if(is_file("../../inc/func/regBackup.php")){
            if(!unlink("../../inc/func/regBackup.php")){
                $error++;
            }
        }

        if(is_file("../../locale/en/backup_en.php")){
            if(!unlink("../../locale/en/backup_en.php")){
                $error++;
            }
        }

        if(is_file("../../locale/it/backup_it.php")){
            if(!unlink("../../locale/it/backup_it.php")){
                $error++;
            }
        }

        if(is_file("../../core/mngBackup.php")){
            if(!unlink("../../core/mngBackup.php")){
                $error++;
            }
        }

        if(is_file("../../core/restore_db.php")){
            if(!unlink("../../core/restore_db.php")){
                $error++;
            }
        }

        
        $home->name_function="backup";
        $home->delete();
        


			if($home->delete()){
                header("Location: ../../index.php?man=plugins&op=show&msg=pluginDelSucc");
                exit;
			}else{
                header("Location: ../../index.php?man=plugins&op=show&msg=pluginDelErr");
                exit;
			}

		
            }else{
                header("Location: ../../index.php?man=plugins&op=show&msg=pluginDelErr");
                exit;
			}

    } else if($op=="add"){

        if($plugins->create()){
            
            $error=0;

                        
            // ALERT
            if(copy('alert/backup_alert.php', '../../inc/alert/backup_alert.php')){
                chmod('../../inc/alert/backup_alert.php',0777);
                }else{
                    $error++;
                }

            // ALL
            if(copy('reg/regBackup.php', '../../inc/func/regBackup.php')){
                chmod('../../inc/func/regBackup.php',0777);
            }else{
                $error++;
            }

            // LOCALE EN
            if(copy('locale/en/backup_en.php', '../../locale/en/backup_en.php')){
                chmod('../../locale/en/backup_en.php',0777);
                }else{
                    $error++;
                }

            // LOCALE IT
            if(copy('locale/it/backup_it.php', '../../locale/it/backup_it.php')){
                chmod('../../locale/it/backup_it.php',0777);
                }else{
                    $error++;
                }

            // MNG
            if(copy('mng/mngBackup.php', '../../core/mngBackup.php')){
                chmod('../../core/mngBackup.php',0777);
            }else{
                $error++;
            }
    
            if(copy('config/restore_db.php', '../../core/restore_db.php')){
                chmod('../../core/restore_db.php',0777);
            }else{
                $error++;
            }
    
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

