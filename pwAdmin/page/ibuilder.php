<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../css/ibuild.css">
<link rel="stylesheet" type="text/css" href="../css/windowstyle.css">
<script src="../js/conv.js"></script>
<script src="../js/refined.js"></script>
<script src="../js/baseitemdata.js"></script>
<script src="../js/cards.js"></script>
<script src="../js/ibuild.js"></script>
</head>
<body>
<?php
include "../config.php";
include "../basefunc.php";
include "../php/pw_items_ext.php";
include "../php/pw_items.php";
include "../page/cpanel.php";

echo "<div id='BodyCont'>";
$AllowN = false;
if ((isset($_SESSION['un']))&&(isset($_SESSION['pw']))&&(isset($_SESSION['id']))){
	$username=$_SESSION['un'];
	$password=$_SESSION['pw'];
	$userid=intval($_SESSION['id']);
	if ($PassType == 1){
		$Salt = "0x".md5($username.$password);
	}else if ($PassType == 2){
		$Salt = base64_encode(hash('md5',strtolower($username).$password, true));
	}else if ($PassType == 3){
		$Salt = md5($username.$password);
	}
	if (($Salt == $AdminPw) && ($userid == $AdminId)){
		$AllowN = true;
	}
}else{
	echo "<script>parent.window.location.href = '../index.php';</script>";
}
SessionVerification();
if ($AllowN !== true){die;}

$allClass = 219;
$maxClass = 6;
if ($ServerVer >= 80){
	$allClass = 4095;
	$maxClass = 12;
}elseif ($ServerVer >= 50){
	$allClass = 1023;
	$maxClass = 10;
}elseif ($ServerVer >= 40){
	$allClass = 255;
	$maxClass = 8;
}
?>
<br><br><br>
<div id='MainItemDiv'>
<center>
<a href='javascript:void(0);' onclick="document.getElementById('MathDiv').style.display='block';"><button> Math </button></a>  <a href='javascript:void(0);' onClick="document.getElementById('ShopItemWindow').style.display='none';document.getElementById('SelectWindow').style.display='block';"><button> Packet List </button></a> 
<?php 
if ($WebShop!==false){
	echo"<a href='javascript:void(0);' onClick='document.getElementById(\"SelectWindow\").style.display=\"none\";document.getElementById(\"ShopItemWindow\").style.display=\"block\";'><button> Shop List </button></a>";
}
?>
</center>

<center> Item Data </center>
<table border='0'  width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;'>
<tr><td colspan="2">
<select id='Sel_Item_Type' style='font-size:12px;width:70px;' onChange="ChangeItemType();">
		<option value='W' selected>Weapon</option>
		<option value='A'>Armor</option>
		<option value='J'>Jewelry</option>
		<?php
		if ($ServerVer >=70){
				echo"<option value='C'>Cards</option>";
		}
		?>
		<option value='F'>Fashion</option>
		<option value='O'>Other Octet</option>
		<option value='U'>Utility</option>
		<option value='M'>Mats & Herbs</option>
</select>

<select id='Sel_WSub_Type' style='font-size:12px;width:127px;' onChange="ChangeWeaponType();">
<?php
	for ($i = 1; $i <= count($IBMenuSC[1]); $i++) {
		$tmpArr = explode ("#", $IBMenuSC[1][$i]);
		$MenN = $tmpArr[0];
		$reqV = $tmpArr[1];
		if ($ServerVer >= $reqV){
			if ($i == 1){
				echo "<option value='".$i."' selected>".$MenN."</option>";
			}else{
				echo "<option value='".$i."'>".$MenN."</option>";
			}
		}
	}
?>
</select>
<select id='Sel_ASub_Type' style='font-size:12px;width:127px; display:none;' onChange="ChangeArmorType();">
<?php
	for ($i = 1; $i <= count($IBMenuSC[2]); $i++) {
		$tmpArr = explode ("#", $IBMenuSC[2][$i]);
		$MenN = $tmpArr[0];
		$reqV = $tmpArr[1];
		if ($ServerVer >= $reqV){
			if ($i == 1){
				echo "<option value='".$i."' selected>".$MenN."</option>";
			}else{
				echo "<option value='".$i."'>".$MenN."</option>";
			}
		}
	}
?>
</select>
<select id='Sel_JSub_Type' style='font-size:12px;width:127px; display:none;' onChange="ChangeJewelType();">
<?php
	for ($i = 1; $i <= count($IBMenuSC[3]); $i++) {
		$tmpArr = explode ("#", $IBMenuSC[3][$i]);
		$MenN = $tmpArr[0];
		$reqV = $tmpArr[1];
		if ($ServerVer >= $reqV){
			if ($i == 1){
				echo "<option value='".$i."' selected>".$MenN."</option>";
			}else{
				echo "<option value='".$i."'>".$MenN."</option>";
			}
		}
	}
?>
</select>
<select id='Sel_OSub_Type' style='font-size:12px;width:127px; display:none;' onChange="ChangeOtherType();">
<?php
	for ($i = 1; $i <= count($IBMenuSC[4]); $i++) {
		$tmpArr = explode ("#", $IBMenuSC[4][$i]);
		$MenN = $tmpArr[0];
		$reqV = $tmpArr[1];
		if ($ServerVer >= $reqV){
			if ($i == 1){
				echo "<option value='".$i."' selected>".$MenN."</option>";
			}else{
				echo "<option value='".$i."'>".$MenN."</option>";
			}
		}
	}
?>		
</select>

<select id='Sel_USub_Type' style='font-size:12px;width:127px; display:none;' onChange="ChangeUtilType();">
<?php
	for ($i = 1; $i <= count($IBMenuSC[5]); $i++) {
		$tmpArr = explode ("#", $IBMenuSC[5][$i]);
		$MenN = $tmpArr[0];
		$reqV = $tmpArr[1];
		if ($ServerVer >= $reqV){
			if ($i == 1){
				echo "<option value='".$i."' selected>".$MenN."</option>";
			}else{
				echo "<option value='".$i."'>".$MenN."</option>";
			}
		}
	}
?>	
</select>

<select id='Sel_MSub_Type' style='font-size:12px;width:127px; display:none;' onChange="ChangeMatType();">
<?php
	for ($i = 1; $i <= count($IBMenuSC[6]); $i++) {
		$tmpArr = explode ("#", $IBMenuSC[6][$i]);
		$MenN = $tmpArr[0];
		$reqV = $tmpArr[1];
		if ($ServerVer >= $reqV){
			if ($i == 1){
				echo "<option value='".$i."' selected>".$MenN."</option>";
			}else{
				echo "<option value='".$i."'>".$MenN."</option>";
			}
		}
	}
?>		
</select>

<select id='Sel_FSub_Type' style='font-size:12px;width:127px; display:none;' onChange="ChangeFashType();">
<?php
	for ($i = 1; $i <= count($IBMenuSC[7]); $i++) {
		$tmpArr = explode ("#", $IBMenuSC[7][$i]);
		$MenN = $tmpArr[0];
		$reqV = $tmpArr[1];
		if ($ServerVer >= $reqV){
			if ($i == 1){
				echo "<option value='".$i."' selected>".$MenN."</option>";
			}else{
				echo "<option value='".$i."'>".$MenN."</option>";
			}
		}
	}
?>
</select>

<select id='Sel_CSub_Type' style='font-size:12px;width:127px; display:none;' onChange="ChangeCardType();">
<?php
	for ($i = 1; $i <= count($IBMenuSC[8]); $i++) {
		$tmpArr = explode ("#", $IBMenuSC[8][$i]);
		$MenN = $tmpArr[0];
		$reqV = $tmpArr[1];
		if ($ServerVer >= $reqV){
			if ($i == 1){
				echo "<option value='".$i."' selected>".$MenN."</option>";
			}else{
				echo "<option value='".$i."'>".$MenN."</option>";
			}
		}
	}
?>
</select></td></tr>

<tr><td colspan="2"><div style="height:32px;width:32px;border: 1px solid #000;vertical-align:middle;display:inline-block; background-image: url('../images/icons/0.gif');"><img src=""  id="item_icon" alt="Item Icon" height="100%" width="100%" onerror="this.style.visibility = 'hidden'" onload="this.style.visibility = 'visible'"></div>
<?php
	$icount=0;
	$tcount=0;
	$MMName[1]="Weapon";
	$MMName[2]="Armor";
	$MMName[3]="Jewelry";
	$MMName[4]="Other Octet";
	$MMName[5]="Utility";
	$MMName[6]="Mats & Herbs";
	$MMName[7]="Fashion";
	$MMName[8]="Cards";
	$statTxt="Lets count the predefined items:\\n";
	for ($i = 1; $i <= count($ItemMod); $i++) {
		for ($a = 1; $a <= count($ItemMod[$i]); $a++) {
			$id="SItmC".$i."S".$a;
			$tmp = "display:none;";
			if (($i==1)&&($a==1)){$tmp = "";}
			echo "<select id='".$id."' style='width: 165px;".$tmp."' onChange='getPItemData(this)'>";
			for ($b = 1; $b <= count($ItemMod[$i][$a]); $b++) {
				$ItemMod[$i][$a][$b] = str_replace('\'', '&rsquo;', $ItemMod[$i][$a][$b]);
				$tmpArr =  explode("#", $ItemMod[$i][$a][$b]);
				$ItmName = $tmpArr[0];
				$ItemId = $tmpArr[1];
				if (($ItemId <= $ItemIdLimit)||($ItemIdLimit<1)){
					$ItmCol = intval($tmpArr[3]);
					if (($i < 4) || (($i == 4) && ($a == 7))||(($i == 5) && ($a == 1))){
						$ItmGrd = $tmpArr[2];
						$grdShow = " [".$ItmGrd."]";
					}else{
						$grdShow = "";
					}
					$Show=True;
					$TheCol = $ItemColor[$ItmCol];
					if (($i == 4)&&($a==1)){
						$itmData = explode(" ", $tmpArr[4]);
						$grdShow = " [".$itmData[2]."]";
						$itmData[2] = intval($itmData[2]);
						$TheCol = $PWraceCol[$itmData[2]];
						if (($ServerVer < 40)&& ($itmData[2] > 3)){
							$Show=false;
						}elseif (($ServerVer < 50)&&($itmData[2] > 4)){
							$Show=false;
						}elseif (($ServerVer < 80)&&($itmData[2] > 5)){
							$Show=false;
						}
					}
					if ($Show === True){
						if (($b==1)&&($a==1)&&($i==1)){
							echo"<option value='".$ItemMod[$i][$a][$b]."' selected> ".$ItmName.$grdShow." </option>";
						}else{
							echo"<option value='".$ItemMod[$i][$a][$b]."' style='background-color:".$TheCol.";'> ".$ItmName.$grdShow." </option>";
						}
					}
				}
				$icount++;
			}
			echo "</select>";
		}
		$tcount=$tcount+$icount;
		$statTxt=$statTxt."      ".$MMName[$i].": ".$icount." item\\n";
		$icount=0;
	} 
	$statTxt=$statTxt."  Total: ".$tcount." item loaded";
?>
</td></tr>
<tr><td>Item ID</td><td><input type="text" maxlength="6" id="Sel_Item_Id" value='4583' style='width: 40px;'><span id='Inp_Grade'>&nbsp;&nbsp;&nbsp;
Gr. <select id='Inp_Grade1' onChange = "ChangeGrade();">
	<option value="1" selected> 1 </option>
	<option value="2"> 2 </option>
	<option value="3"> 3 </option>
	<option value="4"> 4 </option>
	<option value="5"> 5 </option>
	<option value="6"> 6 </option>
	<option value="7"> 7 </option>
	<option value="8"> 8 </option>
	<option value="9"> 9 </option>
	<option value="10"> 10 </option>
	<option value="11"> 11 </option>
	<option value="12"> 12 </option>
	<option value="13"> 13 </option>
	<option value="14"> 14 </option>
	<option value="15"> 15 </option>
	<option value="16"> 16 </option>
	<option value="17"> 17 </option>
	<option value="18"> 18 </option>
	<option value="19"> 19 </option>
	<option value="20"> 20 </option>
	</select> &nbsp;&nbsp;&nbsp;<input type="text" maxlength="3" id="Inp_Grade2" value='1' style='width: 22px;text-align:center;'></span>
</td></tr>
<tr><td>Mask: </td><td>
<select id='Sel_Mask1' style='font-size:12px;width:80px;' onChange="ChangeMaskType();">
<option value="0">Can't Equip</option>
<option value="1" selected>Weapon</option>
<option value="16">Chest [Armor]</option>
<option value="64">Leg [Armor]</option>
<option value="128">Foot [Armor]</option>
<option value="256">Arm [Armor]</option>
<option value="2">Helm [Armor]</option>
<option value="8">Cloack [Armor]</option>
<option value="4">Necklace</option>
<option value="32">Belt</option>
<option value="1536">Ring</option>
<option value="262144">Heaven Book</option>
<option value="4096">Flyer Mount</option>
<option value="8192">Chest [Fashion]</option>
<option value="16384">Leg [Fashion]</option>
<option value="65536">Arm [Fashion]</option>
<option value="32768">Foot [Fashion]</option>
<option value="524288">Chat Smiley</option>
<option value="131072">Att/Def Hiero</option>
<option value="1048576">HP Charm</option>
<option value="2097152">MP Charm</option>
<option value="2048">Ammo</option>
<?php
	if ($ServerVer >= 37){		
		echo"<option value='1077936128'>Blessig box</option>";
		echo"<option value='8388608'>Elf</option>";
	}
	if ($ServerVer >= 39){	
		echo"<option value='33554432'>Hair [Fashion]</option>";
		echo"<option value='16777216'>Vendor Shop</option>";
	}
	if ($ServerVer >= 49){	
		echo"<option value='536870912'>Weapon [Fashion]</option>";
		echo"<option value='67108864'>Order</option>";
	}
	if ($ServerVer >=70){	
		echo"<option value='2147483585'>Card</option>"; 
		echo"<option value='402653184'>Mark of Might</option>"; 
	}
	
	if ($ServerVer >=80){	
		echo"<option value='-2147483584'>Sky Chart</option>"; 
	}
