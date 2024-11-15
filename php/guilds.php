<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include "../config.php";
include "../basefunc.php";
$resp="Unknown Error";
// SessionVerification();
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
		if ((VerifyAdmin($link, $un, $pw, $id, $ma)!==false)&&((isset($data['CRole']))&&(isset($data['CChannel']))&&(isset($data['CText'])))){
			$chat_role=intval($data['CRole']);
			$chat_channel=intval($data['CChannel']);
			$chat_text=trim($data['CText']);
			if ($chat_text != ""){
				$result = mysqli_query($link,"SELECT zoneid FROM point WHERE zoneid IS NOT NULL");
				$count = @mysqli_num_rows($result);
				$sockres = @FSockOpen('localhost', $ServerPort, $errno, $errstr, 1);
				if (!$sockres){
					mysqli_query($link,"DELETE FROM online");
					$SRunning=false;
				}else{
				   @FClose($sockres);
				   $SRunning=true;
				}   
				mysqli_free_result($result);	
				if ($SRunning===true){
					include("./packet_class.php");
					$ChatBroadCast = new WritePacket();
					$ChatBroadCast -> WriteUByte($chat_channel); 	//chat channel id
					$ChatBroadCast -> WriteUByte(0); 				//Emotion
					$ChatBroadCast -> WriteUInt32($chat_role);		//Roleid	but if offline then need to use 0
					$ChatBroadCast -> WriteUString($chat_text); 	//Text
					$ChatBroadCast -> WriteOctets(""); 				//Data
					$ChatBroadCast -> Pack(0x78); 					//Opcode
					$ChatBroadCast -> Send("localhost", 29300); 
					$resp="";
				}else{
					$resp="Server is offline, cannot send message!";
				}
			}else{
				$resp="Do not send empty message!";
			}
		}else{
			$resp="No permission!";
		}
	}	
	mysqli_close($link);

} else {
	if (!isset($_GET['action'])) { die(); }
	if ($_GET['action'] === 'list') {
		include("./packet_class.php");
		$guildList = getGuilds()['list'];
		$simplifierFunc = function($guild) {
			return [
				"name" => $guild['name'],
				"members" => count($guild['members']),
				"level" => $guild['level'] + 1,
				"id" => $guild['id'],
			];
		};
		$guildList = array_map($simplifierFunc, $guildList);
		echo json_encode($guildList);
		die();
	} else if ($_GET['action'] === 'guild' && isset($_GET['id'])) {
		include("./packet_class.php");
		$guild = getGuildInfo(intval($_GET['id']));
		echo json_encode($guild);
		die();
	} else if ($_GET['action'] === 'my-guilds' && isset($_SESSION['id'])) {
		include("./packet_class.php");
		$guildList = getGuilds()['list'];
		$id=$_SESSION['id'];
		$roles_array = loadUserRoles($id);
		$roleIds = array_map(function ($role) { return $role['roleid']; }, $roles_array);
		$userGuilds = array_filter($guildList, function ($guild) use ($roleIds) {
			$memberIds = array_map(function ($role) { return $role['role_id']; }, $guild['members']);
			$intersect = array_intersect($roleIds, $memberIds);
			return count($intersect) > 0;
		});
		$simplifierFunc = function($guild) {
			return [
				"id" => $guild['id'],
				"name" => $guild['name'],
				"members" => count($guild['members']),
				"level" => $guild['level'] + 1
			];
		};
		echo json_encode(array_map($simplifierFunc, array_values($userGuilds)));
		die();
	} else if ($_GET['action'] === 'delete' && isset($_GET['id']) && isset($_SESSION['id']) && $_SESSION['id'] == $AdminId) {
		include("./packet_class.php");
		$guild = deleteGuild(intval($_GET['id']));
		echo json_encode($guild);
		die();
	}

	if ($_GET['action'] === 'my-guilds' && isset($_SESSION['id'])) {
		die('[]');
	}
}
echo $resp."!";

?>
