function getRndInt(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min;
}

function numToFloat32Hex(v,le){
    if(isNaN(v)) return false;
    var buf = new ArrayBuffer(4);
    var dv  = new DataView(buf);
    dv.setFloat32(0, v, true);
    return ("0000000"+dv.getUint32(0,!(le||false)).toString(16)).slice(-8);
}

function NametoHex(str) {
	var hex = '';
	for(var i=0;i<str.length;i++) {
		hex += ''+str.charCodeAt(i).toString(16)+"00";
	}
	if (hex.length % 2 === 1) {
		hex = "0"+hex;
	}
	return hex;
}

function DectoRevHex(str, flen, adds) {
	var hex = parseInt(str, 10).toString(16);
	if ((adds != "0" )&&(hex != 0)){
		if ((hex.length % 2) == 0){
			hex = adds+"0"+hex;
		}else{
			hex = adds+""+hex;
		}
	}
	
	var diff = flen - parseInt(hex.length);
	for(var i=0;i<diff;i++) {
		hex = '0'+hex;
	}	
	diff = parseInt(hex.length / 2);
	var nhex = "";
	for(var i=0;i<diff;i++) {
		nhex = hex.substr(i*2,2)+nhex;
	}		
	if (nhex.length>flen){nhex=nhex.substr(0, flen);}
	return nhex;
}

function GetSqlDate() {
	var cDate = new Date;
    return cDate.getFullYear()
           + '-'
           + ("00" + (cDate.getMonth()+1)).slice(-2)
           + '-'
           + ("00" + cDate.getDate()).slice(-2)
		   + ' '
		   + ("00" + cDate.getHours()).slice(-2)
		   + ':'
		   + ("00" + cDate.getMinutes()).slice(-2)
		   + ':'
		   + ("00" + cDate.getSeconds()).slice(-2)
		   ;
}

function NumToDate(sec) {
	var cDate = new Date(parseInt(sec,10)*1000);
    return cDate.getFullYear()
           + '-'
           + ("00" + (cDate.getMonth()+1)).slice(-2)
           + '-'
           + ("00" + cDate.getDate()).slice(-2)
		   + ' '
		   + ("00" + cDate.getHours()).slice(-2)
		   + ':'
		   + ("00" + cDate.getMinutes()).slice(-2)
		   + ':'
		   + ("00" + cDate.getSeconds()).slice(-2)
		   ;
}

function DectoHex(str, flen, adds) {
	var hex = parseInt(str, 10).toString(16);
	if ((adds != "0" )&&(hex != 0)){
		if ((hex.length % 2) == 0){
			hex = adds+"0"+hex;
		}else{
			hex = adds+""+hex;
		}
	}
	
	var diff = flen - parseInt(hex.length);
	for(var i=0;i<diff;i++) {
		hex = '0'+hex;
	}	
	if (hex.length>flen){hex=hex.substr(0, flen);}
	return hex;
}

function GetElfMaxSkill(point){
	if (point > 90){
		return 8;
	}else if (point > 80){
		return 7;
	}else if (point > 70){
		return 6;
	}else if (point > 50){
		return 5;
	}else{
		return 4;
	}
	return 0;
}

function CType2CTypeInt(wst){
	if (wst == "W"){
		return 1;
	}else if(wst == "A"){
		return 2;
	}else if(wst == "J"){
		return 3;
	}else if(wst == "O"){
		return 4;
	}else if(wst == "U"){
		return 5;	
	}else if(wst == "M"){
		return 6;
	}else if(wst == "F"){
		return 7;
	}else if(wst == "C"){
		return 8;
	}
	return 0;
}

function ReverseNumber(nr){
	if (nr.length % 2 == 1){nr = "0"+nr;}
	var nr1 = "";
	var diff = nr.length/2;
	

	for(var i=0;i<diff;i++) {
		nr1 = nr.substr(i*2,2)+nr1;
	}
	return nr1;
}

function parseFloat(str) {
    var float = 0, sign, order, mantiss,exp,
    int = 0, multi = 1;
    if (/^0x/.exec(str)) {
        int = parseInt(str,16);
    }else{
        for (var i = str.length -1; i >=0; i -= 1) {
            if (str.charCodeAt(i)>255) {
                console.log('Wrong string parametr'); 
                return false;
            }
            int += str.charCodeAt(i) * multi;
            multi *= 256;
        }
    }
    sign = (int>>>31)?-1:1;
    exp = (int >>> 23 & 0xff) - 127;
    mantiss = ((int & 0x7fffff) + 0x800000).toString(2);
    for (i=0; i<mantiss.length; i+=1){
        float += parseInt(mantiss[i])? Math.pow(2,exp):0;
        exp--;
    }
    return float*sign;
}


function HextoDec(str, flen, adds, rev) {
	var diff = flen - str.length;
	for (var i=0;i<diff;i++) {
		str = '0'+str;
	}	
	if (rev !== false){var Hex = ReverseNumber(str);}else{var Hex = str;}
	if (ishex(Hex) !== true){Hex=0;}
	Hex = Hex.replace(/^0+/, '');
	if (Hex == ""){Hex=0;}
	if (adds != 0){
		if (Hex.substr(0, 1) == adds){
			Hex=Hex.substr(1);
		}else{
			Hex = 0;
		}
	}
	return parseInt(Hex, 16);
}

function ishex(c) {
	regexp = /^[0-9a-fA-F]+$/;
	if (regexp.test(c)){
		return true;
    }else{
        return false;
    }
}

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function ClassIdToClassName(id){
	if (id==1){
		return "Blademaster";
	}else if (id==2){
		return "Wizard";
	}else if (id==3){
		return "Barbarian";
	}else if (id==4){
		return "Venomancer";
	}else if (id==5){
		return "Archer";
	}else if (id==6){
		return "Cleric";
	}else if (id==7){
		return "Assassin";
	}else if (id==8){
		return "Psychick";
	}else if (id==9){
		return "Seeker";
	}else if (id==10){
		return "Mystic";
	}else if (id==11){
		return "Duskblade";
	}else if (id==12){
		return "Stormbringer";
	}
	return "";
}

function ElfStartStat(id){
	if (id == 22181){
		var eStat = [15, 5, 3, 5];
	}else if (id == 23752){
		var eStat = [5, 15, 5, 3];
	}else if (id == 23753){
		var eStat = [5, 3, 15, 5];
	}else if (id == 23754){
		var eStat = [3, 5, 5, 15];
	}else{
		var eStat = [0, 0, 0, 0];
	}
	return eStat;
}

function isRune(id){
	for (i = 0; i < RuneList.length; i++) {
		if (RuneList[i][0]==id){
			return true;
		}
	}
	return false;
}


function ChangeSecToTimeSting(sec, len){
	var timeL = "";
	var dayL = Math.floor(sec / 86400);
	var hourL = Math.floor((sec % 86400) / 3600);
	var minL = Math.floor((sec % 3600) / 60);
	var secL = sec % 60;
	if (len == 1){
		if (dayL > 0){timeL = dayL+"d";}
		if (hourL > 0){timeL = timeL+" "+hourL+"h";}
		if (minL > 0){timeL = timeL+" "+minL+"m";}
		if (secL > 0){timeL = timeL+" "+secL+"s";}	
	}else if(len==2){
		if (dayL > 0){timeL = dayL+"day";}
		if (hourL > 0){timeL = timeL+" "+hourL+"hour";}
		if (minL > 0){timeL = timeL+" "+minL+"min";}
		if (secL > 0){timeL = timeL+" "+secL+"sec";}					
	}
	return timeL;
}