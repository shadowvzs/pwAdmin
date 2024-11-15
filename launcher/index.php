<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Perfect World RetroMS Server</title>
</head>
<?php
	include "../config.php";
	include "../basefunc.php";
?>
<body style="background-color: black">
	<center>
		<a href="https://retroms.ddns.net/" target="_blank" style="outline: none;text-decoration:none" rel="noopener noreferrer">
			<img src="../images/banner.jpg" alt="Perfect World RetroMS" border="0" style="width:100%">
			<h2 style="color:#ffff88;margin-bottom: 4px;"> Perfect World - RetroMS </h2>
			<div style="color:#fff;"> Server Status:
				<?php
					$con = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
					$isOnline = ServerOnline($con);
					if ($isOnline!==false){
						echo "<b style='color:#00ff00;'>Online</b>";
						if (is_numeric($isOnline)) {
							echo " <i style='color:#7777ff;'>($isOnline players)</i>";
						}
					}else{
						echo "<i style='color:red;'>Offline</i>";
					}
					mysqli_close($con);
				?>
			</div>
			<table border="0" style="color: #ffffcc; width: 100%; max-width:600px;">
				<thead>
					<tr>
						<th> Name </th>
						<th> Type </th>
						<th> Race </th>
						<th> Max Lv </th>
						<th> EXP/SP </th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="text-align:center"> Wodan </td>
						<td style="text-align:center"> PVP </td>
						<td style="text-align:center"> 3 </td>
						<td style="text-align:center"> 105 </td>
						<td style="text-align:center"> 1x </td>
					</tr>
				</tbody>
			</table>
		</a>
		<br>
		<table border="0" style="color: white">
			<tr>
				<td><b>Website: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
				<td style="text-align:left;"> <a href="https://retroms.ddns.net/" target="_blank" style="outline: none;text-decoration:none;color: white" rel="noopener noreferrer"> http://retroms.ddns.net/ </a> </td>
			</tr>
			<tr>
				<td><b> Discord: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
				<td style="text-align:left;"> <a href="https://discord.gg/r8MtRK37wX" target="_blank" style="outline: none;text-decoration:none;color: white" rel="noopener noreferrer"> https://discord.gg/r8MtRK37wX </a> </td>
			</tr>
	</center>
</body>
</html>
