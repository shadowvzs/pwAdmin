<?php 
ini_set('display_errors', 1);
session_start();

include "../config.php";
include "../basefunc.php";
include "../php/sendgamemail.php";
include("../php/packet_class.php");
$header_arr = array("error" => "Unknown Error!", "success" => "", "gold" => "", "point" => "");

		$un=$_SESSION['un'];
		$pw=$_SESSION['pw'];
		$uid=$_SESSION['id'];
		$userid=$uid;
		$ma=$_SESSION['ma'];
		//check server if running
		$roleId = 12032; 
		$GRoleData=GetRoleData($roleId, $ServerVer);
		 echo "<pre>";
		 printf(json_encode($GRoleData['status']['level2'], JSON_PRETTY_PRINT));
		 echo "</pre>";
		 // $GRoleData['status']['level2'] = 32;
		 // PutRoleData($roleId, $GRoleData, $ServerVer);
		 // 32
		 // 5016
		 // 96210000
		 // $GRoleData['status']['skills'] = "090000009e00000000000000010000009f0000000000000001000000a00000000000000001000000a10000000000000001000000a700000000000000010000002b010000000000000a00000048010000000000000a00000049010000000000000a0000004a010000000000000a000000";
         // PutRoleData($roleId, $GRoleData, $ServerVer);
		//$GRoleData['base']['name'] = "HMM";
		/*
		if ($GRoleData["equipment"]['invC'] > 0) {
			foreach ($GRoleData["equipment"]['inv'] as &$value) {
				if (strpos($value['data'], "96210000") !== false) {
					$value['expire_date'] = 60;
				}
			}
		}
		if ($GRoleData["pocket"]['itemsC'] > 0) {
			foreach ($GRoleData["pocket"]['items'] as &$value) {
				if (strpos($value['data'], "96210000") !== false) {
					$value['expire_date'] = 60;
				}
			}
		}
		if ($GRoleData["storehouse"]['itemsC'] > 0) {
			foreach ($GRoleData["storehouse"]['items'] as &$value) {
				if (strpos($value['data'], "96210000") !== false) {
					$value['expire_date'] = 60;
				}
			}
		}
		PutRoleData($roleId, $GRoleData, $ServerVer);
		*/
		// $GRoleData=GetRoleData($roleId, $ServerVer);
		// printf(json_encode($GRoleData, JSON_PRETTY_PRINT));
	
die('aa');

