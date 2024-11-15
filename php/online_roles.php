<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../config.php");
include("../basefunc.php");

$link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);

$query="SELECT u.ID, a.userid FROM users as u INNER JOIN point as p ON u.ID=p.uid LEFT JOIN auth as a ON u.ID=a.userid WHERE p.zoneid IS NOT NULL GROUP BY u.ID";
$statement = $link->prepare($query);
$statement->execute();
$statement->bind_result($id, $authId);
$statement->store_result();
$result = $statement->num_rows;
$onlineUsers = [];

if (!$result) {
	$statement->close();
	mysqli_close($link);
	die("[]");
}
while($statement->fetch()) {
	$onlineUsers[] = ["id" => $id, "gm" => $authId > 0];
}
$statement->close();

include("../php/packet_class.php");
$onlineRoles = [];
$hideGM = true;

$showId = false;
$userIds = array_map(function ($a) { return intval($a['id']); }, $onlineUsers);
if (isset($_SESSION['id']) && in_array(intval($_SESSION['id']), $userIds)) {
	$showId = true;
}


foreach($onlineUsers as $onlineUser) {
	if ($hideGM && $onlineUser['gm']) { continue; }
	$roles = loadUserRoles($onlineUser['id']);
	if (count($roles) === 0) { continue; }
	$onlineRole = $roles[0];
	foreach($roles as $role) {
		if ($onlineRole['lastLogin'] < $role['lastLogin']) {
			$onlineRole = $role;
		}
	}

	$onlineRoleDto = [
		"name" => $onlineRole['rolename'],
		"level" => $onlineRole['rolelevel'],
		"exp" => $onlineRole['exp'],
		"gm" => $onlineUser['gm'],
	];
	if ($showId) {
		$onlineRoleDto['id'] = $onlineRole['roleid'];
	}
	$onlineRoles[] = $onlineRoleDto;
}
mysqli_close($link);

echo(json_encode($onlineRoles));
die();
?>
