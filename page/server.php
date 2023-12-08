<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../css/server.css">
<link rel="stylesheet" type="text/css" href="../css/windowstyle.css">
<script src="../js/server.js"></script>
</head>
<body>
<?php
include "../config.php";
include "./cpanel.php";
include "../basefunc.php";
SessionVerification();
echo "<div id='BodyCont'>";
if (isset($_SESSION['un'])){
	echo "<div id='BckgrndImg'></div>";	
	echo"<div id='LogDiv'>
	<div id='LogHeaderDiv'> &nbsp; &nbsp; <b>Feedback log</b></div>
	<div id='LogContDiv'>
		<span id='LogMsg'>
		
		</span>
	</div>
		<center> <a href='javascript:void(0);' onClick='ClearLogMsg();' style='position:absolute;bottom:10px;'><button>Clear</button></a></center>
	</div>";
	
	
	$un=$_SESSION['un'];
	$pw=$_SESSION['pw'];
	$ma=$_SESSION['ma'];
	$id=$_SESSION['id'];
	if ($PassType == 1){
		$Salt = "0x".md5($un.$pw);
	}else if ($PassType == 2){
		$Salt = base64_encode(hash('md5',strtolower($un).$pw, true));
	}else if ($PassType == 3){
		$Salt = md5($un.$pw);
	}
	//first admin verify
	if (($id==$AdminId)&&($Salt==$AdminPw)){
		echo"<script>AddLogMsg('$cfgFile loaded...');</script>";
		$link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
		$result = mysqli_query($link,"SELECT zoneid FROM point WHERE zoneid IS NOT NULL");
		$count = @mysqli_num_rows($result);
		$sockres = @FSockOpen($LanIP, $ServerPort, $errno, $errstr, 1);
		if (!$sockres){
			mysqli_query($link,"DELETE FROM online");
			$SRunning=false;
			echo"<script>AddLogMsg('server is offline...');</script>";
		}else{
		   @FClose($sockres);
		   $SRunning=true;
		   echo"<script>AddLogMsg('server is online...');</script>";
		}   
		mysqli_free_result($result);	
		if (VerifyAdmin($link, $un, $pw, $id, $ma)!==false){
			//$WebDir $ServerPath
			//$ServerFile[9]
			$expArr=explode("*",$ServerFile[9]);
			$ptemplate = $ServerPath.$expArr[0]."/ptemplate.conf";
			$expArr=explode("*",$ServerFile[7]);
			$gdeliveryconf = $ServerPath.$expArr[0]."/gamesys.conf";
			$expArr=explode("*",$ServerFile[7]);
			$uniquenamedconf = $ServerPath.$expArr[0]."/gamesys.conf";
			$ClassMask[1]=1;
			$ClassMask[2]=2;
			$ClassMask[3]=16;
			$ClassMask[4]=8;
			$ClassMask[5]=128;
			$ClassMask[6]=64;
			$ClassMask[7]=4;
			$ClassMask[8]=32;
			$ClassMask[9]=256;
			$ClassMask[10]=512;
			$ClassMask[11]=1024;
			$ClassMask[12]=2048;
			$maxClass = count($PWclass);
			$raceCount = intval($maxClass/2);
		
			// searched commands
			$cmd_debug = "debug_command_mode";
			$cmd_class = "allow_login_class_mask";
			$cmd_exp = "exp_bonus";
			$cmd_sp = "sp_bonus";
			$cmd_drop = "drop_bonus";
			$cmd_gold = "money_bonus";
			$cmd_tw = "battlefield";
			$cmd_pvp = "pvp";
			$cmd_maxname = "max_name_len";
			$cmd_caseins = "case_insensitive";
			//default values
			$debug_mode=0;
			$def_cmask=219;
			$debug_cmd="";
			$exp_bonus=0;
			$sp_bonus=0;
			$drop_bonus=0;
			$gold_bonus=0;
			$pvp=1;
			$tw=1;
			$isensivity=0;
			$max_name=32;
			
			if ($file = fopen($ptemplate, "r")) {
				$step=0;
				while(!feof($file)) {
					$line = trim(strtolower(fgets($file)));
					if (substr($line,0,strlen($cmd_class))==$cmd_class){
						 $tmpArr = explode("=", $line);
						 $def_cmask = trim($tmpArr[1]);
					}elseif(substr($line,0,strlen($cmd_exp))==$cmd_exp){
						$tmpArr = explode("=", $line);
						$exp_bonus = trim($tmpArr[1]);
					}elseif(substr($line,0,strlen($cmd_sp))==$cmd_sp){
						$tmpArr = explode("=", $line);
						$sp_bonus = trim($tmpArr[1]);
					}elseif(substr($line,0,strlen($cmd_drop))==$cmd_drop){
						$tmpArr = explode("=", $line);
						$drop_bonus = trim($tmpArr[1]);
					}elseif(substr($line,0,strlen($cmd_gold))==$cmd_gold){
						$tmpArr = explode("=", $line);
						$gold_bonus = trim($tmpArr[1]);
					}elseif(substr($line,0,strlen($cmd_debug))==$cmd_debug){
						$tmpArr = explode("=", $line);
						$debug_cmd = trim($tmpArr[1]);
					}
				}
				echo"<script>AddLogMsg('ptemplate.conf loaded...');</script>";
				if ($file = fopen($gdeliveryconf, "r")){
					while(!feof($file)) {
						$line = trim(strtolower(fgets($file)));
						if (substr($line,0,strlen($cmd_tw))==$cmd_tw){
							 $tmpArr = explode("=", $line);
							 $tw = trim($tmpArr[1]);
						}elseif(substr($line,0,strlen($cmd_pvp))==$cmd_pvp){
							$tmpArr = explode("=", $line);
							$pvp = trim($tmpArr[1]);
						}elseif(substr($line,0,strlen($cmd_maxname))==$cmd_maxname){
							$tmpArr = explode("=", $line);
							$max_name = trim($tmpArr[1]);
						}
					}
					fclose($file);
					echo"<script>AddLogMsg('gdeliveryd/gamesys.conf loaded...');</script>";
					if ($file = fopen($uniquenamedconf, "r")){
					while(!feof($file)) {
						$line = trim(strtolower(fgets($file)));
						if (substr($line,0,strlen($cmd_caseins))==$cmd_caseins){
							 $tmpArr = explode("=", $line);
							 $cmd_caseins = trim($tmpArr[1]);
						}
					}
					fclose($file);
					echo"<script>AddLogMsg('uniquenamed/gamesys.conf loaded...');</script>";
					}else{
						echo"<script>AddLogMsg('Wrong path: $uniquenamedconf...');</script>";
					}
				}else{
					echo"<script>AddLogMsg('Wrong path: $gdeliveryconf...');</script>";
				}
				

				 if ($maxClass < 9){
					 $tempNr = pow(2, 8)-1;
				 }else{
					 $tempNr = pow(2, $maxClass)-1;
				 }
				 if ($def_cmask > $tempNr){$def_cmask=$tempNr;}
				
				$tmpNr=$def_cmask;
				$Checked[0] = "";
				$Checked[1] = " checked";
				$ClassMaskSel = explode(" ", ClassMask2String ($def_cmask));
				
				echo"<div id='ptemplateDiv'><center><b>Server - Classes</b></center><table border='0' style='font-face:arial;font-size:10;'>";
				for ($i = 1; $i <= $raceCount; $i++) {
					$c1=$i*2-1;
					$c2=$i*2;
					echo"<tr><td><input type='checkbox' id='Class".$c1."' value='".$ClassMask[$c1]."' onClick='GenerateClassMask();' ".$Checked[$ClassMaskSel[($c1-1)]]."><b> ".$PWclass[$c1]." &nbsp;&nbsp;</b></td><td><input type='checkbox' id='Class".$c2."' value='".$ClassMask[$c2]."' onClick='GenerateClassMask();' ".$Checked[$ClassMaskSel[($c2-1)]]."><b> ".$PWclass[$c2]." &nbsp;&nbsp;</b></td></tr>";
				} 
				
				echo"<tr><td colspan='2' style='text-align: center;'>Class Mask: &nbsp;&nbsp;<input type='text' id='ClassMask' maxlength='4' value='".$def_cmask."' style='width:35px;text-align:center;'></td></tr>
				</table><br><center>Basic settings</center>";
				// DebugMode  XPBonus  SPBonus DRBonus MNBonus PvP TW NameSens NameLength
				echo "<table border='0' style='font-size:12;width:100%;'>
				<tr><td>debug_command_mode</td><td width='37' style='text-align:center;'><input type='checkbox' value='$debug_mode' id='DebugMode' ".$Checked[$debug_mode]."></td></tr>
				<tr><td>exp_bonus</td> <td width='37' style='text-align:center;'><input type='text' id='XPBonus' maxlength='4' value='$exp_bonus' style='width:35px;text-align:center;'></td></tr>
				<tr><td>sp_bonus</td> <td width='37' style='text-align:center;'><input type='text' id='SPBonus' maxlength='4' value='$sp_bonus' style='width:35px;text-align:center;'></td></tr>
				<tr><td>drop_bonus</td> <td width='37' style='text-align:center;'><input type='text' id='DRBonus' maxlength='4' value='$drop_bonus' style='width:35px;text-align:center;'></td></tr>
				<tr><td>money_bonus</td> <td width='37' style='text-align:center;'><input type='text' id='MNBonus' maxlength='4' value='$gold_bonus' style='width:35px;text-align:center;'></td></tr>
				<tr><td>PvP server</td><td width='37' style='text-align:center;'><input type='checkbox' value='$pvp' id='PvP' ".$Checked[$pvp]."></td></tr>
				<tr><td>TW on server</td><td width='37' style='text-align:center;'><input type='checkbox' value='$tw' id='TW' ".$Checked[$tw]."></td></tr>
				<tr><td>Case insensitivity</td><td width='37' style='text-align:center;'><input type='checkbox' value='$isensivity' id='NameSens' ".$Checked[$isensivity]."></td></tr>
				<tr><td>Max name length</td><td width='37' style='text-align:center;'><input type='text' value='$max_name' id='NameLength' maxlength='4' style='width:35px;text-align:center;'></td></tr>
				</table><br><center><a href='javascript:void(0);' onClick='SendData(1);'><button>Save all</button></a></center>
				";
			}else{
				echo"<div id='ptemplateDiv'><center><font color='red'> 	Wrong <b>ptemplate.conf</b> path or serfer folder permission!<br>please edit <b>$cfgFile</b> for correct folder path<br><br>if you not deleted then try this <a href='javascript:void(0);' onclick='parent.location.href = \"../setup.php\";'><i><b>link</b></i></a> for setup</font></center>";
				echo"<script>AddLogMsg('ptemplate.conf not found, wrong path in $cfgFile...');</script>";
			}
			echo"<div id='ChatDiv'><table border='0' width='100%' style='font-size:12;'>";
			echo"<center>Text:</center> <input type='text' id='CText' maxlength='100' value='Hello everybody!' style='width:100%;'></td></tr>
			<tr><td style='text-align:center;'>
			<span style='float:left;'>
			
			
			<tr><td>Channel: <select id='CChannel'>
			<option value='0'>Normal</option>
			<option value='1'>World</option>
			<option value='2'>Party</option>
			<option value='3'>Guild</option>
			<option value='7'>Trade</option>
			<option value='9' selected>System</option>
			<option value='12'>Horn</option>
			<option value='4'>Misc blue1</option>
			<option value='6'>Misc orange</option>
			<option value='10'>Misc blue2</option>
			</select></div>&nbsp;&nbsp;&nbsp;
			Role id: <input type='text' id='CRole' maxlength='4' value='0' style='width:40px;text-align:center;font-size:12px;'></span>  <a href='javascript:void(0);' onClick='SendData(2);' style='float:right;'><button>Send message</button></a></td></tr>
			</table></div>";
			
			echo"<div id='ServerStatus' style='font-size:12;font-weigth:900;display:none;'><center><span style='font-size:16px;'><u>Server Status</u></span></font><br>
			<table border='0' style='font-size:12; width:100%;'>";
			$Status[0] = "<font color='red'>Not running</font>";
			$Status[1] = "<font color='blue'>Running</font>";
			for ($i = 1; $i <= count($ServerFile); $i++) {
				$tmpArr = explode("*", $ServerFile[$i]);
				$res = shell_exec("pgrep ".$tmpArr[1]);
				//if ($res==""){$sstat=0;}else{if ((($i==count($ServerFile))&&($sstat==1)) or ($i<count($ServerFile))){$sstat=1;}}
				if ($res==""){$sstat=0;}else{$sstat=1;}
				echo "<tr><td style='text-align:left;'>".$tmpArr[1]."</td><td id='StatusTD".$i."'>".$Status[$sstat]."</td></tr>";
			}
			echo"</table>
			<br>";
			if ($sstat==1){
				echo" <a href='javascript:void(0);' onClick='SendData(1);'><button>Stop</button></a>";
			}else{
				echo " <a href='javascript:void(0);' onClick='SendData(1);'><button>Start</button></a>";
			}
			 
			echo" <a href='javascript:void(0);' onClick='SendData(1);'><button>Refresh</button></a> </center></div>";
			
			echo"<div id='ServerMapStatus' style='font-size:12;font-weigth:900;display:none;'><center><span style='font-size:16px;'><u>Running</u></span></font><br>
			<table border='0' style='font-size:12; width:100%;'>";
			$Status[0] = "<font color='red'>Not running</font>";
			$Status[1] = "<font color='blue'>Running</font>";
			$res = "";//shell_exec("ps -fC gs | awk '{ print $9 }'");
			if (trim($res)==""){
				echo "<tr><td style='text-align:center;' colspan='2'><i><font color='red'> server is offline </font></i></td></tr>";
			}else{
				$out = explode("\n",$res);
				$cMaps=0;
				for ($i = 1; $i <= count($out); $i++) {
					$out[$i]=trim($out[$i]);
					if ($out[$i] != ""){
						$cMaps++;
						echo "<tr><td style='text-align:left;'>".$out[$i]."</td><td> <a href='javascript:void(0);' onClick='TurnOffMap(".$out[$i].");'><button>Stop</button></a>  </td></tr>";
					}
				}
				if ($cMaps==0){echo "<tr><td style='text-align:center;' colspan='2'><i><font color='red'> server is offline </font></i></td></tr>";}
			}
			echo"</table>
			<br>
			<a href='javascript:void(0);' onClick='SendData(1);'><button>Refresh</button></a>
			</center></div>";
		}
		mysqli_close($link);
	}
}else{
	echo "<script>window.location.href = '../index.php';</script>";
}
echo"</div>";

