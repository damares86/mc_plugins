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
	include("../class/Page.php");

	$database = new Database();
	$db = $database->getConnection();


if(filter_input(INPUT_GET,"idToDel")){	
	$idToDel = filter_input(INPUT_GET,"idToDel");
	$json_file = '../inc/quotes.json';
	$data = file_get_contents($json_file);
	$quotesJson = json_decode($data, true);

	rename("../inc/quotes.json","../inc/quotes_tmp.json");

	$quotesArr=array();
	foreach($quotesJson as $row){
		if($row['id']!=$idToDel){
			$quotesArr[]=$row;
		}
	}

	if(!empty($quotesArr)){	
		$file='../inc/quotes.json';

		$json=json_encode($quotesArr);

		if(file_put_contents($file, $json, FILE_APPEND)){
			chmod($file,0777);
			unlink('../inc/quotes_tmp.json');
		}else{
			rename("../inc/quotes_tmp.json","../inc/quotes.json");
			header("Location: ../index.php?man=quotes&op=show&msg=quoteDelErr");
			exit;
		}

		header("Location: ../index.php?man=quotes&op=show&msg=quoteDelSucc");
		exit;
	}else{
		unlink('../inc/quotes_tmp.json');
		header("Location: ../index.php?man=quotes&op=show&msg=quoteDelSucc");
		exit;
	}
}

if(filter_input(INPUT_POST,"subReg")){

	if(!$_POST['quote']||!$_POST['author']){
		header("Location: ../index.php?man=quotes&op=show&msg=quoteMissing");
		exit;
	}

	$quotesJson="";
	$id="";
	if(is_file("../inc/quotes.json")){
        $json_file = '../inc/quotes.json';
        $data = file_get_contents($json_file);
        $quotesJson = json_decode($data, true);
		$id=count($quotesJson)+1;
		rename("../inc/quotes.json","../inc/quotes_tmp.json");
    }else{
		$quotesJson=array();
		$id=1;
	}

	$quotes=array(
		"id"		=> $id,
		"quote" 	=> $_POST['quote'],
		"author"	=> $_POST['author']
	);

	$quotesJson[]=$quotes;

	$file='../inc/quotes.json';

	$json=json_encode($quotesJson);

	if(file_put_contents($file, $json, FILE_APPEND)){
		chmod($file,0777);
		unlink('../inc/quotes_tmp.json');
		header("Location: ../index.php?man=quotes&op=show&msg=quoteSucc");
		exit;
	}else{
		rename("../inc/quotes_tmp.json","../inc/quotes.json");
		header("Location: ../index.php?man=quotes&op=show&msg=quoteErr");
		exit;
	}

	
}