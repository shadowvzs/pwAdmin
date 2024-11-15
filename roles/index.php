<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../css/server.css">
<link rel="stylesheet" type="text/css" href="../css/guilds.css">
<script src="../js/role.js"></script>
</head>
<body>
<?php
include "../config.php";
include "../common/cpanel.php";
include "../basefunc.php";
SessionVerification();
?>
<div>
	<aside class='box hide-xs max-w-64' style="margin-top:24px;">
		<div id="roles-list-container" style="padding: 16px;">
			<b>Roles:</b>
			<div id="roles-list" style="font-size: 14px;padding-top: 8px;" />
		</div>
	</aside>
</div>
<div id="role-window-container" class="absolute-center"></div>
<script>
	const roleService = new RoleService();
	// const guildWindowCmp = new GuildWindowComponent('#guild-window-container');
	// guildWindowCmp.init().hide();
	
	const onClick = async (minimalRoleData) => {
		const loadedRole = await roleService.getRole(minimalRoleData.roleid);
		console.log(loadedRole);
	};
	
	const roleListCmp = new RoleListComponent('#roles-list', onClick);
	roleService.getMyRoles().then(roles => {
		roleListCmp.init(roles);
	});

</script
</body>
</html>
