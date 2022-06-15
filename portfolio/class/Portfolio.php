<?php

class Portfolio{


    private $conn;
    private $table_name = "portfolio";

    public $id;
    public $theme;
    public $project_title;
    public $main_img;
    public $description;
    public $client;
    public $completed;
    public $category;
    public $link;

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    // create new role record
    function insert(){
        
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    project_title = :project_title,
                    description = :description,
                    client = :client,
                    completed = :completed,
                    category = :category,
                    link = :link";
                  
        // prepare the query
        $stmt = $this->conn->prepare($query);
        
        // bind the values
        $stmt->bindParam(':project_title', $this->project_title);       
        $stmt->bindParam(':description', $this->description);    
        $stmt->bindParam(':client', $this->client);       
        $stmt->bindParam(':completed', $this->completed);       
        $stmt->bindParam(':category', $this->category);       
        $stmt->bindParam(':link', $this->link);       

        // execute the query, also check if query was successful
        if($stmt->execute()){ 
            if($this->uploadPhoto()){
                return true;
            }else{
                $this->showError($stmt);
                return false;
            }
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

            $query = "UPDATE
                    " . $this->table_name . "
                SET
                    project_title = :project_title,
                    description = :description,
                    client = :client,
                    completed = :completed,
                    category = :category,
                    link = :link
                WHERE
                id = :id";
      

                // prepare the query
                $stmt = $this->conn->prepare($query);
               

                // bind the values
                $stmt->bindParam(':project_title', $this->project_title);       
                $stmt->bindParam(':description', $this->description);    
                $stmt->bindParam(':client', $this->client);       
                $stmt->bindParam(':completed', $this->completed);       
                $stmt->bindParam(':category', $this->category);       
                $stmt->bindParam(':link', $this->link);       
                $stmt->bindParam(':id', $this->id);       
               
        
            // execute the query, also check if query was successful
            if($stmt->execute()){
                $query1="SELECT * FROM ".$this->table_name." WHERE project_title = :project_title LIMIT 0,1";
                $stmt1 = $this->conn->prepare($query1);
                $stmt1->bindParam(':project_title', $this->project_title);       
                $stmt1->execute();
                $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
                $actualImage=$row1['main_img'];
                if(($this->main_img)==$actualImage){
                    return true;
                    
                }else{

                    // HEADER?

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
          
            $target_directory = "../../misc/portfolio/img/";
            $target_file = $target_directory . $this->main_img;
            if(!file_exists($target_file)){
                // print_r("ok");
                // exit;
                $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
                $file_upload_error_messages="";
                
                $allowed_file_types=array("jpg", "png");
                if(!in_array($file_type, $allowed_file_types)){
                    header("Location: ../index.php?man=portfolio&op=show&msg=formatImgErr");
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
                        WHERE project_title = :project_title";
                        
                        // prepare the query
                        $stmt2 = $this->conn->prepare($query2);
                                
                            
                    
                        // bind the values
                        $stmt2->bindParam(':main_img', $this->main_img);
                        $stmt2->bindParam(':project_title', $this->project_title);    
                    
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
                    completed DESC
                    LIMIT
                    {$from_record_num}, {$records_per_page}";  
  
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
  
        return $stmt;
    }



    public function countAll(){
    
        $query = "SELECT id FROM portfolio";
    
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
    
        $num = $stmt->rowCount();
    
        return $num;
    }

    public function countById(){
    
        $query = "SELECT * FROM portfolio
                WHERE category = :category";
    
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam('category', $this->category);
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
        $this->project_title = $row['project_title'];
        $this->main_img = $row['main_img'];
        $this->description = $row['description'];
        $this->client = $row['client'];
        $this->completed = $row['completed'];
        $this->link = $row['link'];   
        $this->category = $row['category'];   
    }


    function showByCat($from_record_num, $records_per_page){
        $query = "SELECT *
        FROM " . $this->table_name . "
        WHERE category = :category
        ORDER BY
                    completed DESC
                    LIMIT
                    {$from_record_num}, {$records_per_page}";
  
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam('category', $this->category);
        $stmt->execute();
        return $stmt;
    

    }

    function showByTitle(){
        $query = "SELECT *
        FROM " . $this->table_name . "
        WHERE project_title = :project_title
        LIMIT 0,1";
  
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam('project_title', $this->project_title);
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        $this->id = $row['id'];
        $this->project_title = $row['project_title'];
        $this->main_img = $row['main_img'];
        $this->description = $row['description'];
        $this->client = $row['client'];
        $this->completed = $row['completed'];
        $this->link = $row['link'];   
        $this->category = $row['category'];   
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

    
 // delete the project
 function delete(){
    
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $this->id);

   
        if($stmt->execute()){
            return true;
        }else{
            $this->showError($stmt);
            return false;
        }  
        

}





}

?>