?>	

</select> <input type="text" maxlength="8" id="Sel_Mask2" value='1' style='width: 70px;font-size:10px;'></td></tr>
<tr><td>Proctype: </td><td>
<select id='Sel_ProcType1' style='font-size:12px;width:75px;' onChange="ChangeProcType();">
<option value="0">Free</option>
<option value="1">Revival Scroll</option>
<option value="8">Fashion, Flyer</option>
<option value="19">No drop, trade</option>
<option value="64">Bind Equip</option>
<option value="32791">Soulbound</option>
</select> <a href="javascript:void(0);" onclick="document.getElementById('ProcTypeDiv').style.display='block';"><button>[*]</button></a> <input type="text" maxlength="8" id="Sel_ProcType2" value='0' style='width: 45px;font-size:10px;'></td></tr>
<tr><td>Count: </td><td><input type="text" maxlength="5" id="Sel_Count" value='1' style='width: 50px;font-size:10px;' disabled> &nbsp;&nbsp;&nbsp;Max: &nbsp;&nbsp;<input type="text" maxlength="5" id="Sel_MaxCount" value='1' style='width: 50px;font-size:10px;' disabled></td></tr>
<tr><td>Guid1: </td><td><input type="text" maxlength="5" id="Sel_Guid1" value='0' style='width: 40px;font-size:10px;' disabled>&nbsp;&nbsp;&nbsp;&nbsp;Guid2: <input type="text" maxlength="5" id="Sel_Guid2" value='0' style='width: 40px;font-size:10px;' disabled></td></tr>
<tr><td>Expire: </td><td><input type="checkbox" id="Expire_Act" style='vertical-align: middle;'><input type="text" maxlength="5" id="Sel_Expire" value='0' style='width: 75px;font-size:10px; text-align:center;' disabled> <a href="javascript:void(0);" onclick="document.getElementById('ExpirationDiv').style.display='block';"><button>Set</button></a></td></tr>
</table><br>

<center> Class Mask </center>
<table border='0'  width='100%' style='font-size: 10px;font-family: arial;border-collapse: collapse;'>
<?php
	echo "<script> maxMask = ".$allClass.";</script>";
	$Class_Mask = $allClass;
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
	$Checked[0] = "";
	$Checked[1] = " checked";
	$ClassMaskSel = explode(" ", ClassMask2String ($Class_Mask));
	$raceCount = intval($maxClass/2);
	for ($i = 1; $i <= $raceCount; $i++) {
		$c1=$i*2-1;
		$c2=$i*2;
		echo"<tr><td><input type='checkbox' id='Class".$c1."' value='".$ClassMask[$c1]."' onClick='GenerateClassMask();' ".$Checked[$ClassMaskSel[($c1-1)]]."><b> ".$PWclass[$c1]." &nbsp;&nbsp;</b></td><td><input type='checkbox' id='Class".$c2."' value='".$ClassMask[$c2]."' onClick='GenerateClassMask();' ".$Checked[$ClassMaskSel[($c2-1)]]."><b> ".$PWclass[$c2]." &nbsp;&nbsp; </b></td></tr>";
	} 
	echo"<tr><td colspan='2' style='text-align: center;'>Class Mask: &nbsp;&nbsp;<input type='text' id='ClassMask' maxlength='5' value='".$Class_Mask."' style='width:55px;text-align:center;'></td></tr>";
?>
</table><br>

<div id="socketStonesDiv" style="display:inline;">
<center> Socket Stone Data </center>
<input type="radio" name="SelectedStone" id="SelStone1" checked onFocus="StoneSelection('1');"  style='vertical-align: middle;'>
<select id = "StoneType1" onChange="StoneSelection('1');" onFocus="StoneSelection('1');" style="width: 190px;">
<?php 
for ($i = 1; $i <= count($SoulStone[1]); $i++) {
		$tmpArr =  explode("#", $SoulStone[1][$i]);
		if ((intval($tmpArr[7]) > $ServerVer) === false){
			echo "<option value='".$SoulStone[1][$i]."'> ".$tmpArr[5]." gr. ".$tmpArr[6]." </option>";
		}
}
?>
</select><br>
<input type="radio" name="SelectedStone" id = "SelStone2" onFocus="StoneSelection('2');" style='vertical-align: middle;'> <select id = "StoneType2" onChange="StoneSelection('2');" onFocus="StoneSelection('2');" style="width: 190px;">
<?php 
for ($i = 1; $i <= count($SoulStone[2]); $i++) {
		$tmpArr =  explode("#", $SoulStone[2][$i]);
		if ((intval($tmpArr[7]) > $ServerVer) === false){
			echo "<option value='".$SoulStone[2][$i]."'> ".$tmpArr[5]." gr. ".$tmpArr[6]." </option>";
		}
}
?>
</select><br>
<input type="radio" name="SelectedStone" id = "SelStone3" onFocus="StoneSelection('3');" style='vertical-align: middle;'> <select id = "StoneType3" onChange="StoneSelection('3');" onFocus="StoneSelection('3');" style="width: 190px;">
<?php 
for ($i = 1; $i <= count($SoulStone[3]); $i++) {
		$tmpArr =  explode("#", $SoulStone[3][$i]);
		if ((intval($tmpArr[7]) > $ServerVer) === false){
			echo "<option value='".$SoulStone[3][$i]."'> ".$tmpArr[5]." gr. ".$tmpArr[6]." </option>";
		}
}
?>
</select><br>
<span style='font-face:arial; font-size:12px;'><a title='The stone id from elements.data'>Stone</a> <input type="text" maxlength="5" id="Inp_StoneId" value='0' style='width: 35px;'> <a title='Which addon, it is 1st column in elements.data'>Addon</a> <input type="text" maxlength="5" id="Inp_StoneAddon" value='0' style='width: 35px;'> <a title='How much bonus give this addon'>Nr</a> <input type="text" maxlength="5" id="Inp_StoneValue" value='0' style='width: 35px;'></span>
<br>
<span style='float:left;font-face:arial; font-size:12px;'>Bonus: <span id='SockAddTxt'> </span></span><a href="javascript:void(0);" style="float:right;" onClick="AddNewSckAddon();"><button>Insert this stone</button></a><br>
</div>
</div> 

<div id="WeaponInputDiv" style="display:block;">
<center><b><span id='Gear1_Name' style='font-size:16px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'>☆☆☆I-Beam Sledgehammer</span></b></center><br>
<table border='0'  width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;'>
	<tr><td>Level Req.: </td><td><input type="text" maxlength="3" id="Inp_W_LvReq" value='1' style='width: 45px; background-color:#ffd;' onKeyUp="CalcStatReq();"> </td></tr>
	<tr><td>Class Req.: </td><td><input type="text" maxlength="4" id="Inp_W_Class" value='<?php echo $Class_Mask; ?>' style='width: 55px;'> </td></tr>
	<tr><td>PDmg: </td><td><input type="text" maxlength="9" id="Inp_W_PDmg1" value='10' style='width: 70px; background-color:#ffd;'> - <input type="text" maxlength="9" id="Inp_W_PDmg2" value='20' style='width: 70px; background-color:#ffd;'></td></tr>
	<tr><td>MDmg: </td><td><input type="text" maxlength="9" id="Inp_W_MDmg1" value='0' style='width: 70px;'> - <input type="text" maxlength="9" id="Inp_W_MDmg2" value='0' style='width: 70px;'></td></tr>
	<tr><td>STR Req.: </td><td><input type="text" maxlength="4" id="Inp_W_STR" value='5' style='width: 50px; background-color:#ffd;'></td></tr>
	<tr><td>AGI Req.: </td><td><input type="text" maxlength="4" id="Inp_W_AGI" value='5' style='width: 50px; background-color:#ffd;'> </td></tr>
	<tr><td>INT Req.: </td><td><input type="text" maxlength="4" id="Inp_W_INT" value='0' style='width: 50px;'></td></tr>
	<tr><td>CON Req.: </td><td><input type="text" maxlength="4" id="Inp_W_CON" value='0' style='width: 50px;'> </td></tr>
	<tr><td>Duratibility: </td><td><input type="text" maxlength="4" id="Inp_W_CurDur" value='10' style='width: 45px; background-color:#ffd;'> - <input type="text" maxlength="4" id="Inp_W_MaxDur" value='20' style='width: 45px; background-color:#ffd;'></td></tr>
	<tr style='display:none;'><td>Item Type: </td><td> <select id='Inp_W_ItType' disabled><option value='2c00' selected>Weapon</option><option value='2400'>Armor & Acces.</option></select></td></tr>
	<tr style='display:none;'><td>Item Flag: </td><td> <select id='Inp_W_ItFlag'><option value='00'>00</option><option value='01'>01</option><option value='02'>02</option><option value='03' selected>03</option><option value='04'>04</option></select> </td></tr>
	<tr><td>WType: </td><td><select id='Inp_W_Ranged'><option value='0' selected>Melee [STR]</option><option value='1'>Ranged [AGI]</option><option value='2'>Melee [AGI]</option></select> </td></tr>
	<tr style='display:none;'><td>Type: </td><td>
		<select id='Inp_W_Type' disabled style='width: 190px;'>
			<option value='9' selected>Axe & Hammer</option>
			<option value='5'>Polearm & Spear</option>
			<option value='1'>Sword & Blade</option>
			<option value='182'>Glove & Claw</option>
			<option value='13'>Bow & Xbow & Slingshot</option>
			<option value='292'>Magic Instrument</option>
		<?php	
		if ($ServerVer >= 40){
			echo"
			<option value='23749'>Dagger</option>
			<option value='25333'>Sphere</option>";
		}
		if ($ServerVer >= 80){
			echo"
			<option value='44878'>Sabre</option>
			<option value='44967'>Schythe</option>";
		}
		?>
		</select> </td></tr>
	<tr><td>Ammo: </td><td><select id='Inp_W_Ammo'>
		<option value='0' selected>No ammo</option>
		<option value='8546'>Arrow</option>
		<option value='8547'>Bolt</option>
		<option value='8548'>Bullet</option>
		<option value='13700'>Snowball</option></select> </td></tr>
	<tr><td>ASpeed: </td><td><select id='Inp_W_AttRate1' style='font-size:12px;width:150px;' onChange="SCInp(1);">
		<option value='22' selected>0.91 Poleaxe & Hammer</option>
		<option value='24'>0.83 Dual Axe & Hammer</option>
		<option value='20'>1.00 Pike & Spear</option>
		<option value='22'>1.11 Sword & Blade</option>
		<option value='18'>0.91 Dual Sword & Blade</option>
		<option value='14'>1.43 Glove and Claw</option>
		<option value='30'>0.67 Bow</option>
		<option value='32'>0.62 Crossbow</option>
		<option value='28'>0.71 Slingshot</option>
		<option value='16'>1.25 Magic Sword, Wand</option>
		<option value='20'>1.00 Magic Staff</option>
		<?php
		if ($ServerVer >= 40){
			echo"
			<option value='16'>1.25 Dagger</option>
			<option value='22'>0.91 Sphere</option>";
		}
		if ($ServerVer >= 80){
			echo"
			<option value='18'>1.11 Sabre</option>
		    <option value='20'>1.00 Schythe</option>";
		} 
		?>
	</select> <input type="text" maxlength="2" id="Inp_W_AttRate2" value='22' style='width: 22px;'></td></tr>
	<tr><td>Range: </td><td><select id='Inp_W_Range1' style='font-size:12px;' onChange="SCInp(2);">
		<option value='3.5' selected>3.5 Axe & Hammer</option>
		<option value='5.0'>5 Pike & Spear</option>
		<option value='4.5'>4.5 Poleblade</option>
		<option value='3.0'>3 Sword and Blade</option>
		<option value='2.5'>2.5 Glove and Claw</option>
		<option value='20.0'>20 Bow, Xbox, Sling</option>
		<option value='3.0'>3 Magic Weapons</option>
		<?php
		if ($ServerVer >= 40){
			echo"
			<option value='2.5'>2.5 Dagger</option>
			<option value='3.0'>3 Sphere</option>";
		}
		if ($ServerVer >= 40){
		echo"
		<option value='3.0'>3 Sabre</option>
		<option value='3.0'>3 Schythe</option>";
		}
		?>
	</select> <input type="text" maxlength="2" id="Inp_W_Range2" value='3.5' style='width: 32px;'></td></tr>
	<tr><td>MinRange: </td><td><select id='Inp_W_MinRange1' style='font-size:12px;' onChange="SCInp(3);">
		<option value='0' selected>No Min. Range</option>
		<option value='5'>5 Bow</option>
		<option value='5.5'>5.5 Crossbow</option>
		<option value='4.5'>4.5 Slingshot</option>
	</select> <input type="text" maxlength="2" id="Inp_W_MinRange2" value='0' style='width: 32px;'></td></tr>
	<tr><td>Socket: </td><td> <select id='Inp_W_Socket'><option value='0' selected>No socket</option><option value='1'>1 socket</option><option value='2'>2 socket</option><option value='3'>3 socket</option><option value='4'>4 socket</option></select></td></tr>
	<tr><td colspan="2"><span id="SocketWSack"></span></td></tr>
	<tr><td>Addons: </td><td><select id='Inp_W_AddonType'  style='width: 190px;' onchange='CheckAddonType(this);'>
		<?php
		$bool = true;
		for( $i = 1; $i <= count($Addons) ; $i++ ){
			$tmpArr = explode ("#", $Addons[$i]);
			$pos = strpos($tmpArr[3],"W");
			if ($pos !== FALSE){
				if (intval($tmpArr[5]) <= $ServerVer){	
					if ($bool){
						$bool = false;
						echo "<option value='".$Addons[$i]."' selected>".$tmpArr[4]."</option>";
					}else{
						echo "<option value='".$Addons[$i]."'>".$tmpArr[4]."</option>";
					}
				}
			}
		}
		for( $i = 1; $i <= count($AddonsS) ; $i++ ) {
			$tmpArr = explode ("#", $AddonsS[$i]);
			if (intval($tmpArr[5]) <= $ServerVer){	
				echo "<option value='".$AddonsS[$i]."'>".$tmpArr[4]."</option>";
			}
		}		
		?>
		</select></td></tr><tr><td></td><td>Addon amount: <input type="text" maxlength="5" id="Inp_W_AddonAmount" value='1' style='width: 50px;'> <a href='javascript:void(0);' onclick='AddNewAddon();'><button> + </button></a></td></tr>
	<tr><td colspan ="2"><div id="AddonListDivW"></div></td></tr>
	<tr><td>Refine: </td><td> 
	<select id='Inp_W_RefLv'>
		<option value='0'>Not refined</option>
		<option value='1'>+1</option>
		<option value='2'>+2</option>
		<option value='3'>+3</option>
		<option value='4'>+4</option>
		<option value='5'>+5</option>
		<option value='6'>+6</option>
		<option value='7'>+7</option>
		<option value='8'>+8</option>
		<option value='9'>+9</option>
		<option value='10'>+10</option>
		<option value='11'>+11</option>
		<option value='12'>+12</option>
	</select></td></tr>
	<tr><td>Crafter: </td><td><input type="text" maxlength="12" id="Inp_W_Crafter"  style='width: 190px;'> </td></tr>
