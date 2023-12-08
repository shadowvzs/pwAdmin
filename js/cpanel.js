var TimerHolder = setInterval(myTimer, 1000);
function myTimer() {
	var d = new Date();
	var date = new Date(Date.now()+SrvrTmZone*1000+d.getTimezoneOffset()*60000);
	var hours = date.getHours();
	if (hours < 10){hours="0"+hours;}
	var minutes = "0" + date.getMinutes();
	var seconds = "0" + date.getSeconds();
	document.getElementById("STime_Count").innerHTML = hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
}
var SrvrTmZone=0;


function ResetKeys(){
	var obj={"Tool":7};
	SettingsDataWithAjax(2, obj);
}

function SendCC(){
	var SN = document.getElementById('ServerName').value;
	var AL = 0;
	if (document.getElementById('AllowLogin').checked !== false){AL = 1;}
	var AR = 0;
	if (document.getElementById('AllowReg').checked !== false){AR = 1;}
	var RI = parseInt(document.getElementById('IPLimit').value,10) || 1;
	var RS = parseInt(document.getElementById('SessionLimit').value,10) || 1;
	var SG = parseInt(document.getElementById('StartGold').value,10) || 0;
	var SP = parseInt(document.getElementById('StartPoint').value,10) || 0;
	var MP = parseInt(document.getElementById('MaxPoint').value,10) || 0;
	var MI = parseInt(document.getElementById('MaxItmId').value,10) || 0;

	var WC = 0;	
	if (document.getElementById('WebControl').checked !== false){WC = 1;}
	var WS = 0;	
	if (document.getElementById('WebShop').checked !== false){WS = 1;}
	var DB = 0;	
	if (document.getElementById('Donation').checked !== false){DB = 1;}
	var FB = 0;	
	if (document.getElementById('Forum').checked !== false){FB = 1;}
	var FU = document.getElementById('ForumUrl').value;	
	var VG=parseInt(document.getElementById('PointExch').value,10) || 0;
	var VE=0;
	if (document.getElementById('Voteing').checked !== false){VE = 1;}
	var VT=parseInt(document.getElementById('VoteInt').value,10) || 0;
	var selO=document.getElementById('VoteFor');
	var VF=parseInt(selO.options[selO.selectedIndex].value,10) || 0;
	var VR=parseInt(document.getElementById('VoteRew').value,10) || 0;
	var WD=parseInt(document.getElementById('WShopDel').value,10) || 0;
	var selO=document.getElementById('PassType');
	var MH=document.getElementById('DB_Host').value;
	var DN=document.getElementById('DB_Name').value;
	var DP=document.getElementById('DB_Passwd').value;
	var MU=document.getElementById('DB_User').value;
	var PI=document.getElementById('ServerIP').value;
	var LI=document.getElementById('LanIP').value;
	var SU=document.getElementById('SSH_User').value;
	var SJ=document.getElementById('SSH_Passwd').value;
	var SR=document.getElementById('ServerPath').value;
	var WL=0;
	if (document.getElementById('WebShopLog').checked !== false){WL = 1;}
	var PT=parseInt(selO.options[selO.selectedIndex].value,10) || 0;
	selO=document.getElementById('ShopDB');
	var SD=parseInt(selO.options[selO.selectedIndex].value,10) || 1;
	var ServPaths="";
	for (i = 1; i <= 9; i++) {
		ServPaths=ServPaths+document.getElementById('FolderPath'+i).value+"*"+document.getElementById('FilePath'+i).value;
		if (i<9){ServPaths=ServPaths+"#";}
	}
	var obj={	
	"sn":SN, "al":AL, "ar":AR, "ri":RI,
	"rs":RS, "sg":SG, "sp":SP, "mp":MP,
	"mi":MI, "wc":WC, "ws":WS, "db":DB,
	"fb":FB, "fu":FU, "vg":VG, "ve":VE,
	"vt":VT, "vf":VF, "vr":VR, "mh":MH,
	"dn":DN, "dp":DP, "mu":MU, "pi":PI,
	"li":LI, "su":SU, "sj":SJ, "sr":SR,
	"pt":PT, "wl":WL, "wd":WD, "sd":SD,
	"as":ServPaths
	};
	SettingsDataWithAjax(1, obj);
}

function ChangeTab(tId){
	var dv;
	for (i = 1; i <= 999; i++) {
		dv=document.getElementById('VTab'+i);
		if (dv!=null){
			if (i!=tId){
				dv.style.display='none';
			}else{
				dv.style.display='block';
			}
			
		}else{
			break;
		}
	}
}

function SettingsDataWithAjax(typ, obj) {
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
					alert("Settings was saved! We reload the window!");
					location.href=location.href;
				}
			}else if(typ==2){
				if (fdbck != ""){
					alert(fdbck);
				}else{
					alert("Keys reseted! May need relog!");
					location.href=location.href;
				}
			}
        }
    };
	if (typ==1){
		xmlhttp.open("POST", "../php/savesettings.php", false);
	}else if (typ==2){
		xmlhttp.open("POST", "../php/resetkeys.php", false);
	}
	xmlhttp.setRequestHeader("Content-type", "application/json");	
	
	var myJSON = JSON.stringify(obj);
    xmlhttp.send(myJSON);
	return false;
}
