<?php
// Start the session
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- CHANGE HERE TO YOUR DOMAIN -->
<link rel="canonical” href="https://retroms.ddns.net">
<meta name="description" content="Perfect World - Retro MS, the old version of this MMORPG game, from 2009, it is free to play oriented server, where we respect eachother." />
<meta charset="utf-8">
<?php
$cfgFile="./config.php";
include $cfgFile;
include "./basefunc.php";
if (!(file_exists($cfgFile))) {
	header('Location: ./setup.php');
}else{
	if (isset($_SESSION['UD1'])){
		if ($_SESSION['UD1'] != 1){
			if (isset($_SESSION['UD2'])){
				unset ($_SESSION['UD2']);
			}
		}else{
			$_SESSION['UD2'] = $AKey1;
		}
	}else{
		$_SESSION['UD1'] = 1;
		$_SESSION['UD2'] = $AKey1;
	}
}
?>
<title>Perfect World RetroMS - Welcome on our free server</title>
<link rel="stylesheet" type="text/css" href="./css/index.css">
 <link rel="icon" type="image/x-icon" href="./images/pwicon3.png">
<script src="./js/index.js?s=<?php echo uniqid(); ?>"></script>
</head>
<body>
<span style="display:flex;flex-direction:column;align-items: center;justify-content: center;">
<table border="0" width="1000" style="-webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;"><tr>
<td width="99" align="center" style="text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:white;">
<b><span style="color: #ffff88">Perfect</span> <span style="color: #ffff99">World </span><span style="color: #ffffaa">RetroMS</span></b>
	<br><br>
<b><span style="color: #ffffcc">Wodan</span></b><br><span id="statusID">
<?php
	$con = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
	$isOnline = ServerOnline($con);
	if ($isOnline !== false){
		echo "<span style='color:lightgreen'><b>Online</b></span>";
	}else{
		echo "<span style='color:red'><b>Offline</b></span>";
	}
	mysqli_close($con);
	echo"<script>SrvrTmZone = parseInt('".date('Z')."',10);</script>";
?>
</span><br><br><a href="javascript:void(0);" onClick="ShowPage('./page/info.php');" style='text-decoration:none;'><b><span style='color:#ffffee'>Time:</span></b><br><b><span style="color:#ffffff"><span id='STime_Count'><?php echo date("H:i:s"); ?></span></span></b></a></td>
<td width="812" align="center"><img src="./images/banner.jpg" alt="Perfect World RetroMS" border="0"></td>
<td width="99" align="center" style="text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:white;"><b><span style="color:#ffff99">Three race</span></b><br><b><span style="color:#ffffaa">Six class</span></b><br><br><b><span style="color:#ffffcc">Lv 79 & 100</span> <span style="color:#ffffdd">skills + Elfs</span></b><br><br><b><span style="color:#ffffee">Ver.: 1.4.1</span><br>PvP Ready</b></td>
</tr></table>
<div style="border:0px solid #000;"><table id="ButtonRow"><tr>
<td><a href="javascript:void(0);" class="myButton" onClick="ClrWin();ShowPage('./page/news.php');">Home</a></td>
<td><a href="javascript:void(0);" class="myButton" onClick="ClrWin();ShowPage('./page/story.php');">Story</a></td>
<td><a href="javascript:void(0);" class="myButton" onClick="ClrWin();ShowPage('./page/downloads.php');">Download</a></td>
<td><a href="javascript:void(0);" class="myButton" onClick="ClrWin();ShowPage('./page/guide.php');">Guide</a></td>
<?php
if ($Forum!==false){ 
echo "<td><a href='javascript:void(0);' class='myButton' onClick=ClrWin();ShowPage('./page/forum.php');>Forum</a></td>";
}
if ($Donation!==false){ 
echo "<td><a href='javascript:void(0);' class='myButton' onClick=ClrWin();ShowPage('./page/donation.php');;>Donation</a></td>";
} 
?>
<td id="TdButLog"><a href="javascript:void(0);" class="myButton" onClick="ShowLoginDiv();" id="LoginButton">Sign In</a></td>
<?php
if ($RegisEnabled!==false){ 
	echo"<td id='TdButReg'><a href='./register' class='myButton' id='RegButton'>Registration</a></td>";
}
?>
</tr></table></div><br>
</span>
<div id='PageContainer' style='position: absolute;left:0px;right:0px;'></div>
<div id="loginDiv">
	<div id="lHeaderDiv"> &nbsp; &nbsp; <b>Login Panel</b></div>
	<div id="lContDiv">
		<table border="0">
		<tr>
			<td style="font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:#fff;">
				<b>Username:&nbsp;&nbsp;</b>
			</td>
			<td><input type="text" name="login" id='luname' maxlength="20" onkeydown="if (event.keyCode == 13){SendLoginData();};"></td>
		</tr>
		<tr>
			<td style="font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:#fff;"> <b>Password:</b></td>
			<td><input type="password" name="passwd" id='lupass' maxlength=" 20" onkeydown="if (event.keyCode == 13){SendLoginData();};"></td>
		</tr>
		</table>
		<br>
		<span style="text-align:center"> <a href="javascript:void(0);" class="myButton" onClick="SendLoginData();" id="LoginButton1">Login</a>&nbsp;&nbsp;<a href="javascript:void(0);" class="myButton" onClick="document.getElementById('loginDiv').style.left='-1000px';">Cancel</a></span>
	</div>
