var VTIndex=0;
var VTimers = [];
var VTimerSpan = [];  
var VTimerFunc;
var VInterval;
var Vhour = [];
var Vmin = [];
var Vsec = [];
var VId = [];
var ExchRate = 0;
var ExchPoint = 0;
var ExchMaxG = 0;

function StartGlobalTimer(){
    var VTimerFunc = setInterval(function () {
		for (i = 0; i < VTIndex; i++) { 
			if (VTimers[i] > 0){
				Vhour[i] = parseInt(VTimers[i] / 3600, 10);
				Vmin[i] = parseInt((VTimers[i] - Vhour[i] * 3600) / 60, 10);
				Vsec[i] = parseInt(VTimers[i] % 60, 10);

				Vhour[i] = Vhour[i] < 10 ? "0" + Vhour[i] : Vhour[i];
				Vmin[i] = Vmin[i] < 10 ? "0" + Vmin[i] : Vmin[i];
				Vsec[i] = Vsec[i] < 10 ? "0" + Vsec[i] : Vsec[i];
				VTimerSpan[i].innerHTML = Vhour[i] + ":" + Vmin[i] + ":" + Vsec[i];
				VTimers[i]--;
			}else{
				if (VTimers[i] == 0){
					VTimers[i] = -1;
					FinishedVoteTimer(VId[i]);
				}
			}
		}
    }, 1000);	
}

function stringToDate(s) {
  var dateParts = s.split(' ')[0].split('-'); 
  var timeParts = s.split(' ')[1].split(':');
  var d = new Date(dateParts[0], --dateParts[1], dateParts[2]);
  d.setHours(timeParts[0], timeParts[1], timeParts[2])

  return d
}

function SetVotTimers(dstack){
	var dstck=dstack.split(",");
	var dv;
	var maxVOpt=7;
	var stcki=dstck.length;
	var si=0;
	var cDate = new Date;
	var diff;
    var cDat = cDate.getFullYear()+'-'+("00"+(cDate.getMonth()+1)).slice(-2)+'-'+("00" + cDate.getDate()).slice(-2)+' '+("00" + cDate.getHours()).slice(-2)+':'+("00" + cDate.getMinutes()).slice(-2)+':'+("00" + cDate.getSeconds()).slice(-2);
	VTIndex=0;
	for(var i=1;i <= maxVOpt; i++){
		dv=document.getElementById('VoteRow'+i);
		if (dv != null){
			VTIndex++;
			if (dstck[si] != null){
				if (dstck[si].length<10){diff=0;}else{
					diff=(VInterval*3600) - parseInt((stringToDate(cDat)-stringToDate(dstck[si]))/1000,10);
				}
			}else{
				diff=0;
			}
			NewVoteTimer(diff, VTIndex);
			si++;
			if (si >= dstck.length){si=0;}
		}
	}
}

function FinishedVoteTimer(voteid){
	var div1 = document.getElementById(('VoteTimer'+voteid));
	div1.innerHTML = "<a href='javascript:void(0);' onClick='SendVoteData("+voteid+");' title='Vote to site, make our server mor popular so we can get more plyer!'><button> Vote </button></a>"
}

function NewVoteTimer (duration, voteid){
	if (duration > 0){
		VTimers[VTIndex] = duration;
		VId[VTIndex] = voteid;
		VTimerSpan[VTIndex] = document.getElementById(('VoteTimer'+voteid));
		VTIndex++;
	}else{
		FinishedVoteTimer(voteid);
	}
}