function ShopLog($conn, $buyWith, $price, $roleId, $roleName, $shopid, $idata, $amount){
	$username = $_SESSION['un'];
	$userid=$_SESSION['id'];
	global $WebShopLog;
	global $WShopLogDel;
	
	if ($WebShopLog !== false){
		$mysqltime = date ("Y-m-d H:i:s", time());
		$stmt = $conn->prepare("INSERT INTO wshoplog (user, uname, role, rname, buydate, currency, price, amount, idata, shopid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("isissiiisi", $userid, $username, $roleId, $roleName, $mysqltime, $buyWith, $price, $amount, $idata, $shopid);
		$stmt->execute(); 
		$stmt->close();		
		if ($WShopLogDel>0){
			$query = "DELETE FROM wshoplog WHERE DATEDIFF(NOW(), DATE(buydate)) > ".$WShopLogDel;
			$conn->query($query);
		}
	}
}

function UserOnlineCheck($con, $id){
	if ($con->connect_errno) {
		return "";
		//echo "<script>alert('Sorry, this website is experiencing problems (failed to make a MySQL connection)!');</script>";
	}else{
		$query = "SELECT uid FROM point WHERE uid=? AND zoneid IS NOT NULL";
		$statement = $con->prepare($query);
		$statement->bind_param('i', $id);
		$statement->execute();
		$statement->store_result();
		$result = $statement->num_rows;
		$count = $result;
		$statement->free_result();
	}
	return $count;
}

function ShopItemTimeVerification($iDate){
	if (strpos($iDate, ' ') !== false){
		$ISDate = explode(" ",$iDate);
		if (($ISDate[0]==0)||($ISDate[0]==3)){
			return true;
		}else{
			if ($ISDate[0]==1){
				$cTimeStamp=time();
				if (($cTimeStamp<intval($ISDate[2]))||($cTimeStamp>intval($ISDate[3]))){
					return false;
				}else{
					return true;
				}
			}elseif ($ISDate[2]){
				$hour1 = explode(" ",$ISDate[2]);
				$hour2 = explode(" ",$ISDate[3]);
				$hour3 = date('H:i');
				$dtime1 = intval($hour1[0])*60+intval($hour1[1]);
				$dtime2 = intval($hour2[0])*60+intval($hour2[1]);
				$dtime3 = intval($hour3[0])*60+intval($hour3[1]);
				if (($dtime3<$dtime1)||($dtime3>$dtime2)){
					return false;
				}else{
					return true;
				}					
			}
			
		}
	}
	return false;
}


function CheckAndFixRunes($idata, $RuneList){
	$iArr = explode("#", $idata);
	if ($RuneList != ""){
		$MType=substr($iArr[4], 0, 1);
		$SType=intval(substr($iArr[4], 1));
		if (($MType=="W")||($MType=="A")||($MType=="J")||($MType=="O")&&(($SType==6)||($SType==3))){
			//we need to be sure so need recalculate again where starting the addons in octet so we need check each case...
			$Octet=$iArr[15];
			$x=0;
			$olen=strlen($Octet);
			if ($MType=="W"){
				if ($olen > 151){
					$NameLen = intval(hexdec(substr($Octet,46,2))/2);
					$SocketC = intval(hexdec(substr($Octet,136+$NameLen*4,2))); 
					$x=$NameLen*4+$SocketC*8+144+8;
				}
			}elseif(($MType=="A")||(($MType=="O")&&($SType==3))){
				if ($olen > 135){
					$NameLen = intval(hexdec(substr($Octet,46,2))/2);
					$SocketC = intval(hexdec(substr($Octet,120+$NameLen*4,2))); 
					$x=$NameLen*4+$SocketC*8+128+8;
				}					
			}elseif($MType=="J"){
				if ($olen > 135){
					$NameLen = intval(hexdec(substr($Octet,46,2))/2);
					$SocketC = intval(hexdec(substr($Octet,120+$NameLen*4,2))); 
					$x=$NameLen*4+$SocketC*8+128+8;
				}		
			}elseif(($MType=="O")&&($SType==6)){				
				$x=104;
			}
			
			$Runes=explode("*",$RuneList);
			$before=substr($Octet,0,$x);
			$after=substr($Octet,$x);
			$alen=strlen($after);
			$newAfter=$after;
			for ($i = 0; $i < count($Runes); $i++) {
				$pos = strpos($after, $Runes[$i]);
				if ($pos !== false){
					if (($pos+24)<=$alen){
						$RawRuneTime=substr($after,$pos+16,8);
						$RuneTime=intval(ConvRevHexToDec($RawRuneTime))*60+time();
						$RunTimeHex=ConvDecToRevHex($RuneTime);
						$befAft=substr($newAfter,0,$pos+16);
						$aftAft=substr($newAfter,$pos+24);
						$newAfter=$befAft.$RunTimeHex.$aftAft;
					}
				}
			}
			return $before.$newAfter;
		}
	}
	return $iArr[15];
}

function ShopItemDiscVerification($iDate){
	if (strpos($iDate, ' ') !== false){
		$ISDate = explode(" ",$iDate);
		if ($ISDate[0]==3){
			$cTimeStamp=time();
			if (($cTimeStamp<intval($ISDate[2]))||($cTimeStamp>intval($ISDate[3]))){
				return 0;
			}else{
				return intval($ISDate[4], 10);
			}
		}
	}
	return 0;
}


if ($header_arr["success"]!=""){$header_arr["error"]="";}
$return_arr = array();
$return_arr[0]=$header_arr;
echo json_encode($return_arr);	
?>
