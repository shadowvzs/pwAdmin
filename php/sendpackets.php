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
		if ((VerifyAdmin($link, $un, $pw, $id, $ma)!==false)&&(isset($data["sendpacket"]))){
			$itmList = $data['itemlist'];
			$amount=count($itmList);
			$s=0;
			$f=0;
			if ($amount > 0){
				for($x = 0; $x < $amount; $x++ ){
					if ($itmList[$x] != ""){
						$Arr=explode("#", $itmList[$x]);
						if ($Arr[2]==""){$Arr[2]="[GM]: ".$Arr[5];}
						if ($Arr[3]==""){$Arr[3]="Special gift to you, from GM!";}
						if (SysSendMail($Arr[0], $Arr[2], $Arr[3], $Arr[7], $Arr[10], $Arr[11], $Arr[15], $Arr[9], $Arr[14], $Arr[12], $Arr[13], $Arr[8], $Arr[1]) == 0){
							$s++;
						} 
					}
				}
				if ($s>0){
					$header_arr["success"]="{$s}/{$amount} mail sent";
				}else{
					$header_arr["error"]="Mail sending failed!";
				}
			}else{
				$header_arr["error"]="Packet not selected!";
			}
		}else{
			$header_arr["error"]="No permission!";
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
