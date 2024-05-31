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
$DB_Host="localhost";
$DB_User="DragonCity";
$DB_Password="123456";
$DB_Name="pw";

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

	include("/var/www/html/pwAdmin/config.php");
	include("/var/www/html/pwAdmin/basefunc.php");
	include("/var/www/html/pwAdmin/php/packet_class.php");
	
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
	$whitelist = [
		"atilaf1" => ["atilaf1", "jump01","jump02","jump03","jump04","jump05","jump06","jump07","jump08","jump09"],
		"valmyris" => ["valmyris", "valmochi"],
		"silver410" => ["silver410"],
		"rikmans2" => ["rikmans", "rikmans1", "rikmans2", "rikmans3", "rikmans5", "herrmann", "herrmann2", "herrmann3"],
		"shadowvzs" => ["shadowvzs", "shadowvzs87", "pwadmin"],
		"enisha" => ["enisha"],
		"petra15" => ["petra15", "petra23"],
		// "itzawake" => ["itzawake"],
		// "pista322" => ["pista322"],
		"salvation" => ["salvation", "salvation1", "salvation2", "salvation3"],
		"steven1993" => ["steven1993", "steve1993", "steven199", "steve199", "steven19", "steve19"],
		"navicht" => ["navicht", "shimokita", "gloryday", "tzira19", "czira19", "hzira19"],
		"lumme22" => ["lumme11", "lumme22", "lumme33", "lumme44", "lumme55", "lumme66", "lumme77", "lumme88", "lumme99", "truelove"],
		"escoblade" => ["escoblade", "barbpowa", "escopw", "darcylol", "lumme55", "lumme66", "lumme77", "lumme88", "lumme99", "truelove"],
		"kwikee2" => ["racksonracks", "kwikee2"],
		"jersonkie" => ["jersonkie", "azelea", "azelea143"],
		"kaspererek" => ["kaspererek"],
		"junops2" => ["junops", "junops2"],
		"baq2507" => ["baq2507"],
		"nahidmiah" => ["nahidmiah"],
		"amon80" => ["amon80", "galex98", "congol80", "gamon09", "gamon98", "bank80", "bank98"],
		"ksoooo" => ["ksoooo", "ksoocler"],
		"yowhatsup" => ["yowhatsup"],
		"adro123" => ["adro123"],
		"mystic1" => ["mystic1", "mystic2", "mystic3", "mystic4", "rikmans0"],
		"zxcqwe" => ["zxcqwe"],
		"g0kusen" => ["g0kusen", "kurum1", "d3javu", "walnut"],
		"catmoon" => ["catmoon", "catmoon08"],
		"jhonywalker" => ["jhonywalker"],
		"itzawake" => ["itzawake"],
		"libation" => ["libation"],
		"marcin11pl" => ["marcin11pl", "marcinio11pl"],
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
}

file_put_contents($storageFileName, time()-100);


?>
