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
        if(is_file("../../class/Post.php")){
            if(!unlink("../../class/Post.php")){
                $error++;
            }
        }

        if(is_file("../../class/Categories.php")){
            if(!unlink("../../class/Categories.php")){
                $error++;
            }
        }

        // DELETE MNG
        if(is_file("../../core/mngPost.php")){
            if(!unlink("../../core/mngPost.php")){
                $error++;
            }
        }

        // DELETE MNG CAT
        if(is_file("../../core/mngCat.php")){
            if(!unlink("../../core/mngCat.php")){
                $error++;
            }
        }

        // DELETE ALL
        if(is_file("../../inc/func/allPost.php")){
            if(!unlink("../../inc/func/allPost.php")){
                $error++;
            }
        }


        // DELETE REG
        if(is_file("../../inc/func/regPost.php")){
            if(!unlink("../../inc/func/regPost.php")){
                $error++;
            }
        }


       // DELETE REG CAT
        if(is_file("../../inc/func/regCat.php")){
            if(!unlink("../../inc/func/regCat.php")){
                $error++;
            }
        }

        // DELETE ALL
        if(is_file("../../inc/func/allCat.php")){
            if(!unlink("../../inc/func/allCat.php")){
                $error++;
            }
        }

        // DELETE LOCALE IT
        if(is_file("../../locale/en/post_en.php")){
            if(!unlink("../../locale/en/post_en.php")){
                $error++;
            }
        }

        // DELETE LOCALE IT
        if(is_file("../../locale/it/post_it.php")){
            if(!unlink("../../locale/it/post_it.php")){
                $error++;
            }
        }

        // DELETE DEFAULT PAGE
        if(is_file("../../../post.php")){
            if($plugins->deletePage())
                if(!unlink("../../../post.php")){
                    $error++;
                }
                if(is_file("../../../blog.php")){
                        if(!unlink("../../../blog.php")){
                            $error++;
                        }
                        
                    }else{
                        $error++;
                    }
        
            }else{
                $error++;
            }


        // DELETE ALERT
        if(is_file('../../inc/alert/post_alert.php')){
            if(!unlink('../../inc/alert/post_alert.php')){
                $error++;
            }
        }
        
        // DROP TABLES
        $db->query("DROP TABLE `post`, `categories`");

        $home->name_function="post";
        $home->delete();
        
        $home->name_function="cat";
        $home->delete();

        
        
        $menu->pagename=$plugin_name;   
        $menu->delete();
        
        $page->type="default";

        $page->id=5;
        $page->delete();
        
        $page->id=6;
        $page->delete();
        
        $plugins->plugin_name=$name;
        
		
        unlink("../../inc/class_initialize.php");

			if($plugins->deletePage()){
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
            if(copy('class/Post.php', '../../class/Post.php')){
                chmod('../../class/Post.php',0777);
            }else{
                $error++;
            }

            
            if(copy('class/Categories.php', '../../class/Categories.php')){
                chmod('../../class/Categories.php',0777);
            }else{
                $error++;
            }

            // ALL
            if(copy('all/allPost.php', '../../inc/func/allPost.php')){
                chmod('../../inc/func/allPost.php',0777);
            }else{
                $error++;
            }

            
            // ALL
            if(copy('reg/regPost.php', '../../inc/func/regPost.php')){
                chmod('../../inc/func/regPost.php',0777);
            }else{
                $error++;
            }

            // MNG
            if(copy('mng/mngPost.php', '../../core/mngPost.php')){
                chmod('../../core/mngPost.php',0777);
            }else{
                $error++;
            }

            
            // MNG CAT
            if(copy('mng/mngCat.php', '../../core/mngCat.php')){
                chmod('../../core/mngCat.php',0777);
            }else{
                $error++;
            }

            
            // PAGE
            if(copy('page/post.php', '../../../post.php')){
                chmod('../../../post.php',0777);
            }else{
                $error++;
            }
            

            if(copy('page/blog.php', '../../../blog.php')){
                chmod('../../../blog.php',0777);
            }else{
                $error++;
            }

            // REG CAT
            if(copy('reg/regCat.php', '../../inc/func/regCat.php')){
                chmod('../../inc/func/regCat.php',0777);
            }else{
                $error++;
            }            

            
            // ALL CAT
            if(copy('all/allCat.php', '../../inc/func/allCat.php')){
                chmod('../../inc/func/allCat.php',0777);
            }else{
                $error++;
            }


            // LOCALE EN
            if(copy('locale/en/post_en.php', '../../locale/en/post_en.php')){
                chmod('../../locale/en/post_en.php',0777);
            }else{
                $error++;
            }
            
            // LOCALE IT
            if(copy('locale/it/post_it.php', '../../locale/it/post_it.php')){
                chmod('../../locale/it/post_it.php',0777);
            }else{
                $error++;
            }
            
            
            // ALERT
            if(copy('alert/post_alert.php', '../../inc/alert/post_alert.php')){
                chmod('../../inc/alert/post_alert.php',0777);
            }else{
                $error++;
            }
            
            // CREATE TABLES AND DEFAULT CATEGORY

            $db->query("CREATE TABLE post (
                id int(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                main_img VARCHAR(255) NOT NULL,
                gall VARCHAR(255) NOT NULL,
                title VARCHAR(255) NOT NULL,
                summary text COLLATE utf8_unicode_ci NOT NULL,
                content text COLLATE utf8_unicode_ci NOT NULL,
                modified datetime NOT NULL,
                category_id INT (5) NOT NULL)");

            $db->query("CREATE TABLE IF NOT EXISTS categories
                            ( id INT ( 5 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                category_name VARCHAR(255) NOT NULL)");

            $db->query("INSERT INTO categories
                            (id, category_name)
                            VALUES ('1','Misc')
                            ");


     

            // $db->query("INSERT INTO default_page 
            // (id, page_name, layout, header, img, block1_type, block1, block1_bg, block1_text, block2_type, block2, block2_bg, block2_text, block3_type,block3, block3_bg, block3_text, block4_type,block4, block4_bg, block4_text,  block5_type,block5, block5_bg, block5_text,  block6_type,block6, block6_bg, block6_text) 
            // VALUES ('5','Blog', 'default', '1', 'visual.jpg', 't',  '','none','#000000', 'n', '', 'none','#000000', 'n',  '', 'none','#000000', 'n', '', 'none','#000000', 'n', '', 'none','#000000', 'n', '', 'none','#000000')
            // ");

            // $db->query("INSERT INTO default_page 
            // (id, page_name, layout, header, img, block1_type, block1, block1_bg, block1_text, block2_type, block2, block2_bg, block2_text, block3_type,block3, block3_bg, block3_text, block4_type,block4, block4_bg, block4_text,  block5_type,block5, block5_bg, block5_text,  block6_type,block6, block6_bg, block6_text) 
            // VALUES ('6','Post', 'default', '1', 'visual.jpg', 't',  '','none','#000000', 'n', '', 'none','#000000', 'n',  '', 'none','#000000', 'n', '', 'none','#000000', 'n', '', 'none','#000000', 'n', '', 'none','#000000')
            // ");



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

