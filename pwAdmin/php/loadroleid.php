<?php 
session_start();
include "../config.php";
include "../basefunc.php";
$header_arr = array("error" => "Unknown Error!", "success" => "", "roleid" => "");
SessionVerification();
if ($_SESSION['UD3'] != $AKey2){die();}
$data = json_decode(file_get_contents('php://input'), true);
if (($data)&&(isset($_SESSION['un']))) {
	$link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
	if ($link->connect_errno) {
		$header_arr["error"]="Sorry, this website is experiencing problems (failed to make a MySQL connection)!";
	}else{	
		$un=$_SESSION['un'];
		$pw=$_SESSION['pw'];
		$uid=$_SESSION['id'];
		$ma=$_SESSION['ma'];
		if ((isset($data['rolename']))&&(VerifyAdmin($link, $un, $pw, $uid, $ma))){
			$oname = $data['rolename'];
			$name = iconv("UTF-8", "UTF-16LE", $oname);
			$data = pack("N", -1).cuint(strlen($name)).$name."\x00";
			$redy = cuint(3033).cuint(strlen($data)).$data;
			if(!@$sock=socket_create(AF_INET, SOCK_STREAM, SOL_TCP)){
				$header_arr["error"]="Unable to bind socket";
			}else{
				socket_connect($sock,"localhost",29400);
				socket_set_block($sock);
				socket_send($sock, $redy, 8192, 0);
				socket_recv($sock, $buf, 8192, 0);
				socket_set_nonblock($sock);
				socket_close($sock);
				$b = $buf;
				$h=array_values(unpack("N", substr($b, 11, 4)));
				list($id) = array_values(unpack("N", substr($b, 11, 4)));
				$header_arr["roleid"]=$id;
				$header_arr["success"]="id found";
			}
		}
	}
	mysqli_close($link);
}


function cuint($data){
    if($data < 128)
        return strrev(pack("C", $data));
    else if($data < 16384)
        return strrev(pack("S", ($data | 0x8000)));
    else if($data < 536870912)
        return strrev(pack("I", ($data | 0xC0000000)));
    return strrev(pack("c", -32) . pack("i", $data));
}

if ($header_arr["success"]!=""){$header_arr["error"]="";}
$return_arr = array();
$return_arr[0]=$header_arr;
echo json_encode($return_arr);	
?>