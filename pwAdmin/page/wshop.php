<?php
//session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../css/ddmenu1.css">
<link rel="stylesheet" type="text/css" href="../css/wshop.css">
<link rel="stylesheet" type="text/css" href="../css/windowstyle.css">
<script src="../js/conv.js"></script>
<script src="../js/refined.js"></script>
<script src="../js/cards.js"></script>
<script src="../js/baseitemdata.js"></script>
<script src="../js/wshop.js"></script>
<style>

</style>
</head>
<body>
<?php 
include "../config.php";
include "../basefunc.php";
include "./cpanel.php";
include "../php/packet_class.php";
include "../php/pw_items_ext.php";
if ($WebShop!==true){
	die();
}
SessionVerification();

$WPoint = 0;
$IGGold = 0;

$un=$_SESSION['un'];
$pw=$_SESSION['pw'];
$uid=$_SESSION['id'];
$Salt=md5($un.$pw);
$ma=$_SESSION['ma'];
$link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
$query = "SELECT VotePoint FROM users WHERE ID=?";
$statement = $link->prepare($query);
$statement->bind_param('i', $uid);
$statement->execute();
$statement->bind_result($LWebPoint);
$statement->store_result();
$result = $statement->num_rows;
if (!$result) {
	echo "<script>alert('User not exist');</script>";
	exit;
}else{
	while($statement->fetch()) {
		$WPoint=$LWebPoint;
	}
}
mysqli_close($link);


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
echo"<script> maxClass = parseInt('".$allClass."', 10);</script>";
$max = count($Addons);
for ($i = 1; $i <= $max; $i++) {
	echo"<script>AddonL[parseInt('".$i."', 10)] = '".$Addons[$i]."';</script>";
}
for ($i = 1; $i <= count($AddonsS); $i++) {
	echo"<script>AddonL[parseInt('".($i+$max)."', 10)] = '".$AddonsS[$i]."';</script>";
}
$c = 0;
for ($n = 1; $n <= count($SoulStone); $n++) {
	for ($i = 1; $i <= count($SoulStone[$n]); $i++) {
		$c++;
		echo"<script>SockL[parseInt('".$c."', 10)] = '".$SoulStone[$n][$i]."';</script>";
	}
}

for ($i = 1; $i <= count($ElfSkills); $i++) {
	echo"<script>ElfSkillL[parseInt('".$i."', 10)] = '".$ElfSkills[$i]."';</script>";
}

for ($i = 1; $i <= count($PetSkill); $i++) {
	echo"<script>PetSkillL[parseInt('".$i."', 10)] = '".$PetSkill[$i]."';</script>";
}

for ($i = 1; $i <= count($FashCol); $i++) {
	echo"<script>FashCol[parseInt('".$i."', 10)] = '".$FashCol[$i]."';</script>";
}


$CharCount=0;
$GetUserRolesArg = new WritePacket();
$GetUserRolesArg -> WriteUInt32(-1); // always
$GetUserRolesArg -> WriteUInt32($uid); // userid
$GetUserRolesArg -> Pack(0xD49);//0xD49
if ($GetUserRolesArg -> Send("localhost", 29400)){ // send to gamedbd
	//return;
	$GetUserRolesRes = new ReadPacket($GetUserRolesArg); // reading packet from stream
	$GetUserRolesRes -> ReadPacketInfo(); // read opcode and length
	$GetUserRolesRes -> ReadUInt32(); // always
	$GetUserRolesRes -> ReadUInt32(); // retcode
	$CharCount = $GetUserRolesRes -> ReadCUInt32();
	
	for ($i = 0; $i < $CharCount; $i++){
		$roleid = $GetUserRolesRes -> ReadUInt32();
		$rolename = $GetUserRolesRes -> ReadUString();
		$RoleNameArr[$i] = $rolename;
		$RoleIdArr[$i] = $roleid;
	}
}
?>
<div id="Bckgrnd"> </div>
<div id='BodyCont'>

