<?php
// require '../phpDebug/src/Debug/Debug.php';   			// if not using composer

// $debug = new \bdk\Debug(array(
//     'collect' => true,
//     'output' => true,
// ));


/*
==========================================================================

Mini Cms is a project by damares86 (https://github.com/damares86/mini_cms)

==========================================================================
*/


if(!$_POST['dbname']||!$_POST['username']||!$_POST['db_password']||!$_POST['host']){
  header("Location: ../../restore.php?err=missing");
  exit;
} 



unlink('../class/Database.php');

  $db_name=filter_input(INPUT_POST,"dbname");
  $username=filter_input(INPUT_POST,"username");
  $db_password=filter_input(INPUT_POST,"db_password");
  $host=filter_input(INPUT_POST,"host");
  $file_handle = fopen('../class/Database.php', 'w');
  fwrite($file_handle, '<?php');
  fwrite($file_handle, "\n");
  fwrite($file_handle, "class Database{");
  fwrite($file_handle, "\n");
  fwrite($file_handle, 'public $db_name="'.$db_name.'";');
  fwrite($file_handle, "\n");
  fwrite($file_handle, 'public $username="'.$username.'";');
  fwrite($file_handle, "\n");
  fwrite($file_handle, 'public $password="'.$db_password.'";');
  fwrite($file_handle, "\n");
  fwrite($file_handle, 'public $host="'.$host.'";');
  fwrite($file_handle, "\n");
  fwrite($file_handle, 'public $conn;');
  fwrite($file_handle, "\n");
  fwrite($file_handle, "\n");
  fwrite($file_handle, "public function getConnection(){");
  fwrite($file_handle, "\n");
  fwrite($file_handle, '$this->conn = null;');
  fwrite($file_handle, "\n");
  fwrite($file_handle, 'try{');
  fwrite($file_handle, "\n");
  fwrite($file_handle, '$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);');
  fwrite($file_handle, "\n");
  fwrite($file_handle, '}catch(PDOException $exception){');
  fwrite($file_handle, "\n");
  fwrite($file_handle, 'echo "Connection error: " . $exception->getMessage();');
  fwrite($file_handle, "\n");
  fwrite($file_handle, '}');
  fwrite($file_handle, "\n");
  fwrite($file_handle, 'return $this->conn;');
  fwrite($file_handle, "\n");
  fwrite($file_handle, '}');
  fwrite($file_handle, "\n");
  fwrite($file_handle, '}');
  fwrite($file_handle, "\n");

  fwrite($file_handle, '?>');

chmod('../class/Database.php',0777);


try
   {
     $db = new PDO('mysql:dbname='.$db_name.';host='.$host,$username,$db_password);
     $sql = implode(array_map(function ($v) {
        return file_get_contents($v);
        }, glob("../*.sql")));

     $qr = $db->exec($sql); 
     echo "Import action - 100% successfull";
   }
   catch (PDOException $e) 
   {
     echo 'Connection failed: ' . $e->getMessage();
}

// spl_autoload_register('autoloader');

// function autoloader($class){
// 	include("../class/$class.php");
// }

// $database = new Database();
// $db = $database->getConnection();

// $query = '';
// $sqlScript = file('../backup.sql');
// foreach ($sqlScript as $line)	{
	
// 	$startWith = substr(trim($line), 0 ,2);
// 	$endWith = substr(trim($line), -1 ,1);
	
// 	if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
// 		continue;
// 	}
		
// 	$query = $query . $line;
// 	if ($endWith == ';') {
// 		mysqli_query($conn,$query) or die('<div class="error-response sql-import-response">Problem in executing the SQL query <b>' . $query. '</b></div>');
// 		$query= '';		
// 	}
// }

unlink("../../restore.php");
unlink("../backup.sql");

class DeleteOnExit {
   function __destruct() {
      unlink(__FILE__);
   }
}
$delete_on_exit = new DeleteOnExit();

header("Location: ../../");
exit;

?>