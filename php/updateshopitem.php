<?php 
session_start();
include "../config.php";
include "../basefunc.php";
$header_arr = array("error" => "Unknown Error!", "success" => "", "action" => "update", "id" => "");
$list_arr = array();
SessionVerification();
$data = json_decode(file_get_contents('php://input'), true);
if ( $data ) {
	$un=$_SESSION['un'];
	$pw=$_SESSION['pw'];
	$id=$_SESSION['id'];
	$ma=$_SESSION['ma'];	
	$link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
	if ($link->connect_errn) {
		$header_arr["error"]="Sorry, this website is experiencing problems (failed to make a MySQL connection)!";
	}else{
		if ((VerifyAdmin($link, $un, $pw, $id, $ma)!==false)&&(isset($data["updateshopitem"]))){
			$itm = $data['item'];
			$dt=explode("#", $itm);
			$statement = $link->prepare("SELECT id FROM webshop WHERE itmid=? AND imask=? AND iproc=? AND expir=? AND octet=? AND igrd=? AND stim=? AND id=?");
			$statement->bind_param('iiiisisi', $dt[7], $dt[8], $dt[9], $dt[14], $dt[15], $dt[18], $dt[19], $dt[20] );
			$statement->execute();
			$statement->bind_result($uname, $ID, $TIME);
			$statement->store_result();
			$result = $statement->num_rows;
			
			if (($itm != "")&&($result==1)){
							$header_arr["error"]="No permission!";
				$columns="pcst=?, gcst=?, itit=?, idsc=?, cats=?, iname=?, idate=?, itmid=?, imask=?, iproc=?, iqty=?, imax=?, guid1=?, guid2=?, expir=?, octet=?, wscat=?, icol=?, igrd=?, stim=?";
				$mysqltime = date ("Y-m-d H:i:s", time());
				
				$sql="UPDATE webshop SET ".$columns." WHERE id=?";
				$stmt = $link->prepare($sql); 
				$stmt->bind_param("iisssssiiiiiiiissiisi", $dt[0], $dt[1], $dt[2], $dt[3], $dt[4], $dt[5], $dt[6], $dt[7], $dt[8], $dt[9], $dt[10], $dt[11], $dt[12], $dt[13], $dt[14], $dt[15], $dt[16], $dt[17], $dt[18], $dt[19], $dt[20]);
				if ($stmt->execute()){
					$header_arr["id"]=$dt[20];
					$header_arr["success"]="Item updated";
					$list_arr[0]=$itm;					
				}else{
					$header_arr["error"]="Updateing item failed!";
				}
				$stmt->close();	
				
			}else{
				$header_arr["error"]="MySQL error, cannot update!";
			}
			
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
