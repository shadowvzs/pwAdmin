<?php 
session_start();
include "../config.php";
include "../basefunc.php";
include "../php/sendgamemail.php";
include("../php/packet_class.php");
$header_arr = array("error" => "Unknown Error!", "success" => "", "gold" => "", "point" => "");
SessionVerification();
if ($_SESSION['UD3'] != $AKey2){die();}
$data = json_decode(file_get_contents('php://input'), true);
if (($data)&&(isset($_SESSION['un']))) {
	if ((isset($data['Amount']))&&(isset($data['buyWith']))&&(isset($data['shopid']))&&(isset($data['rolename']))&&(isset($data['transWith']))&&(isset($data['IData']))&&(isset($data['roleid']))&&(isset($data['RuneList']))){
		$idata = trim($data['IData']);
		$idata = str_replace('"', '', $idata);
		$idata = str_replace('>', '', $idata);
		$idata = str_replace('<', '', $idata);
		$idata = str_replace("'", '', $idata);
		$idata = str_replace("=", '', $idata);
		$idata = str_replace('|', '#', $idata);
		$idata = str_replace('@', '+', $idata);
		$un=$_SESSION['un'];
		$pw=$_SESSION['pw'];
		$uid=$_SESSION['id'];
		$userid=$uid;
		$ma=$_SESSION['ma'];
		$RuneList=trim($data['RuneList']);
		//check server if running
		if (strpos($idata, "#") !== false){
			$iArr = explode("#", $idata);
			if (count($iArr) == 20){
				$conn = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
				if (($conn->connect_error)||(mysqli_connect_error())) {
					$header_arr["error"]="Cannot connect to mysql database";
				}else{		
					$valid = false; // init as false				
					if ($WShopDB==1){
						$query = "SELECT id FROM webshop WHERE pcst=? AND gcst=? AND itmid=? AND imask=? AND iproc=? AND imax=? AND expir=? AND octet=? AND stim=?";
						$statement = $conn->prepare($query);
						$statement->bind_param('iiiiiiiss', $iArr[0], $iArr[1], $iArr[7], $iArr[8], $iArr[9], $iArr[11], $iArr[14], $iArr[15], $iArr[19]);
						$statement->execute();
						$statement->store_result();
						$result = $statement->num_rows;	
						if ($result>0){
							$valid = TRUE;
						}
						$statement->free_result();
					}

					if ($RuneList != ""){
						$newOctet=CheckAndFixRunes($idata, $RuneList);
						if ($newOctet!=""){
							$iArr[15]=$newOctet;
						}
					}
					if ($valid !== false){
						$buyWith = intval($data['buyWith']);
						$transWith = intval($data['transWith']);
						$Amount = intval($data['Amount']);
						$roleId = intval($data['roleid']);
						$ShopId = intval($data['shopid']);
						$roleName = $data['rolename'];
						if (($buyWith > 0) && ($transWith > 0) && ($Amount > 0) && ($roleId > 0) && ($iArr[11] >= $Amount)){
							$sockres = @FSockOpen($LanIP, $ServerPort, $errno, $errstr, 10);
							if (!$sockres){
								$header_arr["error"]="Server is offline";
							}else{
								@FClose($sockres);
								//on
								$buyable=ShopItemTimeVerification($iArr[19]);
								$Discount=ShopItemDiscVerification($iArr[19]);
								if ($Discount<0){$Discount=0;}
								if ($Discount>100){$Discount=100;}
								if ($buyable !== false){
									if ($buyWith == 1){
										if (UserOnlineCheck($conn, $userid) == 0){
											$GRoleData=GetRoleData($roleId, $ServerVer);
											$gold=$GRoleData['pocket']['money'];
											$price = $iArr[0]*$Amount-intval($iArr[0]*$Amount*$Discount/100);
											if ($gold >= $price){
												$GRoleData['pocket']['money']=$gold-$price;
												if ($transWith == 1){
													$expir = $iArr[14];
													if ($expir>0){$expir=$expir+time();}
													$sent=false;
													PutRoleData($roleId, $GRoleData, $ServerVer);
													$GRoleData=GetRoleData($roleId, $ServerVer);
													if (($gold-$GRoleData['pocket']['money']) == $price){
														if (SysSendMail($roleId, ("[SHOP]: ".$iArr[2]), ("Thank you for bought this item from web!"), $iArr[7], $Amount, $iArr[11], $iArr[15], $iArr[9], $expir, $iArr[12], $iArr[13], $iArr[8], 0) == 0){
															$sent=true;
															ShopLog($conn, $buyWith, $price, $roleId, $roleName, $ShopId, $idata, $Amount);
															PutRoleData($roleId, $GRoleData);	
															$header_arr["success"]="Thank you for buying, your item sent!";	
														}else{
															$GRoleData['pocket']['money']=$gold+$price;
															PutRoleData($roleId, $GRoleData, $ServerVer);
															$header_arr["error"]="Mail sending failed!";
														} 
														$header_arr["gold"]=$GRoleData['pocket']['money'];
													}else{
														$header_arr["error"]="In game gold transaction failed!";
													}
												}											
											}else{
												$header_arr["error"]="Insufficient gold (need ".$price." gold)!";	
											}
										}else{
											$header_arr["error"]="Try again after you log out from game!";	
										}
									}else if ($buyWith == 2){
										//check user point & gold
										$query = "SELECT VotePoint FROM users WHERE ID=?";
										$statement = $conn->prepare($query);
										$statement->bind_param('i', $userid);
										$statement->execute();
										$statement->bind_result($LWebPoint);
										$statement->store_result();
										$result = $statement->num_rows;
										if ($result) {
											while($statement->fetch()) {
												$WPoint=$LWebPoint;
											}
											$price = $iArr[1]*$Amount-intval($iArr[1]*$Amount*$Discount/100);
											
											if ($WPoint >= $price){
												$NPoint = $WPoint - $price;
												$query = "UPDATE users SET VotePoint = $NPoint WHERE ID=?";
												$stmt = $conn->prepare($query);
												$stmt->bind_param('i', $userid);
												$stmt->execute(); 
												$stmt->close();											
												if ($transWith == 1){
													$expir = $iArr[14];
													if ($expir>0){$expir=$expir+time();}
													if (SysSendMail($roleId, ("[SHOP]: ".$iArr[2]), ("Thank you for bought this item from web!"), $iArr[7], $Amount, $iArr[11], $iArr[15], $iArr[9], $expir, $iArr[12], $iArr[13], $iArr[8], 0) == 0){
														$header_arr["point"]=$NPoint;
														ShopLog($conn, $buyWith, $price, $roleId, $roleName, $ShopId, $idata, $Amount);
														$header_arr["success"]="Thank you for buying, your item sent!";	
													}else{
														$query = "UPDATE users SET VotePoint = $WPoint WHERE ID=?";
														$stmt = $conn->prepare($query);
														$stmt->bind_param('i', $userid);
														$stmt->execute(); 
														$stmt->close();		
														$header_arr["point"]=$WPoint;	
														$header_arr["error"]="Mail sending failed!";														
													} 	
												}
											}else{
												$header_arr["error"]="Insufficient point (".($price-$WPoint)." missing)!";
											}
										}
										$statement->free_result();
									}
								}else{
									$header_arr["error"]="Item at moment not buyable!";
								}
							}
						}
					}else{
						$header_arr["error"]="Item not found!";	
					}	
				}
				$conn->close();				
			}			
		}
	}
}

