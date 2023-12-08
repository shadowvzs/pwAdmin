<?php 
session_start();
include "../config.php";
include "../basefunc.php";
$SelItemD = "../items/SList.txt";
$header_arr = array("error" => "Unknown Error!", "success" => "");
$list_arr = array();
SessionVerification();
$c=0;
if ($_SESSION['UD3'] != $AKey2){die();}
$data = json_decode(file_get_contents('php://input'), true);
if (($data)&&(isset($_SESSION['un']))) {
	$link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
	if ($link->connect_errno) {
		$header_arr["error"]="Sorry, this website is experiencing problems (failed to make a MySQL connection)!";
	}else{	
		$un=$_SESSION['un'];
		$pw=$_SESSION['pw'];
		$uid=$_SESSION['id'];
		$ma=$_SESSION['ma'];
		if ((VerifyAdmin($link, $un, $pw, $uid, $ma))&&(isset($data['savetempitems']))){
			$itmList = $data['itemlist'];
			$amount=count($itmList);
			if ($amount > 0){
				$file = fopen($SelItemD, "w");
				for($x = 0; $x < $amount; $x++ ){
					if ($itmList[$x] != ""){
						if ($amount != 1){
							fwrite($file, $itmList[$x].PHP_EOL);
						}else{
							fwrite($file, $itmList[$x]);
						}
					}
				}
				fclose($file);
				$header_arr['success']=$amount." packet saved!";
			}
		}
	}
	mysqli_close($link);
}


if ($header_arr["success"]!=""){$header_arr["error"]="";}
$return_arr = array();
$return_arr[0]=$header_arr;
$return_arr[1]=$list_arr;
echo json_encode($return_arr);	
?>