</table><br><br>
<center><a title="Auto subtype detection"><input type="checkbox" id="Inp_W_AutoOctet" style='vertical-align: middle;' checked></a> <input type="text" id="Inp_W_NewOctet" value='' style='width: 120px;'> <a href='javascript:void(0);' onclick="LoadOctet(1);"><button> Load Octet </button></a></center>
</div>


<div id="ArmorInputDiv" style="display:none;">
<center><b><span id='Gear2_Name' style='font-size:16px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'>☆☆☆Blessed Chain Hauberk</span></b></center><br>
<table border='0'  width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;'>
	<tr><td>Level Req.: </td><td><input type="text" maxlength="3" id="Inp_A_LvReq" value='1' style='width: 45px; background-color:#ffd;' onKeyUp="CalcStatReq();"> </td></tr>
	<tr><td>Class Req.: </td><td><input type="text" maxlength="4" id="Inp_A_Class" value='<?php echo $Class_Mask; ?>' style='width: 55px;'> </td></tr>
	<tr><td>HP: </td><td><input type="text" maxlength="9" id="Inp_A_HP" value='0' style='width: 70px;'></td></tr>
	<tr><td>MP: </td><td><input type="text" maxlength="9" id="Inp_A_MP" value='0' style='width: 70px;'></td></tr>
	<tr><td>Dodge: </td><td><input type="text" maxlength="6" id="Inp_A_Dodge" value='0' style='width: 50px;'></td></tr>
	<tr><td>Phy. Def: </td><td><input type="text" maxlength="6" id="Inp_A_PDef" value='6' style='width: 50px; background-color: #ffd;'></td></tr>
	<tr><td>Metal Def: </td><td><input type="text" maxlength="6" id="Inp_A_Metal" value='5' style='width: 50px; background-color: #ffd;'></td></tr>
	<tr><td>Wood Def: </td><td><input type="text" maxlength="6" id="Inp_A_Wood" value='5' style='width: 50px; background-color: #ffd;'></td></tr>
	<tr><td>Water Def: </td><td><input type="text" maxlength="6" id="Inp_A_Water" value='5' style='width: 50px; background-color: #ffd;'></td></tr>
	<tr><td>Fire Def: </td><td><input type="text" maxlength="6" id="Inp_A_Fire" value='5' style='width: 50px; background-color: #ffd;'></td></tr>
	<tr><td>Earth Def: </td><td><input type="text" maxlength="6" id="Inp_A_Earth" value='5' style='width: 50px; background-color: #ffd;'></td></tr>
	<tr><td>STR Req.: </td><td><input type="text" maxlength="4" id="Inp_A_STR" value='5' style='width: 50px;background-color:#ffd;'></td></tr>
	<tr><td>AGI Req.: </td><td><input type="text" maxlength="4" id="Inp_A_AGI" value='5' style='width: 50px;'> </td></tr>
	<tr><td>INT Req.: </td><td><input type="text" maxlength="4" id="Inp_A_INT" value='0' style='width: 50px;'></td></tr>
	<tr><td>CON Req.: </td><td><input type="text" maxlength="4" id="Inp_A_CON" value='0' style='width: 50px;'> </td></tr>
	<tr><td>Duratibility: </td><td><input type="text" maxlength="4" id="Inp_A_CurDur" value='42' style='width: 45px;background-color:#ffd;'> - <input type="text" maxlength="4" id="Inp_A_MaxDur" value='42' style='width: 45px;background-color:#ffd;'></td></tr>
	<tr style='display:none;'><td>Item Type: </td><td> <select id='Inp_A_ItType' disabled><option value='2c00'>Weapon</option><option value='2400' selected>Armor & Acces.</option></select></td></tr>
	<tr style='display:none;'><td>Item Flag: </td><td> <select id='Inp_A_ItFlag'><option value='00'>00</option><option value='01'>01</option><option value='02'>02</option><option value='03' selected>03</option><option value='04'>04</option></select> </td></tr>
	<tr><td>Socket: </td><td> <select id='Inp_A_Socket'><option value='0' selected>No socket</option><option value='1'>1 socket</option><option value='2'>2 socket</option><option value='3'>3 socket</option><option value='4'>4 socket</option></select></td></tr>
	<tr><td colspan="2"><span id="SocketASack"></span></td></tr>
	<tr><td>Addons: </td><td><select id='Inp_A_AddonType' onchange='CheckAddonType(this);'>
	<?php
		$bool = true;
		for( $i = 1; $i <= count($Addons) ; $i++ ){
			$tmpArr = explode ("#", $Addons[$i]);
			$pos = strpos($tmpArr[3],"A");
			if ($pos !== FALSE){
				if (intval($tmpArr[5]) <= $ServerVer){	
					if ($bool){
						$bool = false;
						echo "<option value='".$Addons[$i]."' selected>".$tmpArr[4]."</option>";
					}else{
						echo "<option value='".$Addons[$i]."'>".$tmpArr[4]."</option>";
					}
				}
			}
		}
	?>
	</select></td></tr><tr><td></td><td>Addon amount: <input type="text" maxlength="5" id="Inp_A_AddonAmount" value='1' style='width: 50px;'> <a href='javascript:void(0);' onclick='AddNewAddon();'><button> + </button></a></td></tr>
	<tr><td colspan ="2"><div id="AddonListDivA"></div></td></tr>
	<tr><td>Refine: </td><td> 
	<select id='Inp_A_RefLv'>
		<option value='0' selected>Not refined</option>
		<option value='1'>+1</option>
		<option value='2'>+2</option>
		<option value='3'>+3</option>
		<option value='4'>+4</option>
		<option value='5'>+5</option>
		<option value='6'>+6</option>
		<option value='7'>+7</option>
		<option value='8'>+8</option>
		<option value='9'>+9</option>
		<option value='10'>+10</option>
		<option value='11'>+11</option>
		<option value='12'>+12</option>
	</select></td></tr>
	<tr><td>Crafter: </td><td><input type="text" maxlength="12" id="Inp_A_Crafter"  style='width: 190px;'> </td></tr>
</table>
<br><br>
<center><a title="Auto subtype detection"><input type="checkbox" id="Inp_A_AutoOctet" style='vertical-align: middle;' checked></a> <input type="text" id="Inp_A_NewOctet" value='' style='width: 120px;'> <a href='javascript:void(0);' onclick="LoadOctet(2);"><button> Load Octet </button></a></center>
</div>

<div id="JewelInputDiv" style="display:none;">
<center><b><span id='Gear3_Name' style='font-size:16px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'>☆☆☆Beast Bone Necklace</span></b></center><br>
<table border='0'  width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;'>
	<tr><td>Level Req.: </td><td><input type="text" maxlength="3" id="Inp_J_LvReq" value='1' style='width: 45px; background-color:#ffd;'> </td></tr>
	<tr><td>Class Req.: </td><td><input type="text" maxlength="4" id="Inp_J_Class" value='<?php echo $Class_Mask; ?>' style='width: 55px;'> </td></tr>
	<tr><td>PAttack: </td><td><input type="text" maxlength="9" id="Inp_J_PAtt" value='0' style='width: 70px;'></td></tr>
	<tr><td>MAttack: </td><td><input type="text" maxlength="9" id="Inp_J_MAtt" value='0' style='width: 70px;'></td></tr>
	<tr><td>Dodge: </td><td><input type="text" maxlength="6" id="Inp_J_Dodge" value='0' style='width: 50px;'></td></tr>
	<tr><td>Physical Def: </td><td><input type="text" maxlength="6" id="Inp_J_PDef" value='6' style='width: 50px; background-color:#ffd;'></td></tr>
	<tr><td>Metal Def: </td><td><input type="text" maxlength="6" id="Inp_J_Metal" value='0' style='width: 50px;'></td></tr>
	<tr><td>Wood Def: </td><td><input type="text" maxlength="6" id="Inp_J_Wood" value='0' style='width: 50px;'></td></tr>
	<tr><td>Water Def: </td><td><input type="text" maxlength="6" id="Inp_J_Water" value='0' style='width: 50px;'></td></tr>
	<tr><td>Fire Def: </td><td><input type="text" maxlength="6" id="Inp_J_Fire" value='0' style='width: 50px;'></td></tr>
	<tr><td>Earth Def: </td><td><input type="text" maxlength="6" id="Inp_J_Earth" value='0' style='width: 50px;'></td></tr>
	<tr><td>STR Req.: </td><td><input type="text" maxlength="4" id="Inp_J_STR" value='0' style='width: 50px;'></td></tr>
	<tr><td>AGI Req.: </td><td><input type="text" maxlength="4" id="Inp_J_AGI" value='0' style='width: 50px;'> </td></tr>
	<tr><td>INT Req.: </td><td><input type="text" maxlength="4" id="Inp_J_INT" value='0' style='width: 50px;'></td></tr>
	<tr><td>CON Req.: </td><td><input type="text" maxlength="4" id="Inp_J_CON" value='0' style='width: 50px;'> </td></tr>
	<tr><td>Duratibility: </td><td><input type="text" maxlength="4" id="Inp_J_CurDur" value='42' style='width: 45px;background-color:#ffd;'> - <input type="text" maxlength="4" id="Inp_J_MaxDur" value='42' style='width: 45px;background-color:#ffd;'></td></tr>
	<tr style='display:none;'><td>Item Type: </td><td> <select id='Inp_J_ItType' disabled><option value='2c00'>Weapon</option><option value='2400' selected>Armor & Acces.</option></select></td></tr>
	<tr style='display:none;'><td>Item Flag: </td><td> <select id='Inp_J_ItFlag'><option value='00'>00</option><option value='01'>01</option><option value='02'>02</option><option value='03' selected>03</option><option value='04'>04</option></select> </td></tr>
	<tr><td>Addons: </td><td><select id='Inp_J_AddonType' onchange='CheckAddonType(this);'>
	<?php
		$bool = true;
		for( $i = 1; $i <= count($Addons) ; $i++ ){
			$tmpArr = explode ("#", $Addons[$i]);
			$pos = strpos($tmpArr[3],"J");
			if ($pos !== FALSE){
				if (intval($tmpArr[5]) <= $ServerVer){	
					if ($bool){
						$bool = false;
						echo "<option value='".$Addons[$i]."' selected>".$tmpArr[4]."</option>";
					}else{
						echo "<option value='".$Addons[$i]."'>".$tmpArr[4]."</option>";
					}
				}
			}
		}
	?>
	</select></td></tr><tr><td></td><td>Addon amount: <input type="text" maxlength="5" id="Inp_J_AddonAmount" value='1' style='width: 50px;'> <a href='javascript:void(0);' onclick='AddNewAddon();'><button> + </button></a></td></tr>
	<tr><td colspan ="2"><div id="AddonListDivJ"></div></td></tr>
	<tr><td>Refine: </td><td> 
	<select id='Inp_J_RefLv'>
		<option value='0' selected>Not refined</option>
		<option value='1'>+1</option>
		<option value='2'>+2</option>
		<option value='3'>+3</option>
		<option value='4'>+4</option>
		<option value='5'>+5</option>
		<option value='6'>+6</option>
		<option value='7'>+7</option>
		<option value='8'>+8</option>
		<option value='9'>+9</option>
		<option value='10'>+10</option>
		<option value='11'>+11</option>
		<option value='12'>+12</option>
	</select></td></tr>
	<tr><td>Crafter: </td><td><input type="text" maxlength="12" id="Inp_J_Crafter"  style='width: 190px;'> </td></tr>
</table>
<br><br>
<center><a title="Auto subtype detection"><input type="checkbox" id="Inp_J_AutoOctet" style='vertical-align: middle;' checked></a> <input type="text" id="Inp_J_NewOctet" value='' style='width: 120px;'> <a href='javascript:void(0);' onclick="LoadOctet(3);"><button> Load Octet </button></a></center>
</div>

<div id="FlyerInputDiv" style="display:none;">
<center><b><span id='Flyer_Name' style='font-size:16px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'>Gliding Heavens Sword</span></b></center><br>
<div id='OctDesc1' style='color: #f0f;font-size:14px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'></div><br>
<table border='0'  width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;'>
	<tr><td>Req. Level: </td><td><input type="text" maxlength="3" id="Inp_F_ReqLv" value='1' style='width: 35px;text-align:center;'> </td></tr>
	<tr><td>Race: </td><td><select id="Inp_F_Race">
	<?php
	echo "<option value='$allClass' selected> All class </option>";
	
	?>
	<option value="3"> Human </option>
	<option value="24"> Beastkind </option>
	<option value="192"> Wing Elf </option>
	<?	
	if ($ServerVer >= 40){	
		echo"<option value='36'> Tideborn </option>";
	}
	if ($ServerVer >= 50){	
		echo"<option value='768'> Earthguard </option>";
	}
	if ($ServerVer >= 80){	
		echo"<option value='3072'> Nightshade </option>";
	}
	?>	
	
	</select></td></tr>
	<tr><td>Grade: </td><td><input type="text" maxlength="2" id="Inp_F_Grade" value='1' style='width: 25px;text-align:center;'> </td></tr>
	<tr><td>Fuel: </td><td><input type="text" maxlength="4" id="Inp_F_Fuel1" value='450' style='width: 45px;text-align:center;'> / <input type="text" maxlength="4" id="Inp_F_Fuel2" value='900' style='width: 45px;text-align:center;'></td></tr>
	<tr><td>Speed: </td><td><input type="text" maxlength="4" id="Inp_F_Speed1" value='1' style='width: 45px;text-align:center;background-color: #ffd;'> / <input type="text" maxlength="4" id="Inp_F_Speed2" value='3' style='width: 45px;text-align:center;background-color: #ffd;'></td></tr>
