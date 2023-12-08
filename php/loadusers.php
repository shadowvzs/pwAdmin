<?php 
session_start();
include "../config.php";
include "../basefunc.php";
$resp="Unknown Error";
$err_arr = array("error" => "Unknown Error!");
$user_arr = array();
SessionVerification();
if ($_SESSION['UD3'] != $AKey2){die();}
$data = json_decode(file_get_contents('php://input'), true);
if (($data)&&(isset($_SESSION['un']))) {
	$sname=trim($data["sname"]);
	$stype=$data["stype"];
	if ($stype == 5){$sname = StrToLower($sname);}
	$un=$_SESSION['un'];
	$pw=$_SESSION['pw'];
	$uid=$_SESSION['id'];
	$ma=$_SESSION['ma'];
	if ($uid==$AdminId){
		$link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
		$Admin=VerifyAdmin($link, $un, $pw, $uid, $ma);
		if ($Admin!==false){
			if ($stype==1){
				$statement = $link->prepare("SELECT ID, name, truename, email FROM users");
			}elseif ($stype==2){
				$statement = $link->prepare("SELECT ID, name, truename, email FROM users WHERE idnumber=?");
				$statement->bind_param('s', $sname);
			}elseif ($stype==3){
				$statement = $link->prepare("SELECT ID, name, truename, email FROM users WHERE ID=?");
				$statement->bind_param('i', intval($sname));
			}elseif ($stype==4){
				$statement = $link->prepare("SELECT ID, name, truename, email FROM users WHERE name LIKE CONCAT(?,'%') OR truename LIKE CONCAT(?,'%')");
				$statement->bind_param('ss', StrToLower($sname), $sname);
			}elseif ($stype==5){
				$statement = $link->prepare("SELECT ID, name, truename, email FROM users WHERE email=?");
				$statement->bind_param('s', $sname);
			}elseif ($stype==6){
				$statement = $link->prepare("SELECT users.ID, users.name, users.truename, users.email FROM users INNER JOIN point ON users.ID=point.uid WHERE point.zoneid IS NOT NULL");
			}elseif ($stype==7){	
				$query = "SELECT users.ID, users.name, users.truename, users.email FROM users INNER JOIN point ON users.ID=point.uid WHERE point.lastlogin >= ( CURDATE() - INTERVAL $txt DAY)";
				$statement = $link->prepare($query);
			}elseif ($stype==8){
				$statement = $link->prepare("SELECT users.ID, users.name, users.truename, users.email FROM users INNER JOIN auth ON users.ID=auth.userid WHERE auth.zoneid = '1' GROUP BY auth.userid");
			}elseif ($stype==9){
				$statement = $link->prepare("SELECT users.ID, users.name, users.truename, users.email FROM users INNER JOIN auth ON users.ID=auth.userid WHERE auth.zoneid = '1'  INNER JOIN point ON users.ID=point.uid WHERE point.zoneid IS NOT NULL GROUP BY auth.userid");
			}
			$c=0;
			if ($stype >0){
				$statement->execute();
				$statement->bind_result($id1, $name1, $rname1, $email1);
				$statement->store_result();
				$result = $statement->num_rows;
				if ($result) {
					while($statement->fetch()) {
						$rank=0;
						if ((CountMysqlRows($link, 5, $id1))>0){
							$rank=1;
						}
						$user_arr[$c] = array("userid" => $id1, "username" => $name1, "realname" => $rname1, "email" => $email1, "rank" => $rank);
						$c++;
					}   
				}
				$statement->close();	
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
echo json_encode($return_arr);	
?>
