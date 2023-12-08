var maxClass = 0;
function GenerateClassMask(){
	var result = 0;
	var chckboxId = "";
	var chckbox;
	var maxClass = 12;
	var resBox = document.getElementById('ClassMask');
	for (var i = 1; i <= maxClass; i++) { 
		chckboxId = "Class"+i;
		chckbox = document.getElementById(chckboxId);
		if (chckbox){
			chckbox = document.getElementById(chckboxId);
			if (chckbox.checked){
				result = result + parseInt(chckbox.value);
			}
		}
	}
	resBox.value = result;
}

function ClearLogMsg(){
	var div = document.getElementById('LogMsg');
	div.innerHTML = "";
}

function AddLogMsg(msg){
	var div = document.getElementById('LogMsg');
	var oldMsg = div.innerHTML;
	var d = new Date();
	d = d.toLocaleTimeString();//new Date(hours, minutes, seconds);
	oldMsg = oldMsg.replace(/<(?!\b>|\/?br>)[^>]+>/g, " ");
	div.innerHTML = "<b>"+d+": "+msg+"</b><br>"+oldMsg;
}

function SendData(n){
	if (n==1){
		var DM = 0;
		var CM = parseInt(document.getElementById('ClassMask').value);
		var XP = parseInt(document.getElementById('XPBonus').value);
		var SP = parseInt(document.getElementById('SPBonus').value);
		var DP = parseInt(document.getElementById('DRBonus').value);
		var MN = parseInt(document.getElementById('MNBonus').value);
		var PP = 0;
		var TW = 0;
		var NS = 0;
		var NL = parseInt(document.getElementById('NameLength').value);
		if (document.getElementById('DebugMode').checked){DM=1;}
		if (document.getElementById('PvP').checked){PP=1;}
		if (document.getElementById('TW').checked){TW=1;}
		if (document.getElementById('NameSens').checked){NS=1;}
		var obj={
			"dm":DM, "cm":CM, "xp":XP, "sp":SP, "dp":DP, "mn":MN,
			"pp":PP, "tw":TW, "ns":NS, "nl":NL
		};
		ServerDataWithAjax(1, obj);
	}else if (n==2){
		var CR = parseInt(document.getElementById('CRole').value);
		var CC = parseInt(document.getElementById('CChannel').value);
		var CT = document.getElementById('CText').value;
		var obj={"CRole":CR, "CChannel":CC, "CText":CT};	
		ServerDataWithAjax(2, obj);	
	}
}

function ServerDataWithAjax(typ, obj) {
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
					alert("Chat message sent!");
					AddLogMsg("Chat message sent to server...")
				}
			}
        }
    };
	if (typ==1){
		xmlhttp.open("POST", "../php/editservercfg.php", false);
	}else if (typ==2){
		xmlhttp.open("POST", "../php/sendchatmsg.php", false);
	}
	xmlhttp.setRequestHeader("Content-type", "application/json");	
	
	var myJSON = JSON.stringify(obj);
    xmlhttp.send(myJSON);
	return false;
}