</table>
<br><br>
<center> <input type="text" id="Inp_O1_NewOctet" value='' style='width: 120px;'> <a href='javascript:void(0);' onclick="LoadOctet(401);"><button> Load Octet </button></a></center>
</div>

<div id="PetEggInputDiv" style="display:none;">
<center><b><span id='Pet_Name' style='font-size:16px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'>Whitewind</span></b></center><br>
<div id='OctDesc2' style='color: #f0f;font-size:14px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'></div><br>
<table border='0'  width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;'>
	<tr><td>Req. Level: </td><td><input type="text" maxlength="3" id="Inp_P_ReqLv" value='1' style='width: 35px;text-align:center;'> </td></tr>
	<tr><td>Pet Type: </td><td><select id="Inp_P_PetType1" onChange="document.getElementById('Inp_P_PetType2').value=document.getElementById('Inp_P_PetType1').value;" disabled>
		<option value="0"> Mount </option>
		<option value="1"> Battle Pet </option>
		<option value="2"> Baby Pet </option>
	</select> <input type="text" maxlength="2" id="Inp_P_PetType2" value='0' style='width: 25px;text-align:center;'>
	<tr><td>Class: </td><td><select id="Inp_P_Class1" onChange="document.getElementById('Inp_P_Class2').value=document.getElementById('Inp_P_Class1').value;">
	<?php
	echo "<option value='$allClass' selected> All class </option>";
	
	?>
	<option value="8"> Werefox </option>
	</select> <input type="text" maxlength="4" id="Inp_P_Class2" value='<?php echo $allClass; ?>' style='width: 55px;text-align:center;'></td></tr>
	<tr><td>Egg id: </td><td><input type="text" maxlength="5" id="Inp_P_EggId" value='1' style='width: 50px;text-align:center;background-color: #ffd;'> </td></tr>
	<tr><td>Pet id: </td><td><input type="text" maxlength="5" id="Inp_P_PetId" value='1' style='width: 50px;text-align:center;background-color: #ffd;'></td></tr>
	<tr><td>Loyality: </td><td><input type="text" maxlength="3" id="Inp_P_Loyality" value='1' style='width: 45px;text-align:center;'> </td></tr>
	<tr><td>Level: </td><td><input type="text" maxlength="3" id="Inp_P_Lv" value='1' style='width: 45px;text-align:center;'></td></tr>
	<tr><td>Exp: </td><td><input type="text" maxlength="4" id="Inp_P_Exp" value='0' style='width: 45px;text-align:center;background-color: #ffd;'></td></tr>

	<tr><td colspan="2"><div id="Pet_Skill_Div" style="display: none;">Skill:&nbsp;&nbsp;&nbsp;&nbsp;
	<select id="Inp_P_SkillData" style = "width: 110px;">
	<?php
		for( $i = 1; $i <= count($PetSkill); $i++ ){
			$PetSkill[$i] = str_replace('\'', '&rsquo;', $PetSkill[$i]);
			$tmpArr =  explode("#", $PetSkill[$i]);
			$SkillName = $tmpArr[1];
			$ItmCol = $tmpArr[3];
			$MaxLv = $tmpArr[2];
			if ($i == 1){
				echo "<option value='".$PetSkill[$i]."' selected style='background-color:".$ItemColor[$ItmCol].";'>".$SkillName." [Max Lv. ".$MaxLv."]</option>";
			}else{
				echo "<option value='".$PetSkill[$i]."' style='background-color:".$ItemColor[$ItmCol].";'>".$SkillName." [Max Lv. ".$MaxLv."]</option>";
			}
		}
	?>	
	</select> <input type="text" maxlength="1" id="Inp_P_LSkillLv" value='1' style='width: 45px;text-align:center;'> 
	<a href='javascript:void(0);' onclick='AddNewPetSkill();'><button>Learn</button></a><span style='position:relative;left:50px;' id="LearnedPetSkills"></span>
	</div>
	</td></tr>
</table>
<br><br>
<center><input type="text" id="Inp_O2_NewOctet" value='' style='width: 120px;'> <a href='javascript:void(0);' onclick="LoadOctet(402);"><button> Load Octet </button></a></center>
</div>

<div id="BBoxInputDiv" style="display:none;">
<center><b><span id='BBox_Name' style='font-size:16px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'>Heavenly Blessing</span></b></center><br>
<div id='OctDesc3' style='color: #f0f;font-size:14px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'></div><br>
<table border='0'  width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;'>
	<tr><td>Level Req.: </td><td><input type="text" maxlength="3" id="Inp_B_LvReq" value='1' style='width: 45px; background-color: #ffd;'> </td></tr>
	<tr><td>Class Req.: </td><td><input type="text" maxlength="4" id="Inp_B_Class" value='<?php echo $Class_Mask; ?>' style='width: 55px; background-color: #ffd;'> </td></tr>
	<tr><td>HP: </td><td><input type="text" maxlength="9" id="Inp_B_HP" value='0' style='width: 70px;'></td></tr>
	<tr><td>MP: </td><td><input type="text" maxlength="9" id="Inp_B_MP" value='0' style='width: 70px;'></td></tr>
	<tr><td>Dodge: </td><td><input type="text" maxlength="6" id="Inp_B_Dodge" value='0' style='width: 50px;'></td></tr>
	<tr><td>Physical Def: </td><td><input type="text" maxlength="6" id="Inp_B_PDef" value='0' style='width: 50px;'></td></tr>
	<tr><td>Metal Def: </td><td><input type="text" maxlength="6" id="Inp_B_Metal" value='0' style='width: 50px;'></td></tr>
	<tr><td>Wood Def: </td><td><input type="text" maxlength="6" id="Inp_B_Wood" value='0' style='width: 50px;'></td></tr>
	<tr><td>Water Def: </td><td><input type="text" maxlength="6" id="Inp_B_Water" value='0' style='width: 50px;'></td></tr>
	<tr><td>Fire Def: </td><td><input type="text" maxlength="6" id="Inp_B_Fire" value='0' style='width: 50px;'></td></tr>
	<tr><td>Earth Def: </td><td><input type="text" maxlength="6" id="Inp_B_Earth" value='0' style='width: 50px;'></td></tr>
	<tr><td>STR Req.: </td><td><input type="text" maxlength="4" id="Inp_B_STR" value='0' style='width: 50px;'></td></tr>
	<tr><td>AGI Req.: </td><td><input type="text" maxlength="4" id="Inp_B_AGI" value='0' style='width: 50px;'> </td></tr>
	<tr><td>INT Req.: </td><td><input type="text" maxlength="4" id="Inp_B_INT" value='0' style='width: 50px;'></td></tr>
	<tr><td>CON Req.: </td><td><input type="text" maxlength="4" id="Inp_B_CON" value='0' style='width: 50px;'> </td></tr>
	<tr><td>Duratibility: </td><td><input type="text" maxlength="4" id="Inp_B_CurDur" value='42' style='width: 45px;;'> - <input type="text" maxlength="4" id="Inp_B_MaxDur" value='42' style='width: 45px;'></td></tr>
	<tr style='display:none;'><td>Item Type: </td><td> <select id='Inp_B_ItType' disabled><option value='2c00'>Weapon</option><option value='2400' selected>Armor & Acces.</option></select></td></tr>
	<tr style='display:none;'><td>Item Flag: </td><td> <select id='Inp_B_ItFlag'><option value='00'>00</option><option value='01'>01</option><option value='02'>02</option><option value='03' selected>03</option><option value='04'>04</option></select> </td></tr>
	<tr><td>Socket: </td><td> <select id='Inp_B_Socket'><option value='0' selected>No socket</option><option value='1'>1 socket</option><option value='2'>2 socket</option><option value='3'>3 socket</option><option value='4'>4 socket</option></select></td></tr>
	<tr><td colspan="2"><span id="SocketBSack"></span></td></tr>
	<tr><td>Addons: </td><td><select id='Inp_B_AddonType' onChange="CheckAddonType(this);">
	<?php
		$bool = true;
		for( $i = 1; $i <= count($Addons) ; $i++ ){
			$tmpArr = explode ("#", $Addons[$i]);
			$pos = strpos($tmpArr[3],"B");
			if ($pos !== FALSE){
				if (intval($tmpArr[5]) <= $ServerVer){	
					if ($bool){
						$bool = false;
						echo "<option value='".$Addons[$i]."' selected>".$tmpArr[4]."</option>";
					}else{
						echo "<option value='".$Addons[$i]."'>".$tmpArr[4]."</option>";
					}
				}
			}
		}
	?>
	</select></td></tr>
	<tr><td></td><td>Addon amount: <input type="text" maxlength="5" id="Inp_B_AddonAmount" value='1' style='width: 50px; background-color: #ffd;'> <a href='javascript:void(0);' onclick='AddNewAddon();'><button>+</button></a></td></tr>
	<tr><td colspan ="2"><div id="AddonListDivB"></div></td></tr>
	<tr><td colspan="2">Refine: 
	<select id='Inp_B_RefType' style='width: 60px;'>
		<option value='11'>Pattack (Axe)</option>
		<option value='12'>Pattack (Fist)</option>
		<option value='13'>Pattack (Bow)</option>
		<option value='14'>Mattack (Any)</option>
		<option value='21'>HP (Heavy)</option>
		<option value='22'>HP (Light)</option>
		<option value='23'>HP (Magic)</option>
		<option value='31'>Pdef</option>
		<option value='32'>Mdef</option>
		<option value='33'>Dodge</option>

	</select> Gr.
	<select id='Inp_B_RefGr' style='width: 45px;'>
		<?php
			for( $i = 1; $i <= 20; $i++ ){
				echo "<option value='".$i."'>+".$i."</option>";
			}
		?>
	</select>
	<select id='Inp_B_RefLv' style='width: 70px;'>
		<option value='0'>Not ref.</option>
		<?php
			for( $i = 1; $i <= 12; $i++ ){
				echo "<option value='".$i."'>+".$i."</option>";
			}
		?>
	</select></td></tr>
	<tr><td>Crafter: </td><td><input type="text" maxlength="12" id="Inp_B_Crafter"> </td></tr>
</table>
<br><br>
<center> <input type="text" id="Inp_O3_NewOctet" value='' style='width: 120px;'> <a href='javascript:void(0);' onclick="LoadOctet(403);"><button> Load Octet </button></a></center>
</div>

<div id="ElfInputDiv" style="display:none;">
<center><b><span id='Elf_Name' style='font-size:16px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'>Infliction</span></b></center><br>
<div id='OctDesc4' style='color: #f0f;font-size:14px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'></div><br>
<table border='0'  width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;'>
	<tr><td colspan="2">Level: </td><td colspan="2"><input type="text" maxlength="3" id="Inp_E_Lv" value='1' style='width: 45px;' onKeyUp="ChangeElfLevel();" onChange="ChangeElfLevel();"> </td></tr>
	<tr><td colspan="2">Current Exp: </td><td colspan="2"><input type="text" maxlength="3" id="Inp_E_Exp" value='0' style='width: 45px;'> </td></tr>
	<tr><td colspan="2">Stamina: </td><td colspan="2"><input type="text" maxlength="7" id="Inp_E_Stamina" value='20000' style='width: 75px;'></td></tr>
	<tr><td>STR: </td><td><input type="text" maxlength="4" id="Inp_E_STR" value='0' style='width: 40px;' onChange="ChangeElfPointTalent();"> +<span id="E_StartStr">15</span></td><td>AGI: </td><td><input type="text" maxlength="4" id="Inp_E_AGI" value='0' style='width: 40px;' onChange="ChangeElfPointTalent();"> +<span id="E_StartAgi">5</span></td></tr>
	<tr><td>INT: </td><td><input type="text" maxlength="4" id="Inp_E_INT" value='0' style='width: 40px;' onChange="ChangeElfPointTalent();"> +<span id="E_StartInt">5</span></td><td>CON: </td><td><input type="text" maxlength="4" id="Inp_E_CON" value='0' style='width: 40px;' onChange="ChangeElfPointTalent();"> +<span id="E_StartCon">3</span></td></tr>
	<tr><td>Points: </td><td> <span id="RemainElfPoint">0</span> [<span id="TotalElfPoint">0</span>] </td><td> Luck: </td><td><input type="text" maxlength="3" id="Inp_E_LuckPoint" value='0' style='width: 25px;' onKeyUp="ChangeElfPointTalent();" onChange="ChangeElfPointTalent();"> / <span id="MaxElfPoint"> 0 </span></td></tr>
	<tr><td>Metal: </td><td><input type="text" maxlength="4" id="Inp_E_Metal" value='0' style='width: 45px;' onKeyUp="ChangeElfPointTalent();" onChange="ChangeElfPointTalent();"></td><td>Wood:</td><td><input type="text" maxlength="4" id="Inp_E_Wood" value='0' style='width: 45px;' onKeyUp="ChangeElfPointTalent();" onChange="ChangeElfPointTalent();"></td></tr>
	<tr><td>Water: </td><td><input type="text" maxlength="4" id="Inp_E_Water" value='0' style='width: 45px;' onKeyUp="ChangeElfPointTalent();" onChange="ChangeElfPointTalent();"></td><td>Fire:</td><td><input type="text" maxlength="4" id="Inp_E_Fire" value='0' style='width: 45px;' onKeyUp="ChangeElfPointTalent();" onChange="ChangeElfPointTalent();"></td></tr>
	<tr><td>Earth: </td><td><input type="text" maxlength="4" id="Inp_E_Earth" value='0' style='width: 45px;' onKeyUp="ChangeElfPointTalent();" onChange="ChangeElfPointTalent();"></td><td>Free:</td><td> <span id="RemainElfTalent">1</span> [<span id="Inp_E_Talent">1</span>]</td></tr>
	<tr><td colspan="2">Trade Stat: </td><td colspan="2">
	<select id= "Inp_E_Trade">
		<option value="00000000" selected> Bounded </option>
		<option value="c7a24c4f"> Tradeable </option>
	</select></td></tr>
	<tr><td>Gears:</td><td colspan="3" style='text-align:center;'>
	<a href="javascript:void(0);" title="Change Gear 1" onclick="ChangeElfGearIcon(1);"><img src="../images/icons/slot.gif" id="EGearImg1" width="32" height="32"></a>
	<a href="javascript:void(0);" title="Change Gear 2" onclick="ChangeElfGearIcon(2);"><img src="../images/icons/slot.gif" id="EGearImg2" width="32" height="32"></a>
	<a href="javascript:void(0);" title="Change Gear 3" onclick="ChangeElfGearIcon(3);"><img src="../images/icons/slot.gif" id="EGearImg3" width="32" height="32"></a> 
	<a href="javascript:void(0);" title="Change Gear 4" onclick="ChangeElfGearIcon(4);"><img src="../images/icons/slot.gif" id="EGearImg4" width="32" height="32"></a>
	</td></tr>
	<tr><td>Gear <span id='elfGearId'>1</span>: </td><td colspan='3'>