function ShopLog($conn, $buyWith, $price, $roleId, $roleName, $shopid, $idata, $amount){
	global $username;
	global $userid;
	global $WebShopLog;
	global $WShopLogDel;
	
	if ($WebShopLog !== false){
		$mysqltime = date ("Y-m-d H:i:s", time());
		$stmt = $conn->prepare("INSERT INTO wshoplog (user, uname, role, rname, buydate, currency, price, amount, idata, shopid) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("isissiiisi", $userid, $username, $roleId, $roleName, $mysqltime, $buyWith, $price, $amount, $idata, $shopid);
		$stmt->execute(); 
		$stmt->close();		
		if ($WShopLogDel>0){
			$query = "DELETE FROM wshoplog WHERE DATEDIFF(NOW(), DATE(buydate)) > ".$WShopLogDel;
			$conn->query($query);
		}
	}
}

function UserOnlineCheck($con, $id){
	if ($con->connect_errno) {
		return "";
		//echo "<script>alert('Sorry, this website is experiencing problems (failed to make a MySQL connection)!');</script>";
	}else{
		$query = "SELECT uid FROM point WHERE uid=? AND zoneid IS NOT NULL";
		$statement = $con->prepare($query);
		$statement->bind_param('i', $id);
		$statement->execute();
		$statement->store_result();
		$result = $statement->num_rows;
		$count = $result;
		$statement->free_result();
	}
	return $count;
}

function ShopItemTimeVerification($iDate){
	if (strpos($iDate, ' ') !== false){
		$ISDate = explode(" ",$iDate);
		if (($ISDate[0]==0)||($ISDate[0]==3)){
			return true;
		}else{
			if ($ISDate[0]==1){
				$cTimeStamp=time();
				if (($cTimeStamp<intval($ISDate[2]))||($cTimeStamp>intval($ISDate[3]))){
					return false;
				}else{
					return true;
				}
			}elseif ($ISDate[2]){
				$hour1 = explode(" ",$ISDate[2]);
				$hour2 = explode(" ",$ISDate[3]);
				$hour3 = date('H:i');
				$dtime1 = intval($hour1[0])*60+intval($hour1[1]);
				$dtime2 = intval($hour2[0])*60+intval($hour2[1]);
				$dtime3 = intval($hour3[0])*60+intval($hour3[1]);
				if (($dtime3<$dtime1)||($dtime3>$dtime2)){
					return false;
				}else{
					return true;
				}					
			}
			
		}
	}
	return false;
}


function CheckAndFixRunes($idata, $RuneList){
	$iArr = explode("#", $idata);
	if ($RuneList != ""){
		$MType=substr($iArr[4], 0, 1);
		$SType=intval(substr($iArr[4], 1));
		if (($MType=="W")||($MType=="A")||($MType=="J")||($MType=="O")&&(($SType==6)||($SType==3))){
			//we need to be sure so need recalculate again where starting the addons in octet so we need check each case...
			$Octet=$iArr[15];
			$x=0;
			$olen=strlen($Octet);
			if ($MType=="W"){
				if ($olen > 151){
					$NameLen = intval(hexdec(substr($Octet,46,2))/2);
					$SocketC = intval(hexdec(substr($Octet,136+$NameLen*4,2))); 
					$x=$NameLen*4+$SocketC*8+144+8;
				}
			}elseif(($MType=="A")||(($MType=="O")&&($SType==3))){
				if ($olen > 135){
					$NameLen = intval(hexdec(substr($Octet,46,2))/2);
					$SocketC = intval(hexdec(substr($Octet,120+$NameLen*4,2))); 
					$x=$NameLen*4+$SocketC*8+128+8;
				}					
			}elseif($MType=="J"){
				if ($olen > 135){
					$NameLen = intval(hexdec(substr($Octet,46,2))/2);
					$SocketC = intval(hexdec(substr($Octet,120+$NameLen*4,2))); 
					$x=$NameLen*4+$SocketC*8+128+8;
				}		
			}elseif(($MType=="O")&&($SType==6)){				
				$x=104;
			}
			
			$Runes=explode("*",$RuneList);
			$before=substr($Octet,0,$x);
			$after=substr($Octet,$x);
			$alen=strlen($after);
			$newAfter=$after;
			for ($i = 0; $i < count($Runes); $i++) {
				$pos = strpos($after, $Runes[$i]);
				if ($pos !== false){
					if (($pos+24)<=$alen){
						$RawRuneTime=substr($after,$pos+16,8);
						$RuneTime=intval(ConvRevHexToDec($RawRuneTime))*60+time();
						$RunTimeHex=ConvDecToRevHex($RuneTime);
						$befAft=substr($newAfter,0,$pos+16);
						$aftAft=substr($newAfter,$pos+24);
						$newAfter=$befAft.$RunTimeHex.$aftAft;
					}
				}
			}
			return $before.$newAfter;
		}
	}
	return $iArr[15];
}

function ShopItemDiscVerification($iDate){
	if (strpos($iDate, ' ') !== false){
		$ISDate = explode(" ",$iDate);
		if ($ISDate[0]==3){
			$cTimeStamp=time();
			if (($cTimeStamp<intval($ISDate[2]))||($cTimeStamp>intval($ISDate[3]))){
				return 0;
			}else{
				return intval($ISDate[4], 10);
			}
		}
	}
	return 0;
}


if ($header_arr["success"]!=""){$header_arr["error"]="";}
$return_arr = array();
$return_arr[0]=$header_arr;
echo json_encode($return_arr);	
?>
