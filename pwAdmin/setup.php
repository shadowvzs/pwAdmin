<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<?php 
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-cache");
header("Pragma: no-cache");
?>
<title>Perfect World RetroMS Web Setup</title>
<style>
body {
	position: relative;
	min-width: 1000px;
	font-family:Arial;
	background-image:
    linear-gradient(
      to right, 
      #fffdc2,
      #fffdc2 15%,
      #d7f0a2 15%,
      #d7f0a2 85%,
      #fffdc2 85%
    );
}
#DialogDiv1, #DialogDiv2, #DialogDiv3, #DialogDiv4, #DialogDiv5, #DialogDiv6, #DialogDiv7 {
	position: absolute;
	background-color: #eee;
	border: 1px inset #333;
	-moz-box-shadow: 10px 10px 14px -7px #777;
	-webkit-box-shadow: 10px 10px 14px -7px #777;
	box-shadow: 10px 10px 14px -7px #777;
	top: 200px;
	left:-1000px;
	transition: 0.4s;
	transition-timing-function: ease-in;
	height: 190px;
	width: 400px;
	font-family:Arial;
	font-size:12px;
	opacity: 0.95;
	-webkit-transition-delay: 0.5s; /* Safari */
    transition-delay: 0.5s;
}
#DHeaderDiv{
	position: absolute;
	left: 1px;
	right: 1px;
	top: 1px;
	height: 20px;
	border: 1px solid #000;
	pad1ding: 2px 10px;
	line-height:20px;
	font-size:16px;
	text-shadow: 1px 1px 2px #000, 0px 0px 1px #000; 
	color:#fff;
	background-color: #fff;
	background: -webkit-linear-gradient(-90deg, #88d, #337);
	background: -o-linear-gradient(-90deg, #88d, #337);
	background: -moz-linear-gradient(-90deg, #88d, #337);
	background: linear-gradient(-90deg, #88d, #337);
}

#DContDiv{
	position: absolute;
	border: 1px solid #777;
	left: 1px;
	right: 1px;
	top: 22px;
	bottom:1px;
	padding: 10px 20px;
	background-color: #fff;
	background: -webkit-linear-gradient(-90deg, #eef, #ddf);
	background: -o-linear-gradient(-90deg, #eef, #ddf);
	background: -moz-linear-gradient(-90deg, #eef, #ddf);
	background: linear-gradient(-90deg, #eef, #ddf);
}
button{
	-moz-box-shadow: 2px 2px 4px -2px #777;
	-webkit-box-shadow: 2px 2px 4px -2px #777;
	box-shadow: 2px 2px 4px -2px #777;
}
button:hover{
	-moz-box-shadow: 0px 0px 0px 0px #777;
	-webkit-box-shadow: 0px 0px 0px 0px  #777;
	box-shadow: 0px 0px 0px 0px #777;
}
#bigCont{
	position: fixed;
	overflow: hidden;
	width: 100%;
	height: 100%;
}
input:focus{
	background-color: #ffd;
}
input{
	text-shadow: 2px 2px 3px #aaa; 
}

#ProgressDiv{
	position:relative;
	top: -1000px;
	width: 300px;
	height: 70px;
	margin: 0 auto;
	transition: 0.75s;
	transition-timing-function: ease-out;
	border: 1px solid #777;
	padding: 10px 20px;
	background-color: #fff;	
	-moz-box-shadow: 10px 10px 14px -7px #777;
	-webkit-box-shadow: 10px 10px 14px -7px #777;
	box-shadow: 10px 10px 14px -7px #777;
	text-align:center;	
	opacity:0.9;
	-webkit-transition-delay: 0.5s; /* Safari */
    transition-delay: 0.5s;	
}
#ProgressBody{
	position: absolute;
	border: 1px solid #777;
	left: 1px;
	right: 1px;
	top: 22px;
	color:#fff;
	text-shadow: 2px 2px 3px #000; 
	bottom:1px;
	padding: 10px 20px;
	background-color: #fff;
	background: -webkit-linear-gradient(-90deg, #eef, #ddf);
	background: -o-linear-gradient(-90deg, #eef, #ddf);
	background: -moz-linear-gradient(-90deg, #eef, #ddf);
	background: linear-gradient(-90deg, #eef, #ddf);
}
#ProgressCont{
	position: absolute;
	bottom:10px;
	left:10px;
	right:10px;
	height:16px;
	background-color: #eee;
	bor1der: 1px inset #eee;
	border: 1px solid #000;
	background: repeating-linear-gradient(45deg,#eee,#eee 10px,#ddd 10px,#ddd 20px);
	border-radius: 5px;
}
#ProgressBar{
	position:absolute;
	background-color: #f00;
	top:1px;
	left:1px;
	height:14px;
	width:0%;
    -webkit-transition: width 2s; /* For Safari 3.1 to 6.0 */
    transition: width 2s;	
	transition-timing-function: ease-out;
	-webkit-transition-timing-function: ease-out;
	opacity:0.4;
	border-radius: 5px;
}
#ProgressRate{
	position:absolute;
	width:100%;
	height:100%;
	width:0%;
	font-family:arial;
	font-size:12px;
	color:#000;
	top:1px;
	text-align:center;
	display:inline-block;
}
</style>

<script>
	var WebPath = "";
	var SelUser = 0;
	var AType = 1;
function Confirm(nr){
	if (nr == 1){
		var HostAddr = document.getElementById('HostAddr').value;
		var MysqlUser = document.getElementById('MySQLUser').value;
		var MysqlPw = document.getElementById('MySQLPasswd').value;
		var ServAddr = document.getElementById('ServerAddr').value;
		var LanIP = document.getElementById('LanIp').value;
		var Port = document.getElementById('Port').value;
		var newUrl = "./setup_proc.php?do=setConnection&host="+HostAddr+"&user="+MysqlUser+"&passwd="+MysqlPw+"&ServerIp="+ServAddr+"&LanIp="+LanIP+"&Port="+Port;
		ifr.src = newUrl;
	}else if(nr == 2){
		var Db_name = document.getElementById('DbName').value;
		var MIp = document.getElementById('MaxAccSession').value;
		var MBr = document.getElementById('MaxAccIp').value;
		var passType = 0;
		if (document.getElementById('MD5Pass').checked !== false){
			passType = 1;
		}else if (document.getElementById('Base64Pass').checked !== false){
			passType = 2;
		}else if (document.getElementById('BinPass').checked !== false){
			passType = 3;
		}
		var newUrl = "./setup_proc.php?do=checkDb&dbname="+Db_name+"&MaxIp="+MIp+"&MaxSess="+MBr+"&passType="+passType;
		ifr.src = newUrl;
	}else if(nr == 3){
		var Db_name = document.getElementById('DbName').value;
		var SQL_path = document.getElementById('SqlPath').value;
		var MIp = document.getElementById('MaxAccSession').value;
		var MBr = document.getElementById('MaxAccIp').value;
		var passType = 0;
		if (document.getElementById('MD5Pass').checked !== false){
			passType = 1;
		}else if (document.getElementById('Base64Pass').checked !== false){
			passType = 2;
		}else if (document.getElementById('BinPass').checked !== false){
			passType = 3;
		}		
		var newUrl = "./setup_proc.php?do=CreateDb&dbname="+Db_name+"&path="+SQL_path+"&MaxIp="+MIp+"&MaxSess="+MBr+"&passType="+passType;
		ifr.src = newUrl;
	}else if(nr == 4){
		var VoteL1 = document.getElementById('VoteUrl1').value;
		var VoteL2 = document.getElementById('VoteUrl2').value;
		var VoteL3 = document.getElementById('VoteUrl3').value;
		var VoteL4 = document.getElementById('VoteUrl4').value;
		var VoteL5 = document.getElementById('VoteUrl5').value;
		var VoteL6 = document.getElementById('VoteUrl6').value;
		var VoteL7 = document.getElementById('VoteUrl7').value;	
		var VoteN1 = document.getElementById('VoteNam1').value;
		var VoteN2 = document.getElementById('VoteNam2').value;
		var VoteN3 = document.getElementById('VoteNam3').value;
		var VoteN4 = document.getElementById('VoteNam4').value;
		var VoteN5 = document.getElementById('VoteNam5').value;
		var VoteN6 = document.getElementById('VoteNam6').value;
		var VoteN7 = document.getElementById('VoteNam7').value;
		var VoteRew = document.getElementById('VoteRew').value;
		var VoteFreq = document.getElementById('VoteInt').value;
		var VoteP = document.getElementById('VoteForPoint');
		var VoteG = document.getElementById('VoteForGold');
		var VoteExc = document.getElementById('VoteExc').value;
		
		var VoteFor = 0;
		
		if (VoteP.checked){
			VoteFor=1;
		}else if(VoteG.checked){
			VoteFor=2;
		}
		var newUrl = "./setup_proc.php?do=SaveVoteSystem&VoteFreq="+VoteFreq+"&VoteFor="+VoteFor+"&VoteRew="+VoteRew+"&VoteL1="+VoteL1+"&VoteL2="+VoteL2+"&VoteL3="+VoteL3+"&VoteL4="+VoteL4+"&VoteL5="+VoteL5+"&VoteL6="+VoteL6+"&VoteL7="+VoteL7+"&VoteN1="+VoteN1+"&VoteN2="+VoteN2+"&VoteN3="+VoteN3+"&VoteN4="+VoteN4+"&VoteN5="+VoteN5+"&VoteN6="+VoteN6+"&VoteN7="+VoteN7+"&VoteExc="+VoteExc;
		ifr.src = newUrl;
	}else if(nr == 5){
		parent.MoveOut('DialogDiv3');
		parent.MoveIn('DialogDiv4');
		var newUrl = "./setup_proc.php?do=SkipVote";
		ifr.src = newUrl;
	}else if(nr == 6){
		var LS1 = document.getElementById('LOGSERVICE1').value;
		var LS2 = document.getElementById('LOGSERVICE2').value;
		var LS3 = document.getElementById('LOGSERVICE3').value;
		var UN1 = document.getElementById('UNIQUENAMED1').value;
		var UN2 = document.getElementById('UNIQUENAMED2').value;
		var UN3 = document.getElementById('UNIQUENAMED3').value;
		var AU1 = document.getElementById('AUTH1').value;
		var AU2 = document.getElementById('AUTH2').value;
		var AU3 = document.getElementById('AUTH3').value;
		var DB1 = document.getElementById('GAMEDBD1').value;
		var DB2 = document.getElementById('GAMEDBD2').value;
		var DB3 = document.getElementById('GAMEDBD3').value;
		var AC1 = document.getElementById('GACD1').value;
		var AC2 = document.getElementById('GACD2').value;
		var AC3 = document.getElementById('GACD3').value;
		var GF1 = document.getElementById('GFACTIOND1').value;
		var GF2 = document.getElementById('GFACTIOND2').value;
		var GF3 = document.getElementById('GFACTIOND3').value;
		var DD1 = document.getElementById('GDELIVERYD1').value;
		var DD2 = document.getElementById('GDELIVERYD2').value;
		var DD3 = document.getElementById('GDELIVERYD3').value;
		var GL1 = document.getElementById('GLINKD1').value;
		var GL2 = document.getElementById('GLINKD2').value;
		var GL3 = document.getElementById('GLINKD3').value;
		var GD1 = document.getElementById('GAMED1').value;
		var GD2 = document.getElementById('GAMED2').value;
		var GD3 = document.getElementById('GAMED3').value;
		var SP = document.getElementById('SERVERFOLDER').value;
		var newUrl = "./setup_proc.php?do=SetServerStartUp&sp="+SP+"&ls="+LS1+"*"+LS2+"*"+LS3+"&un="+UN1+"*"+UN2+"*"+UN3+"&au="+AU1+"*"+AU2+"*"+AU3+"&db="+DB1+"*"+DB2+"*"+DB3+"&ac="+AC1+"*"+AC2+"*"+AC3+"&gf="+GF1+"*"+GF2+"*"+GF3+"&dd="+DD1+"*"+DD2+"*"+DD3+"&gl="+GL1+"*"+GL2+"*"+GL3+"&gd="+GD1+"*"+GD2+"*"+GD3;
		ifr.src = newUrl;
	}else if(nr == 7){
		MoveOut('DialogDiv4');
		MoveIn('DialogDiv5');
		var newUrl = "./setup_proc.php?do=SkipStartUp";
		ifr.src = newUrl;
	}else if(nr == 8){	
		var SN = document.getElementById('SERVERNAME').value;
		var DB = 0;
		var FB = 0;
		var WS = 0;
		var WC = 0;
		var FU = document.getElementById('ForumUrl').value;
		var AT = AType;
		var SU = document.getElementById('SelectedUser').value;
		var AN = document.getElementById('pwAdminU').value;
		var AE = document.getElementById('pwAdminE').value;
		var AP = document.getElementById('pwAdminP').value;
		var SP = document.getElementById('StartPoint').value;
		var SG = document.getElementById('StartGold').value;
		var SV = document.getElementById('ServVer').value;
		if (document.getElementById('DonationButton').checked){DB=1;}
		if (document.getElementById('WebShopButton').checked){WS=1;}
		if (document.getElementById('ForumButton').checked){FB=1;}
		if (document.getElementById('WebConfig').checked){WC=1;}
		var IIL = parseInt(document.getElementById('ItemIdLimit').value,10) || 0;
		var newUrl = "./setup_proc.php?do=FinalSettings&sn="+SN+"&db="+DB+"&fb="+FB+"&fu="+FU+"&at="+AT+"&su="+SU+"&an="+AN+"&ae="+AE+"&ap="+AP+"&sp="+SP+"&sg="+SG+"&sv="+SV+"&ws="+WS+"&iil="+IIL+"&wc="+WC;
		ifr.src = newUrl;
	}else if(nr == 9){
		var newUrl = "./setup_proc.php?do=DelCFG";
		ifr.src = newUrl;
	}else if(nr == 10){
		var newUrl = "./setup_proc.php?do=SkipCFG";
		ifr.src = newUrl;
	}else if(nr == 99){
		var newUrl = "./setup_proc.php?do=CancelSetup";
		ifr.src = newUrl;
	}
}
function MoveOut(e){
	document.getElementById(e).style.left = (BigW+50)+"px";
}
function MoveIn(e){
	document.getElementById(e).style.left = newL+"px";
}
function ChangeVoteForTxt(){
	var VoteP = document.getElementById('VoteForPoint');
	var VoteG = document.getElementById('VoteForGold');
	var VoteFT = document.getElementById('VoteForTxt');
	if (VoteP.checked){
		VoteFT.innerHTML=" <font color='#0000ff'>Point</font>";
	}else if(VoteG.checked){
		VoteFT.innerHTML=" <font color='#ffaa00'>Gold</font>";
	}	
}

function ChangeAccountFields(){
	var AU = document.getElementById('pwAdminU');
	var AE = document.getElementById('pwAdminE');
	var AP = document.getElementById('pwAdminP');
	var SU = document.getElementById('SelectedUser');
	
	if (document.getElementById('LoadAccount').checked){
		AU.disabled = true;
		AE.disabled = true;
		AP.disabled = true;
		SU.disabled = false;
		AType = 1;
	}else if(document.getElementById('CreateAccount').checked){
		SU.disabled = true;
		AU.disabled = false;
		AE.disabled = false;
		AP.disabled = false;
		AType = 2;
	}	

}
function ChangeSQLPath(typ){
	var SQLPath = document.getElementById('SqlPath');
	if (typ == 0){
		SQLPath.value = WebPath+"/SQL/PW.sql";
	}else if (typ == 1){
		SQLPath.value = WebPath+"/SQL/PW64.sql";
	}else if (typ == 2){
		SQLPath.value = WebPath+"/SQL/PW1.sql";
	}
}

function ChangeProgressBar(cur){
	var pt=document.getElementById('ProgressText');
	var pr=document.getElementById('ProgressRate');
	var pb=document.getElementById('ProgressBar');
	var max=5;
	var perc=Math.round(cur*100/max);
	pb.style.width=perc+"%";
	pr.innerHTML=perc+"%";
	if (cur==0){
		pt.innerHTML="Connection...";
	}else if (cur==1){
		pt.innerHTML="Database...";
	}else if (cur==2){
		pt.innerHTML="Vote...";
	}else if (cur==3){
		pt.innerHTML="Server Paths...";
	}else if (cur==4){
		pt.innerHTML="Administration...";
	}else if (cur==5){
		pt.innerHTML="Finished...";
	}
}
</script>

</head>
<body>
<div id="bigCont">
<div id="ProgressDiv"><div id="DHeaderDiv"> &nbsp; &nbsp; <b>Progressbar</b></div>
	
	<div id="ProgressBody"><span id="ProgressText">Connection...</span>
	<div id="ProgressCont">
		<div id="ProgressBar"></div>
		<div id="ProgressRate">0%</div>
	</div>
	</div>
</div>
<iframe id='worker' style='display:none; position:fixed; top: 100px'></iframe>
<script>	var ifr = document.getElementById('worker');   </script>
<?php
	echo "<script>WebPath='".getcwd()."';</script>";
?>
<div id="DialogDiv1" style='height: 280px;'>
	<div id="DHeaderDiv"> &nbsp; &nbsp; <b>Setup: Step 1 - Connection</b></div>
	<div id="DContDiv">
	<?php


	?>
		<table border="0" style="font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:#fff;">
		<tr><td> <b>Host Address:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  </b></td><td>   <input type="text" id='HostAddr' maxlength="100" value="localhost" style="width:190px;text-align:center;"></td></tr>
		<tr><td> <b>MySQL User:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    </b></td><td>   <input type="text" id='MySQLUser' maxlength="100" value="root" style="width:190px;text-align:center;"><br></td></tr>
		<tr><td> <b>MySQL Password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td><td>   <input type="password" id='MySQLPasswd' maxlength="100" value="root" style="width:190px;text-align:center;"><br></td></tr>
		<tr><td> <b>Server Address:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  </b></td><td>   <input type="text" id='ServerAddr' maxlength="100" value="shadowvzs.ddns.net" style="width:190px;text-align:center;"></td></tr>
		<tr><td> <b>Lan Ip:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    </b></td><td>   <input type="text" id='LanIp' maxlength="100" value="<?php echo $_SERVER['SERVER_ADDR']; ?>" style="width:190px;text-align:center;"><br></td></tr>
		<tr><td> <b>Port:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td><td>   <input type="text" id='Port' maxlength="100" value="29000" style="width:190px;text-align:center;"><br></td></tr>
		</table><br><center><font color='red'><b><span id='AnswerBox'>Note: Complete the fields</span></b></font></center><br>
		<center> <a href="javascript:void(0);" onClick="Confirm(1);" id="ConButton1"><button>Confirm</button></a>&nbsp;&nbsp;<a href="javascript:void(0);" onClick="MoveOut('DialogDiv1');Confirm(99);"><button>Cancel</button></a></center>
	</div>
</div>

<div id="DialogDiv2" style='height: 305px;'>
	<div id="DHeaderDiv"> &nbsp; &nbsp; <b>Setup: Step 2 - Database</b></div>
	<div id="DContDiv">
		<table border="0">
		<tr><td style="font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:#fff;"> <b>Database name:&nbsp;&nbsp;&nbsp;&nbsp;  </b></td><td> &nbsp;&nbsp; <input type="text" name="DbName" id='DbName' maxlength="100" value="pw" style="width:50px;text-align:center;">&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="Confirm(3);" style="float:right;"><button>Create & Load</button></a></td></tr>
		<tr id='SqlRow'><td style="font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:#fff;"> <b>Load from:&nbsp;&nbsp;  </b></td><td>   <input type="text" name="SqlPath" id='SqlPath' maxlength="100" value="<?php echo getcwd().'/SQL/PW.sql'; ?>" style="width:220px;"></td></tr>
		<tr><td colspan="2" style="font-size:14px;text-align:center;text-shadow: 2px 2px 5px #444;"><br> <b>Password Type</b> <i> <a href="javascript:void(0);" onClick="alert('Password Types:\nMD5: &quot;0x&quot;.md5(username.password);\nBASE64: base64_encode(hash(&quot;md5&quot;,username.password, true));\nVarBin: &quot;0x&quot;.md5(username.password) with varbintohexstring');" style="text-decoration:none; color:blue;">[?]</a></i></td></tr>
		<tr><td style="font-size:14px;text-shadow: 2px 2px 5px #444; color: #337;"><input type="radio" name="PassType" value="1" checked id="MD5Pass" onclick="ChangeSQLPath(0);"> <b> MD5 </b></td><td style="font-size:14px;text-shadow: 2px 2px 5px #444;color: #337;"><span style="text-align:center;"><input type="radio" name="PassType" value="2" id="Base64Pass" onclick="ChangeSQLPath(1);"> <b>Base64</b></span><span style="float:right;"><input type="radio" name="PassType" value="3" id="BinPass" onclick="ChangeSQLPath(2);"> <b>VarBin</b></span></td></tr>
		<tr><td colspan="2" style="font-size:14px;text-align:center;text-shadow: 2px 2px 5px #444;"><br> <b>Account Registration Limit</b></td></tr>
		<tr style="text-shadow: 2px 2px 5px #777;"><td style="font-size:12px;"> <b>Per ip:&nbsp;&nbsp; <input type="text" id="MaxAccIp" maxlength="2" value="3" style="width:20px;text-align:center;"></td><td style="font-size:12px;"><span style="float:right;"><b>Per browser:</b>&nbsp;&nbsp;</b> <input type="text" id="MaxAccSession" maxlength="1" value="2" style="width:20px;text-align:center;"></b></span></td></tr>
		<tr><td colspan="2" align="center"><br> <font color='red'><b><span id='AnswerBox1' style='text-align: center;font-size:12px;'>Limit for stop spam account creations</span></b></font></td></tr>
		</table><br>
		<center>
		<span id='SqlDone1'><a href="javascript:void(0);" onClick="Confirm(2);"><button>Confirm</button></a>&nbsp;&nbsp;<a href="javascript:void(0);" onClick="MoveOut('DialogDiv2'); Confirm(99);"><button>Cancel</button></a></span>
		<br><br>
		</center>
	</div>
</div>

<div id="DialogDiv3" style='height: 465px;'>
	<div id="DHeaderDiv"> &nbsp; &nbsp; <b>Setup: Step 3 - Vote Options</b></div>
	<div id="DContDiv">
		<table border="0">
		<tr><td style="font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:#fff;"> <b>Vote interval:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  </b></td><td style='font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000;'>   <input type="text" name="VoteInt" id='VoteInt' maxlength="100" style="width:35px;text-align:center;" value="12"> <b>Hour</b> &nbsp;&nbsp;&nbsp;<i>(0 = disable vote)</i> </td></tr>
		<tr><td style="font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:#fff;" colspan="2" align="center"> <b> Select what reward do you want for voteing </b></td></tr>
		<tr><td style="font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000;" colspan="2" align="center"> <input type="radio" name="VoteFor" value="1" checked id="VoteForPoint" onclick="ChangeVoteForTxt();"> <b> <font color='#0000ff'>Web Point</font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b><input type="radio" name="VoteFor" value="2" id="VoteForGold" onclick="ChangeVoteForTxt();"> <b> <font color='#ffaa00'>Shop Gold</font> </b></td></tr>
		<tr><td style="font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:#fff;"> <b>Vote reward:</b></td><td style='font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000;'>   <input type="text" name="VoteRew" id='VoteRew' maxlength="100"  style="width:50px; text-align:center;" value="100"> &nbsp;&nbsp;&nbsp;&nbsp;<b><span id='VoteForTxt'><font color='#0000ff'>Point</font></span></b></td></tr>
		<tr><td style="font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:#fff;"><b>Point => Gold:</b></td><td style="font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:#000;" align="center"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <b><font color = '#ffff00'>1.00</font> <font color='#ffaa00'>Gold</font>  = <input type="text" id='VoteExc' maxlength="5"  style="width:50px; text-align:center;" value="100"> &nbsp;<font color='#0000ff'>Point</font></b></td></tr>
		<table border="0" style="font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:#fff;" width="100%">
		<tr><td width="70%" align="center"> <b> Vote Link  </b></td><td width="30%" align="center"> <b>Site Name</b></td></tr>
		<tr><td> <input type="text" id='VoteUrl1' maxlength="100" value="http://www.xtremetop100.com/out.php?site=1132361031" style="width:240px;"></td><td><input type="text" id='VoteNam1' maxlength="50" value="XtremTop100" style="width:100px;"></td></tr>
		<tr><td> <input type="text" id='VoteUrl2' maxlength="100" value="" style="width:240px;"></td><td><input type="text" id='VoteNam2' maxlength="50" value="" style="width:100px;"></td></tr>
		<tr><td> <input type="text" id='VoteUrl3' maxlength="100" value="" style="width:240px;"></td><td><input type="text" id='VoteNam3' maxlength="50" value="" style="width:100px;"></td></tr>
		<tr><td> <input type="text" id='VoteUrl4' maxlength="100" value="" style="width:240px;"></td><td><input type="text" id='VoteNam4' maxlength="50" value="" style="width:100px;"></td></tr>
		<tr><td> <input type="text" id='VoteUrl5' maxlength="100" value="" style="width:240px;"></td><td><input type="text" id='VoteNam5' maxlength="50" value="" style="width:100px;"></td></tr>
		<tr><td> <input type="text" id='VoteUrl6' maxlength="100" value="" style="width:240px;"></td><td><input type="text" id='VoteNam6' maxlength="50" value="" style="width:100px;"></td></tr>
		<tr><td> <input type="text" id='VoteUrl7' maxlength="100" value="" style="width:240px;"></td><td><input type="text" id='VoteNam7' maxlength="50" value="" style="width:100px;"></td></tr>
		<tr><td style="font-size:10px;text-shadow: 1px 1px 2px #000, 0px 0px 1px #000; color:#ffa;" colspan="2" align="center"> <b> Example: http://www.xtremetop100.com/out.php?site=1132361038</b></td></tr></table>
		</table>
		<br><center><font color='red'><b><span id='AnswerBox2'>Note: Add atleast 1 Vote Link</span></b></font><br><br>
		<a href="javascript:void(0);" onClick="Confirm(4);"><button>Confirm</button></a>&nbsp;&nbsp;<a href="javascript:void(0);" onClick="Confirm(5);"><button>Skip</button></a>
		<br><br>
		</center>
	</div>
</div>

<div id="DialogDiv4" style='height: 450px;'>
	<div id="DHeaderDiv"> &nbsp; &nbsp; <b>Setup: Step 4 - Server Paths</b></div>
	<div id="DContDiv">
		<table border="0" style="font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:#fff;text-align:center;">
		<tr><td colspan="2" align="center"> <b> <font color='#fff'>PW Server folder and file paths</font> </b></td></tr>
		<tr><td> <b>Server Folder:&nbsp;&nbsp;  </b></td><td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id='SERVERFOLDER' maxlength="200" value="/root" style="width:200px;text-align:center;"></td></tr></table>
		<br><br>
		<table border="0" style="font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:#fff;">
		<tr><td style="color:#ff7;"> <b><u>Service name</u></b></td><td style="color:#ff7;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>Folder</u> <span style="float:right;"><u>File</u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>Wait</u></span></b></td></tr>
		<tr><td style="color:#000;font-size:12px;"> <b>LOGSERVICE:&nbsp;&nbsp;  </b></td><td>   <input type="text" id='LOGSERVICE1' maxlength="200" value="/logservice" style="width:100px;">&nbsp;<input type="text" id='LOGSERVICE2' maxlength="200" value="logservice" style="width:80px;">&nbsp;<input type="text" id='LOGSERVICE3' maxlength="200" value="1"  style="width:20px;text-align:center;"></td></tr>
		<tr><td style="color:#000;font-size:12px;"> <b>UNIQUENAMED:&nbsp;&nbsp;  </b></td><td>   <input type="text" id='UNIQUENAMED1' maxlength="200" value="/uniquenamed" style="width:100px;">&nbsp;<input type="text" id='UNIQUENAMED2' maxlength="200" value="uniquenamed" style="width:80px;">&nbsp;<input type="text" id='UNIQUENAMED3' maxlength="200" value="1" style="width:20px;text-align:center;"></td></tr>
		<tr><td style="color:#000;font-size:12px;"> <b>AUTH:&nbsp;&nbsp;  </b></td><td>   <input type="text" id='AUTH1' maxlength="200" value="/authd/build" style="width:100px;">&nbsp;<input type="text" id='AUTH2' maxlength="200" value="authd" style="width:80px;">&nbsp;<input type="text" id='AUTH3' maxlength="200" value="3" style="width:20px;text-align:center;"></td></tr>
		<tr><td style="color:#000;font-size:12px;"> <b>GAMEDBD:&nbsp;&nbsp;  </b></td><td>   <input type="text" id='GAMEDBD1' maxlength="200" value="/gamedbd" style="width:100px;">&nbsp;<input type="text" id='GAMEDBD2' maxlength="200" value="gamedbd" style="width:80px;">&nbsp;<input type="text" id='GAMEDBD3' maxlength="200" value="1" style="width:20px;text-align:center;"></td></tr>
		<tr><td style="color:#000;font-size:12px;"> <b>GACD:&nbsp;&nbsp;  </b></td><td>   <input type="text" id='GACD1' maxlength="200" value="/gacd" style="width:100px;">&nbsp;<input type="text" id='GACD2' maxlength="200" value="gacd" style="width:80px;">&nbsp;<input type="text" id='GACD3' maxlength="200" value="1" style="width:20px;text-align:center;"></td></tr>
		<tr><td style="color:#000;font-size:12px;"> <b>GFACTIOND:&nbsp;&nbsp;  </b></td><td>   <input type="text" id='GFACTIOND1' maxlength="200" value="/gfactiond" style="width:100px;">&nbsp;<input type="text" id='GFACTIOND2' maxlength="200" value="gfactiond" style="width:80px;">&nbsp;<input type="text" id='GFACTIOND3' maxlength="200" value="1" style="width:20px;text-align:center;"></td></tr>
		<tr><td style="color:#000;font-size:12px;"> <b>GDELIVERYD:&nbsp;&nbsp;  </b></td><td>   <input type="text" id='GDELIVERYD1' maxlength="200" value="/gdeliveryd" style="width:100px;">&nbsp;<input type="text" id='GDELIVERYD2' maxlength="200" value="gdeliveryd" style="width:80px;">&nbsp;<input type="text" id='GDELIVERYD3' maxlength="200" value="1" style="width:20px;text-align:center;"></td></tr>
		<tr><td style="color:#000;font-size:12px;"> <b>GLINKD:&nbsp;&nbsp;  </b></td><td>   <input type="text" id='GLINKD1' maxlength="200" value="/glinkd" style="width:100px;">&nbsp;<input type="text" id='GLINKD2' maxlength="200" value="glinkd" style="width:80px;">&nbsp;<input type="text" id='GLINKD3' maxlength="200" value="3" style="width:20px;text-align:center;"></td></tr>
		<tr><td style="color:#000;font-size:12px;"> <b>GAMED:&nbsp;&nbsp;  </b></td><td>   <input type="text" id='GAMED1' maxlength="200" value="/gamed" style="width:100px;">&nbsp;<input type="text" id='GAMED2' maxlength="200" value="gs" style="width:80px;">&nbsp;<input type="text" id='GAMED3' maxlength="200" value="0" style="width:20px;text-align:center;"></td></tr>
		</table>
		<br><center><font color='red'><b><span id='AnswerBox3'>Note: Enter the service paths!</span></b></font><br><br>
		<a href="javascript:void(0);" onClick="Confirm(6);"><button>Confirm</button></a>&nbsp;&nbsp;<a href="javascript:void(0);" onClick="Confirm(7);"><button>Skip</button></a>
		<br><br>
		</center>
	</div>
</div>

<div id="DialogDiv5" style='height: 540px;'>
	<div id="DHeaderDiv"> &nbsp; &nbsp; <b>Setup: Step 5 - Admistration</b></div>
	<div id="DContDiv">
		<table border="0" style="font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:#fff;width:100%;">
		<tr><td> <b>Server Name:&nbsp;&nbsp;  </b></td><td><input type="text" id="SERVERNAME" maxlength="200" value="Perfect World RetroMs" style="width:200px;"></td></tr>
		<tr><td> <b>Donation:&nbsp;&nbsp;</b></td><td style="color:#00a;text-shadow: 1px 1px 2px #000;"><input type="checkbox" id="DonationButton" value="1"><b> <span onclick="document.getElementById('DonationButton').checked=true;">Enable</span> / <span onclick="document.getElementById('DonationButton').checked=false;">Disable</span> &nbsp;&nbsp;</b></td></tr>
		<tr><td> <b>Web Shop:&nbsp;&nbsp;</b></td><td style="color:#00a;text-shadow: 1px 1px 2px #000;"><input type="checkbox" id="WebShopButton" value="1" checked><b> <span onclick="document.getElementById('WebShopButton').checked=true;">Enable</span> / <span onclick="document.getElementById('WebShopButton').checked=false;">Disable</span> &nbsp;&nbsp;</b></td></tr>
		<tr><td> <b>Contol Panel:&nbsp;&nbsp;</b></td><td style="color:#00a;text-shadow: 1px 1px 2px #000;"><input type="checkbox" id="WebConfig" value="1" checked><b> <span onclick="document.getElementById('WebConfig').checked=true;">Enable</span> / <span onclick="document.getElementById('WebConfig').checked=false;">Disable</span> &nbsp;&nbsp;</b></td></tr>
		<tr><td> <b>Forum:&nbsp;&nbsp;</b></td><td style="color:#00a;text-shadow: 1px 1px 2px #000;"><input type="checkbox" id="ForumButton" value="1"><b> <span onclick="document.getElementById('ForumButton').checked=true;document.getElementById('ForumUrl').disabled=false;">Enable</span> / <span onclick="document.getElementById('ForumButton').checked=false;document.getElementById('ForumUrl').disabled=true;">Disable</span> </b></td></tr>
		<tr><td> <b>Forum Url:&nbsp;&nbsp;</b></td><td> <input type="text" id="ForumUrl" maxlength="200" value="http://yourforum.com" style="width:200px;" disabled></td></tr>
		<tr><td> <b>Vesion:&nbsp;&nbsp;</b></td><td> <select id="ServVer" style="width:200px;">
<!--		<option value="10">1.3.1 (3 race)</option>	-->
<!--		<option value="20">1.3.6 (Holy & Dark)</option>	-->
		<option value="30">1.3.9 (Chrono)</option>
		<option value="39" selected>1.4.1 (Elf)</option>
		<option value="40">1.4.2 (Tideborn)</option>
		<option value="50">1.4.4 (Earthguard)</option>
<!--		<option value="60">1.4.5 (Morai)</option>	-->
<!--		<option value="70">1.5.1 (Primal)</option>	-->
		<option value="80">1.5.3 (Nightshade)</option>
		</select></td></tr>
		<tr><td> <b>Item Id Limit:&nbsp;&nbsp;</b></td><td style="color:#00a;text-shadow: 1px 1px 2px #000;"><input type="text" id="ItemIdLimit" maxlength="6" value="0" style="width:100px;"><i>(0=off)</i></td></tr>
		<tr><td style="font-size:12px;color:#000;text-align:center;" colspan="2"> Version needed for several <a title="For Item Builder and Web Shop menu, for Web Shop gold paying and for rol xml."><i><u>stuff</u> (hover here)</i></a>.</td></tr>
		<tr><td colspan="2"> <b><font color='#0000ff'>Start Point:</font>&nbsp;&nbsp; <input type="text" id="StartPoint" maxlength="200" value="0" style="width:50px;text-align:center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='#ffaa00'>Start Gold:</font>&nbsp;&nbsp;</b> <input type="text" id="StartGold" maxlength="200" value="0" style="width:50px;text-align:center;"></b></td></tr>
		</table><br><center><span style="font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:#fff;"><b><u>~ Web Admin ~</u></b></span></center>
		<table border="0" style="font-size:14px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:#fff;">
		<tr><td colspan="2">  <b> Use existing account </b> <input type="radio" name="AdminAccount" value="1" checked id="LoadAccount" onclick="ChangeAccountFields();">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="AdminAccount" value="2" id="CreateAccount" onclick="ChangeAccountFields();"> <b> Create new </b> </td></tr>
		<tr><td style="width: 110px;"> <b>Admin:&nbsp;&nbsp;  </b></td><td> <input type="text" id="SelectedUser" maxlength="200" value="Username" style="width:100px;"> </td></tr>
		<tr><td> <b>Username:</b></td><td> <input type="text" id='pwAdminU' maxlength="20" value="pwAdmin" style="width:100px;" disabled></td></tr>
		<tr><td> <b>Email:&nbsp;&nbsp;</b></td><td>    <input type="text" id='pwAdminE' maxlength="200" value="youremail@something.com" style="width:200px;" disabled></td></tr>
		<tr><td> <b>Password:&nbsp;&nbsp;</b></td><td>    <input type="text" id='pwAdminP' maxlength="20" value="yourpassword" style="width:200px;" disabled></td></tr>
		</table>
		<br><center><font color='red'><b><span id='AnswerBox4'>Note: Select existing users or create new admin account!</span></b></font><br><br>
		<a href="javascript:void(0);" onClick="Confirm(8);"><button>Confirm</button></a>&nbsp;&nbsp;<a href="javascript:void(0);" onClick="Confirm(99);"><button>Cancel</button></a>
		<br><br>
		</center>
	</div>
</div>

<div id="DialogDiv6" style='height: 230px;left:-1000px;'>
	<div id="DHeaderDiv"> &nbsp; &nbsp; <b>Setup: Successfully completed</b></div>
	<div id="DContDiv">
		<table border="0" style="font-size:14px; color:#000;width:100%;">
		<tr><td> <b>Thank you, the website setup finished, this website was made for mainly the old PW versions<br><br></td></tr>
		<tr><td> <b><font color="red"><b>Warning:</b></font> website isn't safe until setup is activated!<br><br></td></tr>
		<tr><td>
		&nbsp;&nbsp;&nbsp;&nbsp;<b>Deactivate:</b> then can't run anymore this setup, recommanded for public server. 
		<br>
		&nbsp;&nbsp;&nbsp;&nbsp;<b>Leave unsafe:</b> if your home server then leave activated.</td></tr>
		<tr><td style="text-align:center;"> <a href="javascript:void(0);" onClick="Confirm(9);"><button>Deactivate</button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="Confirm(10);"><button>Leave unsafe</button></a></td></tr>
		</table>
	</div>
</div>
<script>
var BigW=document.getElementById('bigCont').offsetWidth;
var D1W=document.getElementById('DialogDiv1').offsetWidth;
var newL = parseInt((BigW / 2) - (D1W / 2));
MoveIn ('DialogDiv1');
document.getElementById('ProgressDiv').style.top="50px";
</script>
</div>
</body>
</html>