function SwitchDisplayDataDiv(x){
	if (x==1){
		document.getElementById("AccInfoDiv").style.display='none';
		document.getElementById("ChngInfoDiv").style.display='block';	
	}else if(x==2){
		document.getElementById("AccInfoDiv").style.display='block';
		document.getElementById("ChngInfoDiv").style.display='none';	
	}
}
function SendNewData(){
	var d1 = document.getElementById('CurUnam').value+"-"+document.getElementById('CurUId').value+"-"+document.getElementById('OldUnam').value+"-"+document.getElementById('OldUId').value;
	var d2 = document.getElementById('CurPwd').value+"-"+document.getElementById('NewPwd1').value+"-"+document.getElementById('NewPwd2').value;
	var d3 = document.getElementById('Mail').value;
	var d4 = document.getElementById('RealName').value;
	var d5 = 0;
	var d6 = document.getElementById('dob-year').value+"-"+document.getElementById('dob-month').value+"-"+document.getElementById('dob-day').value;
	var d7 = document.getElementById('mstat').value;
	var g1 = document.getElementById('gender_female');
	var g2 = document.getElementById('gender_male');
	if (g1.checked){d5=2;}else if (g2.checked){d5=1;}
	SendDataWithAjax(13, [d1, d2, d3, d4, d5, d6, d7]);
}
function RequestUserData(n){
	var uid = document.getElementById('LoadUserId').value;
	var lid = document.getElementById('OldUId').value;
	var dArr;
	if (n==1){
		SendDataWithAjax(1, [uid]);
	}else if (n==2){
		var am = parseInt(document.getElementById('AddGoldAmount').value,10)||0;
		if ((am > 0)&&(am < 1000000)){
			dArr=[uid, am];
		}else{
			alert('Please type a number between 1-99999.');
		}
	}else if (n==3){
		var am = parseInt(document.getElementById('AddPointAmount').value,10)||0;
		if ((am > 0)&&(am < 1000000)){
			dArr=[uid, am];
		}else{
			alert('Please type a number between 1-99999.');
		}
	}else if ((n==4)||(n==5)){
		//4 Add GM, 5 Del game rank
		dArr=[uid];
	}else if (n==6){
		var d1 = document.getElementById('BanRoleId').value;
		var d2 = document.getElementById('RoleBanType').value;
		var d3 = document.getElementById('BanRoleGM').value;
		var d4 = document.getElementById('BanRoleWhy').value;
		var d5 = document.getElementById('BanRoleDur').value;
		var bannerId=1024;
		dArr=[bannerId, d1, d2, d3, d4, d5];
	}else if (n==7){
		var d1 = document.getElementById('BanRoleId').value;
		var d2 = document.getElementById('RoleBanType').value;
		var d3 = document.getElementById('BanRoleGM').value;
		var d4 = document.getElementById('BanRoleWhy').value;
		var bannerId=1024;
		dArr=[bannerId, d1, d2, d3, d4];		
	}else if (n==8){
		dArr=[uid];	
	}else if (n==9){
		var am = parseInt(document.getElementById('OldDate').value,10)||0;
		if ((am > 0)&&(am < 36500)){
			dArr=[am];
		}else{
			alert('Please type a number between 1-36500.');
		}
	}else if ((n==10)||(n==11)){
		//10 gold, 11 point:  group reward adding
		var am  = parseInt(document.getElementById('OldDate1').value,10)||0;
		var am1 = parseInt(document.getElementById('rewAmount').value,10)||0;
		if ((am > -1)&&(am < 36500)&&(am1 > 0)&&(am1 < 9999999)){
			dArr=[am1, am];
		}else{
			alert('Please type a number.');
		}
	}
	if ((n>1)&&(n<12)&&(dArr.length>0)){
		if (n==7){n=6;}
		SendDataWithAjax((n+1), dArr);
	}
}

function UserSearch(){
	var txt = document.getElementById('SearchUser').value;
	var sType = 0;
	txt = txt.trim();
	
	if (txt==""){	
		sType = 1;
	}else if (isIPaddress(txt)){
		sType = 2;
	}else if (isNum (txt)){
		sType = 3;
	}else if (isAlphaNum (txt)){
		sType = 4;
	}else if (isEmiladdress (txt)){
		sType = 5;
	}else if (txt=="*"){
		sType = 6;
	}else if (isNegNum (txt)){
		txt = txt.substr(1)
		sType = 7;	
	}else if (txt=="@"){
		sType = 8;
	}else if (txt=="@*"){
		sType = 9;
	}

	if (sType > 0){
		var dArr=[txt,sType];
		SendDataWithAjax(2, dArr);
	}else{
		alert("You need write one from following data to input field: \n- username or real name (type name, example: shadow)\n- email adress (example: your@mail.com)\n- ip address (example: 79.84.75.89)\n- account id (type number)\n- who is online (type: *)\n- show Game Masters (type: @)\n- who was online in last x day (type negative number, example: -3)");
	}
}

