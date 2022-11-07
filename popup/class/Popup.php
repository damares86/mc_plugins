<?php

class Popup{

    private $conn;
    private $table_name = "popup";

    public $id_popup;
    public $title_popup;
    public $editor_popup;
    public $page_popup;

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }


//////////////////////////////////////////

    function initPopupSessVar(){
        
        $_SESSION['error']=0;
        
        if(!empty($_POST['title'])){
            $_SESSION['sess_popup_title']=$_POST['title'];
        }else{
            $_SESSION['error']++;
        }

        if(!empty($_POST['editor1'])){
            $_SESSION['sess_popup_editor']=$_POST['editor1'];
        }else{
            $_SESSION['error']++;
        }

        if(!empty($_POST['pagename'])){
            $_SESSION['sess_popup_page_name']=$_POST['pagename'];
        }else{
            $_SESSION['error']++;
        }

    }

    function modPopupSessVar(){
        $_SESSION['erro']=0;
        $_SESSION['sess_popup_title']=$this->title_popup;
        $_SESSION['sess_popup_editor']=$this->editor_popup;
        $_SESSION['sess_popup_page_name']=$this->page_popup;
    }

    function destroyPopupSessVar(){
        unset($_SESSION['error']);
        unset($_SESSION['sess_popup_title']);
        unset($_SESSION['sess_popup_editor']);
        unset($_SESSION['sess_popup_page_name']);
    }

//////////////////////////////////////////

    function insertPopup(){
        $query="INSERT INTO popup
            SET
            title = :title,
            content = :editor,
            pagename = :pagename";
        
        $stmt= $this->conn->prepare($query);

        $stmt->bindParam(':title', $this->title_popup);
        $stmt->bindParam(':editor', $this->editor_popup);
        $stmt->bindParam(':pagename', $this->page_popup);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }


    function updatePopup(){
        $query="UPDATE popup
            SET
            title = :title,
            content = :content,
            pagename = :pagename
            WHERE
            id = :id";
    
    
            $stmt=$this->conn->prepare($query);
    
            $stmt->bindParam(':id',$this->id_popup);
            $stmt->bindParam(':title',$this->title_popup);
            $stmt->bindParam(':content',$this->editor_popup);
            $stmt->bindParam(':pagename',$this->page_popup);
    
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
    }

    
    function showAllPopup($from_record_num, $records_per_page){
        //select all data
        $query = "SELECT
                    *
                FROM
                   popup
                ORDER BY
                    id DESC
                    LIMIT
                    {$from_record_num}, {$records_per_page}";  
  
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
  
        return $stmt;
    }


    public function countAllPopup(){
    
        $query = "SELECT id FROM popup";
    
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
    
        $num = $stmt->rowCount();
    
        return $num;
    }


    function showPopupById(){
        $query = "SELECT *
        FROM popup
        WHERE id = ?
        LIMIT 0,1";
              
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->id_popup);
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        $this->id_popup = $row['id'];
        $this->title_popup = $row['title'];
        $this->editor_popup = $row['content'];
        $this->page_popup = $row['pagename'];        
    }

    function showPopupByPage(){
        $query="SELECT *
            FROM popup
            WHERE pagename = :pagename";

            $stmt=$this->conn->prepare($query);


        $stmt->bindParam(':pagename',$this->page_popup);       
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->id_popup = $row['id'];
        $this->title_popup = $row['title'];
        $this->editor_popup = $row['content'];
        $this->page_popup = $row['pagename'];
    }


    
    function deletePopup(){

        $query="DELETE FROM popup WHERE id = :id";
    
        $stmt=$this->conn->prepare($query);
    
        $stmt->bindParam(':id',$this->id_popup);
    
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    
    }
    
}

