<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include "../config.php";
include "../basefunc.php";
$resp="Unknown Error";

SessionVerification();
if (!isset($_GET['action'])) { die(); }
if ($_GET['action'] === 'my-roles' && isset($_SESSION['id'])) {
	include("./packet_class.php");
	$id=$_SESSION['id'];
	$roles_array = loadUserRoles($id);
	echo json_encode($roles_array);
	die();
} else if ($_GET['action'] === 'role' && isset($_GET['id']) && isset($_SESSION['id'])) {
	include("./packet_class.php");
	$role = GetRoleData(intval($_GET['id']), $ServerVer);
	echo json_encode($role);
	die();
} else if ($_GET['action'] === 'delete' && isset($_GET['id']) && isset($_SESSION['id']) && $_SESSION['id'] === $AdminId) {
	include("./packet_class.php");
	$guild = deleteGuild(intval($_GET['id']));
	echo json_encode($guild);
	die();
}
// GetRoleData
echo $resp;

?>
