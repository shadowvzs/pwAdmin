<?php 
session_start();
include "../config.php";
include "../basefunc.php";
$ShopItemD = "webshopdb.txt";
$header_arr = array("error" => "Unknown Error!", "success" => "");
$list_arr = array();
$sql_arr = array();
$txt_arr = array();
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
		if ((VerifyAdmin($link, $un, $pw, $id, $ma)!==false)&&(isset($data["importdb"]))){
			$query = "SELECT * FROM webshop";
			$result=$link->query($query);
			$count=$result->num_rows;
			$c=0;
			if ($count>0) {
				while($row=$result->fetch_row()) {
					$sql_arr[$c]=implode("#", (array_slice($row, 1)));
					$c++;
				}
			}
			$result->free();
			$file = fopen("../items/".$ShopItemD, "r");
			$s=0;
			$c=0;
			if ($file) {
				while (($line = fgets($file)) !== false) {
					$line=trim($line);
					if ($line != ""){
						if (in_array($line, $sql_arr, TRUE)!== true){
							$list_arr[$c]=$line;
							$c++;
						}else{
							
						}
					}
				}
				if ($c>0){
					$columns="pcst, gcst, itit, idsc, cats, iname, idate, itmid, imask, iproc, iqty, imax, guid1, guid2, expir, octet, wscat, icol, igrd, stim";
					$questsyn="?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
					for ($i=0; $i<$c; $i++){
						$dt = array_slice(explode("#", $list_arr[$i]), 0, 20); 
						$mysqltime = date ("Y-m-d H:i:s", time());
						$sql="INSERT INTO webshop (".$columns.") VALUES (".$questsyn.")";
						$stmt = $link->prepare($sql); 
						$stmt->bind_param("iisssssiiiiiiiissiis", $dt[0], $dt[1], $dt[2], $dt[3], $dt[4], $dt[5], $dt[6], $dt[7], $dt[8], $dt[9], $dt[10], $dt[11], $dt[12], $dt[13], $dt[14], $dt[15], $dt[16], $dt[17], $dt[18], $dt[19]);
						$stmt->execute(); 
						$iid = $link->insert_id;
						$stmt->close();		
						$list_arr[$i]=$list_arr[$i]."#".$iid;
					}
					$header_arr["success"]=$c." item imported to DB!";
				}else{
					$header_arr["error"]="All item already in database!";
				}
				
			}else{
				$header_arr["error"]="../items/".$ShopItemD." not exist!";
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
