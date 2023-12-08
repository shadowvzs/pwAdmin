<?php 
session_start();
include "../config.php";
include "../basefunc.php";
$ShopItemD = "../items/WebShop.txt";
$SelItemD = "../items/SList.txt";
$header_arr = array("error" => "Unknown Error!", "success" => "");
$list_arr = array();
SessionVerification();
$c=0;
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
		if (VerifyAdmin($link, $un, $pw, $uid, $ma)){
			if (isset($data['loadshopitems'])){
				$query = "SELECT * FROM webshop";
				$result=$link->query($query);
				$count=$result->num_rows;
				if ($count<1) {
					$header_arr["error"]=" Web Shop empty... ";
				}else{
					while($row=$result->fetch_row()) {
						$list_arr[$c]=implode("#", (array_slice($row, 1)))."#".$row[0];
						$c++;
					}
					$header_arr["success"]=$c." item loaded from shop!";
				}
				$result->free();				
			}elseif (isset($data['loadtempitems'])){
				$file = fopen($SelItemD, "r");
				if ($file) {
					while (($line = fgets($file)) !== false) {
						$line=trim($line);
						if ($line != ""){
							$list_arr[$c]=$line;
							$c++;
						}
					}
					if ($c > 0){
						$header_arr["success"]=$c." item loaded from shop!";
					}else{
						$header_arr["error"]="Packet list empty!";
					}
				}else{
					$header_arr["error"]="Packet file not exist!";
				}
				fclose($file);	
			}elseif (isset($data['loadshoplog'])){
				$query = "SELECT * FROM wshoplog";
				$result=$link->query($query);
				$count=$result->num_rows;
				if ($count<1) {
					$header_arr["error"]="Web shop list empty!";
				}else{
					while($row=$result->fetch_row()) {
						$list_arr[$c]=array(
							"id"=>$row[0], "userid"=>$row[1], "uname"=>$row[2],
							"roleid"=>$row[3], "rolename"=>$row[4], "buydate"=>$row[5],
							"currency"=>$row[6], "price"=>$row[7], "amount"=>$row[8],
							"itemdata"=>$row[9], "shopid"=>$row[10]
							);
						$c++;
					}
					$header_arr["success"]=$c." shop item log loaded!";
				}
				$result->free();				
			}
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
