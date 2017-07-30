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
		if (VerifyAdmin($link, $un, $pw, $id, $ma)!==false){
			if ((isset($data['sn']))&&(isset($data['al']))&&(isset($data['ri']))&&(isset($data['rs']))&&(isset($data['sg']))&&(isset($data['sp']))&&(isset($data['mp']))&&(isset($data['mi']))&&(isset($data['wc']))&&(isset($data['db']))&&(isset($data['fb']))&&(isset($data['fu']))&&(isset($data['vg']))&&(isset($data['ve']))&&(isset($data['vt']))&&(isset($data['vf']))&&(isset($data['vr']))&&(isset($data['mh']))&&(isset($data['dn']))&&(isset($data['dp']))&&(isset($data['mu']))&&(isset($data['pi']))&&(isset($data['li']))&&(isset($data['su']))&&(isset($data['sj']))&&(isset($data['sr']))&&(isset($data['pt']))&&(isset($data['as']))&&(isset($data['wl']))&&(isset($data['wd']))&&(isset($data['sd']))){
				$bool[0]="false";
				$bool[1]="true";
				$SN=htmlspecialchars(trim($data['sn']));
				$AL=intval(trim($data['al']));
				$AR=intval(trim($data['ar']));
				$RI=intval(trim($data['ri']));
				$RS=intval(trim($data['rs']));
				$SG=intval(trim($data['sg']));
				$SP=intval(trim($data['sp']));
				$MP=intval(trim($data['mp']));
				$MI=intval(trim($data['mi']));
				$WC=intval(trim($data['wc']));
				$WS=intval(trim($data['ws']));
				$WD=intval(trim($data['wd']));
				$WL=intval(trim($data['wl']));
				$DB=intval(trim($data['db']));
				$FB=intval(trim($data['fb']));
				$VG=intval(trim($data['vg']));
				$VE=intval(trim($data['ve']));
				$VT=intval(trim($data['vt']));
				$VF=intval(trim($data['vf']));
				$VR=intval(trim($data['vr']));	
				$SD=intval(trim($data['sd']));										
				$FU=trim($data['fu']);
				$MH=trim($data['mh']);
				$DN=trim($data['dn']);
				$DP=trim($data['dp']);
				$MU=trim($data['mu']);
				$PI=trim($data['pi']);
				$LI=trim($data['li']);
				$SU=trim($data['su']);
				$SJ=trim($data['sj']);
				$SR=trim($data['sr']);
				$PT=trim($data['pt']);
				$AS=trim($data['as']);
				$FU=trim($data['fu']);
				
				if ((filter_var($FU, FILTER_VALIDATE_URL))&&($FU!="")){
					$filen='../config.php';
					$fileno='../config_old.php';
					$str=file_get_contents($filen);
					$str=str_replace('ServerName="'.$ServerName.'";', 'ServerName="'.$SN.'";', $str);
					$str=str_replace('LoginEnabled='.BoolToSting($LoginEnabled).';', 'LoginEnabled='.$bool[$AL].';', $str);									
					$str=str_replace('RegisEnabled='.BoolToSting($RegisEnabled).';', 'RegisEnabled='.$bool[$AR].';', $str);									
					$str=str_replace('IPRegLimit='.$IPRegLimit.';', 'IPRegLimit='.$RI.';', $str);									
					$str=str_replace('SRegLimit='.$SRegLimit.';', 'SRegLimit='.$RS.';', $str);									
					$str=str_replace('StartGold='.$StartGold.';', 'StartGold='.$SG.';', $str);									
					$str=str_replace('StartPoint='.$StartPoint.';', 'StartPoint='.$SP.';', $str);									
					$str=str_replace('MaxWebPoint='.$MaxWebPoint.';', 'MaxWebPoint='.$MP.';', $str);
					$str=str_replace('ItemIdLimit='.$ItemIdLimit.';', 'ItemIdLimit='.$MI.';', $str);									
					$str=str_replace('WebShop='.BoolToSting($WebShop).';', 'WebShop='.$bool[$WS].';', $str);			
					$str=str_replace('WebShopLog='.BoolToSting($WebShopLog).';', 'WebShopLog='.$bool[$WL].';', $str);			
					$str=str_replace('WShopLogDel='.$WShopLogDel.';', 'WShopLogDel='.$WD.';', $str);			
					$str=str_replace('WShopDB='.$WShopDB.';', 'WShopDB='.$SD.';', $str);			
					$str=str_replace('ControlPanel='.BoolToSting($ControlPanel).';', 'ControlPanel='.$bool[$WC].';', $str);									
					$str=str_replace('Donation='.BoolToSting($Donation).';', 'Donation='.$bool[$DB].';', $str);									
					$str=str_replace('Forum='.BoolToSting($Forum).';', 'Forum='.$bool[$FB].';', $str);									
					$str=str_replace('ForumUrl="'.$ForumUrl.'";', 'ForumUrl="'.$FU.'";', $str);	
					$str=str_replace('VoteButton='.BoolToSting($VoteButton).';', 'VoteButton='.$bool[$VE].';', $str);									
					$str=str_replace('PointExc='.$PointExc.';', 'PointExc='.$VG.';', $str);									
					$str=str_replace('VoteInterval='.$VoteInterval.';', 'VoteInterval='.$VT.';', $str);									
					$str=str_replace('VoteFor='.$VoteFor.';', 'VoteFor='.$VF.';', $str);									
					$str=str_replace('VoteReward='.$VoteReward.';', 'VoteReward='.$VR.';', $str);		
					$str=str_replace('DB_Host="'.$DB_Host.'";', 'DB_Host="'.$MH.'";', $str);									
					$str=str_replace('DB_User="'.$DB_User.'";', 'DB_User="'.$MU.'";', $str);									
					$str=str_replace('DB_Name="'.$DB_Name.'";', 'DB_Name="'.$DN.'";', $str);									
					$str=str_replace('DB_Password="'.$DB_Password.'";', 'DB_Password="'.$DP.'";', $str);									
					$str=str_replace('SSH_User="'.$SSH_User.'";', 'SSH_User="'.$SU.'";', $str);									
					$str=str_replace('SSH_Password="'.$SSH_Password.'";', 'SSH_Password="'.$SJ.'";', $str);									
					$str=str_replace('ServerIP="'.$ServerIP.'";', 'ServerIP="'.$PI.'";', $str);									
					$str=str_replace('LanIP="'.$LanIP.'";', 'LanIP="'.$LI.'";', $str);									
					$str=str_replace('DB_Host="'.$DB_Host.'";', 'DB_Host="'.$MH.'";', $str);									
					$str=str_replace('ServerPath="'.$ServerPath.'";', 'ServerPath="'.$SR.'";', $str);									
					$str=str_replace('PassType='.$PassType.';', 'PassType="'.$PT.'";', $str);									
					$tmpArr=explode("#", $AS);
					for ($i = 1; $i <= 9; $i++){
						$str=str_replace('ServerFile['.$i.']="'.$ServerFile[$i].'";', 'ServerFile['.$i.']="'.$tmpArr[$i].'";', $str);
					}
					chmod($filen, 0777);
					$fperm=substr(sprintf('%o', fileperms($filen)), -4);
					rename($filen, $fileno);
					file_put_contents($filen, $str);
					chmod($filen, 0755);	
					if ($fperm=="0777"){
						$resp="";
					}else{
						$resp="Unable save because not have permission for config file!";
					}
					
				}else{
					$resp="Invalid forum url!";
				}
			}			
		}
	}	
	mysqli_close($link);

}
echo $resp;

?>
