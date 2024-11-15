<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="canonical” href="https://retroms.ddns.net">
<meta name="description" content="Perfect World - Retro MS, the old version of this MMORPG game, from 2009, it is free to play oriented server, where we respect eachother." />
<meta charset="utf-8">
 <link rel="icon" type="image/x-icon" href="../images/pwicon3.png">
<link rel="stylesheet" type="text/css" href="../css/accounts.css">
<link rel="stylesheet" type="text/css" href="../css/windowstyle.css">
<script src="../js/accounts.js?j=22"></script>
</head>
<body>
<?php
include "../config.php";
include "../basefunc.php";
include "../common/cpanel.php";

SessionVerification();
if (isset($_SESSION['un'])){
	echo "<div id='BodyCont'>";
	echo "<div id='BckgrndImg'></div>";
	$link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
    $SRunning=ServerOnline($link);
	$un=$_SESSION['un'];
	$uid=intval($_SESSION['id']);
	$pw=$_SESSION['pw'];
	$ma=$_SESSION['ma'];
	$rank=0;
	if ($link->connect_errno) {
		echo "<script>alert('Sorry, this website is experiencing problems (failed to make a MySQL connection)!');</script>";
		exit;
	}
	$count=CountMysqlRows($link, 1, $uid);
	if (CountMysqlRows($link, 5, $uid)>0){
		$gm=true;
	}else{
		$gm=false;
	}
	$admin=VerifyAdmin($link, $un, $pw, $uid, $ma);
	if ($count>0){
			$tmp2="Member";
			$tmp3="<tr style='display:none;'><td> </td><td><span id='AccInfoCI'> </span></td></tr>";
			if ($gm or $admin) {
				if ($admin){$tmp2="<font color='red'><b>Administrator</b></font>";}else{$tmp2="<font color='blue'><b>Game Master</b></font>";}
			}
			
			$webP=0;
			$genderArr[0]="Unknown";
			$genderArr[1]="Male";
			$genderArr[2]="Female";
				
			if ($admin){
				echo "<div id='AdminDiv'>
				<div class='WindowHead_Light2'>Admin Tools</div>
				<table width='230' style='position:relative;top:35px;'>";
				echo"<tr><td><b>User:</b></td><td> <input type='text' id='LoadUserId'     maxlength='5' value='".$uid."' style='width:50px;text-align:center;'></td><td><a href='javascript:void(0);' onClick='RequestUserData(1);' title='You can load a account data if you type the account id'><button>Load</button></a></td></tr>";
				echo"<tr><td><b>Gold:</b></td><td> <input type='text' id='AddGoldAmount'  maxlength='7' value='0' style='width:50px;text-align:center;'>       </td><td><a href='javascript:void(0);' onClick='RequestUserData(2);' title='You can add item mall gold to account id'><button>Add gold</button></a></td></tr>";
				echo"<tr><td><b>Point:</b></td><td> <input type='text' id='AddPointAmount' maxlength='7' value='0' style='width:50px;text-align:center;'>       </td><td><a href='javascript:void(0);' onClick='RequestUserData(3);' title='You can add web point to account id'><button>Add point</button></a></td></tr>";
				echo"<tr><td colspan='3' align='center'><a href='javascript:void(0);' onClick='RequestUserData(4);' title='Make GM from the account'><button>Add GM</button></a> <a href='javascript:void(0);' onClick='RequestUserData(5);' title='Remove GM rank from account'><button>Remove GM</button></a></td></tr>";
				echo"<tr><td colspan='3' align='center'><a href='javascript:void(0);' onClick='RequestUserData(8);' title='Delete the account, but not the roles data!'><button>Delete</button></a></td></tr>";
				echo "</table><br><br><br>";
				
				
				echo "<center><b>Delete inactives</b></center><table width='230' style='font-size:12px;'>";
				echo "<tr><td colspan='2' align='center'>Delete every account what was inactive in last <input type='text' id='OldDate' maxlength='5' size='3' value='365'> day. <a href='javascript:void(0);' onClick='RequestUserData(9);' title='Delete those account what older than x day (roles data remain)!'><button>Confirm</button></a></td></tr>";
				echo"<tr><td colspan='2' align='center' style='font-size:16px;'><br><b>Reward for actives</b></td></tr>";
				echo "<tr><td colspan='2' align='center'>Give <input type='text' id='rewAmount' maxlength='6' value='100' style='width:50px;text-align:center;'> PW-Gold or Point to everybody who was online in last <input type='text' id='OldDate1' maxlength='5' value='1' style='width:30px;text-align:center;'> day. <br><font size='2'><i>(0 = who is online now)</i></font></td></tr>
					  <tr><td colspan='2' align='center'><a href='javascript:void(0);' onClick='RequestUserData(10);' title='Add item mall old to all account what fit to your condition!'><button>Add Gold</button></a> <a href='javascript:void(0);' onClick='RequestUserData(11);' title='Add web point to all account what fit to your condition!'><button>Add Point</button></a><br><br></td></tr>";
				echo "</table>";
								
								
				echo"<center><b>Ban a character </b><br><span style='font-size:12px;'><i>! Work only if server running !</i></span></center>
				<table style='font-size:12px;'>
				<tr><td>Role id: <input type='text' id='BanRoleId' maxlength='10' value='0' style='width:40px;text-align:center;'></td><td>Duration (sec):</td><td> <input type='text' id='BanRoleDur' maxlength='10' value='3600' style='width:40px;text-align:center;'></td></tr>
				<tr><td>Type: <select id='RoleBanType'>
				<option value='3'>Chat ban</option>
				<option value='4'>Role ban</option>
				</select>
				
				</td><td>GM role: </td><td><input type='text' id='BanRoleGM' maxlength='10' value='-1' style='width:40px;text-align:center;'></td></tr>
				<tr><td colspan='3' align='center'>Reason: <input type='text' id='BanRoleWhy' maxlength='50' value='Take a rest!' style='width:200px;text-align:center;'></td></tr>
				<tr><td colspan='3' align='center'><a href='javascript:void(0);' onClick='RequestUserData(6);' title='Ban the role, prevent the role to login into game!'><button>Ban</button></a> <a href='javascript:void(0);' onClick='RequestUserData(7);' title='Change the ban duration to 1 minute, after 1 minute role again active!'><button>Unban</button></a></td></tr>
				</table>
				</div>";

				echo "<div id='UsersDiv'>
				<div class='WindowHead_Light2'>User List</div>
				<div style='position: relative; top: 30px; height: 100%;'>
				<input type='text' id='SearchUser' maxlength='32' value='' style='width:100px;text-align:center;'> <a href='javascript:void(0);' onClick='UserSearch();' title='You can search after: \n- username or real name (type name, example: shadow)\n- email adress (example: your@mail.com)\n- ip address (example: 79.84.75.89)\n- account id (type number)\n- who is online (type: *)\n- show Game Masters (type: @)\n- who was online in last x day (type negative number, example: -3)'><button>Search</button></a>
				<div id='UserDivCont'><table width='100%' id='UserTable'>";
				$query = "SELECT ID, name, truename, email FROM users";
				$statement = $link->prepare($query);
				$statement->execute();
				$statement->bind_result($id1, $name1, $rname1, $email1);
				$statement->store_result();
				$result = $statement->num_rows;
				if (!$result) {
					echo "<script>alert('Error: Query failed to execute!');</script>";
					exit;
				}else{
					while($statement->fetch()) {
						$RankColor="<font color='#0000ff'>";
						if ((CountMysqlRows($link, 5, $id1))>0){
							$RankColor="<font color='#ff0000'>";
						}
						echo"<tr><td><a style='text-decoration:none;font-size:12px;font-family:arial;' href='javascript:void(0);' title='Name: ".$rname1." and email: ".$email1."' onClick='SendDataWithAjax(1, [".$id1."]);document.getElementById(\"LoadUserId\").value=\"".$id1."\";'>$RankColor".$name1."</font> <i><font color='black' size='2'>[".$id1."]</font></i></a></td></tr>";
					}   
				}

				$statement->close();
				echo "</table></div></div>				
				</div>";
			}
			
			echo"<div id='AccInfoDiv' style='border: 1px solid #000; width:320px;padding:10px;'>
			<div class='WindowHead_Light2'>Account Info</div><br><br>
			<table width='300' border='0' style='font-family:Arial;font-size:12px;'>
			<tr style='display:none;'><td><b>Avatar:</b></td><td><span id='AccInfoAv'>Not added yet</span></td></tr>
			<tr><td><b>Account name:</b></td><td><span id='AccInfoNa'> </span> - <b><i><span id='AccInfoStatus'></span></i></b></td></tr>
			<tr><td><b>Real name:</b></td><td><span id='AccInfoRN'> </span></td></tr>
			<tr><td><b>Password:</b></td><td><span id='AccInfoPw'> </span></td></tr>
			<tr><td><b>Email address:</b></td><td><span id='AccInfoEm'> </span></td></tr>
			<tr><td><b>Gender:</b></td><td><span id='AccInfoGe'> </span></td></tr>
			<tr><td><b>Birthday:</b></td><td><span id='AccInfobd'> </span></td></tr>
			<tr style='display:none'><td><b>Web Point:</b></td><td><span id='AccInfoWP'> </span> <a href='javascript:void(0);' onClick=document.getElementById('ExchangeDiv').style.display='block'; style='float:right;' id='PExchLink'><button>Exchange</button></a></td></tr>
			<tr><td><b>Status:</b></td><td><span id='AccInfoRa'> </span></td></tr>
			<tr><td><b>Reg. date:</b></td><td><span id='AccInfoRD'>".$_SESSION['t']."</span></td></tr>
			<tr><td><b>Last login:</b></td><td><span id='AccInfoLL'> </span></td></tr>
			<tr><td><b>Regist. ip:</b></td><td><span id='AccInfoRegIp'> </td></tr>
			<tr><td><b>Login Ip:</b></td><td><span id='AccInfoLoginIp'> </td></tr>
			<tr id='AccInfoZone' style='display:none;'><td><b>Where:</b></td><td><span id='AccInfoZId'> - </span></td></tr>
			<tr id='AccInfoBanRow' style='display:none;'><td><b>Banned:</b></td><td><span id='AccInfoLA'> - </span></td></tr>
			<tr><td><b>Characters: </b></td><td> <span id='AccInfoCI'>0</span></td></tr>
			<tr><td colspan='2' align='center'><a href='javascript:void(0);' onClick='SwitchDisplayDataDiv(0);'><button>Change Account Data</button></a>";
			if ($admin) {
				echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' onClick='SwitchDisplayDataDiv(2);'><button>WebShop Log</button></a>";
			}
			if ($VoteButton!==false){
				echo "<br><a href='https://youtu.be/5PiYd6XS5pI' rel='noopener noreferrer' target='_blank'><button>How to vote?!</button></a>";
				echo"&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' onClick='document.getElementById(\"VoteDiv\").style.display=\"block\";'><button>Vote to server</button></a>";
			}
			echo"</td></tr>
			</table>
			<div id='ExchangeDiv' style='display:none;text-align:center;font-size:12px;'><b>Exchange Point to Gold</b> <i>($PointExc:1)</i><br>
			I want <input type='text' id='ExchGAmount' maxlength='6' value='1' style='width:40px;text-align:center;' onkeydown='CheckExchCost();'>.00 <b>Gold</b> and pay <span id='ExchPCost'>$PointExc</span> <b>Point</b><br>
			<i>(you can get <span id='ExchGMinRes'>1</span>-<span id='ExchGMaxRes'>xxxx</span> Gold)</i><br>
			<a href='javascript:void(0);' onClick='ExchangePointToGold();'><button>Exchange</button></a> <a href='javascript:void(0);' onClick=document.getElementById('ExchangeDiv').style.display='none';><button>Close</button></a>
			</div>
			<br>
			
			<div style='position:relative; max-height: 200px; overflow: auto;'><table width='250' height='100%' border='0' style='font-family:Arial;font-size:12px;' id='GoldLogTable'>
			<tr><td colspan='2' align='center'><b><u>Acquired Gold History</u></b></td></tr>
			</table></div>";
			if (($VoteButton!==false)&&($VoteCount>0)&&($VoteInterval>0)){
				echo "<div id='VoteDiv' style='font-family:Arial;font-size:15px;z-index:2;display:none;'><div class='WindowHead_Light2'>Vote options<div class='WindowClose_Light1' onclick='document.getElementById(\"VoteDiv\").style.display=\"none\";'>&#10006;</div></div>
				<br><br><table border='0' style='font-family:Arial;font-size:12px;width:100%;' id='VoteList'>";
				echo "<tr><td style='text-align:center;'><b><i><u>Site name</u></i></b></td><td style='text-align:center;width:50px;'><b><i><u>Cooldown</u></i></b></td></tr>";
				for ($i = 1; $i <= sizeof($VoteUrl); $i++) {
					if (strlen($VoteUrl[$i]) > 3){
						echo "<tr id='VoteRow".$i."'><td><b><i>".$VoteSite[$i]."</i></b></td><td style='text-align:center;width:50px;'><span id='VoteTimer".$i."'>--:--:--</span> </td></tr>";
					}
				}
				echo "</table></div>";
			}
			echo "<br>
			<div id='CharInfoDiv' style='font-family:Arial;font-size:12px;'>
			<center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><u>Character list</u></b><br></center>
			<table width='300' border='0' style='font-family:Arial;font-size:12px;' id='CharList'>";
			if ($SRunning===true){
				echo"<tr><td width='200' colspan='2'><b><i> Loading ... </i></b> </td></tr>";
			}else{
				echo"<tr><td align='center' colspan='2'><b><font color='red'> Sorry, server if offline </font></b></td></tr>";
			}
			echo"</table></div>
			</div>";

			if ($admin) {
				echo "<div id='WebshopLogDiv' style='font-family:Arial;font-size:15px;z-index:2;display:none;'>
					<div class='WindowHead_Light2'>Webshop Log
						<div class='WindowClose_Light1' onclick='document.getElementById(\"WebshopLogDiv\").style.display=\"none\";'>&#10006;</div>
					</div>
				<br><br>
				<table border='0' style='font-family:Arial;font-size:12px;width:100%;' id='WebShopLogList'>";
				echo "<thead><tr>
					<th style='text-align:center;'><i>Id</i></th>
					<th style='text-align:center;width:50px;'><i>User</i></th>
					<th style='text-align:center;width:50px;'><i>Name</i></th>
					<th style='text-align:center;width:50px;'><i>Role</i></th>
					<th style='text-align:center;width:50px;'><i>Role Name</i></th>
					<th style='text-align:center;width:50px;'><i>Date</i></th>
					<th style='text-align:center;width:50px;'><i>Currency</i></th>
					<th style='text-align:center;width:50px;'><i>Price</i></th>
					<th style='text-align:center;width:50px;'><i>Item Data</i></th>
					<th style='text-align:center;width:50px;'><i>Shop Id</i></th>
				</tr></thead><tbody></tbody></table>";
	
				echo "</div>";
			}
		
			echo "<div id='ChngInfoDiv' style='border: 1px solid #000; width:320px;padding:10px;'>
			<div class='WindowHead_Light2'>Change Account Data</div><br><br>
			<table width='300' border='0' style='font-family:Arial;font-size:12px;border-collapse: collapse;'>
			<tr style='display:none;'><td width='100'><b>Avatar: </b></td><td> Coming soon....</td></tr>";
			if ($admin){
				echo"<tr><td width='100'><b>User name: </b></td><td> <input type='text' id='CurUnam' maxlength='30' value='".$un."'><input type='hidden' id='OldUnam' maxlength='20' value='".$un."' style='display:none;' readonly></td></tr>";
				echo"<tr><td width='100'><b>User id: </b></td><td> <input type='text' id='CurUId' maxlength='30' value='".$uid."'><input type='hidden' id='OldUId' maxlength='20' value='".$uid."' style='display:none;' readonly></td></tr>";
			}else{
				echo"<tr style='display:none;'><td width='100'><b>User name: </b></td><td>$un<input type='hidden' style='display:none;' id='CurUnam' maxlength='20' value='".$un."' readonly><input type='hidden' id='OldUnam' maxlength='20' value='".$un."' style='display:none;' readonly></td></tr>";
				echo"<tr style='display:none;'><td width='100'><b>User id: </b></td><td>$uid<input type='hidden' style='display:none;' id='CurUId' maxlength='20' value='".$uid."' readonly><input type='hidden' id='OldUId' maxlength='20' value='".$uid."' style='display:none;' readonly></td></tr>";
			}

			echo"<tr><td width='100'><b>Old Password: </b></td><td> <input type='text' id='CurPwd' maxlength='20' value='".$pw."' readonly></td></tr>
			<tr><td width='100'><b>New password: </b></td><td> <input type='text' id='NewPwd1' maxlength='20'></td></tr>
			<tr><td width='100'><b>Password again: </b></td><td> <input type='text' id='NewPwd2' maxlength='20'></td></tr>
			<tr><td width='100'><b>Email address: </b></td><td> <input type='text' id='Mail' maxlength='64' value='".$ma."'></td></tr>
			<tr><td width='100'><b>Real name: </b></td><td> <input type='text' id='RealName' maxlength='32' value=' '></td></tr>
			<tr><td width='100'><b>Gender: </b></td><td>   <input type='radio' name='gender' id='gender_male' value='1' > Male   <input type='radio' name='gender' id='gender_female' value='2'> Female</td></tr>
			<tr><td width='100'><b>Birthday: </b></td><td> 
				<select name='dob-day' id='dob-day' style='width:50px;'>
				
				  <option value='00'>Day</option>
				  <option value='00'>---</option>";
			for ($i = 1; $i <= 31; $i++) {
				if ($i<10){
					echo"<option value='0".$i."'>0".$i."</option>";
				}else{
					echo"<option value='".$i."'>".$i."</option>";
				}
			}

			echo"</select>
				<select name='dob-month' id='dob-month' style='width:70px;'>
				  <option value='00'>Month</option>
				  <option value='00'>-----</option>";
			for ($i = 1; $i <= 12; $i++) {
				if ($i<10){
					echo"<option value='0".$i."'>".$months[$i]."</option>";
				}else{
					echo"<option value='".$i."'>".$months[$i]."</option>";
				}
			}
				echo"</select>
				<select name='dob-year' id='dob-year' style='width:60px;'>
				  <option value='0000'>Year</option>
				  <option value='0000'>----</option>";
			for ($i = 2012; $i >= 1940; $i--) {
				echo" <option value='".$i."'>".$i."</option>";
			}
			echo"</select>
				</td></tr>";
			if ($admin){
				echo"<tr><td width='100'><b>Status: </b></td><td> <select name='mstat' id='mstat'>
				<option value='0'>Member</option>
				<option value='1'>Game Master</option>
				</select></td></tr>";
			}else{
				echo"<tr style='display:none;'><td width='100'><b>Status: </b></td><td> <select name='mstat' id='mstat'>
				<option value='0'>Member</option>
				</select></td></tr>";				
			}
			echo"<tr><td align='right'><a href='javascript:void(0);' onClick='SendNewData();'><button>Save</button></a></td><td align='center'><a href='javascript:void(0);' onClick='SwitchDisplayDataDiv(1);'><button>Cancel</button></a></td></tr>";
			echo"</table><center><font color='red'><b><span id='Feedback_div' style='color:red; font-weight:900;align:center;'></span></b></font></center></div>";
	}else{
		echo "<script>parent.window.location.href = '../index.php';</script>";
	}
	mysqli_close($link);
	echo"</div>";
}else{
	echo "<script>parent.window.location.reload();</script>";
}

echo "<script>
ExchRate=parseInt('".$PointExc."',10)||0;
VInterval=parseInt('".$VoteInterval."',10)||0;
SendDataWithAjax(1, [parseInt('".$uid."',10)]);
</script>";
?>
<script>
StartGlobalTimer();
CheckExchCost();
</script>
</body>
</html>