<?php
		$showg="display:inline;";
		$lastC=1;
		$inc=0;
		$cat=0;
		echo "<div style='display:inline;'><select id='Inp_E_Gear1' style='width:120px;".$showg."' onChange='ChangeElfGear(1);'><option value='0'> Empty Slot</option>";
		for( $i = 1; $i <= count($ItemMod[5][14]) ; $i++ ){
			$tmpArr = explode ("#", $ItemMod[5][14][$i]);
			$cat = intval($tmpArr[2]);
			if ($cat != $lastC){
				if ($cat > 1){
					//echo "<script>alert('".$lastC."/".$cat."-".$tmpArr[1]."');</script>";
					echo "</select> <input type='text' id='Inp_E_GearId".$lastC."' maxlength='5' style='width: 50px;".$showg."' value='0'><input type='text' id='Inp_E_GearName".$lastC."' maxlength='5' style='width: 0px; display:none;' value=''>";
					$showg="display:none;";
					if ($cat < 5){
						echo "<select id='Inp_E_Gear".$cat."' style='width:120px;".$showg."' onChange='ChangeElfGear(".$cat.");'><option value='0'> Empty Slot</option>
						<option value='".$tmpArr[1]."#".$tmpArr[5]."#".$tmpArr[0]."#".$tmpArr[4]."'>".$tmpArr[0]." [lv. ".$tmpArr[5]."]</option>
						";
					}
				}
				$lastC = $cat;
			}else{
				echo "<option value='".$tmpArr[1]."#".$tmpArr[5]."#".$tmpArr[0]."#".$tmpArr[4]."'>".$tmpArr[0]." [lv. ".$tmpArr[5]."]</option>";
			}
		}
		echo "</select> <input type='text' id='Inp_E_GearId4' maxlength='5' style='width: 50px;".$showg."' value='0'><input type='text' id='Inp_E_GearName4' maxlength='5' style='width: 0px; display:none;' value=''>
		</div>
		";
?>
	</td></tr>
	<tr><td colspan="4">Skills [<span id="E_LSkills">0</span>/<span id="E_MaxSkill">4</span>]:&nbsp;&nbsp;&nbsp; <select id='Inp_E_SkillData' style = "width: 110px;">	<?php
		$bool = true;
		for( $i = 1; $i <= count($ElfSkills) ; $i++ ){
			$tmpArr = explode ("#", $ElfSkills[$i]);
			$skillName = $tmpArr[3];
			$skillLv = $tmpArr[1];
			if ($skillLv < 1){
				$skillColor = "#eeeeee";
			}elseif ($skillLv == 15){
				$skillColor = "#ffdd77";
			}else{
				$skillColor = "#aaaaff";
			}

			if ($bool){
				$bool = false;
				echo "<option value='".$ElfSkills[$i]."' selected style='background-color: ".$skillColor.";' title=' d'>".$skillName." [".$skillLv."]</option>";
			}else{
				echo "<option value='".$ElfSkills[$i]."' style='background-color: ".$skillColor.";' title=' d'>".$skillName." [".$skillLv."]</option>";
			}
		}
	?>
	</select><select id="Inp_E_LSkillLv" style="width:40px;">
	<?php
		for( $i = 1; $i <= 10; $i++ ){	
			echo "<option value='".$i."'>".$i."</option>";
		}
	?>
	</select><a href='javascript:void(0);' onclick='AddNewElfSkill();'><button style="width:40px;">Learn</button></a><span style='position:relative;left:30px;' id="LearnedElfSkills"></span></td></tr>
	

	<tr><td>Refine: </td><td> 
	<select id='Inp_E_RefLv'>
		<option value='0'>Not refined</option>
		<?php
			for( $i = 1; $i <= 36; $i++ ){
				echo "<option value='".$i."'>+".$i."</option>";
			}
		?>
	</select></td></tr>
</table>
<br><br>
<center> <input type="text" id="Inp_O4_NewOctet" value='' style='width: 120px;'> <a href='javascript:void(0);' onclick="LoadOctet(404);"><button> Load Octet </button></a></center>
</div>						    

<div id="HieroInputDiv" style="display:none;">
<center><b><span id='Hiero_Name' style='font-size:16px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'>Bronze HP Hiero</span></b></center><br>
<div id='OctDesc5' style='color: #f0f;font-size:14px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'></div><br>
<span  style='font-size: 12px;font-family: arial;'>
<span style='font-size:16px; color: #003;'><input type="radio" name="HieroOption" id="RadHiero1" checked onChange="SearchHiero('0');"> <b><span id='HieroTypeSpan1'> HP </span> Hiero </b></span><br>
<input type="text" maxlength="9" id="Inp_H_Amount" value='100000' style='width: 75px;text-align:center;'> / <span id='HieroMaxSpan'>100000</span>, when <span id='HieroTypeSpan2'> HP </span> lower than <input type="text" maxlength="4" id="Inp_H_Act" value='50' style='width: 45px;text-align:center;'>%, <span id="HieroCooldown">10</span> sec cooldown<br><br>
<br><br>


<span style='font-size:16px;'><input type="radio" name="HieroOption" id="RadHiero2" onChange="SearchHiero('1');"> <b> Attack Hiero </b></span><br>
Type: <select id="Inp_H_AType">
	<option value="0">Physical Damage</option>
	<option value="1">Magic Damage</option>
</select> Damage: <input type="text" maxlength="6" id="Inp_H_Damage" value='5' style='width: 75px;text-align:center;' disabled><br>
Weapon Grade Range: <input type="text" maxlength="6" id="Inp_H_DMinGrd" value='1' style='width: 35px;text-align:center;'> - <input type="text" maxlength="6" id="Inp_H_DMaxGrd" value='15' style='width: 35px;text-align:center;'>
<br> Amount: <input type="text" maxlength="6" id="Inp_H_AStack" value='1' style='width: 50px;text-align:center;' onKeyUp="if (document.getElementById('RadHiero2').checked === true){document.getElementById('Sel_Count').value=document.getElementById('Inp_H_AStack').value;}"><br><br>
<br><br>


<span style='font-size:16px;'><input type="radio" name="HieroOption" id="RadHiero3" onChange="SearchHiero('2');"> <b> Defence Hiero </b></span><br>
Type: <select id="Inp_H_DType">
	<option value="0">Physical Damage</option>
	<option value="1">Magic Damage</option>
</select> Reduce damage by <input type="text" maxlength="6" id="Inp_H_Defence" value='33' style='width: 40px;text-align:center;'>%
<br>Level Range: <input type="text" maxlength="6" id="Inp_H_DMinLv" value='1' style='width: 35px;text-align:center;'> - <input type="text" maxlength="6" id="Inp_H_DMaxLv" value='15' style='width: 35px;text-align:center;'>
<br> Amount: <input type="text" maxlength="6" id="Inp_H_DStack" value='1' style='width: 50px;text-align:center;' onKeyUp="if (document.getElementById('RadHiero3').checked === true){document.getElementById('Sel_Count').value=document.getElementById('Inp_H_DStack').value;}"><br><br>
</span>
<br><br>
<center> <input type="text" id="Inp_O5_NewOctet" value='' style='width: 120px;'> <a href='javascript:void(0);' onclick="LoadOctet(405);"><button> Load Octet </button></a></center>
</div>

<div id="AmmoInputDiv" style="display:none;">
<center><b><span id='Ammo_Name' style='font-size:16px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'>Elite Wolven Arrow</span></b></center><br>
<div id='OctDesc6' style='color: #f0f;font-size:14px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'></div><br>
<table border='0'  width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;'>
	<tr><td>Level Req.: </td><td><input type="text" maxlength="3" id="Inp_M_LvReq" value='0' style='width: 45px;'> </td></tr>
	<tr><td>Class Req.: </td><td><input type="text" maxlength="4" id="Inp_M_Class" value='<?php echo $Class_Mask; ?>' style='width: 55px; background-color: #ffd;'> </td></tr>
	<tr><td>Weapon Grade: </td><td><input type="text" maxlength="2" id="Inp_M_Grade1" value='1' style='width: 40px; background-color: #ffd;'> - <input type="text" maxlength="2" id="Inp_M_Grade2" value='17' style='width: 40px; background-color: #ffd;'></td></tr>
	<tr><td>STR Req.: </td><td><input type="text" maxlength="4" id="Inp_M_STR" value='0' style='width: 50px;'></td></tr>
	<tr><td>AGI Req.: </td><td><input type="text" maxlength="4" id="Inp_M_AGI" value='0' style='width: 50px;'> </td></tr>
	<tr><td>INT Req.: </td><td><input type="text" maxlength="4" id="Inp_M_INT" value='0' style='width: 50px;'></td></tr>
	<tr><td>CON Req.: </td><td><input type="text" maxlength="4" id="Inp_M_CON" value='0' style='width: 50px;'> </td></tr>
	<tr><td>Duratibility: </td><td><input type="text" maxlength="4" id="Inp_M_Dur1" value='1' style='width: 40px;' disabled> - <input type="text" maxlength="4" id="Inp_M_Dur2" value='1' style='width: 40px;' disabled></td></tr>
	<tr><td>Type: </td><td><select id="Inp_M_Ammo">
		<option value='8546'>Arrow [8546]</option>
		<option value='8547'>Bolt [8547]</option>
		<option value='8548'>Bullet [8548] </option>
		<option value='13700'>Snowball [13700]</option></td></tr>
	<tr><td>Damage: </td><td><input type="text" maxlength="4" id="Inp_M_Damage" value='5' style='width: 60px; background-color: #ffd;'></td></tr>
	<tr><td>Addons: </td><td><select id='Inp_M_AddonType' onchange='CheckAddonType(this);'>
	<?php
		$bool = true;
		for( $i = 1; $i <= count($Addons) ; $i++ ){
			$tmpArr = explode ("#", $Addons[$i]);
			$pos = strpos($tmpArr[3],"M");
			if ($pos !== FALSE){
				if (intval($tmpArr[5]) <= $ServerVer){	
					if ($bool){
						$bool = false;
						echo "<option value='".$Addons[$i]."' selected>".$tmpArr[4]."</option>";
					}else{
						echo "<option value='".$Addons[$i]."'>".$tmpArr[4]."</option>";
					}
				}
			}
		}
	?>
	</select></td></tr>
	<tr><td></td><td>Addon amount: <input type="text" maxlength="5" id="Inp_M_AddonAmount" value='1' style='width: 50px; background-color: #ffd;'> <a href='javascript:void(0);' onclick='AddNewAddon();'><button>Add</button></a></td></tr>
	<tr><td colspan ="2"><div id="AddonListDivM"></div></td></tr>
	<tr><td>Amount: </td><td><input type="text" maxlength="5" id="Inp_M_AStack" value='1' style='width: 70px;text-align:center;' onKeyUp="document.getElementById('Sel_Count').value=document.getElementById('Inp_M_AStack').value;"> <a href='javascript:void(0);' onclick="document.getElementById('Inp_M_AStack').value=50000;document.getElementById('Sel_Count').value=document.getElementById('Inp_M_AStack').value;"><button>Max</button></a></td></tr>
</table>
<br><br>
<center> <input type="text" id="Inp_O6_NewOctet" value='' style='width: 120px;'> <a href='javascript:void(0);' onclick="LoadOctet(406);"><button> Load Octet </button></a></center>
</div>

<div id="PotionInputDiv" style="display:none;">
<center><b><span id='Potion_Name' style='font-size:16px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'>Chaos Powder</span></b></center><br>
<div id='OctDesc7' style='color: #f0f;font-size:14px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'></div><br>
<table border='0'  width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;'>
	<tr><td>Level Req.: </td><td><input type="text" maxlength="3" id="Inp_X_LvReq" value='10' style='width: 45px;'> </td></tr>
	<tr><td>Effect ID: </td><td><input type="text" maxlength="3" id="Inp_X_Id" value='728' style='width: 45px; background-color: #ffd;'> </td></tr>
	<tr><td>Effect Lv: </td><td><input type="text" maxlength="2" id="Inp_X_Lv" value='1' style='width: 40px;'></td></tr>
	<tr><td>Amount: </td><td><input type="text" maxlength="5" id="Inp_X_AStack" value='1' style='width: 40px;' onKeyUp="document.getElementById('Sel_Count').value=document.getElementById('Inp_X_AStack').value;"></td></tr>
</table>
<br><br>
<center> <input type="text" id="Inp_O7_NewOctet" value='' style='width: 120px;'> <a href='javascript:void(0);' onclick="LoadOctet(407);"><button> Load Octet </button></a></center>
</div>

