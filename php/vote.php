<?php 
ini_set('display_errors', 1);
session_start();
include "../config.php";
include "../basefunc.php";
date_default_timezone_set('UTC');

$header_arr = array("error" => "Unknown Error!", "success" => "", "voteurl" => "", "voteid" => "0", "votesecleft" => "0");
SessionVerification();
if ($_SESSION['UD3'] != $AKey2){die();}
$data = json_decode(file_get_contents('php://input'), true);
$storagePath = "../votes/";
$storageVoteInfoFile = "$storagePath"."voteInfo.json";

$header_arr["error"]="Invalid vote id!";
if (($data)&&(isset($_SESSION['un']))) {
	// $header_arr["error"]="Try relog!";
	$lastVote = isset($_SESSION['lastVote']) ? $_SESSION['lastVote'] : 0;
	$sinceLastVote = time() - $lastVote;
	if ($sinceLastVote < 30) {
		$header_arr["error"]="Cooldown for the vote is 30 sec";
	} else if ((isset($data['voteid'])) && (isset($_SESSION['id']))){
		$vId = intval($data['voteid']);
		if (($vId <= count($VoteUrl))&&($vId > 0)){
			$VoteLink = $VoteUrl[$vId];
			if ((isset($VoteOut[$vId])) && $VoteOut[$vId] == true) {
				$header_arr["voteid"]=$vId;	
				$header_arr["votesecleft"]=60*60*$VoteInterval;
				$header_arr["success"]="Thank you for voteing";
				$header_arr["voteurl"]=$VoteLink;
				$header_arr["error"]="";
				$return_arr = array();
				$return_arr[0]=$header_arr;
				echo json_encode($return_arr);
				die();
			}
			if (!isset($VoteLink)){$VoteLink="";}
			if (strlen($VoteLink) > 3){
				$_SESSION['lastVote'] = time();
				$un=$_SESSION['un'];
				$pw=$_SESSION['pw'];
				$uid=$_SESSION['id'];
				$ma=$_SESSION['ma'];
				$VoteIntSec = 3600*$VoteInterval;
				$TIME=gmdate("Y-m-d H:i:s");					
				$sepChar = ",";	
				
				$link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
				if ($link->connect_errno) {
					$header_arr["error"]="Sorry, this website is experiencing problems (failed to make a MySQL connection)!";
				}						
				$statement = $link->prepare("SELECT VotePoint, VoteDates FROM users WHERE name=? AND email=? AND ID=?");
				$statement->bind_param('ssi', $un, $ma, $uid);
				$statement->execute();
				$statement->bind_result($VPoint, $VDates);
				$statement->store_result();
				$count = $statement->num_rows;
				
				if($count>0){
					while($statement->fetch()) {
						$expArr=validDateStack($VDates, $VoteUrl);
						if (count($expArr) < $vId){
							$header_arr["error"]="Something wrong, the vote site not found!";
						}else{
							$secDiff = $VoteIntSec - DateDifference($expArr, $vId);
							$header_arr["votesecleft"]=$secDiff;
							$header_arr["voteid"]=$vId;
							
							if (!isset($_SESSION['sId'])){$_SESSION['sId']=rand();}
							$filename = $un."_".$vId.".txt";
							$filePath = $storagePath.$filename;
							$isCheater = false;
							if (file_exists($filePath)) {
								$json = json_decode(file_get_contents($filePath), true);
								$isCheater = (time() - intval($json['date'])) < 43200;
							}
							
							$userIP = $_SERVER['REMOTE_ADDR'];
							if (file_exists($storageVoteInfoFile)) {
								$infoJSON = json_decode(file_get_contents($storageVoteInfoFile), true);
							} else {
								$infoJSON = [];
							}
							
							if (!isset($infoJSON[$un])) {
								$infoJSON[$un] = [];
							}
							if (!in_array($userIP, $infoJSON[$un])) {
								$infoJSON[$un][] = $userIP;
							}
							file_put_contents($storageVoteInfoFile, json_encode($infoJSON, JSON_PRETTY_PRINT));
							
							$logData=array();
							$logData['userId']=$uid;
							$logData['userName']=$un;
							$logData['REMOTE_ADDR']=$userIP;
							$logData['HTTP_X_FORWARDED_FOR']=getenv('HTTP_X_FORWARDED_FOR');
							$logData['date']=$TIME;
							$logData['voteid']=$vId;							
							$logData['VDates']=$expArr;							
							$logData['HTTP_CLIENT_IP']=isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : '-';
							$logData['HTTP_USER_AGENT']=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '-';
							$logData['HTTP_REFERER']=isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '-';
							$logData['GEOIP_COUNTRY_CODE']=isset($_SERVER['GEOIP_COUNTRY_CODE']) ? $_SERVER['GEOIP_COUNTRY_CODE'] : '-';
							$logData['cheater']=$isCheater;
							$logData['secDiff']=$secDiff;
							$logData['VoteIntSec']=$VoteIntSec;
							$logData['sId']=$_SESSION['sId'];
							
							file_put_contents($filePath, json_encode($logData, JSON_PRETTY_PRINT));
					
							if ($isCheater) {
								$header_arr["error"]="Do not try to cheat!";
							} else if ($secDiff < 0){
								$header_arr["votesecleft"]=60*60*$VoteInterval;
								$header_arr["success"]="Thank you for voteing";
								if (($VPoint>($MaxWebPoint-$VoteReward))&&($VoteFor==1)){
									$header_arr["success"]="You reached the maximal point, we cannot add more point, spend it on something!";							
									$Vpoint = $VPoint-$VoteReward;
								}
								//$VoteFor =1 point, =2 gold, we add gold or point
								$Vpoint = $VPoint+$VoteReward;
								if ($VoteFor == 1){
									$query = "UPDATE users SET VotePoint=$Vpoint WHERE ID=?";
									$stmt = $link->prepare($query);
									$stmt->bind_param('i', $uid);
									$stmt->execute(); 
									$stmt->close();			
									$VoteIntHour = 3600*$VoteInterval;
								}elseif ($VoteFor == 2){
									$query="INSERT INTO usecashnow (userid, zoneid, sn, aid, point, cash, status, creatime) VALUES ('$uid', '1', '0', '1', '0', '$VoteReward', '1', '$TIME') ON DUPLICATE KEY UPDATE cash = cash + $VoteReward";
									$stmt = $link->prepare($query);
									$stmt->execute(); 
									$stmt->close();
								}
								if (($VoteFor == 1) or ($VoteFor == 2)){
									$header_arr["voteurl"]=$VoteUrl[$vId];
									$expArr[$vId-1] = $TIME;
									$VDates=implode(",",$expArr);
									$query = "UPDATE users SET VoteDates=? WHERE ID=?";
									$stmt = $link->prepare($query);
									if (!$stmt) {
										printf("Error message: %s\n", $link->error);
									}
									$stmt->bind_param('si', $VDates, $uid);
									$stmt->execute(); 
									$stmt->close();	
								}
								$header_arr["voteurl"]=$VoteUrl[$vId];
							}else{
								$rHour = intval($secDiff / 3600);
								$rMin = intval(($secDiff % 3600)/60);
								$rSec = $secDiff % 60;
								$header_arr["error"]="Need wait: $rHour:$rMin:$rSec !";
							}
						}
					}
					$statement->close();
				}else{
					$header_arr["error"]="Try relog!";
				}
				mysqli_close($link);
			}
			
		}else{
			$header_arr["error"]="Invalid vote id!";
		}
	}
}

function DateDifference($DateStack, $Index){
	$curTime = time();
	$res_sec = $curTime - strtotime($DateStack[$Index-1]);
	return $res_sec;		
}	

function validDateStack($DateStack, &$VoteUrl){
	$sepChar = ",";
	$expDate = "2016-12-01 01:00:00";
	$cdatestack=0;
	$max = count($VoteUrl);
	$newStack=array();
	$expArr = explode($sepChar, $DateStack);
	
	for ($i = 0; $i <= $max; $i++) {
		$newStack[$i] = isset($expArr[$i]) ? $expArr[$i] : $expDate;
	}		
	
	return $newStack;
}

function validateDate($date, $format = 'Y-m-d H:i:s'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

if ($header_arr["success"]!=""){$header_arr["error"]="";}
//$header_arr["success"]=json_encode($header_arr);
$return_arr = array();
$return_arr[0]=$header_arr;
echo json_encode($return_arr);	
?>
