<?php
session_start();
$tmpFile="config.tmp";
$cfgFile="config.php";
if (isset($_GET['do'])){
	$WebShop_table="CREATE TABLE webshop (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	pcst INT SIGNED NOT NULL,
	gcst INT SIGNED NOT NULL,
	itit VARCHAR(64) NOT NULL,
	idsc VARCHAR(255) NOT NULL,
	cats VARCHAR(5) NOT NULL,
	iname VARCHAR(64) NOT NULL,
	idate DATETIME NOT NULL,
	itmid INT NOT NULL,
	imask INT SIGNED NOT NULL,
	iproc INT UNSIGNED NOT NULL,
	iqty INT UNSIGNED NOT NULL,
	imax INT UNSIGNED NOT NULL,
	guid1 INT UNSIGNED NOT NULL,
	guid2 INT UNSIGNED NOT NULL,
	expir INT UNSIGNED NOT NULL,
	octet VARCHAR(8095) NOT NULL,
	wscat VARCHAR(10) NOT NULL,
	icol TINYINT NOT NULL,
	igrd INT NOT NULL,
	stim VARCHAR(64) NOT NULL) DEFAULT CHARSET=utf8";

	$WebShopLog_table="CREATE TABLE wshoplog (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	user INT UNSIGNED NOT NULL,
	uname VARCHAR(50) NOT NULL,
	role INT UNSIGNED NOT NULL,
	rname VARCHAR(50) NOT NULL,
	buydate DATETIME NOT NULL,
	currency TINYINT NOT NULL,
	price INT SIGNED NOT NULL,
	amount INT UNSIGNED NOT NULL,
	idata VARCHAR(8500) NOT NULL,
	shopid INT UNSIGNED NOT NULL) DEFAULT CHARSET=utf8";
	
	$do=$_GET['do'];
	if ($do == "setConnection"){
		if ((isset($_GET['host']))&&(isset($_GET['user']))&&(isset($_GET['passwd']))&&(isset($_GET['ServerIp']))&&(isset($_GET['LanIp']))&&(isset($_GET['Port']))){
			$host=$_GET['host'];
			$user=$_GET['user'];
			$passwd=$_GET['passwd'];
			
			$ServrIp=$_GET['ServerIp'];
			$LanIp=$_GET['LanIp'];
			$Port=intval($_GET['Port']);
			
			if ($Port>0){
				if ((strlen($host)>0)&&(strlen($user)>0)&&(strlen($passwd)>0)){
					$conn=new mysqli($host, $user, $passwd);
					$work=true;
					$msg="";
					if (($conn->connect_error) || (mysqli_connect_error())) {
						$msg="Can\'t connect to MySQL server, wrong data.";
						$work=false;
					}
					if ($work===true){
						$msg="<font color=#0000aa>Connected successfully</font>";
						$work=true;
						$_SESSION['host']=$host;
						$_SESSION['user']=$user;
						$_SESSION['passwd']=$passwd;
						$_SESSION['server']=$ServrIp."#".$LanIp."#".$Port;
						echo"<script>parent.MoveOut('DialogDiv1');
									 parent.MoveIn('DialogDiv2');
									 parent.ChangeProgressBar(1);
									 </script>";
					}else{
						if (isset($_SESSION['host'])){
							unset($_SESSION['host']);
							unset($_SESSION['user']);
							unset($_SESSION['passwd']);		
							unset($_SESSION['db']);			
						}
						if (isset($_SESSION['server'])){
							unset($_SESSION['server']);
						}
					}
					$conn->close();		
				}else{
					echo"<script>parent.document.getElementById('AnswerBox').innerHTML='Don\'t leave empty!';</script>";
				}
			}else{
				$msg="Invalid port, it is useally 29000.";
			}
			echo"<script>parent.document.getElementById('AnswerBox').innerHTML='".$msg."';</script>";
		}
	}elseif($do == "checkDb"){
		if ((isset($_GET['dbname']))&&(isset($_GET['MaxSess']))&&(isset($_GET['MaxIp']))&&(isset($_SESSION['host']))&&(isset($_GET['passType']))){
			$host=$_SESSION['host'];
			$user=$_SESSION['user'];
			$passwd=$_SESSION['passwd'];
			$db=$_GET['dbname'];
			$maxIp=intval($_GET['MaxIp']);
			$maxSess=intval($_GET['MaxSess']);
			$passType=intval($_GET['passType']);
			
			if (strlen($db)>0){
				if (($maxIp > 0) && ($maxSess>0)){
					$conn=new mysqli($host, $user, $passwd, $db);
					$work=true;
					$msg="";
					echo"<script>var div=parent.document.getElementById('DialogDiv2');</script>";
					if (($conn->connect_error)||(mysqli_connect_error())) {
						$work=false;
						$msg="Can\'t connect to $db database, please create it.";
					}
					if ($work===true){
						$query="SHOW TABLES FROM $db LIKE 'users'";
						if ($result=$conn->query($query)) {
							$row_cnt=$result->num_rows;
							if ($row_cnt > 0){
								$msg="<font color=#0000aa>We use this database.</font>";
								$work=true;
								$_SESSION['db']=$db;
								echo"<script>parent.document.getElementById('AnswerBox1').innerHTML='<font color=#0000aa>Database tested!</font>';</script>";
								echo"<script>
									parent.MoveOut('DialogDiv2');
									parent.MoveIn('DialogDiv3');
									parent.ChangeProgressBar(2);
								</script>";
								$fp=fopen("./".$tmpFile, "w") or exit("<script>alert('Unable to write file, maybe write protected folder!')</script>");
								$serverData=explode("#", $_SESSION['server']);
								$dir=getcwd();
								fwrite($fp, "<?php ".PHP_EOL);
								fwrite($fp, '$DB_Host="'.$host.'";'.PHP_EOL);
								fwrite($fp, '$DB_User="'.$user.'";'.PHP_EOL);
								fwrite($fp, '$DB_Password="'.$passwd.'";'.PHP_EOL);
								fwrite($fp, '$DB_Name="'.$db.'";'.PHP_EOL);
								fwrite($fp, '$SSH_User="";'.PHP_EOL);
								fwrite($fp, '$SSH_Password="";'.PHP_EOL);
								fwrite($fp, '$PassType='.$passType.';'.PHP_EOL);
								fwrite($fp, '$ServerIP="'.$serverData[0].'";'.PHP_EOL);
								fwrite($fp, '$LanIP="'.$serverData[1].'";'.PHP_EOL);
								fwrite($fp, '$ServerPort='.$serverData[2].';'.PHP_EOL);
								fwrite($fp, '$WebDir="'.$dir.'";'.PHP_EOL);
								fwrite($fp, '$IPRegLimit='.$maxIp.';'.PHP_EOL);
								fwrite($fp, '$SRegLimit='.$maxSess.';'.PHP_EOL);
								fclose($fp);
								unset($_SESSION['server']);
								$_SESSION['pt']=$passType;
							}else{
								$msg="Not exist the tables in database, please 'Create'.";
								$work=false;								
							}
							$result->close();
						}else{
							$msg="Connection problem.";
							$work=false;
						}
					}
					if ($work===false){
						if (isset($_SESSION['db'])){
							unset($_SESSION['db']);							
						}
					}else{
						if ($result1=$conn->query($WebShop_table)) {}else{
							$msg="Cannot create table for Web Shop... (maybe already exist?)";
						}
						
						if ($result2=$conn->query($WebShopLog_table)) {}else{
							$msg="<script>alert('Cannot create table for Web Shop Log...(maybe already exist?)');</script>";
						}
						$msg="Done";
						$result1->close();	
						$result2->close();							
					}
					
					$conn->close();			
					echo"<script>parent.document.getElementById('AnswerBox1').innerHTML='".$msg."';</script>";
				}else{
					echo"<script>parent.document.getElementById('AnswerBox1').innerHTML='Max IP and browser must be above 0!';</script>";
				}
			}else{
				echo"<script>parent.document.getElementById('AnswerBox1').innerHTML='Don\'t leave empty!';</script>";
			}	
		}			
	}elseif($do == "CreateDb"){
		if ((isset($_GET['dbname']))&&(isset($_SESSION['host']))&&(isset($_GET['path']))&&(isset($_GET['MaxSess']))&&(isset($_GET['MaxIp']))&&(isset($_GET['passType']))){
			$host=$_SESSION['host'];
			$user=$_SESSION['user'];
			$passwd=$_SESSION['passwd'];
			$passType=intval($_GET['passType']);
			$db=$_GET['dbname'];
			$SQLpath=$_GET['path'];
			$sql="CREATE DATABASE ".$db;
			$conn=new mysqli($host, $user, $passwd);
			$load=false;
			$maxIp=intval($_GET['MaxIp']);
			$maxSess=intval($_GET['MaxSess']);
			$msg="";
			if (($maxIp > 0) && ($maxSess>0)){
				if ($conn->query($sql) === TRUE) {
					$msg="Database created successfully, ";
					$load=true;
				} else {
					$conn->close();
					$conn=new mysqli($host, $user, $passwd, $db);
					if (($conn->connect_error)||(mysqli_connect_error())) {
						$msg="Database creation failed.";
					}else{
						$msg="Database already exist, ";
						$load=true;
					}
				}
				$work=false;
				if ($load===true){
					if (file_exists($SQLpath)){
						ob_start();
						echo `whereis mysql`;
						$getMySQLpath=ob_get_contents();
						ob_end_clean();
						$mysqlP=trim(GetMysqlPath());
						$command="$mysqlP -u$user -p$passwd $db < $SQLpath 2>&1";
						$output=shell_exec($command);
						$query="SHOW TABLES FROM $db LIKE 'users'";
						if ($result=$conn->query($query)) {
							$row_cnt=$result->num_rows;
							if ($row_cnt > 0){
								$msg="<font color=#0000aa>".$msg." table creation done.</font>";
								$work=true;
								$_SESSION['pt']=$passType;
							}else{
								$msg="Table creation failed.";
							}
							$result->close();
						}else{
							$msg="Connection problem.";
						}
					}else{
						$msg=$msg." SQL file not exist.";
					}
				}
			}else{
				$msg="Max IP and browser must be above 0!";
				$work=false;
			}
			echo"<script>parent.document.getElementById('AnswerBox1').innerHTML='".$msg."';</script>";
			$conn->close();	
			if ($work==true){
				$_SESSION['db']=$db;
				echo"<script>
					parent.MoveOut('DialogDiv2');
					parent.MoveIn('DialogDiv3');
					parent.ChangeProgressBar(2);
			     </script>";				
				$fp=fopen($tmpFile, "w") or exit("Unable to open file!");
				$serverData=explode("#", $_SESSION['server']);
				$dir=getcwd();
				fwrite($fp, "<?php ".PHP_EOL);
				fwrite($fp, '$DB_Host="'.$host.'";'.PHP_EOL);
				fwrite($fp, '$DB_User="'.$user.'";'.PHP_EOL);
				fwrite($fp, '$DB_Password="'.$passwd.'";'.PHP_EOL);
				fwrite($fp, '$DB_Name="'.$db.'";'.PHP_EOL);
				fwrite($fp, '$SSH_User="";'.PHP_EOL);
				fwrite($fp, '$SSH_Password="";'.PHP_EOL);
				fwrite($fp, '$PassType='.$passType.';'.PHP_EOL);
				fwrite($fp, '$ServerIP="'.$serverData[0].'";'.PHP_EOL);
				fwrite($fp, '$LanIP="'.$serverData[1].'";'.PHP_EOL);
				fwrite($fp, '$ServerPort="'.$serverData[2].'";'.PHP_EOL);
				fwrite($fp, '$WebDir="'.$dir.'";'.PHP_EOL);
				fwrite($fp, '$IPRegLimit='.$maxIp.';'.PHP_EOL);
				fwrite($fp, '$SRegLimit='.$maxSess.';'.PHP_EOL);
				fclose($fp);
				unset($_SESSION['server']);
				$_SESSION['pt']=$passType;
				echo "<script>window.location.href='./".$_SERVER['PHP_SELF']."?do=refrUsers';</script>";
			}
		}				
	}elseif($do == "CancelSetup"){
		if (isset($_SESSION['db'])){
			unset($_SESSION['db']);							
		}			
		if (isset($_SESSION['user'])){
			unset($_SESSION['user']);							
			unset($_SESSION['host']);							
			unset($_SESSION['passwd']);			
		}	
		if (isset($_SESSION['server'])){
			unset($_SESSION['server']);	
		}	
		
		echo"<script>
		function DelayFunc(){
			parent.MoveIn('DialogDiv1');
			parent.ChangeProgressBar(0);
		}
		parent.document.getElementById('DialogDiv1').style.left='-1000px';
		parent.document.getElementById('DialogDiv2').style.left='-1000px';
		parent.document.getElementById('DialogDiv3').style.left='-1000px';
		parent.document.getElementById('DialogDiv4').style.left='-1000px';
		parent.document.getElementById('DialogDiv5').style.left='-1000px';
		parent.document.getElementById('AnswerBox').innerHTML='Note: Complete the fields';
		parent.document.getElementById('AnswerBox1').innerHTML='&nbsp;';
		parent.document.getElementById('AnswerBox2').innerHTML='Note: Add atleast 1 Vote Link';
		parent.document.getElementById('AnswerBox3').innerHTML='Note: Enter the service paths!';
		parent.document.getElementById('AnswerBox4').innerHTML='Note: Select existing users or create new admin account!';

		setTimeout(DelayFunc, 1000);
		//setTimeout(parent.document.getElementById('DialogDiv1').style.left=parent.newL+'px', 2000);
		
		</script>";
		unlink($tmpFile);
	}elseif($do == "SaveVoteSystem"){
		if ((isset($_GET['VoteFreq']))&&(isset($_GET['VoteRew']))&&(isset($_GET['VoteFor']))){
			$VFreq=intval($_GET['VoteFreq']);
			$VRew=intval($_GET['VoteRew']);
			$VExc=intval($_GET['VoteExc']);
			$VFor=$_GET['VoteFor'];
			if ($VFreq < 1){
				echo "<script>window.location.href='./".$_SERVER['PHP_SELF']."?do=SkipVote';</script>";
			}else{
				if ($VRew < 1){
					$msg="Reward for vote must be higher than 0!";
					echo"<script>parent.document.getElementById('AnswerBox2').innerHTML='".$msg."';</script>";
				}else{
					$VLinks=array();	
					$VNames=array();	
					$NVLinks=array();	
					$NVNames=array();
					$cNVLinks=0;					
					array_push($VLinks, trim($_GET['VoteL1']), trim($_GET['VoteL2']), trim($_GET['VoteL3']), trim($_GET['VoteL4']), trim($_GET['VoteL5']), trim($_GET['VoteL6']), trim($_GET['VoteL7']));
					array_push($VNames, trim($_GET['VoteN1']), trim($_GET['VoteN2']), trim($_GET['VoteN3']), trim($_GET['VoteN4']), trim($_GET['VoteN5']), trim($_GET['VoteN6']), trim($_GET['VoteN7']));
					for ($i=0;$i<7;$i++) {
						if (strlen($VLinks[$i]) > 5){
							$cNVLinks=$cNVLinks+1;	
							$NVLinks[]=$VLinks[$i];	
							if ($VNames[$i] == ""){
								$VNames[$i]="Vote ".$cNVLinks;
							}
							$NVNames[]=$VNames[$i];	
						}
					}
					$cVLinks1=count($NVLinks);
					$cVLinks2=count(array_unique($NVLinks));
					if ($cNVLinks < 1){
						$msg="Add atleast 1 link or disable vote!";
						echo"<script>parent.document.getElementById('AnswerBox2').innerHTML='".$msg."';</script>";						
					}elseif (($cNVLinks > 0) && ($cVLinks1!=$cVLinks2)){
						$msg="Dublicate entry!";
						echo"<script>parent.document.getElementById('AnswerBox2').innerHTML='".$msg."';</script>";	
					}else{
						//save vote stuff
						$host=$_SESSION['host'];
						$user=$_SESSION['user'];
						$passwd=$_SESSION['passwd'];
						$db=$_SESSION['db'];
						$column1="VotePoint";
						$column2="VoteDates";
						$conn=new mysqli($host, $user, $passwd, $db);
						$conn->query("ALTER TABLE `users` ADD `$column1` INT(11) DEFAULT 0");
						$conn->query("ALTER TABLE `users` ADD `$column2` varchar(255) NOT NULL DEFAULT ''");
						$conn->close();	
						$TodayDate=date("Y-m-d H:i:s");
						$fp=fopen($tmpFile, "a") or exit("<script>alert('Unable to open file!');</script>");
						fwrite($fp, ''.PHP_EOL);
						fwrite($fp, '$VoteButton=true;'.PHP_EOL);
						fwrite($fp, '$VoteCount='.$cNVLinks.';'.PHP_EOL);
						fwrite($fp, '$VoteInterval='.$VFreq.';'.PHP_EOL);
						fwrite($fp, '$VoteReward='.$VRew.';'.PHP_EOL);
						fwrite($fp, '$VoteFor='.$VFor.';'.PHP_EOL);
						fwrite($fp, '$PointExc='.$VExc.';'.PHP_EOL);
						for ($i=0;$i<7;$i++) { 
							$x=$i+1;
							if ($i < $cNVLinks){
								fwrite($fp, '$VoteSite['.$x.']="'.$NVNames[$i].'";'.PHP_EOL);
								fwrite($fp, '$VoteUrl['.$x.']="'.$NVLinks[$i].'";'.PHP_EOL);
							}else{
								fwrite($fp, '$VoteSite['.$x.']="";'.PHP_EOL);
								fwrite($fp, '$VoteUrl['.$x.']="";'.PHP_EOL);
							}
						}
						fclose($fp);
						$msg="<font color=#0000aa>Vote System enabled!</font>";
						echo"<script>parent.document.getElementById('AnswerBox2').innerHTML='".$msg."';
							parent.MoveOut('DialogDiv3');
							parent.MoveIn('DialogDiv4');	
							parent.ChangeProgressBar(3);							
						</script>";	
					}
				}
			}
		}

	}elseif($do == "SkipVote"){
		$host=$_SESSION['host'];
		$user=$_SESSION['user'];
		$passwd=$_SESSION['passwd'];
		$db=$_SESSION['db'];
		$column1="VotePoint";
		$column2="VoteDates";
		$conn=new mysqli($host, $user, $passwd, $db);
		$conn->query("ALTER TABLE `users` ADD `$column1` INT(11) DEFAULT 0");
		$conn->query("ALTER TABLE `users` ADD `$column2` varchar(255) NOT NULL");
		$conn->close();			
		$TodayDate=date("Y-m-d H:i:s");
		$fp=fopen($tmpFile, "a") or exit("Unable to open file!");
		fwrite($fp, ''.PHP_EOL);
		fwrite($fp, '$VoteButton=false;'.PHP_EOL);
		fwrite($fp, '$VoteCount=0;'.PHP_EOL);
		fwrite($fp, '$VoteInterval=0;'.PHP_EOL);
		fwrite($fp, '$VoteReward=0;'.PHP_EOL);
		fwrite($fp, '$VoteFor=1;'.PHP_EOL);
		fwrite($fp, '$PointExc=0;'.PHP_EOL);
		for ($i=1;$i<8;$i++) { 
			fwrite($fp, '$VoteSite['.$i.']="";'.PHP_EOL);
			fwrite($fp, '$VoteUrl['.$i.']="";'.PHP_EOL);
		}
		fclose($fp);
		$msg="<font color=#0000aa>Vote System disabled!</font>";
		echo"<script>parent.document.getElementById('AnswerBox2').innerHTML='".$msg."';
					parent.MoveOut('DialogDiv3');
					parent.MoveIn('DialogDiv4');	
					parent.ChangeProgressBar(3);					 
			</script>";	
		
	}elseif($do == "SetServerStartUp"){
		if ((isset($_GET['sp']))&&(isset($_GET['ls']))&&(isset($_GET['un']))&&(isset($_GET['au']))&&(isset($_GET['db']))&&(isset($_GET['ac']))&&(isset($_GET['gf']))&&(isset($_GET['dd']))&&(isset($_GET['gl']))&&(isset($_GET['gd']))){
			$SP=$_GET['sp'];					//server path
			$LS=explode("*", $_GET['ls']);	//logservice path
			$UN=explode("*", $_GET['un']);	//uniquenamed path
			$AU=explode("*", $_GET['au']);	//authd path
			$DB=explode("*", $_GET['db']);	//gamedbd path
			$AC=explode("*", $_GET['ac']);	//gacd path
			$GF=explode("*", $_GET['gf']);	//gfactiond path
			$DD=explode("*", $_GET['dd']);	//gdeliveryd path
			$GL=explode("*", $_GET['gl']);	//glinkd path
			$GD=explode("*", $_GET['gd']);	//gamed path
			/*
			if (is_file($SP.$LS[0]."/".$LS[1])) {
				if (is_file($SP.$UN[0]."/".$UN[1])) {
					if (is_file($SP.$AU[0]."/".$AU[1])) {
						if (is_file($SP.$DB[0]."/".$DB[1])) {
							if (is_file($SP.$AC[0]."/".$AC[1])) {
								if (is_file($SP.$GF[0]."/".$GF[1])) {
									if (is_file($SP.$DD[0]."/".$DD[1])) {
										if (is_file($SP.$GL[0]."/".$GL[1])) {
											if (is_file($SP.$GD[0]."/".$GD[1])) {
												$msg="<font color=#0000aa>File verification done. Save info!!</font>";
			*/									
												$msg="<font color=#0000aa>Paths saved!!</font>";
												$fp=fopen($tmpFile, "a") or exit("Unable to open file!");
												fwrite($fp, ''.PHP_EOL);
												fwrite($fp, '$ServerPath="'.$SP.'";'.PHP_EOL);
												fwrite($fp, '$ServerFile[1]="'.$LS[0].'*'.$LS[1].'";'.PHP_EOL);
												fwrite($fp, '$ServerSec[1]='.intval($LS[2]).';'.PHP_EOL);
												fwrite($fp, '$ServerFile[2]="'.$UN[0]."*".$UN[1].'";'.PHP_EOL);
												fwrite($fp, '$ServerSec[2]='.intval($UN[2]).';'.PHP_EOL);
												fwrite($fp, '$ServerFile[3]="'.$AU[0]."*".$AU[1].'";'.PHP_EOL);
												fwrite($fp, '$ServerSec[3]='.intval($AU[2]).';'.PHP_EOL);
												fwrite($fp, '$ServerFile[4]="'.$DB[0]."*".$DB[1].'";'.PHP_EOL);
												fwrite($fp, '$ServerSec[4]='.intval($DB[2]).';'.PHP_EOL);
												fwrite($fp, '$ServerFile[5]="'.$AC[0]."*".$AC[1].'";'.PHP_EOL);
												fwrite($fp, '$ServerSec[5]='.intval($AC[2]).';'.PHP_EOL);
												fwrite($fp, '$ServerFile[6]="'.$GF[0]."*".$GF[1].'";'.PHP_EOL);
												fwrite($fp, '$ServerSec[6]='.intval($GF[2]).';'.PHP_EOL);
												fwrite($fp, '$ServerFile[7]="'.$DD[0]."*".$DD[1].'";'.PHP_EOL);
												fwrite($fp, '$ServerSec[7]='.intval($DD[2]).';'.PHP_EOL);
												fwrite($fp, '$ServerFile[8]="'.$GL[0]."*".$GL[1].'";'.PHP_EOL);
												fwrite($fp, '$ServerSec[8]='.intval($GL[2]).';'.PHP_EOL);
												fwrite($fp, '$ServerFile[9]="'.$GD[0]."*".$GD[1].'";'.PHP_EOL);
												fwrite($fp, '$ServerSec[9]='.intval($GD[2]).';'.PHP_EOL);
												fclose($fp);
												echo"<script>
													parent.MoveOut('DialogDiv4');
													parent.MoveIn('DialogDiv5');
													parent.ChangeProgressBar(4);
													</script>";	
			/*										
											}else{
												$msg="Wrong path: ".$SP.$GD[0]."/".$GD[1]." !";
											}
										}else{
											$msg="Wrong path: ".$SP.$GL[0]."/".$GL[1]." !";
										}
									}else{
										$msg="Wrong path: ".$SP.$DD[0]."/".$DD[1]." !";
									}
								}else{
									$msg="Wrong path: ".$SP.$GF[0]."/".$GF[1]." !";
								}
							}else{
								$msg="Wrong path: ".$SP.$AC[0]."/".$AC[1]." !";
							}
						}else{
							$msg="Wrong path: ".$SP.$DB[0]."/".$DB[1]." !";
						}
					}else{
						$msg="Wrong path: ".$SP.$AU[0]."/".$AU[1]." !";
					}
				}else{
					$msg="Wrong path: ".$SP.$UN[0]."/".$UN[1]." !";
				}
			}else{
				$msg="Wrong path: ".$SP.$LS[0]."/".$LS[1]." !";
			}
			*/
			echo"<script>parent.document.getElementById('AnswerBox3').innerHTML='".$msg."';</script>";	
		}
	}elseif($do == "SkipStartUp"){
		$fp=fopen($tmpFile, "a") or exit("Unable to open file!");
		fwrite($fp, ''.PHP_EOL);
		fwrite($fp, '$ServerPath="/root";'.PHP_EOL);
		fwrite($fp, '$ServerFile[1]="/logservice*logservice";'.PHP_EOL);
		fwrite($fp, '$ServerSec[1]=1;'.PHP_EOL);
		fwrite($fp, '$ServerFile[2]="/uniquenamed*uniquenamed";'.PHP_EOL);
		fwrite($fp, '$ServerSec[2]=1;'.PHP_EOL);
		fwrite($fp, '$ServerFile[3]="/authd/build*authd";'.PHP_EOL);
		fwrite($fp, '$ServerSec[3]=3;'.PHP_EOL);
		fwrite($fp, '$ServerFile[4]="/gamedbd*gamedbd";'.PHP_EOL);
		fwrite($fp, '$ServerSec[4]=1;'.PHP_EOL);
		fwrite($fp, '$ServerFile[5]="/gacd*gacd";'.PHP_EOL);
		fwrite($fp, '$ServerSec[5]=1;'.PHP_EOL);
		fwrite($fp, '$ServerFile[6]="/gfactiond*gfactiond";'.PHP_EOL);
		fwrite($fp, '$ServerSec[6]=1;'.PHP_EOL);
		fwrite($fp, '$ServerFile[7]="/gdeliveryd*gdeliveryd";'.PHP_EOL);
		fwrite($fp, '$ServerSec[7]=1;'.PHP_EOL);
		fwrite($fp, '$ServerFile[8]="/glinkd*glinkd";'.PHP_EOL);
		fwrite($fp, '$ServerSec[8]=3;'.PHP_EOL);
		fwrite($fp, '$ServerFile[9]="/gamed*gs";'.PHP_EOL);
		fwrite($fp, '$ServerSec[9]=0;'.PHP_EOL);
		fclose($fp);
		echo"<script>
			parent.MoveOut('DialogDiv4');
			parent.MoveIn('DialogDiv5');
			parent.ChangeProgressBar(4);
			</script>";			
		$msg="<font color=#0000aa>We use the default paths!</font>";
		echo"<script>parent.document.getElementById('AnswerBox3').innerHTML='".$msg."';</script>";	
	}elseif($do == "FinalSettings"){
		if ((isset($_GET['sn']))&&(isset($_GET['db']))&&(isset($_GET['fb']))&&(isset($_GET['fu']))&&(isset($_GET['at']))&&(isset($_GET['su']))&&(isset($_GET['an']))&&(isset($_GET['ae']))&&(isset($_GET['ap']))&&(isset($_GET['sp']))&&(isset($_GET['sg']))&&(isset($_GET['sv']))&&(isset($_GET['ws']))&&(isset($_GET['iil']))&&(isset($_GET['wc']))){	
			if ((isset($_SESSION['host']))&&(isset($_SESSION['user']))&&(isset($_SESSION['db']))){
				$servnm=trim($_GET['sn']);
				$donbut=trim($_GET['db']);
				$forbut=trim($_GET['fb']);
				$webshp=trim($_GET['ws']);
				$ItemIdLimit=intval(trim($_GET['iil']));
				$forurl=trim($_GET['fu']);
				$acctyp=trim($_GET['at']);
				$selusr=trim($_GET['su']);
				$admnam=StrToLower(trim($_GET['an']));
				$admema=trim($_GET['ae']);
				$admpas=trim($_GET['ap']);
				$strpoi=trim($_GET['sp']);
				$strgol=trim($_GET['sg']);
				$ServVer=trim($_GET['sv']);
				$webcntrl=trim($_GET['wc']);
				$work=false;
				$pwAdminId=0;
				$pwAdminPw="";
				$msg="";
				$host=$_SESSION['host'];
				$user=$_SESSION['user'];
				$passwd=$_SESSION['passwd'];
				$db=$_SESSION['db'];
				$bool[0]="false";
				$bool[1]="true";
				
				if ($acctyp==1){
					if ((strlen($selusr)>3)&&(ctype_alnum($selusr))){
						$link=new mysqli($host, $user, $passwd, $db);
						if ($link->connect_errno) {
							$msg="Cannot connect to MySQL";
						}else{
							$query="SELECT ID, passwd FROM users WHERE name=?";
							$statement=$link->prepare($query);
							$statement->bind_param('s', $selusr);
							$statement->execute();
							$statement->bind_result($ID, $Upass);
							$statement->store_result();
							$result=$statement->num_rows;
							$count=$result;
							$cod_passwd="";
							$selId=0;
							if ($count==1){
								if ($_SESSION['pt']==1){
									while($statement->fetch()) {
										$pwAdminId=$ID;
										$pwAdminPw=$Upass;	
										
									}
									$msg="<font color=#0000aa>".$selusr."[".$selId."] became the pwAdmin!";
									$work=true;
								}elseif ($_SESSION['pt']==2){
									while($statement->fetch()) {
										$pwAdminId=$ID;
										$pwAdminPw=$Upass;	
										
									}
									$msg="<font color=#0000aa>".$selusr."[".$selId."] became the pwAdmin!";
									$work=true;
								}elseif ($_SESSION['pt']==3){
									while($statement->fetch()) {
										$dpasswd=addslashes($Upass);
										$selId=$ID;
									}	
									$rs=mysqli_query($link,"SELECT fn_varbintohexsubstring (1,'$dpasswd',1,0) AS result");
									$GetResult=mysqli_fetch_array($rs, MYSQLI_BOTH);
									$enc_passwd=substr($GetResult['result'],2);
									$pwAdminId=$selId;
									$pwAdminPw=$enc_passwd;
									$msg="<font color=#0000aa>".$selusr."[".$selId."] became the pwAdmin!";
									$work=true;									
								}
							}else{
								$msg="User not exist!";
							}
							$statement->close();
							mysqli_close($link);									
						}
					}else{
						$msg="Enter the username!";
					}
					echo"<script>parent.document.getElementById('AnswerBox4').innerHTML='".$msg."';</script>";	
				}elseif ($acctyp==2){
					if ((strlen($admnam)>5)&&(strlen($admpas)>5)){
						if ((ctype_alnum($admnam))&&(ctype_alnum($admpas))){
							if ((strlen($admema)>5)&&(validEmail($admema))){
										$link=new mysqli($host, $user, $passwd, $db);
										if ($link->connect_errno) {
											$msg="Cannot connect to MySQL";
										}else{
											$query="SELECT name FROM users WHERE name=?";
											$statement=$link->prepare($query);
											$statement->bind_param('s', $admnam);
											$statement->execute();
											$statement->store_result();
											$result=$statement->num_rows;
											$count=$result;
											$statement->close();		
											if ($count==0){
												$Salt1=$admnam.$admpas;
												$IPL=$_SERVER['REMOTE_ADDR'];
												if ($_SESSION['pt']==1){
													$Salt1="0x".md5($Salt1);
													$Salt=$Salt1;
													$query="call adduser('$admnam', '$Salt', '0', '0', '', '$IPL', '$admema', '0', '0', '0', '0', '0', '0', '0', '', '', '$Salt')";
													$rs=mysqli_query($link, $query);
												}elseif ($_SESSION['pt']==2){
													$Salt1=base64_encode(hash('md5',strtolower($admnam).$admpas, true));
													$Salt=$Salt1;
													$query="call adduser('$admnam', '$Salt', '0', '0', '', '$IPL', '$admema', '0', '0', '0', '0', '0', '0', '0', '', '', '$Salt')";
													$rs=mysqli_query($link, $query);
												}elseif ($_SESSION['pt']==3){
													$Salt1=md5($Salt1);
													$Salt="0x".$Salt1;
													$query="call adduser('$admnam', {$Salt}, '0', '0', '', '{$IPL}', '{$admema}', '0', '0', '0', '0', '0', '0', '0', '1970-01-01 08:00:00', ' ', {$Salt})";
													$rs=mysqli_query($link, $query);
												}
												$statement=$link->prepare("SELECT ID, creatime FROM users WHERE name=?");
												$statement->bind_param('s', $admnam);
												$statement->execute();
												$statement->bind_result($ID, $TIME);
												$statement->store_result();
												$result=$statement->num_rows;		
												if ($result==1){	
													while($statement->fetch()) {
															$nr0=0;
															$nr1=1;
															$StartGold=9999900;
															$StartPoint=9999900;
															$stmt=$link->prepare("INSERT INTO usecashnow (userid, zoneid, sn, aid, point, cash, status, creatime) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
															$stmt->bind_param("iiiiiiis", $ID, $nr1, $nr0, $nr1, $nr0, $StartGold, $nr1, $TIME);
															$stmt->execute(); 
															$stmt=$link->prepare("UPDATE users SET VotePoint=? WHERE ID=?");
															$stmt->bind_param('ii', $StartPoint, $ID);
															$stmt->execute(); 
															$stmt->close();
													}
													$msg="<font color=#0000aa>Account created!</font>";
													$pwAdminId=$ID;
													$pwAdminPw=$Salt1;
													$work=true;
												}else{
													$msg="Error: Account creation failed!";
												}

												$statement->close();												
												
											}else{
												$msg="Username already exist!";
											}
											mysqli_close($link);									
										}
							}else{
								$msg="Enter valid email address!";
							}
						}else{
							$msg="Username and password must be alphanumeric!";
						}
					}else{
						$msg="Username and password must be atleast 6 character!";
					}
				}
				
				if ($work!==false){
					$tmpFile2="test.tmp";
					$fp=fopen($tmpFile2, "w") or exit("Unable to open file!");
					fwrite($fp, ''.PHP_EOL);
					fclose($fp);
					$stat=stat($tmpFile2);
					$sKey1=base64_encode(md5(time()."This is my web page and this is first key"));
					$sKey2=base64_encode(md5(time()."Advanced key here, to have more fun"));
					$fp=fopen($tmpFile, "a") or exit("Unable to open file!");
//					fwrite($fp, ''.PHP_EOL);
//					fwrite($fp, '$PHPUser="'.$stat['uid'].'";'.PHP_EOL);
//					fwrite($fp, '$PHPGroup="'.$stat['gid'].'";'.PHP_EOL);
					unlink($tmpFile2);
					fwrite($fp, ''.PHP_EOL);
					fwrite($fp, '$ServerName="'.$servnm.'";'.PHP_EOL);
					fwrite($fp, '$Donation='.$bool[$donbut].';'.PHP_EOL);
					fwrite($fp, '$Forum='.$bool[$forbut].';'.PHP_EOL);
					fwrite($fp, '$ItemIdLimit='.$ItemIdLimit.';'.PHP_EOL);
					fwrite($fp, '$WebShop='.$bool[$webshp].';'.PHP_EOL);
					fwrite($fp, '$WebShopLog=true;'.PHP_EOL);
					fwrite($fp, '$WShopLogDel=30;'.PHP_EOL);
					fwrite($fp, '$WShopDB=1;'.PHP_EOL);
					fwrite($fp, '$ControlPanel='.$bool[$webcntrl].';'.PHP_EOL);
					fwrite($fp, '$ForumUrl="'.$forurl.'";'.PHP_EOL);
					fwrite($fp, '$StartGold='.$strgol.';'.PHP_EOL);
					fwrite($fp, '$StartPoint='.$strpoi.';'.PHP_EOL);
					fwrite($fp, '$RegisEnabled=true;'.PHP_EOL);
					fwrite($fp, '$LoginEnabled=true;'.PHP_EOL);
					fwrite($fp, '$AdminId='.$pwAdminId.';'.PHP_EOL);
					fwrite($fp, '$AdminPw="'.$pwAdminPw.'";'.PHP_EOL);
					fwrite($fp, '$MaxWebPoint=2000000000;'.PHP_EOL);
					fwrite($fp, ''.PHP_EOL);
					fwrite($fp, '$months[1]="January";'.PHP_EOL);
					fwrite($fp, '$months[2]="February";'.PHP_EOL);
					fwrite($fp, '$months[3]="March";'.PHP_EOL);
					fwrite($fp, '$months[4]="April";'.PHP_EOL);
					fwrite($fp, '$months[5]="May";'.PHP_EOL);
					fwrite($fp, '$months[6]="June";'.PHP_EOL);
					fwrite($fp, '$months[7]="July";'.PHP_EOL);
					fwrite($fp, '$months[8]="August";'.PHP_EOL);
					fwrite($fp, '$months[9]="September";'.PHP_EOL);
					fwrite($fp, '$months[10]="October";'.PHP_EOL);
					fwrite($fp, '$months[11]="November";'.PHP_EOL);
					fwrite($fp, '$months[12]="December";'.PHP_EOL);
					fwrite($fp, '$ServerVer='.$ServVer.';'.PHP_EOL);
					fwrite($fp, '$AKey1="'.$sKey1.'";'.PHP_EOL);
					fwrite($fp, '$AKey2="'.$sKey2.'";'.PHP_EOL);
					fwrite($fp, '$PWclsPath[0]="";'.PHP_EOL);
					fwrite($fp, '$PWclsPath[1]="Holy ";'.PHP_EOL);
					fwrite($fp, '$PWclsPath[2]="Dark ";'.PHP_EOL);
					fwrite($fp, '$PWclass[1]="Warrior";'.PHP_EOL);
					fwrite($fp, '$PWclass[2]="Magician";'.PHP_EOL);
					fwrite($fp, '$PWclass[3]="Werebeast";'.PHP_EOL);
					fwrite($fp, '$PWclass[4]="Werefox";'.PHP_EOL);
					fwrite($fp, '$PWclass[5]="Elf Archer";'.PHP_EOL);	
					fwrite($fp, '$PWclass[6]="Elf Priest";'.PHP_EOL);
					if ($ServVer > 39){
						fwrite($fp, '$PWclass[7]="Rogue";'.PHP_EOL);							
						fwrite($fp, '$PWclass[8]="Shaman";'.PHP_EOL);
					}
					if ($ServVer > 49){
						fwrite($fp, '$PWclass[9]="Seeker";'.PHP_EOL);
						fwrite($fp, '$PWclass[10]="Mystic";'.PHP_EOL);							
					}
					if ($ServVer > 79){
						fwrite($fp, '$PWclass[11]="DuskBlade";'.PHP_EOL);
						fwrite($fp, '$PWclass[12]="StormBringer";'.PHP_EOL);							
					}
					
					fwrite($fp, '?>'.PHP_EOL);
					fclose($fp);
					chmod($cfgFile, 0777);
					rename($cfgFile, $cfgFile.".bak");
					rename($tmpFile, $cfgFile);
					echo"<script>
					parent.MoveOut('DialogDiv5');
					parent.MoveIn('DialogDiv6');
					parent.ChangeProgressBar(5);
					</script>";	
				}
				echo"<script>parent.document.getElementById('AnswerBox4').innerHTML='".$msg."';</script>";	

			}else{
				$msg="Refresh your browser!";
				echo"<script>parent.document.getElementById('AnswerBox4').innerHTML='".$msg."';</script>";	
			}
		}
	}elseif($do == "DelCFG"){
		unlink("setup_proc.php");
		unlink("setup.php");
		if (isset($_SESSION['db'])){
			unset($_SESSION['db']);							
		}			
		if (isset($_SESSION['user'])){
			unset($_SESSION['user']);							
			unset($_SESSION['host']);							
			unset($_SESSION['passwd']);			
		}	
		if (isset($_SESSION['server'])){
			unset($_SESSION['server']);	
		}
		echo "<script>parent.window.location.href='./index.php';</script>";
	}elseif($do == "SkipCFG"){		
		if (isset($_SESSION['db'])){
			unset($_SESSION['db']);							
		}			
		if (isset($_SESSION['user'])){
			unset($_SESSION['user']);							
			unset($_SESSION['host']);							
			unset($_SESSION['passwd']);			
		}	
		if (isset($_SESSION['server'])){
			unset($_SESSION['server']);	
		}
		echo "<script>parent.window.location.href='./index.php';</script>";
	}elseif($do == "refrUsers"){

	}
}



function GetMysqlPath(){
	$WhereIsMysql=shell_exec("whereis mysql");
	$expArr=explode(" ", $WhereIsMysql);
	$mysql_path="";
	for ($i=0; $i < count($expArr); $i++) {
		$str=trim($expArr[$i]);
		if (strlen($str)>10){
			$LastPart=substr($str, -10); 
			if (strtolower($LastPart)=="/bin/mysql"){
				$mysql_path=$str;
			}
		}
	}
	return $mysql_path;
}

function validEmail($email){
    if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $email)) {
        return false;
    }
    $email_array=explode("@", $email);
    $local_array=explode(".", $email_array[0]);
    for ($i=0; $i < sizeof($local_array); $i++) {
        if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
            return false;
        }
    }
    if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
        $domain_array=explode(".", $email_array[1]);
        if (sizeof($domain_array) < 2) {
            return false; // Not enough parts to domain
        }
        for ($i=0; $i < sizeof($domain_array); $i++) {
            if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                return false;
            }
        }
    }

    return true;
}
?>