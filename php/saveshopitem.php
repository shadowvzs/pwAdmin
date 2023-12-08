<?php 
session_start();
include "../config.php";
include "../basefunc.php";
$header_arr = array("error" => "Unknown Error!", "success" => "", "action" => "add", "id" => "");
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
		if ((VerifyAdmin($link, $un, $pw, $id, $ma)!==false)&&(isset($data["saveitem2db"]))){
			
			$itm = $data['item'];
			$dt=explode("#", $itm);
			$statement = $link->prepare("SELECT id FROM webshop WHERE itmid=? AND imask=? AND iproc=? AND expir=? AND octet=? AND igrd=? AND stim=?");
			$statement->bind_param('iiiisis', $dt[7], $dt[8], $dt[9], $dt[14], $dt[15], $dt[18], $dt[19]);
			$statement->execute();
			$statement->bind_result($id);
			$statement->store_result();
			$result = $statement->num_rows;
			
			if (($itm != "")&&($result==0)){
				$id1 = $link->insert_id;
				$columns="pcst, gcst, itit, idsc, cats, iname, idate, itmid, imask, iproc, iqty, imax, guid1, guid2, expir, octet, wscat, icol, igrd, stim";
				$questsyn="?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
				$mysqltime = date ("Y-m-d H:i:s", time());
				$sql="INSERT INTO webshop (".$columns.") VALUES (".$questsyn.")";
				$stmt = $link->prepare($sql); 
				$stmt->bind_param("iisssssiiiiiiiissiis", $dt[0], $dt[1], $dt[2], $dt[3], $dt[4], $dt[5], $dt[6], $dt[7], $dt[8], $dt[9], $dt[10], $dt[11], $dt[12], $dt[13], $dt[14], $dt[15], $dt[16], $dt[17], $dt[18], $dt[19]);
				$stmt->execute(); 
				$stmt->close();	
				$id2 = $link->insert_id;
				if ($id1!=$id2){
					$header_arr["success"]="Item saved to database";
					$header_arr["id"]=$id2;
					$list_arr[0]=$itm;
				}else{
					$header_arr["error"]="MySQL error, cannot save!";
				}

			}else{
				$header_arr["error"]="Item already in database or corrupt data!";
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
