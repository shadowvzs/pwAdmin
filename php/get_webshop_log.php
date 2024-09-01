<?php 
session_start();
include "../config.php";
include "../basefunc.php";
$header_arr = array("error" => "Unknown Error!", "reloaduserlist" => "0", "success" => "", "reloaduserdata" => "0");
$wslog_arr = array();
SessionVerification();
if ($_SESSION['UD3'] != $AKey2){die();}
$data = json_decode(file_get_contents('php://input'), true);
if (($data)&&(isset($_SESSION['un']))) {
	$atool=intval($data["tool"]);
	$un=$_SESSION['un'];
	$pw=$_SESSION['pw'];
	$uid=$_SESSION['id'];
	$ma=$_SESSION['ma'];
	if (($uid==$AdminId)&&($atool>0)){
		$link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
		$Admin=VerifyAdmin($link, $un, $pw, $uid, $ma);
		if ($Admin!==false){
				if ($amount>0){
					$TIME=date("Y-m-d H:i:s");
					$query="SELECT id, user, uname, role, rname, buydate, currency, price, idata, shopid FROM wshoplog ORDER BY id DESC";
					$stmt = $link->prepare($query);
					$stmt->execute(); 
					$stmt->bind_result($id, $user, $uname, $role, $rname, $buydate, $currency, $price, $idata, $shopid);
					$stmt->store_result();
					$result = $stmt->num_rows;
					if ($result) {
						while($stmt->fetch()) {
							$wslog_arr[$c] = array(
								"id" => $id,
								"user" => $user,
								"uname" => $uname,
								"role" => $role,
								"rname" => $rname,
								"buydate" => $buydate,
								"currency" => $currency,
								"price" => $price,
								"idata" => $idata,
								"shopid" => $shopid
							);
							$c++;
						}   
					}
					$stmt->close();
					$header_arr["reloaduserdata"]="1";
					$header_arr["success"]="Gold added to account";
				}else{
					$header_arr["error"]="Amount is 0";
				}
		}
		mysqli_close($link);
	}else{
		$header_arr["error"]="No permission for load this data!";
	}
}
if ($header_arr["success"]!=""){$header_arr["error"]="";}
$return_arr = array();
$return_arr[0]=$header_arr;
$return_arr[1]=$wslog_arr;
echo json_encode($return_arr);
?>
