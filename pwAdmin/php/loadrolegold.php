<?php 
session_start();
include "../config.php";
include "../basefunc.php";
$header_arr = array("error" => "Unknown Error!", "success" => "", "gold" => "");
SessionVerification();
if ($_SESSION['UD3'] != $AKey2){die();}
$data = json_decode(file_get_contents('php://input'), true);
if (($data)&&(isset($_SESSION['un']))) {
	if (isset($data['roleid'])){
		$id=intval($data['roleid']);
		if ($id>0){
			include("../php/packet_class.php");
			$GRoleData=GetRoleData($id, $ServerVer);
			$header_arr["gold"]=$GRoleData['pocket']['money'];
			$header_arr["success"]="Gold loaded from role";
		}
	}
}


if ($header_arr["success"]!=""){$header_arr["error"]="";}
$return_arr = array();
$return_arr[0]=$header_arr;
echo json_encode($return_arr);	
?>
