<?php 
session_start();
include "../config.php";
include "../basefunc.php";
$resp="Unknown Error";

SessionVerification();

$data = json_decode(file_get_contents('php://input'), true);
if ( $data ) {
	$username=$data['name'];
	$password=$data['password'];
	if (($username)&&($password)){
		$u=StrToLower(Trim(stripslashes($username)));
		$p=stripslashes($password);
		if (!(((strlen($u)<5)||(strlen($u)>20)||(strlen($p)<5))||(strlen($p)>20))&&((ctype_alnum($u))&&(ctype_alnum($p)))){
			$link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
			if ($link->connect_errno) {
				$resp="Sorry, this website is experiencing problems (failed to make a MySQL connection)!";
				exit;
			}	
			if (VerifyPassword ($link, $u, $p) !== false){
				$statement = $link->prepare("SELECT ID, email, creatime FROM users WHERE name=?");
				$statement->bind_param('s', $u);
				$statement->execute();
				$statement->bind_result($ID, $mail, $TIME);
				$statement->store_result();
				$count = $statement->num_rows;		
				if ($count > 0){
					while($statement->fetch()) {
						$_SESSION['un']=$u;
						$_SESSION['pw']=$p;
						$_SESSION['id']=$ID;
						$_SESSION['ma']=$mail;
						$admin=VerifyAdmin($link, $u, $p, $ID, $mail);
						if (($admin!==true)&&($LoginEnabled !== true)){
							unset($_SESSION['un']);
							unset($_SESSION['pw']);
							unset($_SESSION['id']);
							unset($_SESSION['ma']);		
							$resp="Login disabled, try again later!";								
						}else{
							$_SESSION['t']=$TIME;
							$_SESSION['UD3']=$AKey2;
							$_SESSION['LogTtry'] = 0;
							$resp="";	
						}
					}
				}else{
					$resp="User not exist!";	
				}
				$statement->close();
			}else{
				$resp="Wrong username or password!";
				if (isset($_SESSION['LogTtry'])){
					$_SESSION['LogTtry'] = $_SESSION['LogTtry'] + 1;
				}else{
					$_SESSION['LogTtry'] = 1;
				}
				if ($_SESSION['LogTtry'] > 6){
					$_SESSION['UD1']=2;
					$resp="You tryed too many times with wrong password!";
				}
			}
			mysqli_close($link);
		}else{
			$resp="Username and password must be minimum 6 alphanumeric character!";
		}
	}
}
echo $resp;

?>
