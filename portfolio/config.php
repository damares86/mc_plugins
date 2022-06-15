<?php

// variabili per la visualizzazione negli "if...else" dell'index

// titolo e descrizione per la tabella dei plugin

$plugin_name = "Portfolio";
$link="";
$page_exist=1;
$second_page = "catPortfolio";
$folder = "portfolio";
$description = "It creates a portfolio and manages your projects. It can show all projects, with filters by categories, or show a single project with all its data.";

$sidebar_icon="fas fa-fw fa-image";
$sidebar_title = 'side_port';
$sidebar_sub_show_title = 'side_project';
$sidebar_sub_show_link ="index.php?man=portfolio&op=show";
$sidebar_sub_add_title = 'side_project_cat';
$sidebar_sub_add_link ="index.php?man=catPortfolio&op=show";