</div>
<?php
if ($RegisEnabled!==false){ 
?>
<div id="RegDiv">
<div id="rHeaderDiv"> &nbsp; &nbsp; <b>Registration Panel</b></div>
	<div id="rContDiv">
	<table border="0" style="width:100%">
	<tr>
		<td style="font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:#fff;" title="Please use only letters or numbers!"> <b>Username:</b></td>
		<td>    <input type="text" id="RegUser" maxlength="20" placeholder="Allowed: a-z0-9" pattern="[a-z0-9]{3,32}" ></td>
	</tr>
	<tr>
		<td style="font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:#fff;" title="Please use only letters, numbers, dot or @!"> <b>Email:</b></td>
		<td>   <input type="text" id="RegMail" maxlength="50" placeholder="Use a valid email address!"></td>
	</tr>
	<tr>
		<td style="font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:#fff;" title="Please use only letters, numbers and few symbol!"> <b>Password:</b></td>
		<td>   <input type="password" id="RegPass1" maxlength="20" placeholder="Allowed: a-zA-Z0-9$!.,?"></td>
	</tr>
	<tr>
		<td style="font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:#fff;" title="Please use only letters, numbers and few symbol!"> <b>Pass. again:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
		<td>   <input type="password" id="RegPass2" maxlength="20" placeholder="Allowed: a-zA-Z0-9$!.,?"><br></td>
	</tr>
	<?php
	$rnd1 = rand (1, 10);
	$rnd2 = rand (1, 10);
	$rndS = rand (1, 2);
	$rndStr[1]="+";
	$rndStr[2]="*";
	$finalRS="&nbsp;&nbsp;&nbsp; $rnd1 ".$rndStr[$rndS]." $rnd2";
	if ($rndS==1){
		$finalRR=$rnd1+$rnd2;
	}else{
		$finalRR=$rnd1*$rnd2;
	}

		echo"<tr><td style='font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:#fff;'>    <b>Question:</b></td><td style='font-size:16px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000;'>   ".$finalRS." = ?<br></td></tr>";
		echo"<tr><td style='font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:#fff;'>    <b>Answer:</b></td><td>   <input type='text' id='RegAnswer' maxlength='5'><br></td></tr>";
		echo"<script>RandomCode=parseInt('".$finalRR."');</script>";
	?>
	</table><br>
	<input type="checkbox" id="RegTerm" name="RegTerm" style="position: absolute;left:-10000;opacity:0;">
	<span style="text-align:center"> <a href="javascript:void(0);" class="myButton" onClick="SendRegData();" id="RegButton1">Confirm</a>&nbsp;&nbsp;<a href="javascript:void(0);" class="myButton" onClick="document.getElementById('RegDiv').style.left='-1000px';">Cancel</a></span>
	</div>
</div>
<?php 
}
?>
<script>ShowPage('./page/news.php');</script>
</body>
</html>
