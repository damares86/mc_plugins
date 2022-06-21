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
	include("../class/Post.php");


	$database = new Database();
	$db = $database->getConnection();

	$post = new Post($db);

if(filter_input(INPUT_GET,"idToDel")){
	
	$idToDel = filter_input(INPUT_GET,"idToDel");
	
	$post->id=$idToDel;
	
	// delete the role
	if($post->delete()){
		header("Location: ../index.php?man=post&op=show&msg=postDelSucc");
		exit;
	
		// empty posted values
		// $_POST=array();
	
	}else{
		header("Location: ../index.php?man=post&op=show&msg=postDelErr");
		exit;
	}
}

if(filter_input(INPUT_POST,"subReg")){

	$operation=filter_input(INPUT_POST,"operation");

	$editor = preg_replace('/\s+/', '', $_POST['editor']);
	$editor2 = preg_replace('/\s+/', '', $_POST['editor2']);

	if(!$_POST['title']||empty($editor)||empty($editor2)){
		header("Location: ../index.php?man=post&op=show&msg=postTitleEmpty");
		exit;
	}

	if(empty($editor)||empty($editor2)){
		header("Location: ../index.php?man=post&op=show&msg=postEmpty");
		exit;
	}




	if($operation=="add"){
		if (!isset($_FILES['myfile'])){
			header("Location: ../index.php?man=post&op=show&msg=imgEmpty");
			exit;
		}
		$new_title=$_POST['title'];
		
		$stmt=$post->showAllList();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			
			extract($row);
			
			
			if($new_title==$title){
				header("Location: ../index.php?man=post&op=show&msg=titleExist");
				exit;
			}
		}

		if(isset($_POST['selectGall'])){
			$gallery = str_replace(" ","_",$_POST['gall']);
			$gallery=strtolower($gallery);
			$post->gall = $gallery;
		}else{
			$post->gall = "none";
		}
		
		//inserimento
		$post->title=$_POST['title'];
		$post->main_img=$_FILES['myfile']['name'];
		$post->summary=$_POST['editor'];
		$post->content=$_POST['editor2'];
		$post->category_id=$_POST['category_id'];
		
		// create the post
		if($post->insert()){			
				header("Location: ../index.php?man=post&op=show&msg=postSucc");
				exit;
		}else{
			header("Location: ../index.php?man=post&op=show&msg=postErr");
			exit;
		}
	} else if($operation=="mod"){


		$post->title=$_POST['title'];
		$post->id=$_POST['idToMod'];

		if($_FILES['myfile']['name']){
			$post->main_img=$_FILES['myfile']['name'];
			$post->new_img=1;
		}else{
			$query1="SELECT * FROM post WHERE id = :id LIMIT 0,1";
			$stmt1 = $db->prepare($query1);
			$stmt1->bindParam(':id', $post->id);       
			$stmt1->execute();
			$row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
			$post->main_img=$row1['main_img'];
			$post->new_img=0;
			
		}


		if(isset($_POST['selectGall'])){
			$gallery = str_replace(" ","_",$_POST['gall']);
			$gallery=strtolower($gallery);
			$post->gall = $gallery;
		}else{
			$post->gall = "none";
		}
		
	
		// modifica post
		$post->summary=$_POST['editor'];
		$post->content=$_POST['editor2'];
		$post->category_id=$_POST['category_id'];
		$post->id=$_POST['idToMod'];
		
		
			// update the post
			if($post->update()){
				header("Location: ../index.php?man=post&op=show&msg=postEditSucc");
				exit;
			
				// empty posted values
				// $_POST=array();
			
			}else{
				header("Location: ../index.php?man=post&op=show&msg=postEditErr");
				exit;
			}

	}
	


	// MODIFICA UTENTE



} else {
	echo "errore post";
	exit;
}


exit;

?>

