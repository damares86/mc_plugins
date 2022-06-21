<?php

class Post{


    private $conn;
    private $table_name = "post";

    public $id;
    public $main_img;
    public $gall;
    public $gall_q;
    public $new_img;
    public $title;
    public $summary;
    public $content;
    public $modified;
    public $category_id;

    // constructor
    public function __construct($db){
        $this->conn = $db;
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
            if($this->uploadPhoto()){
                return true;
            }else{
                return false;
            }
           
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
                category_id = :category_id,
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
                $stmt->bindParam(':category_id', $this->category_id);       
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
                    header("Location: ../index.php?man=post&op=show&msg=formatImgErr");
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

    function showByCatId(){
        $query = "SELECT *
        FROM " . $this->table_name . "
        WHERE category_id = ?
        ORDER BY modified DESC";
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->category_id);
        $stmt->execute();

        
        return $stmt;
   
    
        
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