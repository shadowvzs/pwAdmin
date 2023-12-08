<?php 
session_start();
include "../config.php";
include "../basefunc.php";
include "../php/sendgamemail.php";
include("../php/packet_class.php");
$header_arr = array("error" => "Unknown Error!", "success" => "");
$list_arr = array();
SessionVerification();
$data = json_decode(file_get_contents('php://input'), true);
if ( $data ) {
	$un=$_SESSION['un'];
	$pw=$_SESSION['pw'];
	$id=$_SESSION['id'];
	$ma=$_SESSION['ma'];	
	$link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
	if ($link->connect_errno) {
		$header_arr["error"]="Sorry, this website is experiencing problems (failed to make a MySQL connection)!";
	}else{
		if ((VerifyAdmin($link, $un, $pw, $id, $ma)!==false)&&(isset($data["deleteshopitem"]))){
			$itmList=array_unique(array_map("intvalAll",$data['itemid']));
			//faster methode is: array_keys(array_flip($array)); 
			//flip array value with array keys then we ask only array keys
			$max=count($itmList);
			$s=0;
			//$f=0;
			if ($max > 0){
				for($x = 0; $x < $max; $x++ ){
					$stmt = $link->prepare("DELETE FROM webshop WHERE id = ?");
					$stmt->bind_param('i', $itmList[$x]);
					if ($stmt->execute()){
						$list_arr[$s]=$itmList[$x];
						$s++;
					}
					$stmt->close();						
				}
				$header_arr["success"]="{$s}/{$max} shop item deleted!";
			}else{
				$header_arr["error"]="Select atleast 1 item!";
			}
		}else{
			$header_arr["error"]="No permission!";
		}
	}	
	mysqli_close($link);
}

function intvalAll($v){
  return(intval($v));
}

if ($header_arr["success"]!=""){$header_arr["error"]="";}
$return_arr = array();
$return_arr[0]=$header_arr;
$return_arr[1]=$list_arr;
echo json_encode($return_arr);	

?>
