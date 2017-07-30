<?php 
session_start();
include "../config.php";
include "../basefunc.php";
$header_arr = array("error" => "Unknown Error!", "reloaduserlist" => "0", "success" => "", "reloaduserdata" => "0");
$user_arr = array();
SessionVerification();
if ($_SESSION['UD3'] != $AKey2){die();}
$data = json_decode(file_get_contents('php://input'), true);
if (($data)&&(isset($_SESSION['un']))) {
	
	$un=$_SESSION['un'];
	$pw=$_SESSION['pw'];
	$uid=$_SESSION['id'];
	$ma=$_SESSION['ma'];
	$d1 = $data['NameStack'];
	$d2 = $data['PasswordStack'];
	$d3 = $data['Email'];
	$d4 = $data['RealName'];
	$d5 = $data['Gender'];
	$d6 = $data['DateYMD'];
	$d7 = $data['Rank'];	
	$ListUsers=false;
	$expArr=explode("-", $d1);
	$CurUnam=StrToLower(Trim(stripslashes($expArr[0])));
	$CurUId=intval($expArr[1]);
	$OldUnam=StrToLower(Trim(stripslashes($expArr[2])));
	$OldUId=intval($expArr[3]);
	$counter[1]=count($expArr);
	$expArr=explode("-", $d2);
	$CurPw=Trim(stripslashes($expArr[0]));
	$NewPw1=Trim(stripslashes($expArr[1]));
	$NewPw2=Trim(stripslashes($expArr[2]));
	$counter[2]=count($expArr);
	$email=StrToLower(Trim(stripslashes($d3)));
	$rname=Trim(stripslashes($d4));
	$sex=intval(StrToLower(Trim(stripslashes($d5))));
	$bdate=Trim(stripslashes($d6));
	$expArr=explode("-", $d6);
	$counter[3]=count($expArr);
	$rank=intval($d7);
	$changePw=false;
	$validNewPw=false;
	$passVer=true;
	$verifiedPw=false;
	$updatBDate=true;
	$newDateCheck=true;	
	
	$link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
	if ($link->connect_errno) {
		$header_arr["error"]="Sorry, this website is experiencing problems (failed to make a MySQL connection)!";
	}else{	
		$admin=VerifyAdmin($link, $un, $pw, $uid, $ma);
		
		if (($admin!==false)||($uid==$CurUId)){
			if (validate_Date($bdate)){
				if (($counter[1]==4)&&($counter[2]==3)&&($counter[3]==3)){
					if (((strlen($CurUnam)>4)&&(strlen($CurUnam)<21)&&(strlen($CurPw)>4)&&(strlen($CurPw)<21))||((strlen($CurUnam)>3)&&(strlen($CurUnam)<21)&&($admin)&&($CurPw=="")&&($uid!=$OldUId))){
						if (((ctype_alnum($CurUnam))&&(ctype_alnum($CurPw)))||((ctype_alnum($CurUnam))&&($CurPw=="")&&($admin)&&($uid!=$OldUId))){
							if (!(strlen($NewPw1)==0)){
								if ((strlen($NewPw1)>4)&&(strlen($NewPw1)<21)&&(strlen($NewPw2)>4)&&(strlen($NewPw2)<21)&&(ctype_alnum($NewPw1))&&(ctype_alnum($NewPw2))){
									if ($NewPw1==$NewPw2){
										$validNewPw=true;
										$changePw=true;
									}else{
										$header_arr["error"]="New password and password again must be same";
									}
								}else{
									$header_arr["error"]="New password & password again must be minimum 6 alphanumeric character";
								}
							}
						
							if ((($changePw)&& ($validNewPw)) or ($changePw===false)){
								if (validEmail($email)){
									if (($CurUId>15)&&($CurUId % 16 == 0)){	
										if ((($rank==0)||($rank==1))&&(($sex>=0)||($sex<3))){
											if ($PassType==1){
												$Salt1="0x".md5($OldUnam.$CurPw);
											}else if ($PassType==2){
												$Salt1=base64_encode(hash('md5',strtolower($OldUnam).$CurPw, true));
											}else if ($PassType==3){
												$Salt1="0x".md5($OldUnam.$CurPw);
											}
											if (!($admin)){$CurUnam=$OldUnam;$OldUId=$CurUId;}
											if (!($changePw)){
												$NewPw1=$CurPw;
											}
											if ($PassType==1){
												$Salt2="0x".md5($CurUnam.$NewPw1);
											}else if ($PassType==2){
												$Salt2=base64_encode(hash('md5',strtolower($CurUnam).$NewPw1, true));
											}else if ($PassType==3){
												$Salt2="0x".md5($CurUnam.$NewPw1);
											}
											$test2=0;
											if (($admin) && ($CurUId != $OldUId)){
												$test2=CountMysqlRows($link, 1, $CurUId);
											}
											$query = "SELECT ID, name, email, passwd, truename, birthday, gender FROM users WHERE ID=? AND name=?";
											$statement = $link->prepare($query);
											$statement->bind_param('is', $OldUId, $OldUnam);
											$statement->execute();
											$statement->bind_result($LID, $Lname, $Lmail, $LPw, $Lrname, $Lbday, $Lsex);
											$statement->store_result();
											$result = $statement->num_rows;
											$checkIdUsed=0;
											if (($admin) && ($CurUId != $OldUId)){
												$checkIdUsed=CountMysqlRows($link, 1, $CurUId);
											}
											if ($checkIdUsed==0){
												if ($result==1)	{
													while($statement->fetch()) {
													if (($admin) && ($uid != $OldUId)){
														$passVer=false;
														$verifiedPw=true;
													}
												
													if ($passVer){
														if ($PassType==1){
															if ($LPw==$Salt1){
																$verifiedPw=true;
															}else{
																$header_arr["error"]="Wrong username and password combination, don't cheat!";
															}
														}else if($PassType==2){
															if ($LPw==$Salt1){
																$verifiedPw=true;
															}else{
																$header_arr["error"]="Wrong username and password combination, don't cheat!";
															}
														}else if($PassType==3){	
															$LPw = addslashes($LPw);
															$rs=mysqli_query($link,"SELECT fn_varbintohexsubstring (1,'$LPw',1,0) AS result");
															$GetResult = mysqli_fetch_array($rs, MYSQLI_BOTH);
															$LDPw = $GetResult['result'];
															if ($LDPw==$Salt1){
																$verifiedPw=true;
															}else{
																$header_arr["error"]="Wrong username and password combination, don't cheat!";
															}														
														}
													}
													$expArr = explode("-", $bdate);
													if (($expArr[0] == "0000")||($expArr[1] == "00")||($expArr[2] == "00")){
														if ($bdate != "0000-00-00"){
															$header_arr["error"]="Wrong birth date, please select year, month and day!";
															$newDateCheck=false;
														}else{
															$updatBDate=false;
														}
													}
													
													if (($verifiedPw)&&($newDateCheck)){
														$Lrank=CountMysqlRows($link,5,$OldUId);
														$count7=0;
														$genderArr[0]="";
														$genderArr[1]="Male";
														$genderArr[2]="Female";
														$rankArr[0]="Member";
														$rankArr[1]="Game Master";

														if ($Lmail != $email){
															$count7=1;
															if ($uid==$OldUId){$_SESSION['ma']=$email; $Lmail=$email;}
														}
														if (($updatBDate) && ($newDateCheck)){
															$bdate=$bdate." 10:00:00";
															if ($Lbday != $bdate){
																$count7=1;
																$Lbday = $bdate;
															}
														}
														
														if ($Lrname != $rname){
															$count7=1;
															$Lrname = $rname;
														}
														if ($Lsex != $sex){
															$count7=1;
															$Lsex = $sex;
														}
														if ($count7>0){
															$stmt = $link->prepare("UPDATE users SET email = ?, birthday = ?, truename = ?, gender = ? WHERE name=? AND ID=?");
															$stmt->bind_param('sssisi', $Lmail, $Lbday, $Lrname, $Lsex, $OldUnam, $OldUId);
															$stmt->execute(); 
															$stmt->close();
														}

														if ($rank != $Lrank){
															if (($rank==1)&&($Lrank==0)){
																$count7=1;
																//update member to gm
																$rs1=mysqli_query($link, "call addGM('$CurUId', '1')");
															}elseif(($rank==0)&&($Lrank==1)){
																$count7=1;
																//downgade gm to member
																$stmt = $link->prepare("DELETE FROM auth WHERE userid = ?");
																$stmt->bind_param('i', $OldUId);
																$stmt->execute(); 
																$stmt->close();
															}
															$ListUsers=true;
														}
														
														if ($CurUnam!=$OldUnam){
															$count7=1;
															if ($uid==$OldUId){$_SESSION['un']=$CurUnam;}
															//save username
															$changePw=true;
															$stmt = $link->prepare("UPDATE users SET name = ? WHERE ID=?");
															$stmt->bind_param('si', $CurUnam, $OldUId);
															$stmt->execute(); 
															$stmt->close();
															$ListUsers=true;
														}
														
														if ($OldUId!=$CurUId){
															$count7=1;
															if ($uid==$OldUId){
																if ($admin){
																	$_SESSION['id']=$CurUId;
																	$filen='./config.php';
																	$fileno='./config_old.php';
																	$oldConfId = "AdminId=".$OldUId.";";
																	$newConfId = "AdminId=".$CurUId.";";
																	$str=file_get_contents($filen);
																	$str=str_replace($oldConfId, $newConfId, $str);
																	$oldConfId = 'AdminPw="'.$AdminPw.'";';
																	if ($PassType==1){
																		$newConfId = 'AdminPw="0x'.md5($CurUnam.$NewPw1).'";';
																	}elseif ($PassType==2){
																		$newConfId = 'AdminPw="'.base64_encode(hash('md5',strtolower($CurUnam).$NewPw1, true)).'";';
																	}elseif ($PassType==3){
																		$newConfId = 'AdminPw="'.md5($CurUnam.$NewPw1).'";';
																	}
																	
																	$str=str_replace($oldConfId, $newConfId, $str);
																	chmod($filen, 0777);
																	rename($filen, $fileno);
																	file_put_contents($filen, $str);
																	chmod($filen, 0755);
																}
															}
															//change id
															$stmt = $link->prepare("UPDATE users SET ID = ? WHERE ID=?");
															$stmt->bind_param('ii', $CurUId, $OldUId);
															$stmt->execute(); 
															$stmt->close();
															
															if (($admin) && ($CurUId != $OldUId) && ($rank == $Lrank) && ($Lrank==1)){
																//remove gm rank if old id was game account and re add to new id
																$stmt = $link->prepare("DELETE FROM auth WHERE userid = ?");
																$stmt->bind_param('i', $OldUId);
																$stmt->execute(); 
																$stmt->close();
																$rs1=mysqli_query($link, "call addGM('$CurUId', '1')");
															}
															$ListUsers=true;
														}
														
														if ($changePw){
															$count7=1;
															$_SESSION['pw']=$NewPw1;
															if ($PassType==3){
																mysqli_query ($link,"CALL changePasswd ('$CurUnam', $Salt2)");
															}else{
																mysqli_query ($link,"CALL changePasswd ('$CurUnam', '$Salt2')");
															}
															if (($admin)&&($uid==$CurUId)){
																$filen='../config.php';
																$fileno='../config_old.php';
																$str=file_get_contents($filen);
																$oldConfId = 'AdminPw="'.$AdminPw.'";';
																if ($PassType==1){
																	$newConfId = 'AdminPw="0x'.md5($CurUnam.$NewPw1).'";';
																}elseif ($PassType==2){
																	$newConfId = 'AdminPw="'.base64_encode(hash('md5',strtolower($CurUnam).$NewPw1, true)).'";';
																}elseif ($PassType==3){
																	$newConfId = 'AdminPw="'.md5($CurUnam.$NewPw1).'";';																	
																}
																
																$str=str_replace($oldConfId, $newConfId, $str);
																chmod($filen, 0777);
																rename($filen, $fileno);
																file_put_contents($filen, $str);
																chmod($filen, 0755);
															}
														}
														
														if ($count7>0){$header_arr["success"]="Done!";$header_arr["reloaduserdata"]="1";}else{$header_arr["success"]="Unchanged!";}
														if ($ListUsers !== false){
															$header_arr["reloaduserlist"]="1";
														}
													}else{
														$header_arr["error"]="Something went wrong! Maybe birthday not setted?";
													}
														
												}
												}else{
													$header_arr["error"]="Not exist this user with this username or id!";
												}
											}else{
												$header_arr["error"]="This user id is in use! Choose another!";
											}
											$statement->close();
										}else{
											$header_arr["error"]="Invalid data!";
										}
									}else{
										$header_arr["error"]="Incorrect id, your id must be mutiples of 16. Example: 16, 32, 48, 64, 80....";
									}
								}else{
									$header_arr["error"]="Incorrect email address, please type your email address!";
								}
							}else{
								$header_arr["error"]="Leave new password field blank or use a valid password with atleast 6 alphanumeric character!";
							}
						}else{
							$header_arr["error"]="Username and password must have alphanumeric characters!";
						}
					}else{
						$header_arr["error"]="Username and password must be minimum 6 character!";
					}
				}else{
					$header_arr["error"]="Invalid data";
				}
			}else{
				$header_arr["error"]="Invalid birth date data";
			}
		
		}
	}
	mysqli_close($link);
}

function validateDate($date, $format = 'Y-m-d H:i:s'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

if ($header_arr["success"]!=""){$header_arr["error"]="";}
$return_arr = array();
$return_arr[0]=$header_arr;
$return_arr[1]=$user_arr;
echo json_encode($return_arr);	
?>
