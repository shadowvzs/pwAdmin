function ShowLoginDiv(){
	var ADiv = document.getElementById('RegDiv');
	var TCont = document.getElementById('ButtonRow');
	var LDiv = document.getElementById('loginDiv');
	var TdBut = document.getElementById('TdButLog');
	var but = document.getElementById('LoginButton');
	var butH = but.offsetHeight;
	var butW = but.offsetWidth;
	var butT = but.offsetTop;
	var butL = but.offsetLeft;
	var LDivW = LDiv.offsetWidth;
	var newT = TCont.offsetTop + butT + butH + 10;
	var newL = TdBut.offsetLeft + parseInt(TCont.offsetLeft + butL + (butW - LDivW)/2);
	LDiv.style.top = newT + "px";
	LDiv.style.left = newL + "px";
	ADiv.style.left = "-1000px";
}
function ShowRegDiv(){
	var ADiv = document.getElementById('loginDiv');
	var TCont = document.getElementById('ButtonRow');
	var LDiv = document.getElementById('RegDiv');
	var TdBut = document.getElementById('TdButReg');
	var but = document.getElementById('RegButton');
	var butH = but.offsetHeight;
	var butW = but.offsetWidth;
	var butT = but.offsetTop;
	var butL = but.offsetLeft;
	var LDivW = LDiv.offsetWidth;
	var newT = TCont.offsetTop + butT + butH + 10;
	var newL = TdBut.offsetLeft + parseInt(TCont.offsetLeft + butL + (butW - LDivW)/2);
	LDiv.style.top = newT + "px";
	LDiv.style.left = newL + "px";
	ADiv.style.left = "-1000px";
}
function ClrWin(){
	document.getElementById('loginDiv').style.left = "-1000px";
	document.getElementById('RegDiv').style.left = "-1000px";
}
function SendLoginData(){
	var alphaNumRGX=/^[a-z\d]+$/i;
	var emailRGX = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var uname=document.getElementById('luname');
	var upass=document.getElementById('lupass');
	var suname=uname.value;
	var supass=upass.value;
	//check if username is valid alphanumeric characters
	var unamet = alphaNumRGX.test(suname);
	var upasst = alphaNumRGX.test(supass);
	//check string length
	var unamel = suname.length;
	var upassl = supass.length;
	if ((unamet)&&(upasst)&&(unamel>4)&&(unamel<21)&&(upassl>5)&&(upassl<21)){
		var dArr = [suname, supass];
		SendDataWithAjax(1, dArr);
	}else{
		alert("Username and password must be alphameric character and atleast 6 character.");
	}
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
		   var fdbck=this.responseText;
		    if (typ==1){
				if (fdbck != ""){
					alert(fdbck);
				}else{
					location.href="./page/myacc.php";
				}
			}else if(typ==2){
				if (fdbck != ""){
					alert(fdbck);
				}else{
					document.getElementById('luname').value=document.getElementById('RegUser').value;
					document.getElementById('lupass').value=document.getElementById('RegPass1').value;
					SendLoginData();
				}
			}else{
				document.getElementById("PageContainer").innerHTML = fdbck;
			}
        }
    };
	var obj;
	switch (typ){
		case 1:
			xmlhttp.open("POST", "./php/login.php", true);
			xmlhttp.setRequestHeader("Content-type", "application/json");	
			obj = {"name":dArr[0], "password":dArr[1]};		
			break;
		case 2:
			xmlhttp.open("POST", "./php/reg.php", true);
			xmlhttp.setRequestHeader("Content-type", "application/json");	
			obj = {"name":dArr[0], "password1":dArr[1], "password2":dArr[2], "email":dArr[3], "answer1":dArr[4], "answer2":dArr[5], "term":dArr[6]};		
			break;
		case 3:	
			xmlhttp.open("GET", dArr, true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			obj = 2;
			break;
		default:
			//nothing
	}
	var myJSON = JSON.stringify(obj);
    xmlhttp.send(myJSON);
	return false;
}

function SendRegData(){
	var alphaNumRGX=/^[a-z\d]+$/i;
	var emailRGX = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var Rname=document.getElementById('RegUser');
	var Rpass1=document.getElementById('RegPass1');
	var Rpass2=document.getElementById('RegPass2');
	var RMail=document.getElementById('RegMail');
	var Ransw=document.getElementById('RegAnswer');
	var Term=0;
	if (document.getElementById('RegAnswer').checked!==false){Term=1;}
	var sname=Rname.value;
	var spass1=Rpass1.value;
	var spass2=Rpass2.value;
	var semail=RMail.value;
	var sansw=Ransw.value;
	if ((alphaNumRGX.test(sname))&&(alphaNumRGX.test(spass1))&&(sname.length>5)&&(sname.length<20)&&(spass1.length>5)&&(spass1.length<20)){
		if (emailRGX.test(semail)){
			if (spass1==spass2){
				if (RandomCode==sansw){
					var dArr = [sname, spass1,spass2,semail,sansw,RandomCode,Term];
					SendDataWithAjax(2, dArr);
				}else{
					alert("Please enter the correct math result with numbers");
				}
			}else{
				alert("Passwords must match with eachother.");
			}
		}else{
			alert("Please give a valid email address.");
		}
	}else{
		alert("Username and password must be alphameric character and atleast 6 character.");
	}
}

var TimerHolder = setInterval(myTimer, 1000);

function ShowPage(name){
	SendDataWithAjax(3, name);
}

function myTimer() {
	var d = new Date();
	var date = new Date(Date.now()+SrvrTmZone*1000+d.getTimezoneOffset()*60000);
	var hours = date.getHours();
	if (hours < 10){hours="0"+hours;}
	var minutes = "0" + date.getMinutes();
	var seconds = "0" + date.getSeconds();
	document.getElementById("STime_Count").innerHTML = hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
}

function showClassTab(id){
	var tb;
	for (var i=1; i < 12; i++){
		tb=document.getElementById('ClassTab'+i);
		if (tb!=null){
			if (i==id){
				tb.style.display="block";
			}else{
				tb.style.display="none";
			}
		}else{
			break;
		}
	}
}
function showMainTab(id){
	var tb;
	for (var i=1; i < 4; i++){
		tb=document.getElementById('MainTab'+i);
		if (tb!=null){
			if (i==id){
				tb.style.display="block";
			}else{
				tb.style.display="none";
			}
		}else{
			break;
		}
	}
}

var RandomCode=0;
var SrvrTmZone=0;