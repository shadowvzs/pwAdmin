<?php 
session_start();
include "../config.php";
include "../basefunc.php";
$resp="Unknown Error";
SessionVerification();
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
				$sockres = @FSockOpen($LanIP, $ServerPort, $errno, $errstr, 1);
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

}
echo $resp;

?>
