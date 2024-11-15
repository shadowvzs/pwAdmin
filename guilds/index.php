<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../css/server.css">
<link rel="stylesheet" type="text/css" href="../css/guilds.css">
<script src="../js/guild.js"></script>
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
		<div id="guild-list-container" style="padding: 16px;">
			<b>Guilds:</b>
			<div id="guild-list" style="font-size: 14px;padding-top: 8px;" />
		</div>
	</aside>
	<div id="guild-window-container" class="absolute-center"></div>
</div>
<script>
	const guildService = new GuildService();
	const guildWindowCmp = new GuildWindowComponent('#guild-window-container');
	guildWindowCmp.init().hide();
	
	const onGuildClick = (guild) => {
		guildService.getGuild(guild.id).then((guildDetails) => {
			guildWindowCmp.setGuildInformation(guildDetails);
		});
	};
	
	const guildListCmp = new GuildListComponent('#guild-list', onGuildClick);
	guildService.getMyGuilds().then(guilds => {
		guildListCmp.init(guilds);
	});

</script
</body>
</html>