function isIPaddress(ipaddress) {  
 if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ipaddress)){  
    return true;  
  }  
	return false;  
}  

function isAlphaNum (str){
	var alphaNumRGX=/^[a-z\d]+$/i;
	return (alphaNumRGX.test(str) &&(str.length > 1));
}
function isEmiladdress (str){
	var emailRGX = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return emailRGX.test(str);
}

function isNum (str){
	return ((parseInt(str) == str) && (parseInt(str) > 0));
}	

function isNegNum (str){
	if (str.length > 1){
		return ((str.substr(0, 1) == "-") && (parseInt(str.substr(1)) > 0));
	}else{
		return false;
	}
}

function CheckExchCost(){
	setTimeout(function(){
		var ReqGoldInp = document.getElementById('ExchGAmount');
		var ReqGold = ReqGoldInp.value;
		var PCost;
		ReqGold = ReqGold.trim();
		if (ReqGold != parseInt(ReqGold)){
			ReqGold = 0;
		}
		ReqGold = parseInt(ReqGold, 10);
		if (ExchMaxG > 99999){ExchMaxG=99999;}
		if (ReqGold > ExchMaxG){ReqGold=ExchMaxG;}
		if (ReqGold < 0){ReqGold=0;}
		ReqGoldInp.value = parseInt(ReqGold, 10);
		PCost = ExchRate*ReqGold;
		PCost = parseInt(PCost, 10)||0;
		document.getElementById('ExchPCost').innerHTML = '<b>'+PCost+'</b>';		
	},300);
}

function ExchangePointToGold(){
	var ReqGold = parseInt(document.getElementById('ExchGAmount').value, 10)||0;
	if (ReqGold > 0){
		SendDataWithAjax(14, [ReqGold]);
	}
}