<div id="TaskDiceInputDiv" style="display:none;">
<center><b><span id='TaskDice_Name' style='font-size:16px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'>Heaven's Tear</span></b></center><br>
<div id='OctDesc8' style='color: #f0f;font-size:14px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'></div><br>
<table border='0'  width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;'>
	<tr><td>Quest ID: </td><td><input type="text" maxlength="6" id="Inp_T_Quest" value='10' style='width: 60px;'> </td></tr>
	<tr><td>Amount: </td><td><input type="text" maxlength="3" id="Inp_T_Amount" value='1' style='width: 40px;' onKeyUp="document.getElementById('Sel_Count').value=document.getElementById('Inp_T_Amount').value;"></td></tr>
</table>
<br><br>
<center> <input type="text" id="Inp_O8_NewOctet" value='' style='width: 120px;'> <a href='javascript:void(0);' onclick="LoadOctet(408);"><button> Load Octet </button></a></center>
</div>

<div id="GrassInputDiv" style="display:none;">
<center><b><span id='Grass_Name' style='font-size:16px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'>Pure Water</span></b></center><br>
<div id='OctDesc9' style='color: #f0f;font-size:14px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'></div><br>
<table border='0'  width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;'>
	<tr><td>Food Type: </td><td> <select id="Inp_G_Type">
		<option value="1">Fodder</option>
		<option value="2">Meat</option>
		<option value="3">Cookie</option>
		<option value="4">Mushroom</option>
		<option value="8">Fruit & Seed</option>
		<option value="16" selected>Water & Oil</option>
	</select> </td></tr>
	<tr><td>Loyality: </td><td><input type="text" maxlength="3" id="Inp_G_Loyal" value='10' style='width: 45px;'> </td></tr>
	<tr><td>Amount: </td><td><input type="text" maxlength="4" id="Inp_G_Amount" value='1' style='width: 60px;' onKeyUp="document.getElementById('Sel_Count').value=document.getElementById('Inp_G_Amount').value;"></td></tr>
</table>
<br><br>
<center> <input type="text" id="Inp_O9_NewOctet" value='' style='width: 120px;'> <a href='javascript:void(0);' onclick="LoadOctet(409);"><button> Load Octet </button></a></center>
</div>

<div id="StoneInputDiv" style="display:none;">
<center><b><span id='Stone_Name' style='font-size:16px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'>Jargoon Lv1</span></b></center><br>
<div id='OctDesc10' style='color: #f0f;font-size:14px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'></div><br>
<table border='0'  width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;'>
	<tr><td>For Weapon: </td><td>Id: <input type="text" maxlength="5" id="Inp_S_WeaponId" value='577' style='width: 55px;'> Val.: <input type="text" maxlength="5" id="Inp_S_WeaponVal" value='5' style='width: 55px;'></td></tr>
	<tr><td>For Armor: </td><td>Id: <input type="text" maxlength="5" id="Inp_S_ArmorId" value='577' style='width: 55px;'> Val.: <input type="text" maxlength="5" id="Inp_S_ArmorVal" value='5' style='width: 55px;'></td></tr>
	<tr><td>Amount: </td><td><input type="text" maxlength="4" id="Inp_S_Amount" value='1' style='width: 60px;' onKeyUp="document.getElementById('Sel_Count').value=document.getElementById('Inp_G_Amount').value;"></td></tr>
</table>
<br><br>
<center> <input type="text" id="Inp_O10_NewOctet" value='' style='width: 120px;'> <a href='javascript:void(0);' onclick="LoadOctet(410);"><button> Load Octet </button></a></center>
</div>

<div id="MiscInputDiv" style="display:none;">
<center><b><span id='Misc_Name' style='font-size:16px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'>Misc Cat</span></b></center><br>
<div id='MiscDesc' style='color: #f0f;font-size:14px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'></div><br>
<table border='0'  width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;'>
	<tr><td>Octet: </td><td><input type="text" maxlength="5000" id="Inp_Ms_Octet" value='' style='width: 200px;'> </td></tr>
	<tr><td>Amount: </td><td><input type="text" maxlength="5" id="Inp_Ms_Amount" value='1' style='width: 60px;' onKeyUp="document.getElementById('Sel_Count').value=document.getElementById('Inp_Ms_Amount').value;"> <a href='javascript:void(0);' onclick="document.getElementById('Inp_Ms_Amount').value=document.getElementById('Inp_Mss_Amount').value;document.getElementById('Sel_Count').value=document.getElementById('Inp_Ms_Amount').value;"><button>Max</button></a></td></tr>
	<tr><td>Max Stack: </td><td><input type="text" maxlength="5" id="Inp_Mss_Amount" value='1' style='width: 60px;' onKeyUp="document.getElementById('Sel_MaxCount').value=document.getElementById('Inp_Mss_Amount').value;"></td></tr>
	<tr id='MiscQStack' style='display:none;'><td>Quests: </td><td> 
	<?php
		for ($a = 1; $a <= count($MapQuest); $a++){
			echo "<select id='QuestStack".$a."' style='display:none;width:120px;' onchange='document.getElementById(\"Inp_Ms_Octet\").value=DectoRevHex(this.options[this.selectedIndex].value,8,0);'> ";
			for ($x = 0; $x < count($MapQuest[$a]); $x++){
				if ($x==0){
					echo "<option value='".$MapQuest[$a][$x]."' selected> ".$MapQuest[$a][$x]."</option>";	
				}else{
					echo "<option value='".$MapQuest[$a][$x]."'> ".$MapQuest[$a][$x]."</option>";	
				}
			}
			echo"</select>";
		}	
	?>
	</td></tr>
</table>
</div>

<div id="MoraiOrderDiv" style="display:none;">
<center><b><span id='Order_Name' style='font-size:16px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'>Luminance</span></b></center><br>
<div id='OctDesc11' style='color: #f0f;font-size:14px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'></div><br>
<table border='0'  width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;'>
	<tr><td>Order: </td><td><select id='Inp_MO_Clss' style='vertical-align:middle;width: 120px;'>
		<option value='1004'>Corona</option>
		<option value='1005'>Shroud</option>
		<option value='1006'>Luminancs</option>
	</select></td></tr>
	<tr><td>Prestige: </td><td><input type="text" maxlength="5" id="Inp_MO_Prest" value='0' style='width: 65px;'> / <span id='Max_Prestige'></span></td></tr>
	<tr><td>Lose if leave: </td><td><input type="text" maxlength="5" id="Inp_MO_PLose" value='577' style='width: 40px;'> %</td></tr>
</table>
<br><br>
<center> <input type="text" id="Inp_O11_NewOctet" value='' style='width: 120px;'> <a href='javascript:void(0);' onclick="LoadOctet(411);"><button> Load Octet </button></a></center>
</div>

<div id="StarChartDiv" style="display:none;">
	<center><b><span id='StarChart_Name' style='font-size:16px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'><font color="yellow">Star Chart: Keen Vow</font></span></b></center><br>
	<table border='0'  width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;'>
		<tr><td>Class: </td><td>
		<select id='Inp_SC_Clss' style='vertical-align:middle;width: 120px;' disabled>
		<?php
			for ($a = 1; $a <= count($PWclass); $a++){
				if ($a==1){
					echo "<option value='".$a."' selected> ".$PWclass[$a]."</option>";	
				}else{
					echo "<option value='".$a."'> ".$PWclass[$a]."</option>";	
				}
			}	
		?>
	</select>
		<tr><td>Level: </td><td><input type="text" maxlength="6" id="Inp_SC_CurLv" value='1' style='width: 45px;' onChange="ChangeStarChartLevel();"> / <b>50</b> </td></tr>
		<tr><td>Exp: </td><td><input type="text" maxlength="6" id="Inp_SC_CurExp" value='0' style='width: 45px;' onChange="ChangeStarChartExp();"> / <span id='Inp_SC_MaxExp'><b>6</b> <i>(0)</i></span> </td></tr>
	</table>
	<br>
	<table border='0'  width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;text-align:center;'>
	<tr><th>BirthStar</th><th>Aptitude</th><th>Addon</th><th>Rate</th></tr>
<?php
$SCArr[1]=1;
$SCArr[2]=2;
$SCArr[3]=3;
$SCArr[4]=4;
$SCArr[5]=5;
$SCArr[6]=1;
		for ($a = 1; $a <= 5; $a++){
			echo"<tr onmouseover='HighlightRows(".$a.",1);' id='SCTR_BS".$a."'><td><a title='This star connected to #".$SCArr[$a]." and #".$SCArr[$a+1]." Fatestars'>Star #".$a."</a></td><td><input type='text' maxlength='5' id='Inp_SC_BStarApt".$a."' value='1.00' style='width: 45px;' onChange='SetSCAptitude();'></td>
			<td><select id='Inp_SC_BStarAddon".$a."' style='width:90px;' onChange='GetSCCharRate(".$a.");'><option value='0' selected>Closed</option>";
			for ($i = 1; $i <= count($AddonStar); $i++){
				$tmpArr = explode ("#", $AddonStar[$i]);
				$pos = strpos($tmpArr[1], ",");
				$vis="none";
				$vis1="hidden";
				$vis2="disabled";
				if ($tmpArr[1] == "*"){
					$vis="block";
					$vis1="";
					$vis2="";
				}elseif($tmpArr[1]==1){
					$vis="block";
					$vis1="";
					$vis2="";
				}elseif($pos !== false){
					$tmpArr1 = explode (",", $tmpArr[1]);
					if (in_array("1", $tmpArr1)){
						$vis="block";
						$vis1="";
						$vis2="";
					}
				}
				//hidden
				echo "<option value='".$AddonStar[$i]."' style='display:".$vis.";' $vis1 $vis2>".$tmpArr[3]."</option>";	
			}
			echo"</select></td><td><input type='text' maxlength='6' id='Inp_SC_BStarRate".$a."' value='0' style='width: 45px;' onChange='RefreshStarChartAddons();'></td></tr>";
			echo"</td></tr>";
		}
?>			
	</table>
	<br>
	<table border='0'  width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;text-align:center;'>
	<tr><th>FateStar</th><th>Aptitude</th><th>Addon</th><th>Rate</th></tr>
<?php
$SCArr[0]=5;
$SCArr[1]=1;
$SCArr[2]=2;
$SCArr[3]=3;
$SCArr[4]=4;
$SCArr[5]=5;
		for ($a = 1; $a <= 5; $a++){
			echo"<tr onmouseover='HighlightRows(".$a.",2);' id='SCTR_FS".$a."'><td><a title='This star connected to #".$SCArr[$a-1]." and #".$SCArr[$a]." Birthstars'>Star #".$a."</a></td><td><span id='Inp_SC_FStarApt".$a."'> 2.00 </span></td>
			<td><select id='Inp_SC_FStarAddon".$a."' style='width:90px;' onChange='GetSCCharRate(".($a+5).");' disabled><option value='0' selected>Closed</option>";
			for ($i = 1; $i <= count($AddonStar); $i++){
				$tmpArr = explode ("#", $AddonStar[$i]);
				$pos = strpos($tmpArr[1], ",");
				$vis="none";
				if ($tmpArr[1] == "*"){
					$vis="block";
				}elseif($tmpArr[1]==1){
					$vis="block";
				}elseif($pos !== false){
					$tmpArr1 = explode (",", $tmpArr[1]);
					if (in_array("1", $tmpArr1)){
						$vis="block";
					}
				}
				echo "<option value='".$AddonStar[$i]."' style='display:".$vis.";'>".$tmpArr[3]."</option>";	
			}
			echo"</select></td><td><input type='text' maxlength='6' id='Inp_SC_FStarRate".$a."' value='0' style='width: 45px;' onChange='RefreshStarChartAddons();' disabled></td></tr>";
			echo"</td></tr>";
		}
?>			
	</table>
	<br>
	<span id='SCAddonList' style="font-face:arial;font-size:12px;text-shadow: 1px 1px 3px #777;"></span>
	<br><br>
	<center> <input type="text" id="Inp_O12_NewOctet" value='' style='width: 120px;'> <a href='javascript:void(0);' onclick="LoadOctet(412);"><button> Load Octet </button></a></center>
</div>

	
<div id="CardInputDiv" style="display:none;">
	<center><b><span id='Card_Name' style='font-size:16px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'>Card Name</span></b></center><br>
	<table border='0'  width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;'>
	<tr><td>Type: </td><td>
		<select id="Inp_C_Type" onChange="GetCardInfo();">
	<?php
		for ($a = 1; $a <= count($IBMenuSC[8]); $a++){
			$tmpArr = explode("#", $IBMenuSC[8][$a]);
			if ($a==1){
				echo "<option value='".($a-1)."' selected> ".$tmpArr[0]."</option>";	
			}else{
				echo "<option value='".($a-1)."'> ".$tmpArr[0]."</option>";	
			}
		}
	?>	
		</select></td></tr>
		<tr><td>Grade: </td><td>
		<select id='Inp_C_Grade' onChange="GetCardInfo();">
			<option value='0' selected>C</option>
			<option value='1'>B</option>
			<option value='2'>A</option>
			<option value='3'>S</option>
			<option value='4'>S+</option>
		</select>
		</td></tr>
		<tr><td>Level Req.: </td><td><input type="text" maxlength="1" id="Inp_C_LvReq" value='100' style='width: 45px;' onChange="GetCardInfo();"></td></tr>
		<tr><td>Leadership.: </td><td><input type="text" maxlength="1" id="Inp_C_Lead" value='1' style='width: 45px;'></td></tr>
		<tr><td>Level.: </td><td><input type="text" maxlength="6" id="Inp_C_CurLv" value='1' style='width: 35px;' onChange="GetCardInfo();"> / <input type="text" maxlength="6" id="Inp_C_MaxLv" value='15' style='width: 35px;'> 
		<a href='javascript:void(0);' onClick="ChangeCardLevel(-999);" title='Set card level to 1'><button><<</button></a> 
		<a href='javascript:void(0);' onClick="ChangeCardLevel(-1);" title='Decrease card level by 1'><button><</button></a>	
		<a href='javascript:void(0);' onClick="ChangeCardLevel(1);" title='Increase card level by 1'><button>></button></a>
		<a href='javascript:void(0);' onClick="ChangeCardLevel(999);" title='Set card level to maximum'><button>>></button></a>
		
		</td></tr>
		<tr><td>Exp Lv.: </td><td><input type="text" maxlength="6" id="Inp_C_ExpLv" value='0' style='width: 45px;' onChange="GetCardInfo();"> / <span id='Inp_C_MaxExp'>17</span></td></tr>
		<tr><td>Reawaken: </td><td>
		<select id='Inp_C_Reawake' onChange="GetCardInfo();">
			<option value='0' selected>0</option>
			<option value='1'>1</option>
			<option value='2'>2</option>
		</select></td></tr>
		</td></tr>
	</table><br>
	<input type='hidden' style='display:none;' id='CardID'>
	<center> <input type="text" id="Inp_C_NewOctet" value='' style='width: 120px;'> <a href='javascript:void(0);' onclick="LoadOctet(8);"><button> Load Octet </button></a></center>
	<hr><br>
	<span style='text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;color:#fff;'>Current Class:</span> <select id='Inp_C_Clss' style='vertical-align:middle;width: 120px;' onChange="GetCardInfo();">
	<?php
		for ($a = 1; $a <= count($PWclass); $a++){
			if ($a==1){
				echo "<option value='".$a."' selected> ".$PWclass[$a]."</option>";	
			}else{
				echo "<option value='".$a."'> ".$PWclass[$a]."</option>";	
			}
		}	
	?>
	</select>
	<br><br>
	<div class='bubble'>
		<div id='CardBase' style='font-face: arial; font-size: 14px;color: #fff;text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;'></div>
		<div id='CardBonus' style='font-face: arial; font-size: 14px;color: #88f;text-shadow: -1px 0 #007, 0 1px #007, 1px 0 #007, 0 -1px #007;'></div>
		<div id='CardSet' style='font-face: arial; font-size: 12px;'></div>
	</div>
