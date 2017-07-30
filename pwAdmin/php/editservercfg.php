<?php 
session_start();
include "../config.php";
include "../basefunc.php";
$resp="Unknown Error";
SessionVerification();
$data = json_decode(file_get_contents('php://input'), true);
if ( $data ) {
	$un=$_SESSION['un'];
	$pw=$_SESSION['pw'];
	$id=$_SESSION['id'];
	$ma=$_SESSION['ma'];	
	$link = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
	if ($link->connect_errno) {
		$resp="Sorry, this website is experiencing problems (failed to make a MySQL connection)!";
	}else{
		if ((VerifyAdmin($link, $un, $pw, $id, $ma)!==false)&&((isset($data['dm']))&&(isset($data['cm']))&&(isset($data['xp']))&&(isset($data['sp']))&&(isset($data['dp']))&&(isset($data['mn']))&&(isset($data['pp']))&&(isset($data['tw']))&&(isset($data['ns']))&&(isset($data['nl'])))){
			//get conf file names and full path
			$expArr=explode("*",$ServerFile[9]);
			$svFile[0] = $ServerPath.$expArr[0]."/ptemplate.conf";
			$svFolder[0] = $ServerPath.$expArr[0];
			
			$expArr=explode("*",$ServerFile[7]);
			$svFile[1] = $ServerPath.$expArr[0]."/gamesys.conf";
			$svFolder[1] = $ServerPath.$expArr[0];
			
			$expArr=explode("*",$ServerFile[2]);
			$svFile[2] = $ServerPath.$expArr[0]."/gamesys.conf";
			$svFolder[2] = $ServerPath.$expArr[0];
			//the reicived data
			$debug_mode=intval($data['dm']);
			$class_mask=intval($data['cm']);
			$exp_bonus=intval($data['xp']);
			$sp_bonus=intval($data['sp']);
			$drop_bonus=intval($data['dp']);
			$money_bonus=intval($data['mn']);
			$pvp=intval($data['pp']);
			$tw=intval($data['tw']);
			$name_insens=intval($data['ns']);
			$name_max_len=intval($data['nl']);
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
		
			$err = true;
			$fname = $svFolder[0]."/test.tmp";
			if ($file = fopen($fname, "w")){
				fclose($file);
				unlink($fname);
				$fname = $svFolder[1]."/test.tmp";
				if ($file = fopen($fname, "w")){
					fclose($file);
					unlink($fname);
					$fname = $svFolder[2]."/test.php";
					if ($file = fopen($fname, "w")){
						fclose($file);
						unlink($fname);
						$err = false;
					}
				}						
			}

			if ($err===true){
				$resp="Need permission for gdelivery, gamed and uniquenamed folder.<br>Solution: <u><i>chmod -R 777 /your path/</i></u> command...";
			}else{
				$found=0;
				$fname = $svFile[0];
				if ($file = fopen($fname, "r")){
					$tmpFile=$fname.".tmp";
					$fp = fopen($tmpFile, "w") or exit("cannot write file into ".$svFolder[0]."");
					while(!feof($file)) {
						$orig = fgets($file);
						$line = trim(strtolower($orig));
						if (substr($line,0,strlen($cmd_class))==$cmd_class){
							$found=6;
							fwrite($fp, $cmd_debug." = ".$debug_mode.PHP_EOL);
							fwrite($fp, $cmd_class." = ".$class_mask.PHP_EOL);
							fwrite($fp, $cmd_exp." = ".$exp_bonus.PHP_EOL);
							fwrite($fp, $cmd_sp." = ".$sp_bonus.PHP_EOL);
							fwrite($fp, $cmd_drop." = ".$drop_bonus.PHP_EOL);
							fwrite($fp, $cmd_gold." = ".$money_bonus.PHP_EOL);
						}elseif(!((substr($line,0,strlen($cmd_debug))==$cmd_debug) or (substr($line,0,strlen($cmd_exp))==$cmd_exp) or (substr($line,0,strlen($cmd_sp))==$cmd_sp) or (substr($line,0,strlen($cmd_drop))==$cmd_drop) or (substr($line,0,strlen($cmd_gold))==$cmd_gold))){
							fwrite($fp, $orig);
						}
					}
					fclose($file);
					fclose($fp);
					rename($fname, $fname.".bak");
					rename($tmpFile, $fname);
					$resp="";
				}else{
					$resp="Cannot save, wrong path: ".$fname."...";
				}
				if ($resp!=""){
					$found=0;
					$fname = $svFile[1];
					if ($file = fopen($fname, "r")){
						$tmpFile=$fname.".tmp";
						$fp = fopen($tmpFile, "w") or exit("Cannot write file into ".$svFolder[1]);
						while(!feof($file)) {
							$orig = fgets($file);
							$line = trim(strtolower($orig));
							if (substr($line,0,strlen($cmd_tw))==$cmd_tw){
								 $found++;
								 fwrite($fp, $cmd_tw." = ".$tw.PHP_EOL);
							}elseif(substr($line,0,strlen($cmd_pvp))==$cmd_pvp){
								$found++;
								fwrite($fp, $cmd_pvp." = ".$pvp.PHP_EOL);
							}elseif(substr($line,0,strlen($cmd_maxname))==$cmd_maxname){
								$found++;
								fwrite($fp, $cmd_maxname." = ".$name_max_len.PHP_EOL);
							}else{
								fwrite($fp, $orig);
							}
						}
						fclose($file);
						fclose($fp);
						rename($fname, $fname.".bak");
						rename($tmpFile, $fname);
						$resp="";
					}else{
						$resp="Cannot save, wrong path: ".$fname."...";
					}
				}
				if ($resp!=""){
					$found=0;
					$fname = $svFile[2];
					if ($file = fopen($fname, "r")){
						$tmpFile=$fname.".tmp";
						$fp = fopen($tmpFile, "w") or exit("Cannot write file into ".$svFolder[2]);
						while(!feof($file)) {
							$orig = fgets($file);
							$line = trim(strtolower($orig));
							if (substr($line,0,strlen($cmd_caseins))==$cmd_caseins){
								 fwrite($fp, $cmd_caseins." = ".$name_insens.PHP_EOL);
								 $found++;
							}else{
								fwrite($fp, $orig);
							}
						}
						fclose($file);
						fclose($fp);
						rename($fname, $fname.".bak");
						rename($tmpFile, $fname);
						$resp="";
					}else{
						$resp="Cannot save, wrong path: ".$fname."...";
					}
				}
				$resp="For changes you need (re)start the server!";
			}
		}
	}	
	mysqli_close($link);

}
echo $resp;

?>