function EditUserData(userData){
	var userD=JSON.parse(userData);
	if (userD[0]["error"]!=""){
		alert(userD[0]["error"]);
	}else{
		if (Object.keys(userD[1].length>12)){
			var genderN=["","Male","Female"];
			var rankN=["Member","Game Master"];
			var aself=userD[1]["self"];
			var uid=userD[1]["id"];
			var uname=userD[1]["username"];
			var upass=userD[1]["password"];
			var urank=userD[1]["rank"];
			var usrank=userD[1]["srank"];
			var urn=userD[1]["truename"];
			var uem=userD[1]["email"];
			var uct=userD[1]["creatime"];
			var ubd=userD[1]["birthday"];
			var ugnd=userD[1]["gender"];
			var uip=userD[1]["ip"];
			var ExchPoint=userD[1]["votepoint"];
			var uvd=userD[1]["votedate"];
			SetVotTimers(uvd);
			var urn_ext=uname;
			ExchRate=parseInt(userD[1]["pointtogold"]);
			if ((urank>0)||(usrank>0)){
				urn_ext=urn_ext+" ["+uid+"]";
			}
			if (ExchPoint<ExchRate){
				document.getElementById('PExchLink').style.display='none';  
			}else{
				document.getElementById('PExchLink').style.display='inline-block';  
			}
			document.getElementById('AccInfoBanRow').style.display='none';  
			document.getElementById('AccInfoZone').style.display='none';  
			document.getElementById('AccInfoAv').innerHTML='';
			document.getElementById('AccInfoNa').innerHTML=urn_ext;
			document.getElementById('AccInfoRN').innerHTML=urn;
			document.getElementById('AccInfoPw').innerHTML=upass;
			document.getElementById('AccInfoEm').innerHTML=uem;
			document.getElementById('AccInfoGe').innerHTML=genderN[ugnd];
			document.getElementById('AccInfobd').innerHTML=ubd;
			document.getElementById('AccInfoRa').innerHTML=rankN[urank];
			document.getElementById('AccInfoRD').innerHTML=uct;
			document.getElementById('AccInfoIp').innerHTML=uip;
			document.getElementById('AccInfoWP').innerHTML=ExchPoint;
			ExchMaxG=parseInt(ExchPoint/ExchRate,10);
			document.getElementById('CurUnam').value=uname;
			document.getElementById('CurUId').value=uid;
			document.getElementById('OldUnam').value=uname;
			document.getElementById('OldUId').value=uid;
			document.getElementById('CurPwd').value=upass;
			document.getElementById('NewPwd1').value=upass;
			document.getElementById('NewPwd2').value=upass;
			document.getElementById('Mail').value=uem;
			document.getElementById('RealName').value=urn;
			var bdate=ubd.split(" ");
			bdate=bdate[0].split("-");
			var LYear=bdate[0]||0;
			var LMonth=bdate[1]||0;
			var LDay=bdate[2]||0;
			var cYear=parseInt(parent.document.getElementById('dob-year').options[2].value,10);
			document.getElementById('gender_male').checked=false;
			document.getElementById('gender_female').checked=false;
			if (ugnd==1){document.getElementById('gender_male').checked=true;}else if (ugnd==2){document.getElementById('gender_female').checked=true;}
			if (LYear>0){LYear=cYear-parseInt(LYear,10)+2;}
			if (LMonth>0){LMonth=parseInt(LMonth,10)+1;}
			if (LDay>0){LDay=parseInt(LDay,10)+1;}
			document.getElementById('dob-year').selectedIndex=LYear;
			document.getElementById('dob-month').selectedIndex=LMonth;
			document.getElementById('dob-day').selectedIndex=LDay;
			document.getElementById('mstat').selectedIndex = urank;	
		}
		if (Object.keys(userD[2]).length>2){
			var lastlog=userD[2]["lastlogin"];
			var zoneid=userD[2]["zoneid"];
			var zonelid=userD[2]["zonelocalid"];
			document.getElementById('AccInfoLL').innerHTML=lastlog;
			if ((parseInt(zoneid, 10)||0)>0){
				document.getElementById('AccInfoZone').style.display="table-row";
				document.getElementById('AccInfoZId').innerHTML="map id: "+zoneid+" ["+zonelid+"]";
				document.getElementById('AccInfoStatus').innerHTML='<font color=\'#00aa00\'>Online</font>';
			}else{
				document.getElementById('AccInfoZone').style.display="none";
				document.getElementById('AccInfoStatus').innerHTML='<font color=\'#ee0000\'>Offline</font>';
			}
		}
		var table = document.getElementById('GoldLogTable');
		var row;
		var cell;
		var clogc=Object.keys(userD[3]).length;
		table.innerHTML = '';
		if (clogc>0){
			row = table.insertRow(-1);
			cell = row.insertCell(0);
			cell.style.textAlign='center';
			cell.innerHTML='<b><u> Gold Amount</u></b>';
			cell = row.insertCell(1);
			cell.style.textAlign='center';
			cell.innerHTML='<b><u> When Reicived</u></b>';
			for (var i=0; i<clogc; i++){
				row = table.insertRow(-1);
				cell = row.insertCell(0);
				cell.innerHTML='<b>'+userD[3][i]["cash"]+'</b>';
				cell = row.insertCell(1);
				cell.innerHTML=userD[3][i]["fintime"];
			}
		}else{
			row = table.insertRow(-1);
			cell = row.insertCell(0);
			cell.style.textAlign='center';
			cell.colSpan = '2';
			cell.innerHTML='<i>... You have no transaction history ...</i>';			
		}
		table = document.getElementById('CharList');
		table.innerHTML = '';
		var charc=Object.keys(userD[4]).length;
		document.getElementById('AccInfoCI').innerHTML=charc;
		if (charc>0){
			for (var i=0; i<charc; i++){
				if (usrank>0){
					row = table.insertRow(-1);
					cell = row.insertCell(0);
					cell.innerHTML="<a href='javascript:void(0);'><b>"+userD[4][i]["rolename"]+"</b> ["+userD[4][i]["roleid"]+"]</a>";
					cell = row.insertCell(1);
					cell.innerHTML=userD[4][i]["rolepath"]+userD[4][i]["roleclass"]+" ("+userD[4][i]["rolelevel"]+")";						
				}else{
					row = table.insertRow(-1);
					cell = row.insertCell(0);
					cell.innerHTML="<b>"+userD[4][i]["rolename"]+"</b>";
					cell = row.insertCell(1);
					cell.innerHTML=userD[4][i]["rolepath"]+userD[4][i]["roleclass"]+" ("+userD[4][i]["rolelevel"]+")";					
				}
			}
		}else{
			row = table.insertRow(-1);
			cell = row.insertCell(0);
			cell.style.textAlign='center';
			cell.colSpan = '2';
			cell.innerHTML='<i>... You have no character ...</i>';			
		}
		CheckExchCost();
	}
}