</div>

<div id="FashInputDiv" style="display:none;">
<center><b><span id='Fash_Name' style='font-size:16px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;'>Fash Cat</span></b></center><br>
<div id='FashDesc' style='color: #f0f;'></div><br>
<table border='0'  width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;'>
	<tr><td>LvReq: </td><td><input type="text" maxlength="3" id="Inp_Fs_LvReq" value='1' style='width: 50px;'> </td></tr>
	<tr><td>Gender: </td><td><input type="radio" value='0' name="fashGen" id="FashMale" checked> Male &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value='1' name="fashGen" id="FashFemale"> Female</td></tr>
	<tr><td>Color: </td><td>
	<select id="Inp_Fs_Color1" onChange="document.getElementById('Inp_Fs_Color2').value=document.getElementById('Inp_Fs_Color1').value;">
<?php
	for ($a = 1; $a <= count($FashCol); $a++){
		$tmpArr = explode("#", $FashCol[$a]);
		if ($a==2){
			echo "<option value='".$tmpArr[0]."' selected> ".$a.". ".$tmpArr[1]."</option>";	
		}else{
			echo "<option value='".$tmpArr[0]."'> ".$a.". ".$tmpArr[1]."</option>";
		}
	}
?>	
	</select> 
	<input type="text" maxlength="4" id="Inp_Fs_Color2" value='0000' style='width: 50px;'></td></tr>
</table>
<br><br>
<center><a title="Auto subtype detection"><input type="checkbox" id="Inp_F_AutoOctet" style='vertical-align: middle;' checked></a> <input type="text" id="Inp_F_NewOctet" value='' style='width: 120px;'> <a href='javascript:void(0);' onclick="LoadOctet(7);"><button> Load Octet </button></a></center>
</div>


