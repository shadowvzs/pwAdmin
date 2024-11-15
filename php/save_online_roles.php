<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$base_path = "/var/www/html/retroms.ddns.net";
include("$base_path/config.php");
include("$base_path/php/packet_class.php");

$jsonFilepath = "$base_path/data/online_roles.json";
/*
$fileContent = @file_get_contents($storageFileName);
$lastTime = time() - 3600;

if (gettype($fileContent) == "string") {
	$lastTime = intval($fileContent);
}
*/

$link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);

$query="SELECT u.ID, u.name, p.zoneid FROM point as p RIGHT JOIN users as u ON u.ID = p.uid WHERE p.zoneid IS NOT NULL";
$statement = $link->prepare($query);
$statement->execute();
$statement->bind_result($id, $username, $zoneid);
$statement->store_result();
$result = $statement->num_rows;
$onlineIds = [];

if (!$result) {
	$statement->close();
	mysqli_close($link);
	die("[]");
}
while($statement->fetch()) {
	array_push($onlineIds, $id);
}

$statement->close();

$onlineRoles = [];

foreach($onlineIds as $onlineUserId) {
	$roles = loadUserRoles($onlineUserId);
	if (count($roles) === 0) { continue; }
	$onlineRole = $roles[0];
	foreach($roles as $role) {
		if ($onlineRole['lastLogin'] < $role['lastLogin']) {
			$onlineRole = $role;
		}
	}
	$onlineRoles[] = $onlineRole;
}
mysqli_close($link);

file_put_contents($jsonFilepath, json_encode($onlineRoles));
die();

function loadUserRoles($id) {
	global $PWclsPath;
	global $PWclass;
	
	$role_arr = array();
	$CharCount=0;
	$GetUserRolesArg = new WritePacket();
	$GetUserRolesArg -> WriteUInt32(-1); // always
	$GetUserRolesArg -> WriteUInt32($id); // userid
	$GetUserRolesArg -> Pack(0xD49);//0xD49
	if ($GetUserRolesArg -> Send("localhost", 29400)){ // send to gamedbd
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

			// send to gamedbd
			if (!$GetRoleBase -> Send("localhost", 29400)) { return; }

			$GetRoleBase_Re = new ReadPacket($GetRoleBase); // reading packet from stream
			$packetinfo = $GetRoleBase_Re -> ReadPacketInfo(); // read opcode and length
			$GetRoleBase_Re -> ReadUInt32(); // always
			$GetRoleBase_Re -> ReadUInt32(); // retcode
			$GetRoleBase_Re -> ReadUByte();
			$GetRoleBase_Re -> ReadUInt32();
			$GetRoleBase_Re -> ReadUString();
			$GetRoleBase_Re -> ReadUInt32();
			$roleCls = $GetRoleBase_Re -> ReadUInt32();
			$gender = $GetRoleBase_Re -> ReadUByte();
			$GetRoleBase_Re -> ReadOctets();
			$GetRoleBase_Re -> ReadOctets();
			$GetRoleBase_Re -> ReadUInt32();
			$status = $GetRoleBase_Re -> ReadUByte();
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
			$exp = $GetRoleBase_Re -> ReadUInt32();
			$sp = $GetRoleBase_Re -> ReadUInt32();
			$pp = $GetRoleBase_Re -> ReadUInt32();
			$hp = $GetRoleBase_Re -> ReadUInt32();
			$mp = $GetRoleBase_Re -> ReadUInt32();
			$posX = $GetRoleBase_Re -> ReadFloat();
			$posY = $GetRoleBase_Re -> ReadFloat();
			$posZ = $GetRoleBase_Re -> ReadFloat();
			$worldTag = $GetRoleBase_Re -> ReadUInt32();

			$role_arr[$i]=array(
				"id" => $roleid,
				"name" => $rolename,
				"class" => $roleCls,
				"level" => $roleLevel,
				"deleteTime" => $roleDelTime,
				"ban" => $forbidcount,
				"cultivation" => $roleCulti,
				"exp" => $exp,
				"sp" => $sp,
				"pp" => $pp,
				"hp" => $hp,
				"mp" => $mp,
				"posX" => $posX,
				"posY" => $posY,
				"posZ" => $posZ,
				"map" => $worldTag,
				"gender" => $gender,
				"status" => $status,
				"lastLogin" => $roleLastLogin
			);
		}
	}
	
	return $role_arr;
}
?>
