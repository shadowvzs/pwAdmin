<?php 
// ini_set('display_errors', 1);
session_start();

include "../config.php";
include "../basefunc.php";
include("../php/packet_class.php");

$allowedUsers = ['pwadmin', 'cuteness'];
if (isset($_SESSION['id']) && in_array($_SESSION['un'], $allowedUsers) && isset($_GET['id'])) {

	$roleId = intval($_GET['id']);
	$GRoleData=GetRoleData($roleId, $ServerVer);
}

?>
<html>
	<head>
		<title>
			Role checker
		</title>
	</head>
	
	<body>
		<div>
			<form action='role_data.php'>
				<input type="number" name="id" placeholder="Role id" />
				<button type="submit"> Show </button>
			</form>
		</div>
	
		<div>
			<pre>
				<?php
					if (isset($GRoleData)) {
						printf(json_encode($GRoleData, JSON_PRETTY_PRINT));
					}
				?>
			</pre>
		</div>
	</body>
</html>