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

        if(is_file("../../inc/func/allQuotes.php")){
            if(!unlink("../../inc/func/allQuotes.php")){
                $error++;
            }
        }

        if(is_file("../../core/mngQuotes.php")){
            if(!unlink("../../core/mngQuotes.php")){
                $error++;
            }
        }

        if(is_file("../../template/inc/css/quotes.css")){
            if(!unlink("../../template/inc/css/quotes.css")){
                $error++;
            }
        }

        if(is_file("../../scripts/var/quotes.js")){
            if(!unlink("../../scripts/var/quotes.js")){
                $error++;
            }
        }

        if(is_file("../../locale/en/quotes_en.php")){
            if(!unlink("../../locale/en/quotes_en.php")){
                $error++;
            }
        }

        if(is_file("../../locale/it/quotes_it.php")){
            if(!unlink("../../locale/it/quotes_it.php")){
                $error++;
            }
        }

        if(is_file("../../inc/alert/quotes_alert.php")){
            if(!unlink("../../inc/alert/quotes_alert.php")){
                $error++;
            }
        }

        if(is_file("../../inc/quotes.json")){
            if(!unlink("../../inc/quotes.json")){
                $error++;
            }
        }

        $home->name_function="quotes";
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
            if(copy('all/allQuotes.php', '../../inc/func/allQuotes.php')){
                chmod('../../inc/func/allQuotes.php',0777);
            }else{
                $error++;
            }


            // MNG
            if(copy('mng/mngQuote.php', '../../core/mngQuote.php')){
                chmod('../../core/mngQuotes.php',0777);
            }else{
                $error++;
            }


            // TEMPLATE
            if(copy('template/quotes.css', '../../template/inc/css/quotes.css')){
                chmod('../../template/inc/css/quotes.css',0777);
            }else{
                $error++;
            }

            // SCRIPTS
            if(copy('scripts/quotes.js', '../../scripts/var/quotes.js')){
                chmod('../../scripts/var/quotes.js',0777);
            }else{
                $error++;
            }

            // LOCALE EN
            if(copy('locale/en/quotes_en.php', '../../locale/en/quotes_en.php')){
                chmod('../../locale/en/quotes_en.php',0777);
                }else{
                    $error++;
                }

            // LOCALE IT
            if(copy('locale/it/quotes_it.php', '../../locale/it/quotes_it.php')){
                chmod('../../locale/it/quotes_it.php',0777);
                }else{
                    $error++;
                }

            
            // ALERT
            if(copy('alert/quotes_alert.php', '../../inc/alert/quotes_alert.php')){
                chmod('../../inc/alert/quotes_alert.php',0777);
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

