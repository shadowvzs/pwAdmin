<?php 
function VerifyPassword ($link, $name, $password){
	global $PassType;
	$Salt="";
	if ($PassType == 1){
        $Salt = "0x".md5($name.$password);
		$statement = $link->prepare("SELECT passwd FROM users WHERE name=? AND passwd=?");
		$statement->bind_param('ss', $name, $Salt);
		$statement->execute();
		$statement->bind_result($p2);
		$statement->store_result();
		$count = $statement->num_rows;
		if($count==1){
			return true;
			exit;
		}			
		$statement->close();
	}else if ($PassType == 2){
        $Salt = base64_encode(hash('md5',strtolower($name).$password, true));
		$statement = $link->prepare("SELECT passwd FROM users WHERE name=? AND passwd=?");
		$statement->bind_param('ss', $name, $Salt);
		$statement->execute();
		$statement->bind_result($p2);
		$statement->store_result();
		$count = $statement->num_rows;
		if($count==1){
			return true;
			exit;
		}			
		$statement->close();
	}else if ($PassType == 3){	
        $Salt = "0x".md5($name.$password);
		$statement = $link->prepare("SELECT passwd FROM users WHERE name=? AND passwd=CONVERT($Salt USING latin1)");
		$statement->bind_param('s', $name);
		$statement->execute();
		$statement->bind_result($p2);
		$statement->store_result();
		$count = $statement->num_rows;
		if($count==1){
			return true;
			exit;
		}	
		$statement->close();	
	}
	return false;
}

function VerifyAdmin($con, $username, $password, $userid, $email){
	$rValue=false;
	global $PassType;
	global $AdminId;
	global $AdminPw;

	if (!($con->connect_errno)) {
		if ($PassType == 3) {
			$binSalt = "0x".md5($username.$password);
			$query = "SELECT ID, name, email, passwd FROM users WHERE ID=? AND passwd=CONVERT($binSalt USING latin1)";
		} else {
			$query = "SELECT ID, name, email, passwd FROM users WHERE ID=?";
		}
		$statement = $con->prepare($query);
		$statement->bind_param('i', $userid);
		$statement->execute();
		$statement->bind_result($LID, $LNAME, $LEMAIL, $LPASSWD);
		$statement->store_result();
		$result = $statement->num_rows;
		global $PassType;
		if ($result==1)	{
			while($statement->fetch()) {
				if (($username==$LNAME)&&($email==$LEMAIL)&&($userid==$AdminId)){
					if ($PassType == 1){
						$Salt1 = "0x".md5($username.$password);
						$Salt2 = $AdminPw;						
						if (($Salt1==$Salt2)&&($LPASSWD==$Salt2)) {
							$rValue=true;
						}	
					}else if ($PassType == 2){
						$Salt1 = base64_encode(hash('md5',$username.$password, true));
						$Salt2 = $AdminPw;		
						if (($Salt1==$Salt2)&&($LPASSWD==$Salt2)) {
							$rValue=true;
						}		
					}else if ($PassType == 3){	
						$Salt1 = "0x".md5($username.$password);
						$Salt2 = "0x".$AdminPw;						
						if ($Salt1==$Salt2) {
							$rValue=true;
						}					
					}					
				}
			}
		}
		$statement->close();
	}
	return $rValue;	
}

function SessionVerification(){
	global $AKey1;
	if (isset($_SESSION['UD1'])){
		if ($_SESSION['UD1'] != 1){
			die;
		}elseif (isset($_SESSION['UD2'])){
			if ($_SESSION['UD2'] != $AKey1){
				die;
			}
		}else{
			die;
		}
	}else{
		die;
	}
}

function validate_Date($mydate) {
	$format = 'YYYY-MM-DD';
	$expArr=explode('-', $mydate);
	if ((strlen($expArr[0])==4) && (strlen($expArr[1])==2)&& (strlen($expArr[2])==2) && (is_numeric($expArr[0].$expArr[1].$expArr[2]))){
		return true;
	}else{
		return false;
	}
}

