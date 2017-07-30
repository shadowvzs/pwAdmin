<?php 
session_start();
include "../config.php";
include "../basefunc.php";
$resp="Unknown Error";
SessionVerification();
$data = json_decode(file_get_contents('php://input'), true);
if ( $data ) {
	$un=$_SESSION['un'];
	$pw=$_SESSION['pw'];
	$id=$_SESSION['id'];
	$ma=$_SESSION['ma'];	
	$link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
	if ($link->connect_errno) {
		$resp="Sorry, this website is experiencing problems (failed to make a MySQL connection)!";
	}else{
		if (VerifyAdmin($link, $un, $pw, $id, $ma)!==false){
			$filen='../config.php';
			$fileno='../config_old.php';
			$str=file_get_contents($filen);
			$oldConfId = $AKey1;
			$newConfId = base64_encode(md5(time()."This is admin reset key"));
			$str=str_replace($oldConfId, $newConfId, $str);
			$oldConfId = $AKey2;
			$newConfId = base64_encode(md5(time()."Secondary reset alot better!"));
			$str=str_replace($oldConfId, $newConfId, $str);	
			chmod($filen, 0777);
			rename($filen, $fileno);
			file_put_contents($filen, $str);
			chmod($filen, 0755);	
			unset ($_SESSION['un']);
			unset ($_SESSION['pw']);
			unset ($_SESSION['id']);
			unset ($_SESSION['ma']);
			if (isset($_SESSION['t'])){
				unset ($_SESSION['t']);
			}
			if (isset($_SESSION['UD1'])){
				unset ($_SESSION['UD1']);
			}
			if (isset($_SESSION['UD2'])){
				unset ($_SESSION['UD2']);
			}
			if (isset($_SESSION['UD3'])){
				unset ($_SESSION['UD3']);
			}		
			$resp="";
		}else{
			$resp="No permission!";
		}
	}	
	mysqli_close($link);

}
echo $resp;

?>