<?php 
if ($CharCount > 0){
	echo"<script> 
	RMoney = parseInt('".$IGGold."', 10);
	UPoint = parseInt('".$WPoint."', 10);
	</script>";
	
	echo"<div style='position:absolute;top: 5px; color: #fff; z-index:1; right:10px;'><table border='0' style='color: #fff;font-size: 12px;font-family: arial;border-collapse: collapse;'>
	<tr><td>
	<select id='Sel_Role' onChange='ChangeRole();'>";
		for ($i = 0; $i < $CharCount; $i++){
			echo"<option value='".$RoleIdArr[$i]."'>".$RoleNameArr[$i]."</value>";//$RoleNameArr[$i]
		}
	
	echo"</select>&nbsp;&nbsp;&nbsp;
	</td><td> Gold: <span id='Role_Gold'>".$IGGold."</span></td></tr><tr><td> &nbsp; </td><td>Point: <span id='User_Point'>".$WPoint."</span></td></tr></table>
	</div>";
	
}


	$a=0;
	echo"<ul class='menu'>";
	for ($a = 1; $a <= count($ShopMenu); $a++){
		$b=0;
		$c=0;
		$tmpArr = explode("#", $ShopMenu[$a][0][0]);
		if ($ServerVer >= $tmpArr[1]){
			echo "<li><a href='javascript:void(0);' onClick=ShopItemFilter('".$a.$b.$c."');>".$tmpArr[0]."</a>";
				if (count($ShopMenu[$a]) > 1){
					echo "<ul class='submenu'>";
					for ($b = 1; $b < count($ShopMenu[$a]); $b++){
						$c=0;
						$tmpArr = explode("#", $ShopMenu[$a][$b][0]);
						if ($ServerVer >= $tmpArr[1]){
							echo"<li><a href='javascript:void(0);' onClick=ShopItemFilter('".$a.$b.$c."');>".$tmpArr[0]."</a>";
							if (count($ShopMenu[$a][$b]) > 1){
								$DMH = ($b-1) * 40;
								echo "<ul class='deepsubmenu' style='top: ".$DMH."px; width:130px;'>";
								for ($c = 1; $c < count($ShopMenu[$a][$b]); $c++){
									$tmpArr = explode("#", $ShopMenu[$a][$b][$c]);
									if ($ServerVer >= $tmpArr[1]){
										echo"<li><a href='javascript:void(0);' onClick=ShopItemFilter('".$a.$b.$c."');>".$tmpArr[0]."</a></li>";
									}
								}
								echo "</ul>";
							}					
							echo"</li>";
						}
					}
					echo "</ul>";
				}
			echo"</li>";
		}
	}
	echo"</ul>";
	

	echo "<div id='iconHolder'>";
	$c=0;
	if ($WShopDB==1){
		$conn = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
		if (($conn->connect_error)||(mysqli_connect_error())) {
			echo"<script>parent.alert('Cannot connect to mysql database');</script>";
		}else{
			$result=$conn->query("SELECT * FROM webshop");
			$count=$result->num_rows;
			if ($count>0) {
				$id=-1;
				while($row=$result->fetch_row()) {
					$c++;
					$line=implode("#", (array_slice($row, 1)));
					$itemList[$c]=$line;
				}
			}
			$result->free();				
		}	
		$conn->close();		
	}

	for ($b = 1; $b <= $c; $b++){
		$tmpArr = explode("#",$itemList[$b]);
		$showB = "none";
		if (intval(substr($tmpArr[16], 0, 1)) != 1){$showB="none";}
		echo "<span class='iconList' style='display:".$showB.";' id='iconId".$b."'>
			<a href='javascript:void(0);' title='Click if you want buy ".$tmpArr[2]."\n' onmouseover='CreateBubble(".$b.");' onclick='SelectIcon(".$b.");LockItem=false;CreateBubble(".$b.");BuyWindowManager(".$b.");LockItem=true;'>
			<img src='../images/icons/".$tmpArr[7].".gif' onerror='this.onerror=null;this.src=\"../images/icons/0.gif\";'>
			</a><br>
			</span>";
		echo "<script>
		ShopInd++;
		ShopItm[ShopInd] = '".$itemList[$b]."';
		</script>";		
	}

?>
</div>
<div id='PreviewDiv'>
	<div id='CardClass' style='text-align: center;'><span style='text-shadow: -1px 0 #000, 0 1px #000, 1px 0 #000, 0 -1px #000;color:#fff;'>For Class:</span> <select id='Inp_C_Clss' style='vertical-align:middle;width: 120px;' onChange="tmp=LastId;LastId=0;CSSwap=true;CreateBubble(tmp);CSSwap=false;">
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
	</div>
	<div id='TheItemDiv' class='bubble'><span id='itmName'></span><span id="itmRef"></span><span id="itmSock"></span><br><span id="itmData"></span> </div>
</div>
<div id="BuyWindow" style="display:none; font-size: 16px;font-family: arial; color: #fff;">
	<div class="WindowHead_Dark1"><b><span id='Buy_Name'>Item Name</span></b><div class="WindowClose_Dark1" onclick="SelectIcon(0);document.getElementById('BuyWindow').style.display='none';LockItem=false;">&#10006;</div></div>
	<div class="WindowCont_Dark1">
		<input type="text" maxlength="9" id="Buy_ShopId" value='' style='width: 70px; display: none; z-index:0;'>
		<span id="Buy_Amount"><b>Amount:</b> <input type="text" maxlength="6" id="Buy_QTY" value='1' style='width: 70px;' onkeyup="ChangeBuyingAmount();" onkeydown="ChangeBuyingAmount();" onchange="ChangeBuyingAmount();"><i> (max: <span id="Buy_MaxQty">1</span>)</i><br></span>
		<span id="Buy_Price1" style='display:inline;'><span id="Buy_Cost1"><b>Cost: </b></span><input type="radio" name="Buy_PriceType" id="Buy_Price_Gold" checked> <span id="Buy_FPrice1" style='color: #ff9; font-weight: bold;'>1</span> Gold &nbsp;&nbsp;&nbsp; <span style="font-size:12px;color:#ff0;"><i>(Account must be <font color="red">offline</font>)</i></span><br></span>
		<span id="Buy_Price2" style='display:inline;'><span id="Buy_Cost2"><b>Cost: </b></span><input type="radio" name="Buy_PriceType" id="Buy_Price_Point"> <span id="Buy_FPrice2" style='color: #ff9; font-weight: bold;'>1</span> Point<br></span>
		<div class="WindowFoot_Dark1"><a href='javascript:void(0);' onclick='BuyThisItem();'><button>Buy this item</button></a></div>
	</div>
</div>
<?php
	echo "<script>SrvrTmZone = parseInt('".date('Z')."',10);</script>";
	if ($c == 0){
		echo "<div style='position:relative;width:100%;height:100%;top:50%;text-align: center; font-face: arial; font-size: 20px;'>Sorry, currently no item here.</div>";
	}else{
		echo "<script> ShopItemFilter('100');CreateBubble(1); </script>";
	}
	echo "</div>";
	
if ($CharCount > 0){
	echo "
	<script>ChangeRole();</script>
	";
}
?>
</body>
</html>