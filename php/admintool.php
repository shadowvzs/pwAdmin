<?php 
session_start();
include "../config.php";
include "../basefunc.php";
$header_arr = array("error" => "Unknown Error!", "reloaduserlist" => "0", "success" => "", "reloaduserdata" => "0");
$user_arr = array();
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
			if (isset($data["id"])){$id=$data["id"];}
			if (isset($data["amount"])){$amount=$data["amount"];}
			if (isset($data["day"])){$days=$data["day"];}
			if ($atool==2){
				if ($amount>0){
					$TIME=date("Y-m-d H:i:s");
					$query="INSERT INTO usecashnow (userid, zoneid, sn, aid, point, cash, status, creatime) VALUES ('$id', '1', '0', '1', '0', '$amount', '1', '$TIME') ON DUPLICATE KEY UPDATE cash = cash + $amount";
					$stmt = $link->prepare($query);
					$stmt->execute(); 
					$stmt->close();
					$header_arr["reloaduserdata"]="1";
					$header_arr["success"]="Gold added to account";
				}else{
					$header_arr["error"]="Amount is 0";
				}
			}elseif ($atool==3){
				if ($amount>0){
					$query = "UPDATE users SET VotePoint = VotePoint+$amount WHERE ID=?";
					$stmt = $link->prepare($query);
					$stmt->bind_param('i', $id);
					$stmt->execute(); 
					$stmt->close();		
					$header_arr["reloaduserdata"]="1";
					$header_arr["success"]="Point added to account";					
				}else{
					$header_arr["error"]="Amount is 0";
				}
			}elseif ($atool==4){
				$GMr=CountMysqlRows($link,5,$id);
				if ($GMr==0){
					mysqli_query($link, "call addGM('".$id."', '1')");
					$header_arr["success"]="Account became GM account";
					$header_arr["reloaduserdata"]="1";
					$header_arr["reloaduserlist"]="1";
				}else{
					$header_arr["error"]="Already GM account";
				}					
			}elseif ($atool==5){
				$stmt = $link->prepare("DELETE FROM auth WHERE userid = ?");
				$stmt->bind_param('i', $id);
				$stmt->execute(); 
				$stmt->close();		
				$header_arr["reloaduserdata"]="1";
				$header_arr["reloaduserlist"]="1";				
				$header_arr["success"]="Account is normal account";				
			}elseif ($atool==6){
				$BannedId = intval($data["targetid"]);
				$banType = intval($data["bantype"]);
				$GMId = intval($data["gmid"]);
				$Duration = intval($data["bandur"]);
				$Reason = $data["banreason"];
				if (($BannedId > 0)&&($banType > 0)&&($banType < 5)){
					if ($Duration < 5){$Duration=5;}
					include("../php/packet_class.php");
					$Packet = new WritePacket();
					$Packet -> WriteUInt32($GMId); // gmroleid ex. -1
					$Packet -> WriteUInt32(0); // ssid
					$Packet -> WriteUInt32($BannedId); // ID role/account ex. 16
					$Packet -> WriteUInt32($Duration); // Time ex. 3600
					$Packet -> WriteUString($Reason); //Reason
					switch($banType){
						case 1:
							$Packet -> Pack(0x162); //Ban account
							break;
						case 2:
							$Packet -> Pack(0x164); //Ban chat account
							break;
						case 3:
							$Packet -> Pack(0x16A); //Ban chat role
							break;
						case 4:
							$Packet -> Pack(0x168); //Ban role
							break;
						default:
							return;
						}
					$Packet -> Send("localhost", 29100);
					$header_arr["success"]="Ban action executed";	
					$header_arr["reloaduserdata"]="1";
				}else{
					$header_arr["error"]="Use the correct settings!";
				}
			//tool 7 removed
			}elseif ($atool==8){
				if ($id != $AdminId){
					DeleteUserAccount ($link, $id);
					$header_arr["success"]="Account deleted";
					$header_arr["reloaduserlist"]="1";					
				}				
			}elseif ($atool==9){
				if (($days > 0)&&($days<36500)){
					$header_arr["reloaduserlist"]=$days;
					$c=0;
					$query="SELECT uid FROM point WHERE lastlogin < DATE_SUB(NOW(), INTERVAL {$days} DAY) AND uid <> {$AdminId}";
					$statement = $link->prepare($query);
					$statement->execute();
					$statement->bind_result($id1);
					$statement->store_result();
					$result = $statement->num_rows;
					$c=$c+$result;
					if ($result<1) {
						$header_arr["error"]="Do not exist user with that much inactive day since last login date!";
					}else{
						while($statement->fetch()) {
							DeleteUserAccount ($link, $id1);
							$header_arr["reloaduserlist"]="1";
						}   
					}
					
					$header_arr["reloaduserlist"]=$days;
					$statement->close();
					
					
					$query="SELECT ID FROM users WHERE ID <> {$AdminId} AND creatime < DATE_SUB(NOW(), INTERVAL {$days} DAY) AND (NOT EXISTS (SELECT null FROM point WHERE users.ID = point.uid))";
					$statement = $link->prepare($query);
					$statement->execute();
					$statement->bind_result($id1);
					$statement->store_result();
					$result = $statement->num_rows;
					$c=$c+$result;
					if (!$result) {
						$header_arr["error"]="Do not exist user with that much inactive day since last login date!";
					}else{
						while($statement->fetch()) {
							DeleteUserAccount ($link, $id1);
							$header_arr["reloaduserlist"]="1";
						}   
					}
					$statement->close();
					
					
					$header_arr["success"]=$c." user deleted";
				}else{
					$header_arr["error"]="Day must be between 0 and 36500!";
				}
				
			}elseif ($atool==10){
				if ((($days > -1)&&($days<36500))&&(($amount > 0)&&($amount<99999999))){
					if ($days==0){
						$query="SELECT uid FROM point WHERE zoneid IS NOT NULL";
					}else{
						$query="SELECT uid FROM point WHERE lastlogin >= ( CURDATE() - INTERVAL {$days} DAY )";
					}
					$statement = $link->prepare($query);
					$statement->execute();
					$statement->bind_result($id1);
					$statement->store_result();
					$result = $statement->num_rows;
					if (!$result) {
						$header_arr["error"]="Do not exist user who was online in last ".$days." day!";
					}else{
						$nr0 = 0;
						$nr1 = 1;
						$TIME=date("Y-m-d H:i:s");
						while($statement->fetch()) {
							$query="INSERT INTO usecashnow (userid, zoneid, sn, aid, point, cash, status, creatime) VALUES ('$id1', '1', '0', '1', '0', '$amount', '1', '$TIME') ON DUPLICATE KEY UPDATE cash = cash + $amount";
							$stmt = $link->prepare($query);
							$stmt->execute(); 
							$stmt->close();
						}   
						$header_arr["success"]=$result." reicived gold";
						$header_arr["reloaduserdata"]="1";
					}
					$statement->close();											
				}				
			}elseif ($atool==11){
				if ((($days > -1)&&($days<36500))&&(($amount > 0)&&($amount<99999999))){
					if ($days==0){
						$query="SELECT uid FROM point WHERE zoneid IS NOT NULL";
					}else{
						$query="SELECT uid FROM point WHERE lastlogin >= ( CURDATE() - INTERVAL $days DAY )";
					}
					$statement = $link->prepare($query);
					$statement->execute();
					$statement->bind_result($id1);
					$statement->store_result();
					$result = $statement->num_rows;
					if (!$result) {
						$header_arr["error"]="Do not exist user who was online in last ".$days." day!";
					}else{
						while($statement->fetch()) {
							$query = "UPDATE users SET VotePoint=VotePoint+$amount WHERE ID=?";
							$stmt = $link->prepare($query);
							$stmt->bind_param('i', $id1);
							$stmt->execute(); 
							$stmt->close();
						}   
						$header_arr["success"]=$result." reicived point";
						$header_arr["reloaduserdata"]="1";
					}
					$statement->close();
				}				
			}elseif ($atool==12){
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
				$header_arr["error"]="Please relog!";			
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
$return_arr[1]=$user_arr;
echo json_encode($return_arr);	

function DeleteUserAccount ($con, $uid){
	$uid=intval($uid);
	$stmt = $con->prepare("DELETE FROM users WHERE ID = ?");
	$stmt->bind_param('i', $uid);
	$stmt->execute(); 
	$stmt->close();
	$stmt = $con->prepare("DELETE FROM auth WHERE userid = ?");
	$stmt->bind_param('i', $uid);
	$stmt->execute(); 
	$stmt->close();
	$stmt = $con->prepare("DELETE FROM point WHERE uid = ?");
	$stmt->bind_param('i', $uid);
	$stmt->execute(); 
	$stmt->close();
	$stmt = $con->prepare("DELETE FROM usecashnow WHERE userid = ?");
	$stmt->bind_param('i', $uid);
	$stmt->execute(); 
	$stmt->close();
	$stmt = $con->prepare("DELETE FROM usecashlog WHERE userid = ?");
	$stmt->bind_param('i', $uid);
	$stmt->execute(); 
	$stmt->close();
	$stmt = $con->prepare("DELETE FROM forbid WHERE userid = ?");
	$stmt->bind_param('i', $uid);
	$stmt->execute(); 
	$stmt->close();
}
?>
