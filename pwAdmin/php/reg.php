<?php 
session_start();
include "../config.php";
include "../basefunc.php";
$resp="Unkown Error";

SessionVerification();
//obj = {"name":dArr[0], "password1":dArr[1], "password2":dArr[2], "email":dArr[3], "answer1":dArr[4], "answer2":dArr[5], "term":dArr[6]};		
$data = json_decode(file_get_contents('php://input'), true);
if ( $data ) {
	$u=StrToLower(Trim(stripslashes($data['name'])));
	$p1=stripslashes($data['password1']);
	$p2=stripslashes($data['password2']);
	$m=StrToLower(Trim(stripslashes($data['email'])));
	$a1=intval(StrToLower(Trim(stripslashes($data['answer1']))));
	$a2=intval(StrToLower(Trim(stripslashes($data['answer2']))));
	$term=intval(StrToLower(Trim(stripslashes($data['term']))));
	$link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
	$UserNmC=CountMysqlRows($link, 3, $u);
	$UserEmC=CountMysqlRows($link, 2, $m);
	$UserIPC=CountMysqlRows($link, 5, $IPL);
	$IPL = $_SERVER['REMOTE_ADDR'];
	if (substr($IPL, 0, 3)=="192"){
		$IPL = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));
	}
	$sregc=0;
	if (isset($_SESSION['regC'])){
		$sregc=intval($_SESSION['regC']);
	}else{
		if (isset($_COOKIE['regC'])){
			$sregc=intval($_COOKIE['regC']);
		}
	}	
	
	if ($RegisEnabled !== true){
		$resp = "Registration disabled!";
	}else if (empty($u) || empty($p1) || empty($p2) || empty($m)){
		$resp = "Please fill out this form!";
	}else if ((strlen($u)<5)||(strlen($u)>20)||(strlen($p1)<5)||(strlen($p1)>20)){
		$resp = "Username and password must be atleast 6 character!";
	}else if (((ctype_alnum($u))&&(ctype_alnum($p1)))!==true){
		$resp = "Username and password must be alphanumeric!";
	}else if ($p1!=$p2){
		$resp = "Passwords must match with each other!";
	}else if ((validEmail($m))!==true){
		$resp = "Invalid email address!";
	}else if ($UserIPC>=$IPRegLimit){
		$resp = "Cannot register more account from your PC!";
	}else if ($sregc>=$SRegLimit){
		$resp = "Cannot register more account from your PC!";;
	}else if ($UserNmC>0){
		$resp = "Username already exist!";
	}else if ($UserEmC>0){
		$resp = "Email address already exist!";
	}else if ($term!=0){
		$resp = "Bot?!";
	}else{
		if ($PassType==1){
			$Salt = "0x".md5($u.$p1);
			$rs=mysqli_query($link, "call adduser('$u', '$Salt', '0', '0', '', '$IPL', '$m', '0', '0', '0', '0', '0', '0', '0', '', '', '$Salt')");
		}else if ($PassType==2){ 
			$Salt = base64_encode(hash('md5',strtolower($u).$p1, true));
			$rs=mysqli_query($link, "call adduser('$u', '$Salt', '0', '0', '', '$IPL', '$m', '0', '0', '0', '0', '0', '0', '0', '', '', '$Salt')");
		}else if ($PassType==3){ 
			$Salt = "0x".md5($u.$p1);
			$rs=mysqli_query($link, "call adduser('$u', $Salt, '0', '0', '', '$IPL', '$m', '0', '0', '0', '0', '0', '0', '0', '', '', $Salt)");
		}		
		$UserNmC=CountMysqlRows($link, 3, $u);
		if ($UserNmC>0){
			$statement = $link->prepare("SELECT name, ID, creatime FROM users WHERE name=?");
			$statement->bind_param('s', $u);
			$statement->execute();
			$statement->bind_result($uname, $ID, $TIME);
			$statement->store_result();
			$result = $statement->num_rows;
			if (!$result) {
				$resp="Query failed to execute!";
			}else{
				//confirm stuff and gold
				if ($StartGold > 0){
					$nr0 = 0;
					$nr1 = 1;
					$stmt = $link->prepare("INSERT INTO usecashnow (userid, zoneid, sn, aid, point, cash, status, creatime) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
					$stmt->bind_param("iiiiiiis", $ID, $nr1, $nr0, $nr1, $nr0, $StartGold, $nr1, $TIME);
					$stmt->execute(); 
					$stmt->close();
				}
				
				if ($StartPoint > 0){
					$stmt = $link->prepare("UPDATE users SET VotePoint = ? WHERE ID=?");
					$stmt->bind_param('ii', $StartPoint, $ID);
					$stmt->execute(); 
					$stmt->close();
				}
				$_SESSION['un']=$u;
				$_SESSION['pw']=$p1;
				$_SESSION['id']=$ID;
				$_SESSION['ma']=$m;
				$_SESSION['t']=$TIME;
				$_SESSION['UD3'] = $AKey2;
				$_SESSION['LogTtry'] = 0;
				if (isset($_SESSION['regC'])){
					$_SESSION['regC']=$_SESSION['regC']+1;
				}else{
					$_SESSION['regC']=1;
				}
				if (isset($_COOKIE['regC'])){
					setcookie('regC', (intval($_COOKIE['regC'])+1), 2147483646);
				}else{
					setcookie('regC', 1, 2147483646);
				}		
				$resp="";				
			}		
			$statement->close();
		}else{
			$resp = "Problem during account creation";
		}
		mysqli_close($link);
	}
}
echo $resp;
?>
