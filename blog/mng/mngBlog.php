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
	include("../class/Categories.php");


	$database = new Database();
	$db = $database->getConnection();

	$post = new Post($db);
	$categories = new Categories($db);

if(filter_input(INPUT_GET,"idToDel")){
	
	$idToDel = filter_input(INPUT_GET,"idToDel");
	
	$post->id=$idToDel;
	
	// delete the role
	if($post->delete()){
		header("Location: ../index.php?man=blog&op=show&msg=postDelSucc");
		exit;
	
		// empty posted values
		// $_POST=array();
	
	}else{
		header("Location: ../index.php?man=blog&op=show&msg=postDelErr");
		exit;
	}
}

if(filter_input(INPUT_POST,"subReg")){

	$operation=filter_input(INPUT_POST,"operation");

	$post->initCheckSessVar();
	
	if($_SESSION['error']!=0){
		header("Location: ../index.php?man=blog&op=add&msg=pageDataMissing&more=yes&count=2");
		exit;
	}

	$editor = preg_replace('/\s+/', '', $_POST['editor']);
	$editor2 = preg_replace('/\s+/', '', $_POST['editor2']);

	if($operation=="add"){
		
		$new_title=$_POST['title'];
		
		$stmt=$post->showAllList();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			
			extract($row);			
			
			if($new_title==$title){
				header("Location: ../index.php?man=blog&op=show&msg=titleExist");
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
		$post->author=$_POST['author'];

		$cat_names=$_POST['select_cat'];
		
		$cat=array();
		foreach($cat_names as $names){
			$categories->category_name=$names;
			$categories->showByName();
			$cat[]=$categories->id;
		}

		// create the post
		if($post->insert()){
			foreach($cat as $row){
				$category_id=$row['id'];
				$post->addCategories($category_id);
			}			
			header("Location: ../index.php?man=blog&op=show&msg=postSucc");
			exit;
		}else{
			header("Location: ../index.php?man=blog&op=show&msg=postErr");
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
		$post->id=$_POST['idToMod'];
				
		$cat_names=$_POST['select_cat'];
		$cat=array();
		foreach($cat_names as $names){
			$categories->category_name=$names;
			$categories->showByName();
			$cat[]=$categories->id;
		}
		
		// update the post
		if($post->update()){
			for ($i = 0, $n = count($cat) ; $i < $n ; $i++){			
				$category_id=$cat[$i];
				$post->counter=$i;
				$post->editCategories($category_id);

			}
			header("Location: ../index.php?man=blog&op=show&msg=postEditSucc");
			exit;
		
			// empty posted values
			// $_POST=array();
		
		}else{
			header("Location: ../index.php?man=blog&op=show&msg=postEditErr");
			exit;
		}
	}
} else {
	echo "errore post";
	exit;
}


exit;

?>

