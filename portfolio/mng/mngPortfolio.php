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
	include("../class/Portfolio.php");
	include("../class/Categories_Portfolio.php");


	$database = new Database();
	$db = $database->getConnection();

	$portfolio = new Portfolio($db);
	$cat_portfolio = new Categories_Portfolio($db);

if(filter_input(INPUT_GET,"idToDel")){
	
	$idToDel = filter_input(INPUT_GET,"idToDel");
	
	$portfolio->id=$idToDel;
	$portfolio->showById();

	$str=$portfolio->project_title;
	$str = preg_replace('/\s+/', '_', $str);
	$str = strtolower($str);
	$dir="../../misc/portfolio/";
	$filepath = "../../misc/portfolio/" . $str . ".php";

	if(unlink($filepath) || !file_exists(($filepath))){
		if($portfolio->delete()){
			unlink($dir.'/img/'.$portfolio->main_img);
			header("Location: ../index.php?man=portfolio&op=show&msg=projectDelSucc");
			exit;

		}else{
			header("Location: ../index.php?man=portfolio&op=show&msg=projectDelErr");
			exit;
		}
	} else {
		echo "project not deleted";
	}
}

if(filter_input(INPUT_POST,"subReg")){
	$operation=filter_input(INPUT_POST,"operation");

	$editor = preg_replace('/\s+/', '', $_POST['editor']);


	
	
	
	if(!$_POST['project_title']||!$_POST['client']||!$_POST['completed']||!$_POST['link']||!$_POST['category']||empty($editor)){
		header("Location: ../index.php?man=portfolio&op=show&msg=projectEmpty");
		exit;
	}

	if($operation=="add"){
		//inserimento
		if ($_FILES['myfile']['size'] == 0 ){
			header("Location: ../index.php?man=portfolio&op=show&msg=projectImgEmpty");
			exit;
		}

		$portfolio->project_title=$_POST['project_title'];
		$portfolio->main_img=$_FILES['myfile']['name'];
		$portfolio->description=$_POST['editor'];
		$portfolio->client=$_POST['client'];
		$portfolio->completed=$_POST['completed'];
		$portfolio->category=$_POST['category'];
		$portfolio->link=$_POST['link'];

		$dir="../../misc/portfolio/";
		if(!is_dir($dir)){
			mkdir($dir);
			chmod($dir,0777);
		}

		$dirImg="../../misc/portfolio/img/";
		if(!is_dir($dirImg)){
			mkdir($dirImg);
			chmod($dirImg,0777);
		}


		// create the page
		if($portfolio->insert()){

			$str=$portfolio->project_title;
			$str = preg_replace('/\s+/', '_', $str);
			
			$str = strtolower($str);


			if(copy('../template/project.php', ''.$dir.'project.php')){
				rename(''.$dir.'project.php',''.$dir.$str . '.php');
				chmod(''.$dir.$str . '.php',0777);
				header("Location: ../index.php?man=portfolio&op=show&msg=projectSucc");
				exit;
			 } else {
				 echo "ko";
			 }
		
		}else{
			header("Location: ../index.php?man=portfolio&op=show&msg=projectErr");
			exit;
		}
	} else if($operation=="mod"){
		$portfolio->id=$_POST['idToMod'];
		$portfolio->project_title=$_POST['project_title'];
		
		if($_FILES['myfile']['name']){
			$portfolio->main_img=$_FILES['myfile']['name'];
		}else {
			$query1="SELECT * FROM portfolio WHERE project_title = :project_title LIMIT 0,1";
			$stmt1 = $db->prepare($query1);
			$stmt1->bindParam(':project_title', $portfolio->project_title);       
			$stmt1->execute();
			$row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
			$portfolio->main_img=$row1['main_img'];
		}

		$portfolio->description=$_POST['editor'];
		$portfolio->client=$_POST['client'];
		$portfolio->completed=$_POST['completed'];
		$portfolio->category=$_POST['category'];
		$portfolio->link=$_POST['link'];



		// update the page
		if($portfolio->update()){
			header("Location: ../index.php?man=portfolio&op=show&msg=projectEditSucc");
			exit;
		
			// empty posted values
			// $_POST=array();
		
		}else{
			header("Location: ../index.php?man=portfolio&op=show&msg=projectEditErr");
			exit;
		}

	}
	


	// MODIFICA UTENTE



} else {
	header("Location: ../index.php?man=portfolio&op=show&msg=projectEditErr");
	exit;
}


exit;

?>
