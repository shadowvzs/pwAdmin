<?php 
session_start();
include "../config.php";
include "../basefunc.php";
$ShopItemD = "webshopdb.txt";
$header_arr = array("error" => "Unknown Error!", "success" => "");
$list_arr = array();
SessionVerification();
$data = json_decode(file_get_contents('php://input'), true);
if ( $data ) {
	$un=$_SESSION['un'];
	$pw=$_SESSION['pw'];
	$id=$_SESSION['id'];
	$ma=$_SESSION['ma'];	
	$link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
	if ($link->connect_errno) {
		$header_arr["error"]="Sorry, this website is experiencing problems (failed to make a MySQL connection)!";
	}else{
		if ((VerifyAdmin($link, $un, $pw, $id, $ma)!==false)&&(isset($data["exportdb"]))){
				$query = "SELECT * FROM webshop";
				$result=$link->query($query);
				$count=$result->num_rows;
				$c=0;
				if ($count<1) {
					$header_arr["error"]=" Web Shop empty... ";
				}else{
					while($row=$result->fetch_row()) {
						$list_arr[$c]=implode("#", (array_slice($row, 1)));
						$c++;
					}
					$file = fopen("../items/".$ShopItemD, "w");
					if ($file){
						for ($i=0; $i<$c;$i++){
							if ($i < ($c-1)){
								fwrite($file, $list_arr[$i].PHP_EOL);
							}else{
								fwrite($file, $list_arr[$i]);
							}
						}
						$header_arr["success"]=$c." item exported to file!";
					}else{
						$header_arr["error"]="Unable to create file!";
					}
					fclose($file);
				}
				$result->free();
		}else{
			$header_arr["error"]="No permission!";
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
