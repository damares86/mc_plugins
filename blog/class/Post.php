<?php

class Post{


    private $conn;
    private $table_name = "post";

    public $id;
    public $main_img;
    public $new_img;
    public $img_tmp;
    public $title;
    public $summary;
    public $content;
    public $modified;
    public $category_id;
    public $categories;
    public $counter;


    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    function initCheckSessVar(){

        $_SESSION['error']=0;

        if(!empty($_POST['title'])){
            $_SESSION['blog_title']=$_POST['title'];
        }else{
            $_SESSION['error']++;
        }

        if(!empty($_POST['select_cat'])){
            $_SESSION['blog_select_cat']=$_POST['select_cat'];
        }else{
            $_SESSION['error']++;
        }
        
        if($_FILES['myfile']['size']!=0){
            $this->main_img=$_FILES['myfile']['name'];
            $this->img_tmp=$_FILES['myfile']['tmp_name'];
            $this->uploadPhoto();
            $_SESSION['blog_img']=$_FILES['myfile']['name'];
            $_SESSION['blog_old_img']=$_SESSION['blog_img'];
        }else if($_SESSION['blog_old_img']){
            $_SESSION['blog_img']=$_SESSION['blog_old_img'];
        }else{
            $_SESSION['error']++;
        }
    
        if(!empty($_POST['editor'])){
            $_SESSION['blog_editor']=$_POST['editor'];
        }else{
            $_SESSION['error']++;
        }

        if(!empty($_POST['editor2'])){
            $_SESSION['blog_editor2']=$_POST['editor2'];
        }else{
            $_SESSION['error']++;
        }
                       
    }

    function modCheckSessVar(){

        $_SESSION['error']=0;

        $_SESSION['blog_title']=$this->title;
        $_SESSION['blog_select_cat']=$this->category_id;
        $_SESSION['blog_img']=$this->main_img;
        $_SESSION['blog_old_img']=$this->main_img;
        $_SESSION['blog_editor']=$this->summary;
        $_SESSION['blog_editor2']=$this->content;

    }

    function destroyCheckSessVar(){

        $_SESSION['error']=0;

        unset($_SESSION['blog_title']);
        unset($_SESSION['blog_select_cat']);
        unset($_SESSION['blog_img']);
        unset($_SESSION['blog_old_img']);
        unset($_SESSION['blog_editor']);
        unset($_SESSION['blog_editor2']);
        
    }


    // create new role record
    function insert(){
        // insert query
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    main_img = :main_img,
                    gall = :gall,                   
                    title = :title,
                    summary = :summary,
                    content = :content,
                    category_id = :category_id,
                    modified = NOW()";
                    
        // prepare the query
        $stmt = $this->conn->prepare($query);

        // bind the values
        $stmt->bindParam(':main_img', $this->main_img);       
        $stmt->bindParam(':gall', $this->gall);   
        $stmt->bindParam(':title', $this->title);       
        $stmt->bindParam(':summary', $this->summary);       
        $stmt->bindParam(':content', $this->content);       
        $stmt->bindParam(':category_id', $this->category_id);       

        // execute the query, also check if query was successful
        if($stmt->execute()){
            return true;

        }else{
            $this->showError($stmt);
            return false;
        }
    
    }

    function showError($stmt){
        echo "<pre>";
            print_r($stmt->errorInfo());
        echo "</pre>";
    }

    function update(){
        // insert query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                main_img = :main_img,
                gall = :gall,
                title = :title,
                summary = :summary,
                content = :content,
                modified = NOW()
                WHERE
                id = :id";
                // prepare the query
                $stmt = $this->conn->prepare($query);
                
                // bind the values
                $stmt->bindParam(':main_img', $this->main_img);       
                $stmt->bindParam(':gall', $this->gall);       
                $stmt->bindParam(':title', $this->title);   
                $stmt->bindParam(':summary', $this->summary);
                $stmt->bindParam(':content', $this->content); 
                // $stmt->bindParam(':category_id', $this->category_id);       
                $stmt->bindParam(':id', $this->id);
                    
      
        // execute the query, also check if query was successful
        if($stmt->execute()){
            if($this->new_img==1){
                if($this->uploadPhoto()){
                    return true;
                }else{
                    return false;
                }
            }

            return true;

        }else{
            $this->showError($stmt);
            return false;
        }
    
    }