function EditUserList(userData){
	var userD=JSON.parse(userData);
	var rCol=["#0000ff", "#ff0000"];
	if (userD[0]["error"]!=""){
		alert(userD[0]["error"]);
	}else{
		var uc=Object.keys(userD[1]).length;
		var table = document.getElementById('UserTable');
		var row,cell,id,rn,un,rk,em;
		table.innerHTML = '';	
		if (uc == 0){
			row = table.insertRow(-1);
			cell = row.insertCell(0);
			cell.innerHTML='<span style=\'font-size:12px;\'><i> Sorry but no result..... </i></span>';			
		}else{
			for (var i=0;i<uc;i++){
				id=userD[1][i]["userid"];
				un=userD[1][i]["username"];
				rn=userD[1][i]["realname"];
				rk=userD[1][i]["rank"];
				em=userD[1][i]["email"];
				row = table.insertRow(-1);
				cell = row.insertCell(0);
				cell.innerHTML="<a href='javascript:void(0);' title='Name: "+un+" and email: "+em+"' onClick='SendDataWithAjax(1, ["+id+"]);document.getElementById(\"LoadUserId\").value="+id+";'><font color=\""+rCol[rk]+"\">"+un+"</font> <i><font color='black' size='2'>["+id+"]</font></i></a>";
			}
		}
	}
}

function AdminToolHandler(userData, typ){
	var userD=JSON.parse(userData);
	if (userD[0]["error"]!=""){
		alert(userD[0]["error"]);
	}else{
		if (userD[0]["success"]!=""){
			alert(userD[0]["success"]);
		}
		var cId=parseInt(document.getElementById('CurUId').value,10)||0;
		if ((typ==11)||(typ==12)){
			SendDataWithAjax(1, [cId]);
		}else{
			if (userD[0]["reloaduserdata"]=="1"){
				SendDataWithAjax(1, [cId]);
			}
			if (userD[0]["reloaduserlist"]=="1"){
				UserSearch();
			}
		}
	}
}

function ChangeUserDataHandler(userData){
	var userD=JSON.parse(userData);
	var cId=parseInt(document.getElementById('CurUId').value,10)||0;
	if (userD[0]["error"]!=""){
		alert(userD[0]["error"]);
	}else{
		if (userD[0]["success"]!=""){
			alert(userD[0]["success"]);
		}
		if (userD[0]["reloaduserdata"]=="1"){
			SendDataWithAjax(1, [cId]);
		}
		if (userD[0]["reloaduserlist"]=="1"){
			UserSearch();
		}		
		
	}	
}

function PointExchangeHandler(userData){
	var userD=JSON.parse(userData);
	var cId=parseInt(document.getElementById('CurUId').value,10)||0;
	if (userD[0]["error"]!=""){
		alert(userD[0]["error"]);
	}else{
		if (userD[0]["success"]!=""){
			alert(userD[0]["success"]);
		}
		if (userD[0]["reloaduserdata"]=="1"){
			SendDataWithAjax(1, [cId]);
		}
	}	
}

function VoteHandler(userData){
	var userD=JSON.parse(userData);
	if (userD[0]["error"]!=""){
		alert(userD[0]["error"]);
	}else{
		if (userD[0]["success"]!=""){
			alert(userD[0]["success"]);
		}
		NewVoteTimer (["votesecleft"], userD[0]["voteid"]);
		window.parent.location = userD[0]["voteurl"];
	}	
	
}

function SendVoteData(voteid){
	SendDataWithAjax(15, [voteid]);
}