function ClassMask2String ($ClassMask){
	$maxClass = 12;					     	//how much class in server, minimum value is 8.
	$n = $ClassMask;						// just variable for calculation the class masks
	$nci = 0;								//new class index for array, needed for make ordered array
	$cindex = array(2 => 6, 4=> 2, 5 => 7, 6 => 5, 7 => 4);	//fix array indexs for class. wr[0], mg[1], wb[2], wf[3], ep[4], ea[5], psy[6], sin[7] etc
	$resArr = array();						//array where we organise the class order
	
	//--- make normal order for class mask array ($resArr), like how it is in login screen --- 
	for ($i = 1; $i <= $maxClass; $i++) {
		$index=$maxClass-$i;
		$val = intval(pow(2, $index));
		if (isset($cindex[$index])){
			$nci = $cindex[$index];
		}else{
			$nci = $index;
		}
		$nci = intval($nci);
		
		if ($n < $val){
			$resArr[$nci]=0;
		}else{
			$resArr[$nci]=1;
			$n=$n-$val;
		}
	}
	//------ loop is over ---------
	
	//make string from array
	$res="";
	for ($i = 0; $i < $maxClass; $i++) {
		$res = $res." ".$resArr[$i];
	}
	$res=trim($res);
	// then we destroy the array and return the result
	unset ($resArr);
	unset ($cindex);
	return $res;
}
?>
</body>
</html>