<div id="SelectWindow" style="display:none; font-size: 12px;font-family: arial;">
	<div class="WindowHead_Light1"><input type="checkbox" id="Inp_SelAll" style="vertical-align: middle;float:left;" onChange="MarkAllPacketItem();"><b>Mail Packets</b><div class="WindowClose_Light1" onclick="document.getElementById('SelectWindow').style.display='none';document.getElementById('SelectEditWindow').style.display='none';">&#10006;</div></div>
	<div id="SelectWindowCont">
		<span id="SelItemList" style="text-decoration:none; color: #000;">
		<center><i>List is Empty</i></center></span>
		</div>
	<div class="WindowFoot_Light1"><div id="SelMultiTool" style="display:none;"><a href="javascript:void(0);" onclick="GroupResetSelectedItem();"><button>Reset Role [&#10004;]</button></a> <a href="javascript:void(0);" onclick="GroupResetTSelectedItem();"><button>Reset Timer [&#10004;]</button></a> <a href="javascript:void(0);" onclick="GroupRemoveSelectedItem();"><button>Delete [&#10004;] </button></a> <a href='javascript:void(0);' onclick="GroupSendSelectedItem();"><button>Send [&#10004;]</button></a> <a href='javascript:void(0);' onclick="SaveSelectedPackets();"><button>Save [&#10004;]</button></a></div> <a href='javascript:void(0);' onclick="LoadPacketItems();"><button>Load</button></a></div>
</div>

<div id="SelectEditWindow" style="display:none;font-size: 12px;font-family: arial;">
<div class="WindowHead_Light1"><b>Edit Packet Data</b><div class="WindowClose_Light1" onclick="document.getElementById('SelectEditWindow').style.display='none';">&#10006;</div></div>
<br>
<table border='0'  width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;'>
<tr><td>Packet Id: </td><td><input type="text" maxlength="6" id="SE_PacketId" value='' style='width: 50px;' disabled><input type="text" id="SE_CAT" value='' style='width: 50px; display:none;' disabled></td></tr>
<tr><td>Name: </td><td><input type="text" maxlength="32" id="SE_ItemName" value='' style='width: 150px;'></td></tr>
<tr><td>Role Id: </td><td><input type="text" maxlength="6" id="SE_RoleId" value='' style='width: 50px;'></td></tr>
<tr><td>Money: </td><td><input type="text" maxlength="9" id="SE_Money" value='' style='width: 50px;'></td></tr>
<tr><td>Title: </td><td><input type="text" maxlength="50" id="SE_Title" value='' style='width: 100px;'></td></tr>
<tr><td>Body: </td><td><input type="text" maxlength="128" id="SE_Body" value='' style='width: 150px;'></td></tr>
<tr><td>Item Id: </td><td><input type="text" maxlength="6" id="SE_ItemId" value='' style='width: 60px;'></td></tr>
<tr><td>Mask: </td><td><input type="text" maxlength="20" id="SE_Mask" value='' style='width: 100px;'></td></tr>
<tr><td>ProcType: </td><td><input type="text" maxlength="10" id="SE_ProcType" value='' style='width: 70px;'></td></tr>
<tr><td>Stack: </td><td><input type="text" maxlength="9" id="SE_Stack" value='' style='width: 70px;'></td></tr>
<tr><td>Max Stack: </td><td><input type="text" maxlength="9" id="SE_MaxStack" value='' style='width: 70px;'></td></tr>
<tr><td>Guid1: </td><td><input type="text" maxlength="20" id="SE_Guid1" value='' style='width: 70px;'></td></tr>
<tr><td>Guid2: </td><td><input type="text" maxlength="20" id="SE_Guid2" value='' style='width: 70px;'></td></tr>
<tr><td>Expire: </td><td><input type="text" maxlength="20" id="SE_Expire" value='' style='width: 70px;'></td></tr>
<tr><td>Octet: </td><td><input type="text" maxlength="255" id="SE_Octet" value='' style='width: 150px;'></td></tr>
</table>
<center><a href='javascript:void(0);' onclick="UpdateSelItemChanges();"><button>Update</button></a> <a href='javascript:void(0);' onclick="document.getElementById('SelectEditWindow').style.display='none';"><button>Close</button></a></center>
</div>

<div id="ShopItemWindow" style="display:none; font-size: 12px;font-family: arial;">
	<div class="WindowHead_Light1"><input type="checkbox" id="Inp_ShopAll" style="vertical-align: middle; float:left;" onChange="MarkAllShopItem();"><b>WebShop Item List</b><div class="WindowClose_Light1" onclick="document.getElementById('ShopItemWindow').style.display='none';document.getElementById('ShopItemEditWindow').style.display='none';">&#10006;</div></div>
	<div id="ShopItemWindowCont">
		<span id="ShopItemList" style="text-decoration:none; color: #000;">
		<center><i>List is Empty</i></center>
		</span></div>
	<div class="WindowFoot_Light1">
		<div id="ShopMultiTool" style="display:none;">
			<a href="javascript:void(0);" onclick="GroupDeleteShopItems();"><button>Delete [&#10004;]</button></a>
		</div>
		<a href='javascript:void(0);' onclick='LoadShopItems();'><button> Load [SQL]</button></a> 
		<a href='javascript:void(0);' onclick='ExportShopItems(1);'><button> Save [SQL]</button></a>
		<a href='javascript:void(0);' onclick='ExportShopDB();'><button> [DB=>File]</button></a>  
		<a href='javascript:void(0);' onclick='ImportShopDB();'><button> [File=>DB]</button></a>
		
		<a href="javascript:void(0);" onclick="alert('Buttons:\n-Delete: selected item from list\n-Load [File]: Load items from file\n-Save [File]: save all items into file\n-Load [SQL]: load shop items from MySQL \n-Save [SQL]: save shop items to MySQL');"><button> ? </button></a>
	</div>
</div>

<div id="ShopItemEditWindow" style="display:none;font-size: 12px;font-family: arial;">
<div class="WindowHead_Light1"><b>Edit Shop Item</b><div class="WindowClose_Light1" onclick="document.getElementById('ShopItemEditWindow').style.display='none';">&#10006;</div></div>
<br><br>
<table border='0'  width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;'>
<tr><td>Shop Id: </td><td><input type="text" maxlength="6" id="WSE_PacketId" value='' style='width: 50px;' disabled><input type="text" id="WSE_CAT" value='' style='width: 50px;display:none;' disabled></td></tr>
<tr><td>Name: </td><td><input type="text" maxlength="32" id="WSE_ItemName" value='' style='width: 150px;'></td></tr>
<tr><td>Title: </td><td><input type="text" maxlength="50" id="WSE_Title" value='' style='width: 150px;'></td></tr>
<tr><td>Grade: </td><td><input type="text" maxlength="50" id="WSE_Grade" value='' style='width: 150px;'></td></tr>
<tr><td>Description: </td><td><input type="text" maxlength="128" id="WSE_Description" style='width: 150px;'></td></tr>
<tr><td>Item Id: </td><td><input type="text" maxlength="6" id="WSE_ItemId" value='' style='width: 60px;'></td></tr>
<tr><td>Mask: </td><td><input type="text" maxlength="20" id="WSE_Mask" value='' style='width: 100px;'></td></tr>
<tr><td>ProcType: </td><td><input type="text" maxlength="10" id="WSE_ProcType" value='' style='width: 70px;'></td></tr>
<tr><td>Stack: </td><td><input type="text" maxlength="9" id="WSE_Stack" value='' style='width: 70px;'></td></tr>
<tr><td>Max Stack: </td><td><input type="text" maxlength="9" id="WSE_MaxStack" value='' style='width: 70px;'></td></tr>
<tr><td>Guid1: </td><td><input type="text" maxlength="20" id="WSE_Guid1" value='' style='width: 70px;'></td></tr>
<tr><td>Guid2: </td><td><input type="text" maxlength="20" id="WSE_Guid2" value='' style='width: 70px;'></td></tr>
<tr><td>Expire: </td><td><input type="text" maxlength="20" id="WSE_Expire" value='' style='width: 70px;'></td></tr>
<tr><td>Octet: </td><td><input type="text" maxlength="255" id="WSE_Octet" value='' style='width: 150px;'></td></tr>
<tr><td>Price1 (G): </td><td><input type="text" maxlength="9" id="WSE_Price1" value='' style='width: 50px;'></td></tr>
<tr><td>Price2 (W): </td><td><input type="text" maxlength="9" id="WSE_Price2" value='' style='width: 50px;'></td></tr>
<tr><td>Color: </td><td><select id='ItmShpCol' style='width: 150px;'>
<?php
	for ($a = 1; $a < count($ItemColor); $a++){
		echo "<option value='".$a."' style='background-color: #".$ItemColor[$a].";'> ".$ItemColorN[$a]."</option>";
	}
?>
</select></td></tr>
<tr><td>Category: </td><td><select id='ItmShpCat' style='width: 150px;'>
<?php
	$a=0;
	for ($a = 1; $a <= count($ShopMenu); $a++){
		$b=0;
		$c=0;
		$Asub = "";
		$Bsub = "";
		$tmpArr = explode("#", $ShopMenu[$a][0][0]);
		if ($ServerVer >= $tmpArr[1]){
			echo "<option value='".$a.$b.$c."' style='background-color: #ff0;'> ".$Asub.$Bsub." ".$tmpArr[0]."</option>";
			if (count($ShopMenu[$a]) > 1){
				for ($b = 1; $b < count($ShopMenu[$a]); $b++){
					$c=0;
					$Asub = ">>>";
					$Bsub = "";
					$tmpArr = explode("#", $ShopMenu[$a][$b][0]);
					if ($ServerVer >= $tmpArr[1]){
						echo "<option value='".$a.$b.$c."' style='background-color: #ff8;'> ".$Asub.$Bsub." ".$tmpArr[0]."</option>";
						if (count($ShopMenu[$a][$b]) > 1){
							$DMH = ($b-1) * 58;
							for ($c = 1; $c < count($ShopMenu[$a][$b]); $c++){
								$tmpArr = explode("#", $ShopMenu[$a][$b][$c]);
								$Bsub = ">>>";
								if ($ServerVer >= $tmpArr[1]){
									echo "<option value='".$a.$b.$c."' style='background-color: #ffc;'> ".$Asub.$Bsub." ".$tmpArr[0]."</option>";
								}
							}
						}					
					}
				}
			}
		}
	}

?>
</select></td></tr>
<tr><td style="vertical-align:top;">Item Date:</td><td>
<select id="WSE_SType" onchange="EditItemDate();">
	<option value="0" selected>Permanent</option>
	<option value="1">Between Dates</option>
	<option value="2">Daily Once</option>
	<option value="3">Special Offer</option>
</select> <span id="WSE_AHide" style="display:none;"><input type="checkbox" value="1" style='vertical-align:middle;' id="WSE_AutoHideItem"> <a title='Hide item when not buyable, a loop check every non permanent item date when can be showed and hide every second' style="font-face:arial;font-size:10px;">Hide</a></span>
<div id='WSE_Interval' style='font-weight:900;display:none;'>Start at:<br> <input type="text" maxlength="100" id="WSE_Start_Date" style="width: 110px;text-align:center;" value="<?php echo date("Y-m-d H:i:s", time()); ?>">
<br>End at:<br> <input type="text" maxlength="100" id="WSE_End_Date" style="width: 110px;text-align:center;" value="<?php echo date("Y-m-d H:i:s", (time()+3600)); ?>"> <span id="WSE_ShopPromo" style="font-weight:900;display:none;"> Discount: <input type="text" maxlength="100" id="WSE_Discount" style="width: 30px;text-align:center;" value="10"> %</span></div>
<div id='WSE_IntHour' style='font-weight:900;display:none;'>Start at:<br> <input type="text" maxlength="5" id="WSE_Start_Hour" style="width: 50px;text-align:center;" value="01:00"> hour<br> End at:<br> <input type="text" maxlength="5" id="WSE_End_Hour" style="width: 50px;text-align:center;" value="23:59"> hour</div>
</td></tr>
</table>
<center><a href='javascript:void(0);' onclick="UpdateShopItem();"><button> Update </button></a> <a href='javascript:void(0);' onclick="document.getElementById('ShopItemEditWindow').style.display='none';"><button> Close </button></a></center>
</div>

<div style="position:absolute;top:13px;;left:13px;display:block;font-size: 12px;font-family: arial;">
<?php
	echo"<a href='javascript:void(0);' onclick='alert(\"".$statTxt."\");'><button style='width:24px;'>[?]</button></a>";
?>
</div>

<div id="MathDiv" style="display:none;font-size: 12px;font-family: arial;">
	<div class="WindowHead_Light3"><b>Conv Tool</b><div class="WindowClose_Light1" onclick="document.getElementById('MathDiv').style.display='none';">&#10006;</div></div>
	<br><br>
	<table border='0' width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;'>
	<tr><td>Decimal: </td><td><input type="text" maxlength="10" id="Math_Dec" value='0' style='width: 60px; text-align: center;' onKeyUp="MathConvDec();" onChange="MathConvDec();"></td><td> </td></tr>
	<tr><td>Hexadec: </td><td><input type="text" maxlength="8" id="Math_Hex" value='00000000' style='width: 60px; text-align: center;' onKeyUp="MathConvHex();" onChange="MathConvHex();"></td><td><select id="Math_HexLen" style="width:65px;" onChange="MathConvHex();MathConvRHex();"><option value="1">1 byte</option><option value="2">2 byte</option><option value="4" selected>4 byte</option></selected></td></tr>
	<tr><td>R HexaDec: </td><td><input type="text" maxlength="8" id="Math_RHex" value='00000000' style='width: 60px; text-align: center;' onKeyUp="MathConvRHex();" onChange="MathConvRHex();"></td><td><select id="Math_Type" style="width:65px;" onChange="MathConvHex();MathConvRHex();"><option value="0" selected>Normal</option><option value="2">Addon</option><option value="4">Special</option><option value="4">Refine</option><option value="a">Socket</option></selected></td></tr>
	<tr><td>Float: </td><td><input type="text" maxlength="8" id="Math_Float" value='00000000' style='width: 60px; text-align: center;' onKeyUp="MathConvFloat();" onChange="MathConvFloat();"></td><td><select id="Math_FPrec" style="width:65px;" onChange="MathConvFloat();MathConvRFloat();"><option value="1" selected>Prec: 1</option><option value="2" selected>Prec: 2</option><option value="3">Prec: 3</option><option value="4">Prec: 4</option></selected></td></tr>
	<tr><td>R. Float: </td><td><input type="text" maxlength="8" id="Math_RFloat" value='00000000' style='width: 60px; text-align: center;' onKeyUp="MathConvRFloat();" onChange="MathConvRFloat();"></td><td> </td></tr>
	<tr><td colspan="3">Date: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" maxlength="25" id="Math_Date" value='' style='width: 120px; text-align: center;' disabled></td></tr>
	</table>
</div>

<div id="WShopLog" style="display:none;">
	<div class="WindowHead_Light3"><b>Web Shop Log</b><div class="WindowClose_Light1" onclick="document.getElementById('WShopLog').style.display='none';">&#10006;</div></div>
	<div id="WShopLogCont" style='position:absolute;top:30px;left:5px;right:5px;bottom:30px;overflow-y:auto;overflow-x:hidden;'>
		<table border='0' id='WSLogTable' width='100%' style='font-size: 12px;font-family: arial;border-collapse: collapse;'>
		</table>
	</div>
	<input type="text" id="WSLData" value='' style='position:absolute;left:1px;bottom:3px;width:350px;'>
	<a href='javascript:void(0);' onclick='OctSelectedItem(0, 3);'><button style='position:absolute;right:1px;bottom:3px;width:40px;'>Load</button></a>  
</div>

<div id="OctetDiv"><b>
Role Name: <input type="text" maxlength="32" id="Inp_RoleName" style="width: 60px;text-align:center;" value=""> <a href='javascript:void(0);' onclick="GetRoleId();"><button>Get ID</button></a><br>
Role id: <input type="text" maxlength="5" id="Inp_RoleId" style="width: 45px;text-align:center;" value="1024"> &nbsp;&nbsp;&nbsp; Title: <input type="text" maxlength="64" id="Inp_MailTitle" style="width: 100px;" value="It is your lucky day!"><br>
Body: <input type="text" maxlength="256" id="Inp_MailBody" style="width: 400px;" value="We decided to give your a gift, please take it and have fun!"><br>
Money: <input type="text" maxlength="9" id="Inp_Money" style="width: 70px;text-align:right;" value="0">&nbsp;&nbsp;&nbsp; <a href='javascript:void(0);' onclick='SendCustomItem();'><button>Send Item</button></a> <a href='javascript:void(0);' onClick="SelectNewItem();"><button> Make Packet </button></a><span style="float: right;" id="MailResponse"></span>
</b><br><br>

<?php 
if ($WebShop!==false){
?>
<table border="0" style="font-size: 12px;font-family: arial;border-collapse: collapse;">
<tr><td><b>Item title</b>: </td><td> <input type="text" maxlength="50" id="Inp_Title" style="width: 150px;text-align:center;" value="☆☆☆I-Beam Sledgehammer"></td></tr>
<tr><td><b>Item description</b>: </td><td> <input type="text" maxlength="100" id="Inp_Description" style="width: 250px;text-align:center;" value=""></td></tr>
<tr><td><b>Item Color</b>: </td><td> <input type="text" maxlength="100" id="Inp_Color" style="width: 30px;text-align:center;" value=""> <span id='Inp_Color2'></span> </td></tr>
<tr><td><b>Item Expire</b>: </td><td> 
	<a href='javascript:void(0);' onclick='SetExpirTimer(0);'><button>Perm</button></a>
	<a href='javascript:void(0);' onclick='SetExpirTimer(1);'><button>10m</button></a>
	<a href='javascript:void(0);' onclick='SetExpirTimer(2);'><button>30m</button></a>
	<a href='javascript:void(0);' onclick='SetExpirTimer(3);'><button>1h</button></a>
	<a href='javascript:void(0);' onclick='SetExpirTimer(4);'><button>2h</button></a>
	<a href='javascript:void(0);' onclick='SetExpirTimer(5);'><button>7d</button></a>
</td></tr>
<tr><td style='vertical-align:top;'><b>Sale Type</b>: </td><td>
<select id="Inp_ShopType" onchange="ChangeItemDate();">
	<option value="0" selected>Permanent</option>
	<option value="1">Between Dates</option>
	<option value="2">Daily Once</option>
	<option value="3">Special Offer</option>
</select> <span id="Inp_AHide" style="display:none;"><input type="checkbox" value="1" style='vertical-align:middle;' id="Inp_AutoHideItem"> <a title='Hide item when not buyable, a loop check every non permanent item date when can be showed and hide every second'>Auto hide</a></span>
<div id='Inp_Interval' style='font-weight:900;display:none;'>Start at: <input type="text" maxlength="100" id="Inp_Start_Date" style="width: 110px;text-align:center;" value="<?php echo date("Y-m-d H:i:s", time()); ?>">, End at: <input type="text" maxlength="100" id="Inp_End_Date" style="width: 110px;text-align:center;" value="<?php echo date("Y-m-d H:i:s", (time()+3600)); ?>"> <span id="Inp_ShopPromo" style="font-weight:900;display:none;">, Discount: <input type="text" maxlength="100" id="Inp_Discount" style="width: 30px;text-align:center;" value="10"> %</span></div>
<div id='Inp_IntHour' style='font-weight:900;display:none;'>Start at: <input type="text" maxlength="5" id="Inp_Start_Hour" style="width: 50px;text-align:center;" value="01:00"> hour, End at: <input type="text" maxlength="5" id="Inp_End_Hour" style="width: 50px;text-align:center;" value="23:59"> hour</div>
</td></tr>

<tr><td><b>Price 1</b> <i>(Webpoint)</i>: </td><td> <input type="text" maxlength="9" id="Inp_Price2" style="width: 70px;text-align:right;" value="0"> per piece.</td></tr>
<?php if (($ServerVer > 38) && ($ServerVer < 60)){
	echo"<tr><td><b>Price 2</b> <i>(IG Gold)</i>: </td><td> <input type='text' maxlength='9' id='Inp_Price1' style='width: 70px;text-align:right;' value='0'> per piece.</td></tr>";
}else{
	echo"<tr style='display:none;'><td> <td> <input type='text' id='Inp_Price1' value='0'> </td></tr>";
}
?>
</table>
<a href='javascript:void(0);' onclick='AddItemToShop();'><button>Add to Shop List</button></a>  
<?php
	if ($WebShopLog!==false){
		echo "<a href='javascript:void(0);' onclick='LoadShopLog();'><button>Check Shop Log</button></a>";
	}
?>
<br>
<br><br>
<?php 
}
?>

<b>Splited octet data</b> <br>
<span id='octet1' style = 'word-break:normal;'></span><br><br>
Whole Octet <i>(double click for select all)</i><br>
<span id='octet2'></span><input type="text" id="Inp_Octet" style="width: 100%;" value="">
</div>

<div id="PreviewDiv1">
<span id='mit'>

</span>
</div>
<div id="ExpirationDiv">
	<div class="WindowHead_Light3"><b>Expiration Timer</b><div class="WindowClose_Light1" onclick="document.getElementById('ExpirationDiv').style.display='none';">&#10006;</div></div>
	<table style="font-size: 12px;font-family: arial;border-collapse: collapse;width: 100%;position:absolute;top:30px;">
	<?php
		$ServTime = time();
		echo"<tr><td><b>Current Time:</b> <input type='text' id='Cur_Time' value='' style='width:70px;text-align:center;' disabled> <b>+</b> <input type='text' id='Cur_PlusNr' value='0' style='width:35px;text-align:center;'> <select id='Cur_PlusType'><option value='60'>min</option><option value='3600'>hour</option><option value='86400'>day</option></select> <input type='checkbox' style='vertical-align: middle;' id='Expire_Stop'> Stop <input type='text' id='Time_ClntVsServ' value='".$ServTime."' style='position: absolute; left:-9999px;display:none;'></td></tr>";
		echo"<script>
		TCS = document.getElementById('Time_ClntVsServ').value - (Date.now() / 1000 | 0);
		</script>"
	?>
	</table>
</div>


<div id="ProcTypeDiv">
<table style="font-size: 12px;font-family: arial;border-collapse: collapse;width: 100%;">
<?php
	$c1 = 0.5;
	$c3 = -1;
	$c4 = intval(round(count($ProcType)/2));
	
	for ($i = 1; $i <= $c4; $i++) {
		$c1=$c1*2;
		$c2=$c1*2;
		$c3++;
		$c5 = "<input type='checkbox' id='ProcTyp".($c3+1)."' value='".$c2."' onClick='GenerateProcType();'> ".$ProcType[$c3+1]." ";
		if (isset($ProcType[$c3+1])===false){$c5="";}
		echo"<tr><td><input type='checkbox' id='ProcTyp".$c3."' value='".$c1."' onClick='GenerateProcType();'> ".$ProcType[$c3]." </td><td>$c5 </td></tr>";
		$c3++;
		$c1 = $c2;
	} 
?>
</table><br>
<center><a href="javascript:void(0);" onclick="document.getElementById('ProcTypeDiv').style.display='none';"><button>Close</button></a></center>
</div>
<div id="RuneTimeDiv" style='display:none;'><div class="WindowHead_Light3"><b>Rune Settings</b><div class="WindowClose_Light1" onclick="document.getElementById('RuneTimeDiv').style.display='none';">&#10006;</div></div>
	<div style='position:absolute;top:50px;display:block;'>
	Duration: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" maxlength="4" id="Inp_Rune_Time" style="width: 50px;text-align:center;" value="30"> 
	<select id='Rune_Size'>
		<option value="1"checked>minute</option>
		<option value="60">hour</option>
		<option value="1440">day</option>
	</select> <br><br>
	<center><a href="javascript:void(0);" onclick="AddNewAddon();"><button>Insert Rune</button></a></center>
	</div>
</div>

</div>
<?php 
for ($i = 0; $i < count($ItemColor); $i++) {
	echo"<script>
	itmCol[parseInt('".$i."', 10)] = '".$ItemColor[$i]."';
	SrvrTmZone = parseInt('".date('Z')."',10);
	</script>";
}
?>
<script>
	getPItemData(document.getElementById("SItmC1S1"));
	StoneSelection(1);
	LoadShopItems();
	LoadPacketItems();
</script>

<?php 
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
