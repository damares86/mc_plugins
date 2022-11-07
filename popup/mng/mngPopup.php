<?php

require '../phpDebug/src/Debug/Debug.php';   			// if not using composer

$debug = new \bdk\Debug(array(
    'collect' => true,
    'output' => true,
));



session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: ../');
    exit;
}

	// loading class
	include("../class/Database.php");
	include("../class/Popup.php");


	$database = new Database();
	$db = $database->getConnection();

	
	$popup = new Popup($db);


if(filter_input(INPUT_GET,"idToDel")){

	$idToDel = filter_input(INPUT_GET,"idToDel");
		
	$popup->id_popup=$idToDel;

	if($popup->deletePopup()){
		header("Location: ../index.php?man=popup&op=show&msg=popupDelSucc");
		exit;

	}else{
		header("Location: ../index.php?man=popup&op=show&msg=popupDelErr");
		exit;
	}
}

if(filter_input(INPUT_POST,"subReg")){

	$popup->initPopupSessVar();
	
	if($_SESSION['error']!=0){
		header("Location: ../index.php?man=popup&op=add&more=yes&msg=pageDataMissing");
		exit;
	}
	
	$operation=filter_input(INPUT_POST,"operation");
	
	if($operation=="add"){
	
		$popup->title_popup = $_POST['title'];
		$popup->editor_popup = $_POST['editor1'];
		$popup->page_popup = $_POST['pagename'];

		if($popup->insertPopup()){
			header("Location: ../index.php?man=popup&op=show&msg=popupSucc");
			exit;
		}else{
			header("Location: ../index.php?man=popup&op=show&msg=popupErr");
			exit;
		}

	}else if($operation=="mod"){

		$popup->id_popup=$_POST['idToMod'];
		$popup->title_popup = $_POST['title'];
		$popup->editor_popup = $_POST['editor1'];
		$popup->page_popup = $_POST['pagename'];


		if($popup->updatePopup()){
			header("Location: ../index.php?man=popup&op=show&msg=popupModSucc");
			exit;
		}else{
			header("Location: ../index.php?man=papopupge&op=show&msg=popupModErr");
			exit;
		}
	}
}else{
	header("Location: ../index.php?man=popup&op=show");
	exit;
}

