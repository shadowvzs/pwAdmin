<?php 
session_start();
include "../config.php";
include "../basefunc.php";
$header_arr = array("error" => "Unknown Error!", "success" => "", "voteurl" => "", "voteid" => "0", "votesecleft" => "0");
SessionVerification();
if ($_SESSION['UD3'] != $AKey2){die();}
$data = json_decode(file_get_contents('php://input'), true);
if (($data)&&(isset($_SESSION['un']))) {
	if ((isset($data['voteid'])) && (isset($_SESSION['id']))){
		$vId = intval($data['voteid']);
		if (($vId <= count($VoteUrl))&&($vId > 0)){
			$VoteLink = $VoteUrl[$vId];
			if (!isset($VoteLink)){$VoteLink="";}
			if (strlen($VoteLink) > 3){
				$un=$_SESSION['un'];
				$pw=$_SESSION['pw'];
				$uid=$_SESSION['id'];
				$ma=$_SESSION['ma'];
				$VoteIntSec = 3600*$VoteInterval;
				$TIME=date("Y-m-d H:i:s");					
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
							$VDates=validDateStack($VDates, $VoteUrl);
							$expArr = explode($sepChar, $VDates);
							if (count($expArr) < $vId){
								$header_arr["error"]="Something wrong, the vote site not found!";
							}else{
								$secDiff = $VoteIntSec - DateDifference($expArr[$vId-1], $vId);
								$header_arr["votesecleft"]=$secDiff;
								$header_arr["voteid"]=$vId;								
								if ($secDiff < 0){
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
										$nr0 = 0;
										$nr1 = 1;
										$stmt = $link->prepare("INSERT INTO usecashnow (userid, zoneid, sn, aid, point, cash, status, creatime) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
										$stmt->bind_param("iiiiiiis", $uid, $nr1, $nr0, $nr1, $nr0, $VoteReward, $nr1, $TIME);
										$stmt->execute(); 
										$stmt->close();											
									}
									if (($VoteFor == 1) or ($VoteFor == 2)){
										$header_arr["voteurl"]=$VoteUrl[$vId];
										$expArr[$vId-1] = $TIME;
										$VDates=implode(",",$expArr);
										$query = "UPDATE users SET VoteDates=? WHERE ID=?";
										$stmt = $link->prepare($query);
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
	$CurDate = date("Y-m-d H:i:s");
	$sepChar = ",";
	$difference = 0;
	$res_sec = 31536000;

	if((strlen($DateStack) == 19)&&($Index==1)){
		$first_date = new DateTime($DateStack);
		$second_date = new DateTime($CurDate);
		$res_sec=round(($second_date->format('U') - $first_date->format('U')));
	}elseif(!(strlen($DateStack) < strlen($CurDate))){
		if (strpos($DateStack, $sepChar) !== false) {
			$expArr = explode($sepChar, $DateStack);
			if ($Index <= count($expArr)){
				$first_date = new DateTime($expArr[($Index-1)]);
				$second_date = new DateTime($CurDate);
				$res_sec = round(($second_date->format('U') - $first_date->format('U')));
			}
		}
	}
	return $res_sec;		
}	

function validDateStack($DateStack, &$VoteUrl){
	$sepChar = ",";
	$expDate = "2016-12-01 01:00:00";
	$cdatestack=0;
	$max = count($VoteUrl);
	$newStack="";
	
	if (strlen($DateStack) >= strlen($expDate)){
		if (strpos($DateStack, $sepChar) !== false) {
			$expArr = explode($sepChar, $DateStack);
			$sMax = count($expArr);
			if (validateDate($expArr[0])){
				$newStack = $expArr[0];
			}else{
				$newStack = $expDate;
			}				
			for ($i = 2; $i <= $max; $i++) {
				if ($i <= $sMax){
					$tmp=$expArr[($i-1)];
					if (validateDate($tmp)){
						$newStack = $newStack.$sepChar.$tmp;
					}else{
						$newStack = $newStack.$sepChar.$expDate;
					}
				}else{
					$newStack = $newStack.$sepChar.$expDate;
				}
			}		
		}else{
			if (validateDate($DateStack)){
				if ($max==1){
					return $DateStack;
				}else{
					$newStack = $DateStack;
					for ($i = 2; $i <= $max; $i++) {
						$newStack = $newStack.$sepChar.$expDate;
					}
				}
			}else{
				$newStack = $expDate;
				if ($max>1){
					for ($i = 2; $i <= $max; $i++) {
						$newStack = $newStack.$sepChar.$expDate;
					}		
				}				
			}			
		}
		
		return $newStack;
	}else{
		$newStack = $expDate;
		if ($max>1){
			for ($i = 2; $i <= $max; $i++) {
				$newStack = $newStack.$sepChar.$expDate;
			}		
		}
		return $newStack;
	}
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
