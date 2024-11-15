<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$storageFileName = "/var/www/html/pwAdmin/php/hourly_rewards.dat";
$fileContent = @file_get_contents($storageFileName);
$lastTime = time() - 3600;

if (gettype($fileContent) == "string") {
	$lastTime = intval($fileContent);
}

$diff = time() - $lastTime;
if ($diff < 3600) {
	$secLeft = 3600 - $diff;
	$hourLeft = floor($secLeft / 3600);
	$secLeft -= $hourLeft * 3600;
	$minLeft = floor($secLeft / 60);
	$secLeft -= $minLeft * 60;
	$timeLext = str_pad($hourLeft, 2, "0", STR_PAD_LEFT).":".str_pad($minLeft, 2, "0", STR_PAD_LEFT).":".str_pad($secLeft, 2, "0", STR_PAD_LEFT);
	die("Wait $timeLext sec");
}
include("/var/www/html/pwAdmin/config.php");
$link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);

if (true){
	$query="SELECT uid FROM point WHERE zoneid IS NOT NULL";
	$statement = $link->prepare($query);
	$statement->execute();
	$statement->bind_result($id1);
	$statement->store_result();
	$result = $statement->num_rows;
	$sn = 0;
	$ids = array();
	if ($result) {
		$nr0 = 0;
		$nr1 = 1;
		$TIME=date("Y-m-d H:i:s");
		$PwGold = 7 * 100;
		$WebPoint = 5;
		while($statement->fetch()) {
			array_push($ids, $id1);
		}   
	}
	$statement->close();
	
	include("/var/www/html/pwAdmin/config.php");
	include("/var/www/html/pwAdmin/basefunc.php");
	include("/var/www/html/pwAdmin/php/packet_class.php");
	$users = array();
	for($i = 0; $i < count($ids); ++$i) {
		$id = $ids[$i];
		
		$role_arr = loadUserRoles($id);
		$cultiLevels = array_map(
			function($role) {
				$reqLvForCulti = [0, 9, 19, 29, 39, 49, 59, 69, 79, 89];
				$cultiLv = $role['roleculti'];
				$roleLv = $role['rolelevel'];
				if ($cultiLv < 9) {
					$reqLv = $reqLvForCulti[$cultiLv];
					return $roleLv >= $reqLv ? $cultiLv : 0;
				} else {
					return $roleLv >= 89 ? 10 : 0;
				}
				return $n * $n;
			},
			$role_arr
		);
		rsort($cultiLevels);

		$PWGold = $cultiLevels[0] ?? 0;
		if (count($role_arr) > 0 && $PWGold > 0) {
			array_push(
				$users,
				[
					"id" => $id,
					"PWGold" => $PWGold
				]
			);
		}
	}
	
	for($i = 0; $i < count($users); ++$i) {
		$user = $users[$i];
		rewardUser($link, $user['id'], $user['PWGold']);
	}
	echo count($users)." user was online and got rewards";
}
mysqli_close($link);

function rewardUser($link, $userId, $reward) {
	if ($reward <= 0) { return; }
	$PwGold = $reward * 100;
	$TIME=date("Y-m-d H:i:s");
	$query="INSERT INTO usecashnow (userid, zoneid, sn, aid, point, cash, status, creatime) VALUES ('$userId', '1', '0', '1', '0', '$PwGold', '1', '$TIME') ON DUPLICATE KEY UPDATE cash = cash + $PwGold";
	$stmt = $link->prepare($query);
	$stmt->execute(); 
	$stmt->close();
	
	$query = "UPDATE users SET VotePoint=VotePoint+$reward WHERE ID=?";
	$stmt = $link->prepare($query);
	$stmt->bind_param('i', $userId);
	$stmt->execute(); 
	$stmt->close();
}

file_put_contents($storageFileName, time()-100);


?>
