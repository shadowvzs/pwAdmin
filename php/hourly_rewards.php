<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$base_path = "/var/www/html/retroms.ddns.net/";
$storageFileName = "$base_path/php/hourly_rewards.dat";
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
$DB_Host="localhost";
$DB_User="YOUR_DB_USER";
$DB_Password="YOUR_DB_PASSWORD";
$DB_Name="YOUR_DB_NAME";

$link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);

if (true){
	$query="SELECT u.ID, u.name, p.zoneid FROM point as p RIGHT JOIN users as u ON u.ID = p.uid";//WHERE p.zoneid IS NOT NULL
	$statement = $link->prepare($query);
	$statement->execute();
	$statement->bind_result($id, $username, $zoneid);
	$statement->store_result();
	$result = $statement->num_rows;
	$sn = 0;
	$onlineIds = [];
	$userIdMap = [];
	$userNameMap = [];

	if ($result) {
		$nr0 = 0;
		$nr1 = 1;
		$TIME=date("Y-m-d H:i:s");
		$PwGold = 7 * 100;
		$WebPoint = 5;
		while($statement->fetch()) {
			$userNameMap[$username] = $id;
			$userIdMap[$id] = $username;
			if (isset($zoneid)) {
				array_push($onlineIds, $id);
			}
		}   
	}
	$statement->close();

	include("$base_path/config.php");
	include("$base_path/basefunc.php");
	include("$base_path/php/packet_class.php");
	
	$rewardedUserMap = array();
	foreach($onlineIds as $onlineUserId) {
		$role_arr = loadUserRoles($onlineUserId);
		$cultiLevels = array_map(
			function($role) {
				$reqLvForCulti = [0, 9, 19, 29, 39, 49, 59, 69, 79, 89];
				$cultiLv = $role['roleculti'];
				$roleLv = $role['rolelevel'];
				if ($cultiLv < 9) {
					$reqLv = $reqLvForCulti[$cultiLv];
					return $roleLv >= $reqLv ? $cultiLv : 0;
				} else {
					if ($roleLv >= 99 && ($cultiLv == 22 || $cultiLv == 32)) {
						return 12;
					} else if ($roleLv >= 99 && ($cultiLv == 21 || $cultiLv == 31)) {
						return 11;
					} else if ($roleLv >= 89 && ($cultiLv == 20 || $cultiLv == 30)) {
						return 10;
					}
					return 0;
				}
				return $n * $n;
			},
			$role_arr
		);
		
		rsort($cultiLevels);

		$PWGold = $cultiLevels[0] ?? 0;

		$mainUsername = getMainUserId($userIdMap[$onlineUserId]);
		if (!isset($mainUsername)) {
			// echo "missing: $onlineUserId - $onlineUsername";
			continue;
		}
		
		if (!isset($rewardedUserMap[$mainUsername])) {
			$rewardedUserMap[$mainUsername] = 0;
		}

		$finalReward = $PWGold * 5 + 10;
		if (count($role_arr) > 0 && $PWGold > 0 && $rewardedUserMap[$mainUsername] < $finalReward) {	
			$rewardedUserMap[$mainUsername] = $finalReward;
		}
	}
	printf(json_encode($rewardedUserMap, JSON_PRETTY_PRINT));

	foreach($rewardedUserMap as $username => $pwGold) {
		rewardUser($link, $userNameMap[$username], $pwGold);
	}

	echo count($rewardedUserMap)." user got rewards";
}
mysqli_close($link);

function getMainUserId($searchedUsername) {
	// main character => [sub characters]
	$whitelist = [
		"shadowvzs" => ["shadowvzs", "shadowvzs87", "pwadmin"],
];
	foreach($whitelist as $mainUserName => $userNames) {
		if (in_array($searchedUsername, $userNames)) {
			return $mainUserName;
		}
	}
}

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
	echo "$userId $reward <br/>";
}

file_put_contents($storageFileName, time()-100);


?>