    function uploadPhoto(){
        
        if($this->main_img){          
            $target_directory = "../../uploads/img/";
            $target_file = $target_directory . $this->main_img;
            if(!file_exists($target_file)){
                // print_r("ok");
                // exit;
                $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
                $file_upload_error_messages="";
                
                $allowed_file_types=array("jpg", "JPG", "jpeg", "png");
                if(!in_array($file_type, $allowed_file_types)){
                    header("Location: ../index.php?man=blog&op=show&msg=formatImgErr");
                    exit;
                    // $file_upload_error_messages.="<div>Only .zip, .doc, .docx,.pdf files are allowed.</div>";
                    //exit;
                }
                
                if(file_exists($target_file)){
                    $file_upload_error_messages.="File already exists";
                }
                
                // make sure submitted file is not too large, can't be larger than 5 MB
                // if($_FILES['myfile']['size'] > (5120000)){
                    //     $file_upload_error_messages.="<div>Doc must be less than 5 MB in size.</div>";
                    // }
                    
                    // make sure the 'uploads' folder exists
                    // if not, create it
                    if(!is_dir($target_directory)){
                        mkdir($target_directory, 0777, true);
                    }else{
                        chmod($target_directory, 0777);
                    }
                
                if(empty($file_upload_error_messages)){
                    
                    
                    // the physical file on a temporary uploads directory on the server
                    $file = $_FILES['myfile']['tmp_name'];
                    

                    
                    if (move_uploaded_file($file, $target_file)) {
                        chmod($target_file, 0777);
                        
                    }
                }
            }
            $query2 = "UPDATE " . $this->table_name . "
                        SET
                        main_img = :main_img
                        WHERE title = :title";
                        
                        // prepare the query
                        $stmt2 = $this->conn->prepare($query2);
                                
                            
                    
                        // bind the values
                        $stmt2->bindParam(':main_img', $this->main_img);
                        $stmt2->bindParam(':title', $this->title);    
                    
                        // execute the query, also check if query was successful
                        if($stmt2->execute()){
                            return true;
                        }else{
                            return false;
                        }
				
				
                } else {
                    echo "Failed to upload image.";
                }   
        	}

    function addCategories($cat){


        // prendo i permessi esistenti
        $query1= "SELECT
                *
            FROM
                ".$this->table_name."
                WHERE title = :title";

        $stmt1 = $this->conn->prepare($query1);
        $stmt1->bindParam('title', $this->title);

        $stmt1->execute();

        $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

        // converto in array
        if($row1['category_id']){
            $categories=$row1['category_id'];
            $catArr=explode(",",$categories);
        }else{
            $catArr=array();
        }
        
        // aggiungo il file caricato come elemento dell'array e poi lo riconverto in stringa
        $catArr[]=$cat;
        $stringCat=implode(",",$catArr);

        $this->categories=$stringCat;

        // azzero il campo "files"
        $query2= "UPDATE
            ".$this->table_name."
            SET
            category_id = NULL
            WHERE title = :title";

        $stmt2 = $this->conn->prepare($query2);
        $stmt2->bindParam('title', $this->title);

        
        $stmt2->execute();

        // inserisco la nuova stringa dei permessi nel db

        $query= "UPDATE
        ".$this->table_name."
        SET
        category_id = :category
        WHERE title = :title";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam('category', $this->categories);
        $stmt->bindParam('title', $this->title);

        if($stmt->execute()){
            return true;

        }else{
            $this->showError($stmt);
            return false;
        }
    }

    function editCategories($cat){

        if($this->counter>0){
        // prendo i permessi esistenti
        $query1= "SELECT
                *
            FROM
                ".$this->table_name."
                WHERE title = :title";

        $stmt1 = $this->conn->prepare($query1);
        $stmt1->bindParam('title', $this->title);

        $stmt1->execute();

        $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);

        }
        // converto in array
        if($row1['category_id']){
            $categories=$row1['category_id'];
            $catArr=explode(",",$categories);
        }else{
            $catArr=array();
        }
        
        // aggiungo il file caricato come elemento dell'array e poi lo riconverto in stringa
        $catArr[]=$cat;
        $stringCat=implode(",",$catArr);

        $this->categories=$stringCat;
        
        // azzero il campo "files"
        $query2= "UPDATE
            ".$this->table_name."
            SET
            category_id = NULL
            WHERE title = :title";

        $stmt2 = $this->conn->prepare($query2);
        $stmt2->bindParam('title', $this->title);

        
        $stmt2->execute();

        // inserisco la nuova stringa dei permessi nel db

        $query= "UPDATE
        ".$this->table_name."
        SET
        category_id = :category
        WHERE title = :title";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam('category', $this->categories);
        $stmt->bindParam('title', $this->title);