function SendDataWithAjax(typ, dArr) {
	var activexmodes=["Msxml2.XMLHTTP", "Microsoft.XMLHTTP"] //activeX versions to check for in IE
	if (window.ActiveXObject){ //Test for support for ActiveXObject in IE first (as XMLHttpRequest in IE7 is broken)
		for (var i=0; i<activexmodes.length; i++){
			try{
				xmlhttp = new ActiveXObject(activexmodes[i]);
			}catch(e){
			//suppress error
			}
		}
	}else if (window.XMLHttpRequest){
		// if Mozilla, Safari etc
		xmlhttp = new XMLHttpRequest();
	}else{
		return false;
	}
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
			var fdbck=JSON.parse(this.responseText);
		    if (typ==1){
				if (fdbck != ""){
					EditUserData(JSON.stringify(fdbck));
				}else{
					alert("Cannot load user data!");
				}
			}else if(typ==2){
				if (fdbck != ""){
					EditUserList(JSON.stringify(fdbck));
				}else{
					alert("Cannot load user list!");
				}
			}else if((typ>2)&&(typ<11)){
					AdminToolHandler(JSON.stringify(fdbck), typ);
			}else if((typ==11)||(typ==12)){
				if (fdbck != ""){
					AdminToolHandler(JSON.stringify(fdbck), typ);
				}else{
					alert("Cannot load user list!");
				}
			}else if(typ==13){
				if (fdbck != ""){
					ChangeUserDataHandler(JSON.stringify(fdbck));
				}else{
					alert("Cannot save data!");
				}	
			}else if(typ==14){
				if (fdbck != ""){
					PointExchangeHandler(JSON.stringify(fdbck));
				}else{
					alert("Cannot save data!");
				}	
			}else if(typ==15){
				if (fdbck != ""){
					VoteHandler(JSON.stringify(fdbck));
				}else{
					alert("Cannot vote!");
				}		
			}
        }
    };
	var obj;
	if (typ==1){
		xmlhttp.open("POST", "../php/loaduserdata.php", false);
		obj = {"id":dArr[0]};	
	}else if (typ==2){
		xmlhttp.open("POST", "../php/loadusers.php", false);
		obj = {"sname":dArr[0], "stype":dArr[1]};	
	}else if ((typ>2)&&(typ<13)){
		xmlhttp.open("POST", "../php/admintool.php", false);
		if ((typ==3)||(typ==4)){
			obj = {"tool":(typ-1), "id":dArr[0], "amount":dArr[1]};	
		}else if ((typ==5)||(typ==6)||(typ==9)){
			obj = {"tool":(typ-1), "id":dArr[0]};
		}else if (typ==7){		
			obj = {"tool":(typ-1), "bannerid":dArr[0], "targetid":dArr[1], "bantype":dArr[2], "gmid":dArr[3], "banreason":dArr[4],"bandur":dArr[5]};
		}else if (typ==8){
			obj = {"tool":(typ-1), "bannerid":dArr[0], "targetid":dArr[1], "bantype":dArr[2], "gmid":dArr[3], "banreason":dArr[4],"bandur":"10"};
		}else if (typ==10){	
			obj = {"tool":(typ-1), "day":dArr[0]};
		}else if ((typ==11)||(typ==12)){	
			obj = {"tool":(typ-1), "amount":dArr[0], "day":dArr[1]};
		}
	}else if (typ==13){
		xmlhttp.open("POST", "../php/saveuserdata.php", false);
		obj = {"NameStack":dArr[0], "PasswordStack":dArr[1], "Email":dArr[2], "RealName":dArr[3], "Gender":dArr[4], "DateYMD":dArr[5], "Rank":dArr[6]};
	}else if (typ==14){
		xmlhttp.open("POST", "../php/pointexchange.php", false);
		obj = {"amount":dArr[0]};
	}else if (typ==15){
		xmlhttp.open("POST", "../php/vote.php", false);
		obj = {"voteid":dArr[0]};		
	}
	xmlhttp.setRequestHeader("Content-type", "application/json");	
	
	var myJSON = JSON.stringify(obj);
    xmlhttp.send(myJSON);
	return false;
}
