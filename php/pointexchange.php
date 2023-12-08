<?php 
session_start();
include "../config.php";
include "../basefunc.php";
$header_arr = array("error" => "Unknown Error!", "reloaduserlist" => "0", "success" => "", "reloaduserdata" => "0");
SessionVerification();
if ($_SESSION['UD3'] != $AKey2){die();}
$data = json_decode(file_get_contents('php://input'), true);
if (($data)&&(isset($_SESSION['un']))) {
	if (isset($data['amount'])){
		$amount = intval($data['amount']);
		$uid = $_SESSION['id'];
		$TIME=date("Y-m-d H:i:s");
		$link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
		if ($link->connect_errno) {
			$header_arr["error"]="Sorry, this website is experiencing problems (failed to make a MySQL connection)!";
		}						
		$statement = $link->prepare("SELECT VotePoint, creatime FROM users WHERE ID=?");
		$statement->bind_param('i', $uid);
		$statement->execute();
		$statement->bind_result($VPoint, $TIME);
		$statement->store_result();
		$count = $statement->num_rows;
		if($count==1){
			while($statement->fetch()) {
				$PCost = $amount * $PointExc;
				if ($VPoint >= $PCost){
					$diff = $VPoint - $PCost;
					$query = "UPDATE users SET VotePoint=? WHERE ID=?";
					$stmt = $link->prepare($query);
					$stmt->bind_param('ii', $diff, $uid);
					$stmt->execute(); 
					$stmt->close();	
					$amount = $amount * 100; //100 silver = 1 gold
					$query="INSERT INTO usecashnow (userid, zoneid, sn, aid, point, cash, status, creatime) VALUES ('$uid', '1', '0', '1', '0', '$amount', '1', '$TIME') ON DUPLICATE KEY UPDATE cash = cash + $amount";
					if($stmt = $link->prepare($query)){
						$stmt->execute(); 
						$stmt->close();
						$header_arr["success"]="Point traded to gold successfully!";
						$header_arr["reloaduserdata"]="1";
					}else{
						$header_arr["error"]="Transaction failed!";
					}
				}else{
					$diff = $PCost - $VPoint;
					$header_arr["error"]="You dont have enough point, you need $PCost point !";
				}
			}
		}else{
			$header_arr["error"]="User not exist!";
		}
		$statement->close();
		mysqli_close($link);

	}
}


if ($header_arr["success"]!=""){$header_arr["error"]="";}
$return_arr = array();
$return_arr[0]=$header_arr;
echo json_encode($return_arr);	
?>