        if($stmt->execute()){
            return true;

        }else{
            $this->showError($stmt);
            return false;
        }
    }
        
        


    function showAll($from_record_num, $records_per_page){
        //select all data
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . "
                ORDER BY
                    modified DESC
                    LIMIT
                    {$from_record_num}, {$records_per_page}";  
  
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
  
        return $stmt;
    }

    function showAllList(){
        //select all data
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . "
                ORDER BY
                    id DESC";  
  
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
  
        return $stmt;
    }

    function showLastPosts(){
        //select all data
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name . "
                ORDER BY
                    modified DESC
                    LIMIT 3";  
  
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
  
        return $stmt;
    }

    function showTot(){
        //select all data
        $query = "SELECT
                    *
                FROM
                    " . $this->table_name ." ";  
  
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
  
        return $stmt;
    }

    public function countAll(){
    
        $query = "SELECT id FROM post";
    
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
    
        $num = $stmt->rowCount();
    
        return $num;
    }

    function showById(){
        $query = "SELECT *
        FROM " . $this->table_name . "
        WHERE id = ?
        LIMIT 0,1";
        
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $this->id = $row['id'];
        $this->main_img = $row['main_img'];
        $this->gall = $row['gall'];
        $this->title = $row['title'];
        $this->summary = $row['summary'];
        $this->content = $row['content'];
        $this->category_id = $row['category_id'];
        $this->modified = $row['modified'];
    }

    function showByCatId($cat_id,$from_record_num, $records_per_page){

        // drop if exist
        $query2="DROP TEMPORARY TABLE temp_post";

        $stmt2 = $this->conn->prepare( $query2 );
        $stmt2->execute();


        $query = "SELECT *
        FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        
        // create temporary table

        $query2="CREATE TEMPORARY TABLE temp_post(
                id int(5) NOT NULL PRIMARY KEY,
                main_img VARCHAR(255) NOT NULL,
                title VARCHAR(255) NOT NULL,
                summary text COLLATE utf8_unicode_ci NOT NULL,
                content text COLLATE utf8_unicode_ci NOT NULL,
                modified datetime NOT NULL,
                category_id text (255) NOT NULL)";

        $stmt2 = $this->conn->prepare( $query2 );
        $stmt2->execute();

        // insert record in temporary table
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            $catArr=explode(",",$row['category_id']);
            
            if(in_array($cat_id,$catArr)){
                $this->id=$row['id'];
               
                $query3="INSERT INTO
                temp_post 

                 SET
                     id = :id,
                     main_img = :main_img,          
                     title = :title,
                     summary = :summary,
                     content = :content,
                     modified = :modified,
                     category_id = :category_id";
                
                // prepare the query
                $stmt3 = $this->conn->prepare($query3); 
                $stmt3->bindParam('id', $row['id']);
                $stmt3->bindParam(':main_img', $row['main_img']);   
                $stmt3->bindParam(':title', $row['title']);       
                $stmt3->bindParam(':summary', $row['summary']);       
                $stmt3->bindParam(':content', $row['content']);       
                $stmt3->bindParam(':modified', $row['modified']);       
                $stmt3->bindParam(':category_id', $row['category_id']);    

                // $stmt3->execute();                 
                $stmt3->execute();
            }
        }
        
            $query1 = "SELECT *
            FROM temp_post ORDER BY modified DESC
                    LIMIT
                    {$from_record_num}, {$records_per_page}";

            $stmt1 = $this->conn->prepare( $query1 );
          
            $stmt1->execute();

          return $stmt1;
        
    }

    public function countSelected($cat_id){

        $query = "SELECT *
        FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare( $query );
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            $catArr=explode(",",$row['category_id']);
            
            if(in_array($cat_id,$catArr)){
                $this->id=$row['id'];
               
                $query3="INSERT INTO
                temp_post 

                 SET
                     id = :id,
                     main_img = :main_img,          
                     title = :title,
                     summary = :summary,
                     content = :content,
                     modified = :modified,
                     category_id = :category_id";
                
                // prepare the query
                $stmt3 = $this->conn->prepare($query3); 
                $stmt3->bindParam('id', $row['id']);
                $stmt3->bindParam(':main_img', $row['main_img']);   
                $stmt3->bindParam(':title', $row['title']);       
                $stmt3->bindParam(':summary', $row['summary']);       
                $stmt3->bindParam(':content', $row['content']);       
                $stmt3->bindParam(':modified', $row['modified']);       
                $stmt3->bindParam(':category_id', $row['category_id']);    

                // $stmt3->execute();                 
                $stmt3->execute();
            }
        }

        $query1 = "SELECT *
        FROM temp_post";

        $stmt1 = $this->conn->prepare( $query1 );
      
        $stmt1->execute();
    
        $num = $stmt1->rowCount();
    
        return $num;
    }

 // delete the post
 function delete(){
    
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);

    if($result = $stmt->execute()){
        return true;
    }else{
        return false;
    }
}





}

?>