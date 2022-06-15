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
	include("../class/Categories_Portfolio.php");
	include("../class/Portfolio.php");


	$database = new Database();
	$db = $database->getConnection();

	$categories_portfolio = new Categories_Portfolio($db);
	$portfolio = new Portfolio($db);

if(filter_input(INPUT_GET,"idToDel")){
	
	$idToDel = filter_input(INPUT_GET,"idToDel");
	
	$categories_portfolio->id=$idToDel;
	
	$countProject = 0;

	$stmt = $portfolio->showTot();



	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		
		extract($row);

		if($category = $idToDel){
			$countPost ++;
		}

	}


	if($countPost>0){
		header("Location: ../index.php?man=catPortfolio&op=show&msg=catPortfolioDelExist");
		exit;
	}else{
		// delete the category
		if($categories_portfolio->delete()){
			header("Location: ../index.php?man=catPortfolio&op=show&msg=catPortfolioDelSucc");
			exit;
			
		}else{
			header("Location: ../index.php?man=catPortfolio&op=show&msg=catPortfolioDelErr");
			exit;
		}
	}





}

if(filter_input(INPUT_POST,"subReg")){

	$operation=filter_input(INPUT_POST,"operation");
	
	if(!$_POST['category_name']){
		header("Location: ../index.php?man=catPortfolio&op=show&msg=catPortfolioEmpty");
		exit;
	}
	
	$categories_portfolio->category_name=$_POST['category_name'];

	if($operation=="add"){
		//inserimento
		
		if($categories_portfolio->catExists()){
			header("Location: ../index.php?man=catPortfolio&op=show&msg=catPortfolioExists");
		} else {
			
			$categories_portfolio->category_name=$_POST['category_name'];

			
			// create the user
			if($categories_portfolio->create()){
				header("Location: ../index.php?man=catPortfolio&op=show&msg=catPortfolioSucc");
				exit;
			
				// empty posted values
				// $_POST=array();
			
			}else{
				header("Location: ../index.php?man=catPortfolio&op=show&msg=catPortfolioErr");
				exit;
			}
		}
	} else if($operation=="mod"){

		$categories_portfolio->id = $_POST['idToMod'];

		// update the post
		if($categories_portfolio->update()){
			header("Location: ../index.php?man=catPortfolio&op=show&msg=catPortfolioEditSucc");
			exit;
		
			// empty posted values
			// $_POST=array();
		
		}else{
			header("Location: ../index.php?man=catPortfolio&op=show&msg=catPortfolioEditErr");
			exit;
		}



	}
	


	// MODIFICA UTENTE



} else {
	header("Location: ../index.php?man=catPortfolio&op=show&msg=catPortfolioEditErr");
	exit;
}


exit;

?>

