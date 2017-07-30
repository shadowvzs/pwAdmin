<?php
session_start();
if ((!isset($_SESSION['un']))||(!isset($_SESSION['id']))){
	header('Location: ../index.php');
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Perfect World RetroMS Server</title>
<link rel="stylesheet" type="text/css" href="../css/windowstyle.css">
<link rel="stylesheet" type="text/css" href="../css/cpanel.css">
<script src="../js/cpanel.js"></script>
</head>
<body>
<?php
$admin=false;
if (intval($_SESSION['id'])==$AdminId){
	$admin=true;
	echo "<script>var vid=".intval($_SESSION['id']).";</script>";
}
echo"<script>SrvrTmZone = parseInt('".date('Z')."',10);</script>";
?>
<div id='ServTimer' style='position:absolute; top:10px;right:10px;font-face:arial;font-size:14px;text-shadow:0px 0px 15px #00f,0px 0px 15px #00f;font-weight:900;color:#007;'>
<span id='STime_Count'><?php echo date("H:i:s"); ?></span>
</div>
<center>
<div style="width:100%;border:0px solid #000;"><table id="ButtonRow"><tr>
<td><a href="javascript:void(0);" class="myButton" onClick="location.href='./myacc.php';">Account Info</a></td>
<?php 
if ($admin){
?>
	<td><a href='javascript:void(0);' class='myButton' onClick="location.href='./server.php'">Server tool</a></td>
	<td><a href='javascript:void(0);' class='myButton' onClick="location.href='./ibuilder.php';">Item Builder</a></td>
<!-- './cpanel/roledata.php' -->
<?php
}
if ($WebShop !== false){
	echo"<td><a href='javascript:void(0);' class='myButton' onClick='location.href=\"./wshop.php\";'>Web Shop</a></td>";
}
?>
<td><a href="javascript:void(0);" class="myButton" onClick="location.href='../php/logout.php';" id="RegButton">Log out</a></td>
</tr></table></div><br>
<?php 
if ($admin){
	if ($ControlPanel !== false){
		echo"<div id='settingdiv'>
		<a href='javascript:void(0);' title='Click here for settings' onClick='document.getElementById(\"SettingsDiv\").style.display=\"block\";'><img src='../images/settings.png' style='border:none;'></a>
		</div>";
	}
}
?>
</center>
<div id="SettingsDiv">
	<div id="VerticalMenu">
	<ul>
	  <li><a href="javascript:void(0);" onclick="ChangeTab(1);">Server</a></li>
	  <li><a href="javascript:void(0);" onclick="ChangeTab(2);">Service</a></li>
	  <li><a href="javascript:void(0);" onclick="ChangeTab(3);">Vote</a></li>
	</ul>
	</div>
	<div class="WindowHead_Light1"><b>Web Settings</b><div class="WindowClose_Light1" onclick="document.getElementById('SettingsDiv').style.display='none';">&#10006;</div></div>
<center>
<div id='VTab1'>
	<table style="position:absolute; border-collapse: collapse;left:140px;right:5px;top:35px;bottom:1px;font-size: 10px;font-family: arial;text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7); table-layout: fixed;">
	<?php
		$opt[1]="";
		$opt[2]="";
		$opt[3]="";
		$opt[$PassType]=" selected";
		echo"<tr><td><b>MySQL Host:</b></td><td> <input type='text' id='DB_Host' value='".$DB_Host."' maxlength='25' style='width:140px;'><td><b>DB Name:</b></td><td> <input type='text' id='DB_Name' value='".$DB_Name."' maxlength='25' style='width:100px;'></td> </td></tr>";
		echo"<tr><td><b>MySQL User:</b></td><td> <input type='text' id='DB_User' value='".$DB_User."' maxlength='25' style='width:140px;'></td><td><b>Password:</b></td><td> <input type='text' id='DB_Passwd' value='".$DB_Password."' maxlength='25' style='width:100px;'></td></tr>";
		echo"<tr><td><b>Public IP:</b></td><td> <input type='text' id='ServerIP' value='".$ServerIP."' maxlength='25' style='width:140px;'></td><td><b>Lan IP:</b></td><td> <input type='text' id='LanIP' value='".$LanIP."' maxlength='25' style='width:100px;'></td></tr>";
		echo"<tr style='display:none;'><td><b>SSH User:</b></td><td> <input type='text' id='SSH_User' value='".$SSH_User."' maxlength='25' style='width:140px;'><td><b>SSH Password:</b></td><td> <input type='text' id='SSH_Passwd' value='".$SSH_Password."' maxlength='25' style='width:100px;'></td> </td></tr>";
		echo"<tr><td><b>Server Path:</b></td><td> <input type='text' id='ServerPath' value='".$ServerPath."' maxlength='25' style='width:140px;'><td><b>Pass. Type:</b></td><td> <select id='PassType' style='width:80px;'><option value='1'".$opt[1].">MD5</option><option value='2'".$opt[2].">BASE64</option><option value='3'".$opt[3].">Binary</option></select>
		<a href='javascript:void(0);' style='text-decoration:none;color:blue;' onClick='alert(\"Password Types:\\n - MD5: 0x.md5(username.password);\\n - BASE64: base64_encode(hash(md5,username.password, true));\\n - VarBin: 0x.md5(username.password) with varbintohexstring\");'>[?]</a>
		</td></tr>";
	for ($i = 1; $i <= 9; $i++){
		$tmpArr=explode("*", $ServerFile[$i]);
		echo"<tr><td><b>Folder:</b></td><td><input type='text' id='FolderPath".$i."' value='".$tmpArr[0]."' maxlength='25' style='width:140px;'><td><b>File:</b></td><td><input type='text' id='FilePath".$i."' value='".$tmpArr[1]."' maxlength='25' style='width:100px;'></td></td></tr>";
	}
	?>
	</table>
</div>
<div id='VTab2' style='display:none;'>
	<table style="position:absolute; left:140px;top:35px;right:5px;bottom:1px;font-size: 10px;font-family: arial;border-collapse: collapse;text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);float:left;">
	<?php
		$opt[1]="";
		$opt[2]="";
		$opt[3]="";
		$opt[$WShopDB]=" selected";
		echo"<tr><td><b>Server Name:</b></td><td colspan='3'> <input type='text' id='ServerName' value='".$ServerName."' maxlength='25' style='width:200px;'> </td></tr>";
		echo"<tr><td><b>Core Service:</b></td><td colspan='3'> <input type='checkbox' id='AllowLogin' value='1' style='vertical-align:middle'".BoolToChecked($LoginEnabled)."> Login (<i>On / Off</i>) <input type='checkbox' id='AllowReg' value='1' style='vertical-align:middle'".BoolToChecked($RegisEnabled)."> Registration (<i>On / Off</i>) </td></tr>";
		echo"<tr><td><b>Reg Limits:</b></td><td colspan='3'>  <input type='text' id='IPLimit' value='".$IPRegLimit."' maxlength='3' style='width:35px;'> per IP  <input type='text' id='SessionLimit' value='".$SRegLimit."' maxlength='3' style='width:35px;'> per Session</td></tr>";
		echo"<tr><td><b>Security Keys:</b></td><td colspan='3'> <a href='javascript:void(0);' onclick='ResetKeys();'><button>Reset Keys</button></a> for more security <i>(if done you need relog)</i> </td></tr>";
		echo"<tr><td><b>Start Gold:</b></td><td colspan='3'> <input type='text' id='StartGold' value='".$StartGold."' maxlength='9' style='width:50px;'> Boutique Gold after registration</td></tr>";
		echo"<tr><td><b>Start Point:</b></td><td colspan='3'> <input type='text' id='StartPoint' value='".$StartPoint."' maxlength='9' style='width:50px;'> Web Point after registration</td></tr>";
		echo"<tr><td><b>Max Point:</b></td><td colspan='3'> <input type='text' id='MaxPoint' value='".$MaxWebPoint."' maxlength='9' style='width:75px;'> Max Web Point limit per account</td></tr>";
		echo"<tr><td><b>Max Item Id:</b></td><td colspan='3'> <input type='text' id='MaxItmId' value='".$ItemIdLimit."' maxlength='9' style='width:50px;'> filter for item builder <i>(0 is off)</i></td></tr>";
		echo"<tr><td><b>Web Control:</b></td><td> <input type='checkbox' id='WebControl' value='1' style='vertical-align:middle'".BoolToChecked($ControlPanel)."> <i>On / Off</i> </td><td><b>Web Shop:</b></td><td> <input type='checkbox' id='WebShop' value='1' style='vertical-align:middle'".BoolToChecked($WebShop)."> <i>On / Off</i> </td></tr>";
		echo"<tr><td><b>Shop Log delete:</b></td><td> <input type='text' id='WShopDel' value='".$WShopLogDel."' maxlength='3' style='width:50px;'> day <i>(0 = off)</i> </td><td><b>Web Shop Log:</b></td><td> <input type='checkbox' id='WebShopLog' value='1' style='vertical-align:middle'".BoolToChecked($WebShopLog)."> <i>On / Off</i> </td></tr>";
		echo"<tr><td><b>Shop Database:</b></td><td> <select id='ShopDB' style='width:120px;'><option value='1'".$opt[1].">MySQL</option></select></td><td><b> </b></td><td>  </td></tr>";
		echo"<tr><td><b>Donation Page:</b></td><td> <input type='checkbox' id='Donation' value='1' style='vertical-align:middle'".BoolToChecked($Donation)."> <i>On / Off</i> </td><td><b>Forum Page:</b></td><td> <input type='checkbox' id='Forum' value='1' style='vertical-align:middle'".BoolToChecked($Forum)."> <i>On / Off</i> </td></tr>";
		echo"<tr><td><b>Forum Url:</b></td><td colspan='3'> <input type='text' id='ForumUrl' value='".$ForumUrl."' maxlength='25' style='width:300px;'> </td></tr>";
	?>
	</table>
</div>
	<div id='VTab3' style='display:none;'>
	<table style="position:absolute; left:140px;top:35px;right:5px;font-size: 10px;font-family: arial;border-collapse: collapse;text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);float:right;">
	<?php 
		$opt[1]="";
		$opt[2]="";
		$opt[$VoteFor]=" selected";
		echo"<tr><td><b>Point to Gold:</b></td><td> <input type='text' id='PointExch' value='".$PointExc."' maxlength='9' style='width:60px;'> = 1.00 G</td><td><b>Voteing:</b></td><td> <input type='checkbox' id='Voteing' value='1' style='vertical-align:middle'".BoolToChecked($VoteButton)."> <i>On / Off</i> </td></tr>";
		echo"<tr><td><b>Vote Interval:</b></td><td> <input type='text' id='VoteInt' value='".$VoteInterval."' maxlength='2' style='width:40px;'> hour (0 = off)</td><td><b>Vote Reward:</b></td><td><select id='VoteFor' style='width:80px;'><option value='1'".$opt[1].">Point</option><option value='2'".$opt[2].">Gold</option></select></td></tr>";
		echo"<tr><td><b>Vote Reward:</b></td><td colspan='3'> <input type='text' id='VoteRew' value='".$VoteReward."' maxlength='6' style='width:60px;'> Point or Boutique Gold.</td></tr>";
		echo"<tr><td colspan='4' style='color:#00f;text-align:center;'><b>Site Name and Url please edit manually in config file!</b></td></tr>";
		?>
	</table>
	</div>
	<br>	
	<div style="position:absolute;bottom:15px;text-align:center;left:130px;right:1px;"><a href='javascript:void(0);' onclick='SendCC();'><button>Update Configs</button></a></div>
</center>
</div>
<?php 
function BoolToChecked($bool){
	if (($bool===true)||($bool=="true")){
		return " checked";
	}else{
		return "";
	}
}
?>
</body>
</html>
