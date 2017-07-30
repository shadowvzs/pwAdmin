<?php 
session_start();
include "../config.php";
include "../basefunc.php";
$resp="Unknown Error";
$err_arr = array("error" => "Unknown Error!");
$user_arr = array();
$log_arr = array();
$clog_arr = array();
$role_arr = array();
SessionVerification();
if ($_SESSION['UD3'] != $AKey2){die();}
$data = json_decode(file_get_contents('php://input'), true);
if (($data)&&(isset($_SESSION['un']))) {
	$id=$data['id'];
	$un=$_SESSION['un'];
	$pw=$_SESSION['pw'];
	$uid=$_SESSION['id'];
	$ma=$_SESSION['ma'];
	if (($id > 15)&&(($uid==$AdminId)||($uid==$id))){
		$link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
		$Admin=VerifyAdmin($link, $un, $pw, $uid, $ma);

		if (($Admin!==false)||($uid==$id)){
			$query = "SELECT ID, name, truename, email, birthday, creatime, gender, idnumber, VotePoint, VoteDates FROM users WHERE ID=?";
			$statement = $link->prepare($query);
			$statement->bind_param('i', $id);
			$statement->execute();
			$statement->bind_result($LID, $LName, $Lrname, $Lmail, $Lbday, $LRegDate, $Lsex, $LIpAddr, $LWebPoint, $LVC);
			$statement->store_result();
			$result = $statement->num_rows;
			$LastVotes = "";
			$WPoint = 0;
			if (!$result) {
				$err_arr["error"] = "User not exist!";
				exit;
			}else{
				$rank=CountMysqlRows ($link, 5, $id);
				while($statement->fetch()) {
					$LPw="";
					$SelfA=0;
					$srank=0;
					if ($uid==$id){$LPw=$pw;$SelfA=1;}
					if ($Admin!==false){$srank=1;}
					$user_arr = array("self" => $Self, "id" => $LID, "username" => $LName, "password" => $LPw, "rank" => $rank, "srank" => $srank, "truename" =>  $Lrname,"email" =>  $Lmail, "birthday" =>  $Lbday, "creatime" =>  $LRegDate, "gender" =>  $Lsex, "ip" => $LIpAddr, "votepoint" => $LWebPoint, "votedate" => $LVC, "pointtogold" => $PointExc);
				}   
			}
			$statement->close();
			
			$query = "SELECT lastlogin, zoneid, zonelocalid FROM point WHERE uid=?";
			$statement = $link->prepare($query);
			$statement->bind_param('i', $id);
			$statement->execute();
			$statement->bind_result($lastlog, $zoneid, $zonelid);
			$statement->store_result();
			$result = $statement->num_rows;
			if ($result>0) {
				while($statement->fetch()) {
					$log_arr=array("lastlogin" => $lastlog, "zoneid" => $zoneid, "zonelocalid" => $zonelid);
				}
			}
			$statement->close();	
			
			$query = "SELECT cash, fintime FROM usecashlog WHERE userid=?";
			$statement = $link->prepare($query);
			$statement->bind_param('i', $id);
			$statement->execute();
			$statement->bind_result($cash, $fintime);
			$statement->store_result();
			$result = $statement->num_rows;
			if ($result) {
				$i=0;
				while($statement->fetch()) {
					$clog_arr[$i]=array("cash" => $cash, "fintime" => $fintime);
					$i++;
				}
			}
			$statement->close();
			
			include("../php/packet_class.php");
			$CharCount=0;
			$GetUserRolesArg = new WritePacket();
			$GetUserRolesArg -> WriteUInt32(-1); // always
			$GetUserRolesArg -> WriteUInt32($id); // userid
			$GetUserRolesArg -> Pack(0xD49);//0xD49
			if ($GetUserRolesArg -> Send("localhost", 29400)){ // send to gamedbd
				//return;
				$GetUserRolesRes = new ReadPacket($GetUserRolesArg); // reading packet from stream
				$GetUserRolesRes -> ReadPacketInfo(); // read opcode and length
				$GetUserRolesRes -> ReadUInt32(); // always
				$GetUserRolesRes -> ReadUInt32(); // retcode
				$CharCount = $GetUserRolesRes -> ReadCUInt32();
															
				for ($i = 0; $i < $CharCount; $i++){
					$roleid = $GetUserRolesRes -> ReadUInt32();
					$rolename = $GetUserRolesRes -> ReadUString();
										
					$GetRoleBase = new WritePacket();
					$GetRoleBase -> WriteUInt32(-1); // always
					$GetRoleBase -> WriteUInt32($roleid); // userid
					$GetRoleBase -> Pack(0x1F43); // opcode  

					if (!$GetRoleBase -> Send("localhost", 29400)) // send to gamedbd
					return;

					$GetRoleBase_Re = new ReadPacket($GetRoleBase); // reading packet from stream
					$packetinfo = $GetRoleBase_Re -> ReadPacketInfo(); // read opcode and length
					$GetRoleBase_Re -> ReadUInt32(); // always
					$GetRoleBase_Re -> ReadUInt32(); // retcode
					$GetRoleBase_Re -> ReadUByte();
					$GetRoleBase_Re -> ReadUInt32();
					$GetRoleBase_Re -> ReadUString();
					$GetRoleBase_Re -> ReadUInt32();
					$roleCls = cls2class($GetRoleBase_Re -> ReadUInt32());
					$GetRoleBase_Re -> ReadUByte();
					$GetRoleBase_Re -> ReadOctets();
					$GetRoleBase_Re -> ReadOctets();
					$GetRoleBase_Re -> ReadUInt32();
					$GetRoleBase_Re -> ReadUByte();
					$roleDelTime = $GetRoleBase_Re -> ReadUInt32();
					$GetRoleBase_Re -> ReadUInt32();
					$roleLastLogin = $GetRoleBase_Re -> ReadUInt32();
					$forbidcount = $GetRoleBase_Re -> ReadCUInt32();
					for ($x = 0; $x < $forbidcount; $x++){
						$GetRoleBase_Re -> ReadUByte();
						$GetRoleBase_Re -> ReadUInt32();
						$GetRoleBase_Re -> ReadUInt32();
						$GetRoleBase_Re -> ReadUString();
					}
					$GetRoleBase_Re -> ReadOctets();
					$GetRoleBase_Re -> ReadUInt32();
					$GetRoleBase_Re -> ReadUInt32();
					$GetRoleBase_Re -> ReadOctets();
					$GetRoleBase_Re -> ReadUByte();
					$GetRoleBase_Re -> ReadUByte();
					$GetRoleBase_Re -> ReadUByte();
					$GetRoleBase_Re -> ReadUByte();
					$roleLevel = $GetRoleBase_Re -> ReadUInt32();
					$roleCulti = $GetRoleBase_Re -> ReadUInt32();
					$roleClass = $PWclass[$roleCls];
					$rolePath = "";
					if (($roleCulti > 19) && ($roleCulti<23)){
						$rolePath = $PWclsPath[1]." ";
					}elseif(($roleCulti>29)&&($roleCulti<33)){
						$rolePath = $PWclsPath[2]." ";
					}
					if (isset($PWclass[$roleCls])){
						$role_arr[$i]=array("roleid" => $roleid, "rolename" => $rolename, "roleclass" => $roleClass, "rolelevel" => $roleLevel, "rolepath" => $rolePath, "roledel" => $roleDelTime, "roleban" => $forbidcount, "roleculti" => $roleCulti);
					}
				}
			}
			
			$err_arr["error"]="";			
		}
		mysqli_close($link);
	}else{
		$err_arr["error"]="No permission for load this data!";
	}
}

$return_arr = array();
$return_arr[0]=$err_arr;
$return_arr[1]=$user_arr;
$return_arr[2]=$log_arr;
$return_arr[3]=$clog_arr;
$return_arr[4]=$role_arr;
echo json_encode($return_arr);	
?>