function BoolToSting($bool) {
	if ($bool !== false){
		return "true";
	}else{
		return "false";
	}
}

function validEmail($email){
    if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
        return false;
    }
    $email_array = explode("@", $email);
    $local_array = explode(".", $email_array[0]);
    for ($i = 0; $i < sizeof($local_array); $i++) {
        if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
            return false;
        }
    }
    if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
        $domain_array = explode(".", $email_array[1]);
        if (sizeof($domain_array) < 2) {
            return false; // Not enough parts to domain
        }
        for ($i = 0; $i < sizeof($domain_array); $i++) {
            if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                return false;
            }
        }
    }

    return true;
}

function CountMysqlRows ($con, $type, $value){
	if ($con->connect_errno) {
		echo "<script>alert('Sorry, this website is experiencing problems (failed to make a MySQL connection)!');</script>";
		exit;
	}
	if ($type==1){
		$query = "SELECT ID FROM users WHERE ID=?";
		$statement = $con->prepare($query);
		$statement->bind_param('i', $value);
	}elseif($type==2){
		$query = "SELECT email FROM users WHERE email=?";
		$statement = $con->prepare($query);
		$statement->bind_param('s', $value);
	}elseif($type==3){
		$query = "SELECT name FROM users WHERE name=?";
		$statement = $con->prepare($query);
		$statement->bind_param('s', $value);
	}elseif($type==4){
		$query = "SELECT idnumber FROM users WHERE idnumber=?";
		$statement = $con->prepare($query);
		$statement->bind_param('s', $value);
	}elseif($type==5){
		$query = "SELECT userid FROM auth WHERE userid=? GROUP BY userid";
		$statement = $con->prepare($query);
		$statement->bind_param('s', $value);	
	}elseif($type==6){
		$query = "SELECT userid FROM forbid WHERE userid=?";
		$statement = $con->prepare($query);
		$statement->bind_param('s', $value);
	}elseif($type==7){
		$query = "SELECT uid FROM point WHERE uid=? AND zoneid IS NOT NULL";
		$statement = $con->prepare($query);
		$statement->bind_param('s', $value);
	}

	
	$statement->execute();
	$statement->store_result();
	$result = $statement->num_rows;
	$count = $result;
	$statement->close();
	return $count;
}

function ServerOnline($link){
	global $LanIP;
	global $ServerPort;
	$result = mysqli_query($link,"SELECT zoneid FROM point WHERE zoneid IS NOT NULL");
	$count = @mysqli_num_rows($result);
    $sockres = @FSockOpen($LanIP, $ServerPort, $errno, $errstr, 1);
    if (!$sockres){
		mysqli_query($link,"DELETE FROM online");
        $SRunning=false;
    }else{
       @FClose($sockres);
	   $SRunning=true;
    }   
	mysqli_free_result($result);
	return $SRunning;
}
function cls2class ($cls){
	if (($cls > 1) && ($cls < 8) && ($cls != 3)){
		if ($cls == 2){
			return 7;
		}elseif($cls == 4){
			return 3;
		}elseif($cls == 5){
			return 8;
		}elseif($cls == 6){
			return 5;
		}elseif($cls == 7){
			return 6;
		}
	}else{
		return ($cls+1);
	}
}

function ConvRevHexToDec($Hex){
	$newHex="";
	if (strlen($Hex)==8){
		$newHex=substr($Hex,6,2).substr($Hex,4,2).substr($Hex,2,2).substr($Hex,0,2);
		return hexdec($newHex);
	}else{
		return 0;
	}
}

function ConvDecToRevHex($Dec){
	$Hex=dechex(intval($Dec));
	$hlen=8-strlen($Hex);
	for ($i = 0; $i < $hlen; $i++) {
		$Hex="0".$Hex;
	}
	$RevHex=substr($Hex,6,2).substr($Hex,4,2).substr($Hex,2,2).substr($Hex,0,2);
	return $RevHex;
}

?>
