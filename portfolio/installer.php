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
$plugins->second_page = $second_page;
$plugins->page_exist = $page_exist;
$plugins->description=$description;
$plugins->icon=$sidebar_icon;
$plugins->title=$sidebar_title;
$plugins->sub_show_title=$sidebar_sub_show_title;
$plugins->sub_show_link=$sidebar_sub_show_link;
$plugins->sub_add_title=$sidebar_sub_add_title;
$plugins->sub_add_link=$sidebar_sub_add_link;

$op = filter_input(INPUT_GET,"op");

if($op=="del"){

    $plugins->plugin_name=$plugin_name;

    $name = ucfirst($plugin_name);

    if($plugins->delete()){
        
        $error=0;

        // DELETE CLASSES
        if(is_file("../../class/Portfolio.php")){
            if(!unlink("../../class/Portfolio.php")){
                $error++;
            }
        }

        if(is_file("../../class/Categories_Portfolio.php")){
            if(!unlink("../../class/Categories_Portfolio.php")){
                $error++;
            }
        }

        // DELETE MNG
        if(is_file("../../core/mngPortfolio.php")){
            if(!unlink("../../core/mngPortfolio.php")){
                $error++;
            }
        }

        // DELETE MNG CAT
        if(is_file("../../core/mngCatPortfolio.php")){
            if(!unlink("../../core/mngCatPortfolio.php")){
                $error++;
            }
        }

        // DELETE ALL
        if(is_file("../../inc/func/allPortfolio.php")){
            if(!unlink("../../inc/func/allPortfolio.php")){
                $error++;
            }
        }


        // DELETE REG
        if(is_file("../../inc/func/regPortfolio.php")){
            if(!unlink("../../inc/func/regPortfolio.php")){
                $error++;
            }
        }


        // DELETE TEMPLATE
        if(is_file("../../template/project.php")){
            if(!unlink("../../template/project.php")){
                $error++;
            }
        }

        if(is_file("../../template/inc/header_portfolio.php")){
            if(!unlink("../../template/inc/header_portfolio.php")){
                $error++;
            }
        }

       // DELETE REG CAT
        if(is_file("../../inc/func/regCatPortfolio.php")){
            if(!unlink("../../inc/func/regCatPortfolio.php")){
                $error++;
            }
        }

        // DELETE ALL
        if(is_file("../../inc/func/allCatPortfolio.php")){
            if(!unlink("../../inc/func/allCatPortfolio.php")){
                $error++;
            }
        }

        // DELETE LOCALE IT
        if(is_file("../../locale/en/portfolio_en.php")){
            if(!unlink("../../locale/en/portfolio_en.php")){
                $error++;
            }
        }

        // DELETE LOCALE IT
        if(is_file("../../locale/it/portfolio_it.php")){
            if(!unlink("../../locale/it/portfolio_it.php")){
                $error++;
            }
        }

        // DELETE DEFAULT PAGE
        if(is_file("../../../portfolio.php")){
            if($plugins->deletePage())
                if(!unlink("../../../portfolio.php")){
                    $error++;
                }
            }else{
                $error++;
            }


        // DELETE ALERT
        if(is_file('../../inc/alert/portfolio_alert.php')){
            if(!unlink('../../inc/alert/portfolio_alert.php')){
                $error++;
            }
        }
        
        // DROP TABLES
        $db->query("DROP TABLE `portfolio`, `portfolio_categories`");

        $home->name_function="portfolio";
        $home->delete();
        
        $home->name_function="catPortfolio";
        $home->delete();

        $plugins->plugin_name=$name;
        $plugins->deletePage();


        $menu->pagename=$plugin_name;   
        $menu->delete();

        
        function removeFolder($folderName) {
			if (is_dir($folderName))
			$folderHandle = opendir($folderName);

			// if (!$folderHandle){
			// 	header("Location: ../index.php?man=gall&op=show&msg=gallDelSucc");
			// 	exit;
			// }

			while($file = readdir($folderHandle)) {
				if ($file != "." && $file != "..") {
						if (!is_dir($folderName."/".$file))
							unlink($folderName."/".$file);
						else
							removeFolder($folderName.'/'.$file);
				}
			}
			closedir($folderHandle);
			rmdir($folderName);
			
		}

		$folderName="../../../misc/portfolio/";
		
        unlink("../../inc/class_initialize.php");

			if(removeFolder($folderName)||!is_dir($folderName)){
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

            // CLASSES
            if(copy('class/Portfolio.php', '../../class/Portfolio.php')){
                chmod('../../class/Portfolio.php',0777);
            }else{
                $error++;
            }

            if(copy('class/Categories_Portfolio.php', '../../class/Categories_Portfolio.php')){
                chmod('../../class/Categories_Portfolio.php',0777);
            }else{
                $error++;
            }

            // ALL
            if(copy('all/allPortfolio.php', '../../inc/func/allPortfolio.php')){
                chmod('../../inc/func/allPortfolio.php',0777);
            }else{
                $error++;
            }

            // ALL
            if(copy('reg/regPortfolio.php', '../../inc/func/regPortfolio.php')){
                chmod('../../inc/func/regPortfolio.php',0777);
            }else{
                $error++;
            }

            // MNG
            if(copy('mng/mngPortfolio.php', '../../core/mngPortfolio.php')){
                chmod('../../core/mngPortfolio.php',0777);
            }else{
                $error++;
            }


            // MNG CAT
            if(copy('mng/mngCatPortfolio.php', '../../core/mngCatPortfolio.php')){
                chmod('../../core/mngCatPortfolio.php',0777);
            }else{
                $error++;
            }

            // PAGE
            if(copy('page/portfolio.php', '../../../portfolio.php')){
                chmod('../../../portfolio.php',0777);
            }else{
                $error++;
            }


            // TEMPLATE
            if(copy('template/project.php', '../../template/project.php')){
                chmod('../../template/project.php',0777);
            }else{
                $error++;
            }

            // HEADER
            if(copy('template/header_portfolio.php', '../../template/inc/header_portfolio.php')){
                chmod('../../template/inc/header_portfolio.php',0777);
            }else{
                $error++;
            }

            // REG CAT
            if(copy('reg/regCatPortfolio.php', '../../inc/func/regCatPortfolio.php')){
                chmod('./../inc/func/regCatPortfolio.php',0777);
                }else{
                    $error++;
                }

            // ALL CAT
            if(copy('all/allCatPortfolio.php', '../../inc/func/allCatPortfolio.php')){
                chmod('./../inc/func/allCatPortfolio.php',0777);
                }else{
                    $error++;
                }

            // LOCALE EN
            if(copy('locale/en/portfolio_en.php', '../../locale/en/portfolio_en.php')){
                chmod('../../locale/en/portfolio_en.php',0777);
                }else{
                    $error++;
                }

            // LOCALE IT
            if(copy('locale/it/portfolio_it.php', '../../locale/it/portfolio_it.php')){
                chmod('../../locale/it/portfolio_it.php',0777);
                }else{
                    $error++;
                }

            
            // ALERT
            if(copy('alert/portfolio_alert.php', '../../inc/alert/portfolio_alert.php')){
                chmod('../../inc/alert/portfolio_alert.php',0777);
                }else{
                    $error++;
                }

            // CREATE TABLES AND DEFAULT CATEGORY

            $db->query("CREATE TABLE IF NOT EXISTS portfolio
            ( id INT ( 5 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                project_title VARCHAR(255) NOT NULL,
                main_img VARCHAR(255) NOT NULL DEFAULT 'visual.jpg',
                description text COLLATE utf8_unicode_ci NOT NULL,
                client VARCHAR(255) NOT NULL,
                completed date NOT NULL,
                category TEXT(255) NULL,
                link VARCHAR(255) NOT NULL)
                ");

            $db->query("CREATE TABLE IF NOT EXISTS portfolio_categories
                        ( id INT ( 5 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                            category_name VARCHAR(255) NOT NULL)");


            $db->query("INSERT INTO portfolio_categories
                            (id, category_name)
                            VALUES ('1','Web design')
                            ");
    
   
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

