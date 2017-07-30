var RMoney = 0;
var UPoint = 0;
var ShopInd = 0;
var maxClass = 0;
var MReady = true;
var ShopItm = [];
var ShopCat = [];
var FashCol = [];
var AddonL = [];
var SockL = [];
var PetSkillL = [];
var ElfSkillL = [];
var LockItem = false;
var LastId = 0;
var tmp = 0;
var CSSwap = false;
var TmrInd = 0;
var TmdII = 0;
var TmdItm=[];
var cDate = new Date();
var SrvrTmZone=0;
var ClntTmZone=cDate.getTimezoneOffset()*60;
var PITCA = true; //Periodic Item Timer Check Allowed

var GearType = [];
GearType[1] = [];
GearType[2] = [];
GearType[3] = [];
GearType[4] = [];
GearType[5] = [];
GearType[6] = [];
GearType[1][1]="Polehammer";
GearType[1][2]="Poleaxe";
GearType[1][3]="Dual Hammer";
GearType[1][4]="Dual Axe";
GearType[1][5]="Spear";
GearType[1][6]="Polearm";
GearType[1][7]="Staff";
GearType[1][8]="Mace";
GearType[1][9]="Blade";
GearType[1][10]="Sword";
GearType[1][11]="Dual Blade";
GearType[1][12]="Dual Sword";
GearType[1][13]="Fist";
GearType[1][14]="Claw";
GearType[1][15]="Bow";
GearType[1][16]="Crossbow";
GearType[1][17]="Slingshot";
GearType[1][18]="Magic Sword";
GearType[1][19]="Wand";
GearType[1][20]="Magic Quoit";
GearType[1][21]="Magic Staff";
GearType[1][22]="Dagger";
GearType[1][23]="Sphere";
GearType[1][24]="Sabre";
GearType[1][25]="Schythe";
GearType[2][1]="Heavy Plate";
GearType[2][2]="Light Armor";
GearType[2][3]="Magic Robe";
GearType[2][4]="Heavy Leggings";
GearType[2][5]="Light Leggings";
GearType[2][6]="Magic Leggings";
GearType[2][7]="Heavy Footwear";
GearType[2][8]="Light Footwear";
GearType[2][9]="Magic Footwear";
GearType[2][10]="Heavy Wristguard";
GearType[2][11]="Light Wristguard";
GearType[2][12]="Magic Wristguard";
GearType[2][13]="Heavy Helmet";
GearType[2][14]="Magic Headgear";
GearType[2][15]="Manteau/Cloack";
GearType[3][1]="Physical Necklance";
GearType[3][2]="Dodge Necklance";
GearType[3][3]="Magical Necklance";
GearType[3][4]="Physical Waist Adorn";
GearType[3][5]="Dodge Waist Adorn";
GearType[3][6]="Magical Waist Adorn";
GearType[3][7]="Physical Ring";
GearType[3][8]="Magical Ring";
GearType[4][1]="Flying Tool";
GearType[4][2]="Pet Egg";
GearType[4][3]="Bless Box";
GearType[4][4]="Elemental Elf";
GearType[4][5]="Hierogram";
GearType[4][6]="Quiver";
GearType[4][7]="Potion";
GearType[4][8]="Misc";
GearType[4][9]="Pet Food";
GearType[4][10]="Soulstone";
GearType[4][11]="Order Badge";
GearType[4][12]="Star Chart";
GearType[5][1]="Tome";
GearType[5][2]="Misc";
GearType[5][3]="Utility";
GearType[5][4]="Chat Stuff";
GearType[5][5]="Pages & Scrolls";
GearType[5][6]="Dye";
GearType[5][7]="Firework";
GearType[5][8]="Dragon Quest Item";
GearType[5][9]="Pack Reward";
GearType[5][10]="Pet Scroll";
GearType[5][11]="Funny Stuff";
GearType[5][12]="Fuel";
GearType[5][13]="Misc";
GearType[5][14]="Elf Gear";
GearType[5][15]="Rune";
GearType[5][16]="Mark of Might";
GearType[6][1]="Basic Craft Mats";
GearType[6][2]="Basic Jades";
GearType[6][3]="Herbs";
GearType[6][4]="HH Materia";
GearType[6][5]="HH Souledge";
GearType[6][6]="Frost Walk Mat";
GearType[6][7]="Crescent Valley Mat";
GearType[6][8]="Molder";
GearType[6][9]="Tear of Heaven Mat";
GearType[6][10]="Other Mat";
GearType[6][11]="Pair Quest Piece";
GearType[6][12]="Event Reward";


function ChangeRole(){
	var SelO = document.getElementById('Sel_Role');
	var RoleId = SelO.options[SelO.selectedIndex].value;
	var obj={"roleid":RoleId};
	BuyItemWithAjax(1, obj);
}

function SearchAddonNameValue(AddonId, Val){
	var AddonDat = "";
	var tmp;
	var tmp2;
	var tmp3;
	var ValD;  
	Val=Val.toString();
	var ValS = Val.indexOf(" ") >= 0;
	for (var i=1; i<=AddonL.length; i++){
		tmp = AddonL[i].split("#");
		var LId = tmp[0];
		if (tmp[0].indexOf(" ") != -1){
			tmp2=tmp[0].split(" ");
			LId = tmp2[0];
		}
		if (LId == AddonId){
			var AddTit = "";
			if (tmp[2]!= "S"){
				tmp3=StatName[parseInt(tmp[1],10)];
				if (isRune(AddonId)!==false){
					var rData=Val.split(" ");
					if (tmp[2]== "F"){
						rData[0]=parseFloat("0x"+DectoHex(rData[0], 8, 0)).toFixed(2); 
					}
					var timeL=ChangeSecToTimeSting(rData[1]*60, 2);
					if (tmp3!=null){
						AddTit="<font color='#44f'><i>[Rune]</i> "+GetAddonString(parseInt(tmp[1],10), rData[0])+" - "+timeL+"</font>";
					}else{
						AddTit="<font color='#44f'><i>Rune Addon["+AddonId+"]: "+rData[0]+" - "+timeL+"</i></font>";
					}					
				}else{
					if (tmp3!=null){
						AddTit=GetAddonString(parseInt(tmp[1],10), Val);
					}else{
						AddTit="Addon["+AddonId+"]: "+Val;
					}
				}
			}else{
				AddTit = tmp[1];
			}
			return AddTit;
		}
	}
	
	if (AddonDat == ""){
		return "Addon ["+AddonId+"] +"+Val;
	}
	return AddonDat;
}

function SearchSocketNameValue(EQType, StoneId, StoneData){
	var tmp = StoneData.split("#");
	var StAddon = tmp[0];
	var StAddVal = tmp[1];
	var FullStData = "";
	var AddonTxt;
	var APos = 3;
	if (EQType != 1){APos = 4;}
	
	for (var a=1; a<=SockL.length; a++){
		tmp = SockL[a].split("#");
		if (tmp[0] == StoneId){
			var StnData = tmp[APos].split(',');
			AddonTxt = GetAddonString(parseInt(StnData[0], 10), StnData[1]);
			FullStData = tmp[5]+" gr. "+tmp[6]+": "+AddonTxt;
			return FullStData;
		}
	}
	
	if (FullStData == ""){
		FullStData = "Unknown stone grade ?: +"+StAddVal;
		return FullStData;
	}
}

function SearchPetSkillName(SkillId, SkillLv){
	var myArr = [];
	for (var i=1; i<=PetSkillL.length; i++){
		myArr = PetSkillL[i].split("#");
		if (myArr[0] == SkillId){
			return myArr[1] + " Level "+SkillLv;
		}
	}
	return ("Unknown Skill Level " + SkillLv);
}

function SearchElfGearName(GearId){
	var myArr = [];
	var bstat =[];
	for (var i=0; i<ElfGearList.length; i++){
		myArr = ElfGearList[i].split("#");
		if (myArr[1] == GearId){
			bstat = myArr[2].split(" ");
			return myArr[0] + " <font color='#ffff77'>("+myArr[2]+")</font>";
		}
	}
	return ("Unknown ["+GearId+"]");
}

function SearchElfSkillData(SkillId, SkillLv){
	var myArr = [];
	
	for (var i=1; i<=ElfSkillL.length; i++){
		myArr = ElfSkillL[i].split("#");
		if (myArr[0] == SkillId){
			return myArr[3]+"<font color='#ffff77'> (Lv. "+SkillLv+") </font>";
		}
	}
	return ("Unknown Skill (Lv. "+SkillLv+")");	
}

function SearchFashionColor(ColHex){
	var myArr = [];
	
	for (var i=1; i<=FashCol.length; i++){
		myArr = FashCol[i].split("#");
		if (myArr[0] == ColHex){
			return myArr[1];
		}
	}
	return ("Unknown");	
}

function SearchCardId(eId){
	for (var i=1; i<=CardStat.length; i++){
		if (CardStat[i][1] == eId){
			return i;
		}
	}	
	return 0;
}

function SelectIcon(id){
	var d1 = document.getElementById('iconId'+LastId);
	var d2 = document.getElementById('iconId'+id);
	if (d1 != null){
		d1.style.boxShadow = "none";
		d1.style.zIndex="1";
	}
	if (d2 != null){
		d2.style.zIndex="2";
		d2.style.boxShadow = "0 0 10px #fff";
	}
}

function CreateBubble(id){
	document.getElementById("TheItemDiv").style.display="block";
	if (((LockItem !== true)||(CSSwap !== false)) && (LastId != id)){
		LastId = id;
		var myArr = [];
		var MIType;
		var SIType;
		var STxt;
		var Octet = "";
		var procType = "";
		var rctu = "<br><font color='#ffff33'>Right Click to Use</font> <br>";
		document.getElementById('CardClass').style.display='none';
		if ((id > 0) && (id<=ShopInd)){
			document.getElementById('itmRef').innerHTML = "";
			document.getElementById('itmSock').innerHTML = "";
			var itmCol = [];
			itmCol[0] = "#ffffff";
			itmCol[1] = "#ffffff";
			itmCol[2] = "#7777ff";
			itmCol[3] = "#00ff00";
			itmCol[4] = "#ffff00";
			itmCol[5] = "#ff0000";		
			procType = "";
			STxt = "";
			myArr = ShopItm[id].split("#");
			if ((myArr[17] >= 0) && (myArr[17] < itmCol.length)){
				document.getElementById('itmName').innerHTML = "<font color='"+itmCol[myArr[17]]+"'>" + myArr[2] + "</font>";
			}else{
				document.getElementById('itmName').innerHTML = myArr[2];
			}		
			
			Octet = myArr[15];		
			MIType = myArr[4].substr(0, 1);
			SIType = parseInt(myArr[4].substr(1), 10);
			if (myArr[14] > 0){
				var timeL = ChangeSecToTimeSting(myArr[14], 1);
				STxt = STxt + "<font color='red'>Time left: "+timeL+"</font><br>";
			}
			if (MIType == "W"){
				STxt = STxt + GearType[1][SIType]+"<br>";
				STxt = STxt + "Grade Rank " + myArr[18]+"<br>";
				procType = GearProcTypeToStr(myArr[9]);
				if (ishex(Octet) !== false){
					if (Octet.length > 151){
						var LvReq = HextoDec(Octet.substr(0, 4), 4, 0, true);
						var ClReq = HextoDec(Octet.substr(4, 4), 4, 0, true);
						var StrReq = HextoDec(Octet.substr(8, 4), 4, 0, true);
						var ConReq = HextoDec(Octet.substr(12, 4), 4, 0, true);
						var AgiReq = HextoDec(Octet.substr(16, 4), 4, 0, true);
						var IntReq = HextoDec(Octet.substr(20, 4), 4, 0, true);
						var Dur1 = parseInt(HextoDec(Octet.substr(24, 8), 8, 0, true)/100, 10);
						var Dur2 = parseInt(HextoDec(Octet.substr(32, 8), 8, 0, true)/100, 10);
						var EqType = HextoDec(Octet.substr(40, 4), 4, 0, true);
						var ItFlag = HextoDec(Octet.substr(44, 2), 2, 0, true);
						var CNameL = parseInt(HextoDec(Octet.substr(46, 2), 2, 0, true)/2, 10);
						var Cname = "";
						var str;
						var n = 48;
						for (var x=0; x<CNameL; x++){
							str = Octet.substr((48+x*4), 4);
							n = HextoDec(str.substr(0, 2), 2, 0, true);
							Cname = Cname + String.fromCharCode(n);
							n = 48+x*4+4;
						}
						var RangeT = HextoDec(Octet.substr(n, 8), 8, 0, true);
						var WeaponT = HextoDec(Octet.substr(n+8, 8), 8, 0, true);
						var GradeT = HextoDec(Octet.substr(n+16, 8), 8, 0, true);
						var AmmoT = HextoDec(Octet.substr(n+24, 8), 8, 0, true);
						var PDmg1 = HextoDec(Octet.substr(n+32, 8), 8, 0, true);
						var PDmg2 = HextoDec(Octet.substr(n+40, 8), 8, 0, true);
						var MDmg1 = HextoDec(Octet.substr(n+48, 8), 8, 0, true);
						var MDmg2 = HextoDec(Octet.substr(n+56, 8), 8, 0, true);
						var AttRate = HextoDec(Octet.substr(n+64, 8), 8, 0, true);
						var Range = parseFloat('0x'+ReverseNumber(Octet.substr(n+72, 8))).toFixed(2);
						var MinRange = parseFloat('0x'+ReverseNumber(Octet.substr(n+80, 8))).toFixed(2);

						var bpDmg1 = 0;
						var bpDmg2 = 0;
						var bmDmg1 = 0;
						var bmDmg2 = 0;
						var bRange =0;
						var bmaxDur =0;				
					
						var SocketC = HextoDec(Octet.substr(n+88, 8), 8, 0, true);
						var RefId = 0;
						var RefLv = 0;
						var RefVal = 0;
						var tSocket = "";
						if (SocketC>0){
							var SocketSt = [];
							for (var x=1; x<=SocketC; x++){
								SocketSt[x] = HextoDec(Octet.substr(n+88+x*8, 8), 8, 0, true);
								//stoneL = stoneL + "Stone id: "+ SocketSt[x]+"<br>";
							}	
						}
						n = n+SocketC*8;
						var AddonC = HextoDec(Octet.substr(n+96, 8), 8, 0, true);
						var tAddon = "";
						if (AddonC>0){
							var AHex;
							var tmp;
							var shift = 0;
							var SckInd = 0;
							var aType;
							var bAddon;
							var vAddon;
							for (var x=0; x<AddonC; x++){
								AHex = ReverseNumber(Octet.substr(n+104+x*16+shift, 8));
								AHex = AHex.replace(/^0+/, '').trim();
								//2 normal addon, 4 special or refine, a is socket addon
								if (AHex.length % 2 == 0){
									aType = AHex.substr(0,1);
									if (aType=="4"){
										tmp = HextoDec(AHex, 8, aType, false);
										if ((tmp > 1691) && (tmp < 1892)){
											//these addon id range is refine addons
											RefId = HextoDec(Octet.substr(n+104+x*16+shift, 8), 8, 4, true);
											RefVal = HextoDec(Octet.substr(n+104+x*16+shift+8, 8), 8, 0, true);
											RefLv = HextoDec(Octet.substr(n+104+x*16+shift+16, 8), 8, 0, true);
										}else{
											//special weapon addons
											tAddon = tAddon + "&nbsp;&nbsp;" + SearchAddonNameValue(HextoDec(AHex, 8, aType, false), HextoDec(Octet.substr(n+104+x*16+shift+8, 8), 8, 0, true) +" "+ HextoDec(Octet.substr(n+104+x*16+shift+16, 8), 8, 0, true))+"<br>";
										}
										shift = shift + 8;
									}else{
										if (aType=="a"){
											//socket addon
											SckInd++;
											tSocket = tSocket + SearchSocketNameValue(1, SocketSt[SckInd], HextoDec(AHex, 8, aType, false) +"#"+ HextoDec(Octet.substr(n+104+x*16+shift+8, 8), 8, 0, true))+"<br>";
										}else{
											//normal addon
											bAddon = ReverseNumber(AHex, 4);
											vAddon = HextoDec(Octet.substr(n+104+x*16+shift+8, 8), 8, 0, true);
											if (bAddon == "f023"){
												bpDmg1 = bpDmg1 + parseInt(vAddon);
												bpDmg2 = bpDmg2 + parseInt(vAddon);
											}else if (bAddon == "ec23"){
												bpDmg2 = bpDmg2 + parseInt(vAddon);
											}else if (bAddon == "fb23"){
												bmDmg1 = bmDmg1 + parseInt(vAddon);
												bmDmg2 = bmDmg2 + parseInt(vAddon);
												//337 331 5121 4b21
											}else if (bAddon == "a721"){
												bmDmg2 = bmDmg2 + parseInt(vAddon);
											}else if (bAddon == "d721"){
												vAddon = parseFloat('0x'+ReverseNumber(Octet.substr(n+104+x*16+shift+8, 8))).toFixed(2);
												bRange = bRange + vAddon;
											}else if (bAddon == "2c21"){
												vAddon = parseFloat('0x'+ReverseNumber(Octet.substr(n+104+x*16+shift+8, 8))).toFixed(2);
												bmaxDur = bmaxDur + parseInt(vAddon*100);
											}else if (bAddon == "7c22"){
												vAddon = parseFloat('0x'+ReverseNumber(Octet.substr(n+104+x*16+shift+8, 8))).toFixed(2);
											}
											tAddon = tAddon + "&nbsp;&nbsp;" + SearchAddonNameValue(HextoDec(AHex, 8, aType, false), vAddon)+"<br>";
											
										}
									}
								}	
							}	
						}	

						if (RefLv > 0){
							document.getElementById('itmRef').innerHTML = "<font color='#ff0'> +"+RefLv+"</font>";
						}
						
						if (SocketC > 0){
							document.getElementById('itmSock').innerHTML = "<font color='#fcc'> ("+SocketC+" socket)</font>";
						}

						STxt = STxt + "Attack Freq.: "+(20/AttRate).toFixed(2)+"<br>";
						STxt = STxt + "Range: "+Range+"<br>";
						
						if (RangeT == 1){
							STxt = STxt + "Min. Range: "+MinRange+"<br>";
						}
						
						if ((MDmg1 - bmDmg1) > 0){
							STxt = STxt + "Physical Attack: "+(PDmg1 - bpDmg1)+"-"+(PDmg2 - bpDmg2)+"<br>";
							if ((RefLv > 0)&&((RefId>1751)&&(RefId<1772))){
								STxt = STxt + "Magic Attack: <font color='#7777ff'>"+(MDmg1 - bmDmg1 + RefVal)+"-"+(MDmg2 - bmDmg2 + RefVal)+"</font><br>";
							}else{
								STxt = STxt + "Magic Attack: "+(MDmg1 - bmDmg1)+"-"+(MDmg2 - bmDmg2)+"<br>";
							}
						}else{
							if ((RefLv > 0)&&((RefId>1711)&&(RefId<1752))){
								STxt = STxt + "Physical Attack: <font color='#7777ff'>"+(PDmg1 - bpDmg1 + RefVal)+"-"+(PDmg2 - bpDmg2 + RefVal)+"</font><br>";
							}else{
								STxt = STxt + "Physical Attack: "+(PDmg1 - bpDmg1)+"-"+(PDmg2 - bpDmg2)+"<br>";
							}
						}
						STxt = STxt + "Duratibility: "+Dur1+"/"+Dur2+"<br>";
						if ((RangeT == 1)&&(AmmoT>0)){
							if (AmmoT == 8546){
								STxt = STxt + "Ammo Type: Arrow<br>";
							}else if (AmmoT == 8547){
								STxt = STxt + "Ammo Type: Bolt<br>";
							}else if (AmmoT == 8548){
								STxt = STxt + "Ammo Type: Bullet<br>";
							}else{
								
								STxt = STxt + "Ammo Type: Unknown<br>";							
							}
						}
						if (maxClass > ClReq){
							STxt = STxt + "Class Req.: "+ItemClassReq(ClReq)+"<br>";
						}
						if (LvReq > 0){
							STxt = STxt + "Level Req.: "+LvReq+"<br>";
						}
						if (StrReq > 0){
							STxt = STxt + "Strength Req.: "+StrReq+"<br>";
						}					
						if (AgiReq > 0){
							STxt = STxt + "Agility Req.: "+AgiReq+"<br>";
						}					
						if (IntReq > 0){
							STxt = STxt + "Intelligent Req.: "+IntReq+"<br>";
						}					
						if (ConReq > 0){
							STxt = STxt + "Constitution Req.: "+ConReq+"<br>";
						}			

						STxt = STxt + "<font color='#7777ff'>" + tAddon +"</font><font color='#77ffee'>" + tSocket+"</font>";
						
						if (Cname != ""){
							STxt = STxt + "<br><font color='#ff0'><b>Creator: "+Cname+"</b></font><br>";
						}
					}
				}			
			}else if ((MIType == "A")||((MIType == "O")&&(SIType==3))){
				if (MIType == "A"){
					STxt = STxt + GearType[2][SIType]+"<br>";
				}else{
					STxt = STxt + GearType[4][SIType]+"<br>";
				}
				STxt = STxt + "Grade Rank " + myArr[18]+"<br>";
				procType = GearProcTypeToStr(myArr[9]);
				if (Octet.length > 135){
					var LvReq = HextoDec(Octet.substr(0, 4), 4, 0, true);
					var ClReq = HextoDec(Octet.substr(4, 4), 4, 0, true);
					var StrReq = HextoDec(Octet.substr(8, 4), 4, 0, true);
					var ConReq = HextoDec(Octet.substr(12, 4), 4, 0, true);
					var AgiReq = HextoDec(Octet.substr(16, 4), 4, 0, true);
					var IntReq = HextoDec(Octet.substr(20, 4), 4, 0, true);
					var Dur1 = parseInt(HextoDec(Octet.substr(24, 8), 8, 0, true)/100, 10);
					var Dur2 = parseInt(HextoDec(Octet.substr(32, 8), 8, 0, true)/100, 10);
					var EqType = HextoDec(Octet.substr(40, 4), 4, 0, true);
					var ItFlag = HextoDec(Octet.substr(44, 2), 2, 0, true);
					var CNameL = parseInt(HextoDec(Octet.substr(46, 2), 2, 0, true)/2, 10);
					var Cname = "";
					var str;
					var n = 48;
					for (var x=0; x<CNameL; x++){
						str = Octet.substr((48+x*4), 4);
						n = HextoDec(str.substr(0, 2), 2, 0, true);
						Cname = Cname + String.fromCharCode(n);
						n = 48+x*4+4;
					}
					var Pdef = HextoDec(Octet.substr(n, 8), 8, 0, true);
					var Dodge = HextoDec(Octet.substr(n+8, 8), 8, 0, true);
					var HitPoint = HextoDec(Octet.substr(n+16, 8), 8, 0, true);
					var Mana = HextoDec(Octet.substr(n+24, 8), 8, 0, true);
					var Metal = HextoDec(Octet.substr(n+32, 8), 8, 0, true);
					var Wood = HextoDec(Octet.substr(n+40, 8), 8, 0, true);
					var Water = HextoDec(Octet.substr(n+48, 8), 8, 0, true);
					var Fire = HextoDec(Octet.substr(n+56, 8), 8, 0, true);
					var Earth = HextoDec(Octet.substr(n+64, 8), 8, 0, true);
					var bPdef = 0;
					var bHP = 0;
					var bMP = 0;
					var bDodge = 0;
					var bMetal = 0;
					var bWood = 0;
					var bWater = 0;
					var bFire = 0;
					var bEarth = 0;
					var bmaxDur = 0;	
					var SocketC = HextoDec(Octet.substr(n+72, 8), 8, 0, true);
					var RefId = 0;
					var RefLv = 0;
					var RefVal = 0;
					var tSocket = "";
					var tAddon = "";
					if (SocketC>0){
						var SocketSt = [];
						for (var x=1; x<=SocketC; x++){
							SocketSt[x] = HextoDec(Octet.substr(n+72+x*8, 8), 8, 0, true);
						}	
					}
					n = n+SocketC*8;
					var AddonC = HextoDec(Octet.substr(n+80, 8), 8, 0, true);
					if (AddonC>0){
						var AHex;
						var tmp;
						var shift = 0;
						var SckInd = 0;
						var aType;
						var bAddon;
						var vAddon;
						for (var x=0; x<AddonC; x++){
							AHex = ReverseNumber(Octet.substr(n+88+x*16+shift, 8));
							AHex = AHex.replace(/^0+/, '').trim();
							//2 normal addon, 4 special or refine, a is socket addon
							if (AHex.length % 2 == 0){
								aType = AHex.substr(0,1);
								if (aType=="4"){
									tmp = HextoDec(AHex, 8, aType, false);
									if ((tmp > 1691) && (tmp < 1892)){
										//these addon id range is refine addons
										RefId = HextoDec(Octet.substr(n+88+x*16+shift, 8), 8, 4, true);
										RefLv = HextoDec(Octet.substr(n+88+x*16+shift+16, 8), 8, 0, true);
										RefVal = HextoDec(Octet.substr(n+88+x*16+shift+8, 8), 8, 0, true);
									}else{
										//special weapon addons
										tAddon = tAddon + "&nbsp;&nbsp;" + SearchAddonNameValue(HextoDec(AHex, 8, aType, false), HextoDec(Octet.substr(n+88+x*16+shift+8, 8), 8, 0, true) +" "+ HextoDec(Octet.substr(n+88+x*16+shift+16, 8), 8, 0, true))+"<br>";
									}
									shift = shift + 8;
								}else{
									if (aType=="a"){
										//socket addon
										SckInd++;
										tSocket = tSocket + SearchSocketNameValue(2, SocketSt[SckInd], HextoDec(AHex, 8, aType, false) +"#"+ HextoDec(Octet.substr(n+88+x*16+shift+8, 8), 8, 0, true))+"<br>";
									}else{
										
										//normal addon
										bAddon = ReverseNumber(AHex, 4);
										vAddon = HextoDec(Octet.substr(n+88+x*16+shift+8, 8), 8, 0, true);
										if (bAddon == "1421"){
											bHP = bHP + parseInt(vAddon);
										}else if (bAddon == "7322"){
											bMP = bMP + parseInt(vAddon);
										}else if (bAddon == "db20"){
											bPdef = bPdef + parseInt(vAddon);
										}else if (bAddon == "3221"){
											bMetal = bMetal + parseInt(vAddon);
											bWood = bWood + parseInt(vAddon);
											bWater = bWater + parseInt(vAddon);
											bFire = bFire + parseInt(vAddon);
											bEarth = bEarth + parseInt(vAddon);
										}else if (bAddon == "9e22"){
											bDodge = bDodge + parseInt(vAddon);
										}else if (bAddon == "6d21"){
											bMetal = bMetal + parseInt(vAddon);
										}else if (bAddon == "7021"){
											bWood = bWood + parseInt(vAddon);
										}else if (bAddon == "7321"){
											bWater = bWater + parseInt(vAddon);
										}else if (bAddon == "7621"){
											bFire = bFire + parseInt(vAddon);
										}else if (bAddon == "7921"){
											bEarth = bEarth + parseInt(vAddon);
										}else if (bAddon == "2c21"){
											vAddon = parseFloat('0x'+ReverseNumber(Octet.substr(n+88+x*16+shift+8, 8))).toFixed(2);
											bmaxDur = bmaxDur + parseInt(vAddon*100);
										}else if (bAddon == "7c22"){
											vAddon = parseFloat('0x'+ReverseNumber(Octet.substr(n+88+x*16+shift+8, 8))).toFixed(2);
										}
										tAddon = tAddon + "&nbsp;&nbsp;" + SearchAddonNameValue(HextoDec(AHex, 8, aType, false), vAddon)+"<br>";
									}
								}
							}	
						}	
					}	

					if (RefLv > 0){
						document.getElementById('itmRef').innerHTML = "<font color='#ff0'> +"+RefLv+"</font>";
					}
					
					if (SocketC > 0){
						document.getElementById('itmSock').innerHTML = "<font color='#ccf'> ("+SocketC+" socket)</font>";
					}

					if ((RefId>1831)&&(RefId < 1852)&&(RefLv > 0)){
						Pdef = Pdef + RefVal;
					}
					if ((RefId>1871)&&(RefId < 1892)&&(RefLv > 0)){
						Dodge = Dodge + RefVal;
					}	
					if ((RefId>1771)&&(RefId < 1832)&&(RefLv > 0)){
						HitPoint = HitPoint + RefVal;
					}					
					if ((RefId>1851)&&(RefId < 1872)&&(RefLv > 0)){
						Metal = Metal + RefVal;
						Wood = Wood + RefVal;
						Water = Water + RefVal;
						Fire = Fire + RefVal;
						Earth = Earth + RefVal;
					}					

					if ((Pdef - bPdef) > 0){
						STxt = STxt + "Physical Defense: "+(Pdef - bPdef)+"<br>";
					}
					if ((Dodge - bDodge) > 0){
						STxt = STxt + "Dodge: "+(Dodge - bDodge)+"<br>";
					}
					if ((HitPoint - bHP) > 0){
						STxt = STxt + "Hit Point: "+(HitPoint - bHP)+"<br>";
					}
					if ((Mana - bMP) > 0){
						STxt = STxt + "Mana Point: "+(Mana - bMP)+"<br>";
					}
					if ((Metal - bMetal) > 0){
						STxt = STxt + "Metal Defense: "+(Metal - bMetal)+"<br>";
					}
					if ((Wood - bWood) > 0){
						STxt = STxt + "Wood Defense: "+(Wood - bWood)+"<br>";
					}
					if ((Water - bWater) > 0){
						STxt = STxt + "Water Defense: "+(Water - bWater)+"<br>";
					}
					if ((Fire - bFire) > 0){
						STxt = STxt + "Fire Defense: "+(Fire - bFire)+"<br>";
					}
					if ((Earth - bEarth) > 0){
						STxt = STxt + "Earth Defense: "+(Earth - bEarth)+"<br>";
					}
					
					if (Dur2 > 0){
						STxt = STxt + "Duratibility: "+Dur1+"/"+Dur2+"<br>";
					}
					
					if (maxClass > ClReq){
						STxt = STxt + "Class Req.: "+ItemClassReq(ClReq)+"<br>";
					}
					if (LvReq > 0){
						STxt = STxt + "Level Req.: "+LvReq+"<br>";
					}
					if (StrReq > 0){
						STxt = STxt + "Strength Req.: "+StrReq+"<br>";
					}					
					if (AgiReq > 0){
						STxt = STxt + "Agility Req.: "+AgiReq+"<br>";
					}					
					if (IntReq > 0){
						STxt = STxt + "Intelligent Req.: "+IntReq+"<br>";
					}					
					if (ConReq > 0){
						STxt = STxt + "Constitution Req.: "+ConReq+"<br>";
					}			
						
					STxt = STxt + "<font color='#7777ff'>" + tAddon +"</font><font color='#77ffee'>" + tSocket+"</font>";
					
					if (Cname != ""){
						STxt = STxt + "<br><font color='#ff0'><b>Creator: "+Cname+"</b></font><br>";
					}				
				}
			}else if (MIType == "J"){
				STxt = STxt + GearType[3][SIType]+"<br>";
				STxt = STxt + "Grade Rank " + myArr[18]+"<br>";
				procType = GearProcTypeToStr(myArr[9]);
				if (Octet.length > 135){
					var LvReq = HextoDec(Octet.substr(0, 4), 4, 0, true);
					var ClReq = HextoDec(Octet.substr(4, 4), 4, 0, true);
					var StrReq = HextoDec(Octet.substr(8, 4), 4, 0, true);
					var ConReq = HextoDec(Octet.substr(12, 4), 4, 0, true);
					var AgiReq = HextoDec(Octet.substr(16, 4), 4, 0, true);
					var IntReq = HextoDec(Octet.substr(20, 4), 4, 0, true);
					var Dur1 = parseInt(HextoDec(Octet.substr(24, 8), 8, 0, true)/100, 10);
					var Dur2 = parseInt(HextoDec(Octet.substr(32, 8), 8, 0, true)/100, 10);
					var EqType = HextoDec(Octet.substr(40, 4), 4, 0, true);
					var ItFlag = HextoDec(Octet.substr(44, 2), 2, 0, true);
					var CNameL = parseInt(HextoDec(Octet.substr(46, 2), 2, 0, true)/2, 10);
					var Cname = "";
					var str;
					var n = 48;
					for (var x=0; x<CNameL; x++){
						str = Octet.substr((48+x*4), 4);
						n = HextoDec(str.substr(0, 2), 2, 0, true);
						Cname = Cname + String.fromCharCode(n);
						n = 48+x*4+4;
					}
					var Pattack = HextoDec(Octet.substr(n, 8), 8, 0, true);
					var Mattack = HextoDec(Octet.substr(n+8, 8), 8, 0, true);
					var Pdef = HextoDec(Octet.substr(n+16, 8), 8, 0, true);
					var Dodge = HextoDec(Octet.substr(n+24, 8), 8, 0, true);
					var Metal = HextoDec(Octet.substr(n+32, 8), 8, 0, true);
					var Wood = HextoDec(Octet.substr(n+40, 8), 8, 0, true);
					var Water = HextoDec(Octet.substr(n+48, 8), 8, 0, true);
					var Fire = HextoDec(Octet.substr(n+56, 8), 8, 0, true);
					var Earth = HextoDec(Octet.substr(n+64, 8), 8, 0, true);
					var bPattack = 0;
					var bMattack = 0;
					var bPdef = 0;
					var bDodge = 0;
					var bMetal = 0;
					var bWood = 0;
					var bWater = 0;
					var bFire = 0;
					var bEarth = 0;
					var bmaxDur = 0;	
					var SocketC = HextoDec(Octet.substr(n+72, 8), 8, 0, true);
					var RefId = 0;
					var RefLv = 0;
					var RefVal = 0;
					var tSocket = "";
					var tAddon = "";
					if (SocketC>0){
						var SocketSt = [];
						for (var x=1; x<=SocketC; x++){
							SocketSt[x] = HextoDec(Octet.substr(n+72+x*8, 8), 8, 0, true);
						}	
					}
					n = n+SocketC*8;
					var AddonC = HextoDec(Octet.substr(n+80, 8), 8, 0, true);
					if (AddonC>0){
						var AHex;
						var tmp;
						var shift = 0;
						var SckInd = 0;
						var aType;
						var bAddon;
						var vAddon;
						for (var x=0; x<AddonC; x++){
							AHex = ReverseNumber(Octet.substr(n+88+x*16+shift, 8));
							AHex = AHex.replace(/^0+/, '').trim();
							//2 normal addon, 4 special or refine, a is socket addon
							if (AHex.length % 2 == 0){
								aType = AHex.substr(0,1);
								if (aType=="4"){
									tmp = HextoDec(AHex, 8, aType, false);
									if ((tmp > 1691) && (tmp < 1892)){
										//these addon id range is refine addons
										RefId = HextoDec(Octet.substr(n+88+x*16+shift, 8), 8, 4, true);
										RefLv = HextoDec(Octet.substr(n+88+x*16+shift+16, 8), 8, 0, true);
										RefVal = HextoDec(Octet.substr(n+88+x*16+shift+8, 8), 8, 0, true);
									}else{
										//special weapon addons
										tAddon = tAddon + "&nbsp;&nbsp;" + SearchAddonNameValue(HextoDec(AHex, 8, aType, false), HextoDec(Octet.substr(n+88+x*16+shift+8, 8), 8, 0, true) +" "+ HextoDec(Octet.substr(n+88+x*16+shift+16, 8), 8, 0, true))+"<br>";
									}
									shift = shift + 8;
								}else{
									if (aType=="a"){
										//socket addon
										SckInd++;
										tSocket = tSocket + SearchSocketNameValue(2, SocketSt[SckInd], HextoDec(AHex, 8, aType, false) +"#"+ HextoDec(Octet.substr(n+88+x*16+shift+8, 8), 8, 0, true))+"<br>";
									}else{
										//normal addon
										bAddon = ReverseNumber(AHex, 4);
										vAddon = HextoDec(Octet.substr(n+88+x*16+shift+8, 8), 8, 0, true);
										if (bAddon == "f023"){
											bPattack = bPattack + parseInt(vAddon);
										}else if (bAddon == "fb23"){
											bMattack = bMattack + parseInt(vAddon);
										}else if (bAddon == "db20"){
											bPdef = bPdef + parseInt(vAddon);
										}else if (bAddon == "3221"){
											bMetal = bMetal + parseInt(vAddon);
											bWood = bWood + parseInt(vAddon);
											bWater = bWater + parseInt(vAddon);
											bFire = bFire + parseInt(vAddon);
											bEarth = bEarth + parseInt(vAddon);
										}else if (bAddon == "9e22"){
											bDodge = bDodge + parseInt(vAddon);
										}else if (bAddon == "6d21"){
											bMetal = bMetal + parseInt(vAddon);
										}else if (bAddon == "7021"){
											bWood = bWood + parseInt(vAddon);
										}else if (bAddon == "7321"){
											bWater = bWater + parseInt(vAddon);
										}else if (bAddon == "7621"){
											bFire = bFire + parseInt(vAddon);
										}else if (bAddon == "7921"){
											bEarth = bEarth + parseInt(vAddon);
										}else if (bAddon == "2c21"){
											vAddon = parseFloat('0x'+ReverseNumber(Octet.substr(n+88+x*16+shift+8, 8))).toFixed(2);
											bmaxDur = bmaxDur + parseInt(vAddon*100);
										}else if (bAddon == "7c22"){
											vAddon = parseFloat('0x'+ReverseNumber(Octet.substr(n+88+x*16+shift+8, 8))).toFixed(2);
										}
																		
										tAddon = tAddon + "&nbsp;&nbsp;" + SearchAddonNameValue(HextoDec(AHex, 8, aType, false), vAddon)+"<br>";
									}
								}
							}	
						}	
					}	
					if (RefLv > 0){
						document.getElementById('itmRef').innerHTML = "<font color='#ff0'> +"+RefLv+"</font>";
					}
					
					if (SocketC > 0){
						document.getElementById('itmSock').innerHTML = "<font color='#ccf'> ("+SocketC+" socket)</font>";
					}

					if ((RefId>1831)&&(RefId < 1852)&&(RefLv > 0)){
						Pdef = Pdef + RefVal;
					}
					if ((RefId>1871)&&(RefId < 1892)&&(RefLv > 0)){
						Dodge = Dodge + RefVal;
					}	
						
					if ((RefId>1851)&&(RefId < 1872)&&(RefLv > 0)){
						Metal = Metal + RefVal;
						Wood = Wood + RefVal;
						Water = Water + RefVal;
						Fire = Fire + RefVal;
						Earth = Earth + RefVal;
					}					
					if ((Pattack - bPattack) > 0){
						STxt = STxt + "Physical Attack: "+(Pattack - bPattack)+"<br>";
					}
					if ((Mattack - bMattack) > 0){
						STxt = STxt + "Magic Attack: "+(Mattack - bMattack)+"<br>";
					}
					if ((Pdef - bPdef) > 0){
						STxt = STxt + "Physical Defense: "+(Pdef - bPdef)+"<br>";
					}
					if ((Dodge - bDodge) > 0){
						STxt = STxt + "Dodge: "+(Dodge - bDodge)+"<br>";
					}
					if ((Metal - bMetal) > 0){
						STxt = STxt + "Metal Defense: "+(Metal - bMetal)+"<br>";
					}
					if ((Wood - bWood) > 0){
						STxt = STxt + "Wood Defense: "+(Wood - bWood)+"<br>";
					}
					if ((Water - bWater) > 0){
						STxt = STxt + "Water Defense: "+(Water - bWater)+"<br>";
					}
					if ((Fire - bFire) > 0){
						STxt = STxt + "Fire Defense: "+(Fire - bFire)+"<br>";
					}
					if ((Earth - bEarth) > 0){
						STxt = STxt + "Earth Defense: "+(Earth - bEarth)+"<br>";
					}
					
					if (Dur2 > 0){
						STxt = STxt + "Duratibility: "+Dur1+"/"+Dur2+"<br>";
					}
					
					if (maxClass > ClReq){
						STxt = STxt + "Class Req.: "+ItemClassReq(ClReq)+"<br>";
					}
					if (LvReq > 0){
						STxt = STxt + "Level Req.: "+LvReq+"<br>";
					}
					if (StrReq > 0){
						STxt = STxt + "Strength Req.: "+StrReq+"<br>";
					}					
					if (AgiReq > 0){
						STxt = STxt + "Agility Req.: "+AgiReq+"<br>";
					}					
					if (IntReq > 0){
						STxt = STxt + "Intelligent Req.: "+IntReq+"<br>";
					}					
					if (ConReq > 0){
						STxt = STxt + "Constitution Req.: "+ConReq+"<br>";
					}			
						
					STxt = STxt + "<font color='#7777ff'>" + tAddon +"</font><font color='#77ffee'>" + tSocket+"</font>";
					
					if (Cname != ""){
						STxt = STxt + "<br><font color='#ff0'><b>Creator: "+Cname+"</b></font><br>";
					}				
				}
			}else if ((MIType == "O")&&(SIType!=3)){
				STxt = "<font color='#aaaaff'>"+GearType[4][SIType]+"</font><br>";
				
				if (SIType == 1){
					var Fuel1 = HextoDec(Octet.substr(0, 8), 8, 0, true);
					var Fuel2 = HextoDec(Octet.substr(8, 8), 8, 0, true);
					var LvReq = HextoDec(Octet.substr(16, 4), 4, 0, true);
					var Grade = HextoDec(Octet.substr(20, 4), 4, 0, true);
					var Combo = HextoDec(Octet.substr(24, 8), 8, 0, true);
					var Unknown1 = HextoDec(Octet.substr(32, 8), 8, 0, true);
					var Speed1 = parseFloat('0x'+ReverseNumber(Octet.substr(40, 8), 8)).toFixed(2);
					var Speed2 = parseFloat('0x'+ReverseNumber(Octet.substr(48, 8), 8)).toFixed(2);
					var Unknown2 = HextoDec(Octet.substr(56, 4), 4, 0, true);					
					var Unknown3 = HextoDec(Octet.substr(60, 2), 2, 0, true);					
					var Unknown4 = HextoDec(Octet.substr(62, 2), 2, 0, true);	
					if (maxClass > Combo){
						STxt = STxt + "Class Required: "+ItemClassReq(Combo)+"<br>";
					}		
					if (Grade > 0){
						STxt = STxt + "Grade: "+Grade+"<br>";
					}				
					if (LvReq > 0){
						STxt = STxt + "Level Required: "+LvReq+"<br>";
					}				
					STxt = STxt + "Fuel: "+Fuel1+"/"+Fuel2+"<br>";
					STxt = STxt + "Flying Speed: +"+Speed1+"/+"+Speed2+"<br>";
				}else if (SIType == 2){
					if (Octet.length > 119){
						var LvReq = HextoDec(Octet.substr(0, 8), 8, 0, true);
						var ReqCls = HextoDec(Octet.substr(8, 8), 8, 0, true);
						var Loyal = HextoDec(Octet.substr(16, 8), 8, 0, true);
						var PetId = HextoDec(Octet.substr(24, 8), 8, 0, true);
						var Unknown1 = HextoDec(Octet.substr(32, 8), 8, 0, true);	
						var EggId = HextoDec(Octet.substr(40, 8), 8, 0, true);		
						var PetTyp = HextoDec(Octet.substr(48, 8), 8, 0, true);					
						var PetLv = HextoDec(Octet.substr(56, 8), 8, 0, true);					
						var Unknown2 = HextoDec(Octet.substr(64, 8), 8, 0, true);					
						var Unknown3 = HextoDec(Octet.substr(72, 8), 8, 0, true);					
						var Unknown4 = HextoDec(Octet.substr(80, 4), 4, 0, true);
						var PetSkillC = HextoDec(Octet.substr(84, 4), 4, 0, true);	
						var Unknown5 = HextoDec(Octet.substr(88, 8), 8, 0, true);	
						var Unknown6 = HextoDec(Octet.substr(96, 8), 8, 0, true);	
						var Unknown7 = HextoDec(Octet.substr(104, 8), 8, 0, true);	
						var Unknown8 = HextoDec(Octet.substr(112, 8), 8, 0, true);	
						var SkillId;
						var SkillLv;
						var tSkill = ""
						for (var x=0; x<PetSkillC; x++){
							SkillId = HextoDec(Octet.substr(120+x*16, 8), 8, 0, true);
							SkillLv = HextoDec(Octet.substr(120+x*16+8, 8), 8, 0, true);
							tSkill = tSkill + "&nbsp;&nbsp;"+SearchPetSkillName(SkillId, SkillLv)+"<br>";
						}
						
						var petTypT = "";
						if (PetTyp==0){
							petTypT = "Mount";
						}else if (PetTyp==1){
							petTypT = "Battle Pet";
						}else if (PetTyp==2){
							petTypT = "Baby Pet";
						}
						STxt = STxt + "Pet Type: "+petTypT+"<br>";
									
						if (LvReq > 0){
							STxt = STxt + "Level Required: "+LvReq+"<br>";
						}				
						if (maxClass > ReqCls){
							STxt = STxt + "Class Required: "+ItemClassReq(ReqCls)+"<br>";
						}	
							
						if (PetLv > 0){
							STxt = STxt + "Pet Level: "+PetLv+"<br>";
						}
						if (Loyal > 0){
							STxt = STxt + "Loyality: "+Loyal+"<br>";
						}	
						STxt = STxt + "<font color='#7777ff'>" + tSkill +"</font>";
					
					}
				}else if (SIType == 4){
					if (Octet.length > 83){
						var ElfXp = HextoDec(Octet.substr(0, 8), 8, 0, true);
						var ElfLv = HextoDec(Octet.substr(8, 4), 4, 0, true);
						var ElfPt = HextoDec(Octet.substr(12, 4), 4, 0, true);
						var ElfStr = parseInt(HextoDec(Octet.substr(16, 4), 4, 0, true), 10);
						var ElfAgi = parseInt(HextoDec(Octet.substr(20, 4), 4, 0, true), 10);	
						var ElfCon = parseInt(HextoDec(Octet.substr(24, 4), 4, 0, true), 10);		
						var ElfInt = parseInt(HextoDec(Octet.substr(28, 4), 4, 0, true), 10);					
						var ElfTp  = HextoDec(Octet.substr(32, 4), 4, 0, true);					
						var ElfMet = HextoDec(Octet.substr(36, 4), 4, 0, true);					
						var ElfWoo = HextoDec(Octet.substr(40, 4), 4, 0, true);					
						var ElfWat = HextoDec(Octet.substr(44, 4), 4, 0, true);
						var ElfFir = HextoDec(Octet.substr(48, 4), 4, 0, true);	
						var ElfEar = HextoDec(Octet.substr(52, 4), 4, 0, true);	
						var ElfRef = HextoDec(Octet.substr(56, 4), 4, 0, true);	
						var ElfSta = HextoDec(Octet.substr(60, 8), 8, 0, true);	
						var ElfTra = Octet.substr(68, 8);	
						var ElfGea = HextoDec(Octet.substr(76, 8), 8, 0, true);	
						var ElfSki = HextoDec(Octet.substr((84+ElfGea*8), 8), 8, 0, true);	
						var bElfStr = 0;
						var bElfAgi = 0;
						var bElfInt = 0;
						var bElfCon = 0;
						var LckPnt = ElfPt - ElfLv + 1;
						var maxLP = parseInt(Math.floor(ElfLv / 10) * 10, 10);
						var MaxSkill = GetElfMaxSkill(LckPnt);
						var eStat = ElfStartStat(myArr[7]);
						var GearStat = [0, 0, 0, 0];
						bElfStr = eStat[0];
						bElfAgi = eStat[1];
						bElfInt = eStat[2];
						bElfCon = eStat[3];
						var SRes = 0;
									
						STxt = STxt + "Level: "+ElfLv+"<br>";
						if (ElfStr > 0){
							STxt = STxt + "Strength: "+bElfStr+" <font color='#ffff77'>+"+ElfStr+"</font>[ElfStat0]<br>";
						}else{
							STxt = STxt + "Strength: "+bElfStr+"[ElfStat0]<br>";
						}
						if (ElfAgi > 0){	
							STxt = STxt + "Agility: "+bElfAgi+" <font color='#ffff77'>+"+ElfAgi+"[ElfStat1]</font><br>";
						}else{
							STxt = STxt + "Agility: "+bElfAgi+"[ElfStat1]<br>";
						}
						if (ElfCon > 0){
							STxt = STxt + "Constitution: "+bElfCon+" <font color='#ffff77'>+"+ElfCon+"</font>[ElfStat2]<br>";
						}else{
							STxt = STxt + "Constitution: "+bElfCon+"[ElfStat2]<br>";
						}
						if (ElfInt > 0){
							STxt = STxt + "Intelligent: "+bElfInt+" <font color='#ffff77'>+"+ElfInt+"</font>[ElfStat3]<br>";
						}else{
							STxt = STxt + "Intelligent: "+bElfInt+"[ElfStat3]<br>";
						}
						var freeElfPoint = ElfPt-GetElfUsedPoint(ElfStr, ElfAgi, ElfInt, ElfCon);
						STxt = STxt + "Free Points: <font color='#9999ff'>"+freeElfPoint+"</font>/"+ElfPt+"<br>";
						
						if (maxLP > 0){
							STxt = STxt + "Luck Point: <font color='#ff7777'>"+LckPnt+"</font>/"+maxLP+"<br>";
						}
						STxt = STxt + "Energy: [BaseEnergy]<br>";
						STxt = STxt + "Energy Regen: [EnergyRegen]/sec<br>";
						STxt = STxt + "Stamina: "+ElfSta+"/9999999<br>";
						if (ElfTra == "c7a24c4f"){
							STxt = STxt + "State: Trade Ready<br>";
						}else if (ElfTra == "00000000"){
							STxt = STxt + "State: Non Tradeable<br>";
						}else{
							STxt = STxt + "State: Switching<br>";
						}
						var fTal = ElfTp-ElfMet-ElfWoo-ElfWat-ElfFir-ElfEar;
						STxt = STxt + "Talent: <font color='#7777ff'>"+fTal+"</font>/"+ElfTp+" point<br>";
						
						if (ElfMet > 0){
							STxt = STxt + "&nbsp;&nbsp;<font color='#7777ff'>Metal: "+ElfMet+" point</font><br>";
						}					
						if (ElfWoo > 0){
							STxt = STxt + "&nbsp;&nbsp;<font color='#7777ff'>Wood: "+ElfWoo+" point</font><br>";
						}					
						if (ElfWat > 0){
							STxt = STxt + "&nbsp;&nbsp;<font color='#7777ff'>Water: "+ElfWat+" point</font><br>";
						}
						if (ElfFir > 0){
							STxt = STxt + "&nbsp;&nbsp;<font color='#7777ff'>Fire: "+ElfFir+" point</font><br>";
						}
						if (ElfEar > 0){
							STxt = STxt + "&nbsp;&nbsp;<font color='#7777ff'>Earth: "+ElfEar+" point</font><br>";
						}
						
						if (ElfGea>0){
							STxt = STxt + "Equiped Gear: <font color='#ff7777'>"+ElfGea+"</font>/4<br>";
							var GearId;
							var tArr = [];
							var aArr = [];
							var bArr = [];
							var eGTxt = "";
							var GearId;
							var eGErr;
							var n;
							for (var x=1; x<=ElfGea; x++){
								eGErr = 1;
								GearId = HextoDec(Octet.substr(76+x*8, 8), 8, 0, true);
								for (var a=0; a<ElfGearList.length; a++){
									tArr = ElfGearList[a];
									if (tArr[1] == GearId){
										eGErr = 0;
										eGTxt=eGTxt+"&nbsp;&nbsp;<font color='#ffff77'>"+tArr[0]+"</font><br>";
										if (tArr[2].indexOf("*") != -1){
											aArr = tArr[2].split('*');
											for(var c=0;c<aArr.length;c++){
												bArr = aArr[c].split(',');
												n = parseInt(bArr[1],10);
												GearStat[n]=GearStat[n]+parseInt(bArr[0],10);
												eGTxt=eGTxt+"&nbsp;&nbsp;&nbsp;&nbsp;<font color='#7777ff'><span style='font-size:13px;'>"+StatName[n]+" +"+bArr[0]+"</span></font><br>";
											}
										}else{
											aArr = tArr[2].split(',');
											n = parseInt(aArr[1],10);
											GearStat[n]=GearStat[n]+parseInt(aArr[0],10);
											eGTxt = eGTxt+"&nbsp;&nbsp;&nbsp;&nbsp;<font color='#7777ff'><span style='font-size:13px;'>"+StatName[n]+" +"+aArr[0]+"</span></font><br>";
										}
									}
								}
								
								if (eGErr == 1){eGTxt=eGTxt+"&nbsp;&nbsp;<font color='#7777ff'>Unknown Gear["+GearId+"]</font><br>";}
								
								for (var a=0; a<GearStat.length; a++){
									if (GearStat[a] > 0){
										STxt=STxt.replace("[ElfStat"+a+"]", " <font color='#99f'>+"+GearStat[a]+"</font>");
									}else{
										STxt=STxt.replace("[ElfStat"+a+"]", ""); 
									}
								}
								
							}	
							SRes = parseInt(ElfCon, 10)+parseInt(bElfCon, 10)+parseInt(GearStat[3], 10)+100;
							if (SRes > 100){
								STxt=STxt.replace("[BaseEnergy]", "<font color='#ffff77'>"+SRes+"</font>");
							}else{
								STxt=STxt.replace("[BaseEnergy]", "100"); 		
							}
							SRes = ((parseInt(ElfInt, 10)+parseInt(bElfInt, 10)+parseInt(GearStat[2], 10))*2/100+1).toFixed(2);
							if (SRes > 1){
								STxt=STxt.replace("[EnergyRegen]", "<font color='#ffff77'>"+SRes+"</font>"); 
							}else{
								STxt=STxt.replace("[EnergyRegen]", "1.00"); 
							}
						
							STxt = STxt + eGTxt;
						}

						if (ElfSki>0){
							STxt = STxt + "Learned Skills: <font color='#ff7777'>"+ElfSki+"</font>/"+MaxSkill+"<br>";
							for (var x=1; x<=ElfSki; x++){
								STxt = STxt + "&nbsp;&nbsp;<font color='#7777ff'>" + SearchElfSkillData(HextoDec(Octet.substr(84+ElfGea*8+x*8, 4), 4, 0, true), HextoDec(Octet.substr(84+ElfGea*8+x*8+4, 4), 4, 0, true))+"</font><br>";
							}	
						}	
						
					}	
				}else if (SIType == 5){			
					if (Octet.length == 32){
						var DType = HextoDec(Octet.substr(0, 8), 8, 0, true);
						var DMaxLv = HextoDec(Octet.substr(8, 8), 8, 0, true);
						var DRed = parseInt(parseFloat('0x'+ReverseNumber(Octet.substr(16, 8)))*100, 10);
						var DMinLv = HextoDec(Octet.substr(24, 8), 8, 0, true);
						STxt = STxt + "Level Required: <font color='#7777ff'>"+DMinLv+"-"+DMaxLv+"</font> <br>";
						if (DType == 0){
							STxt = STxt + "Reduce incoming physical <br>";
						}else{ 
							STxt = STxt + "Reduce incoming magical <br>";
						}
						STxt = STxt + "damage by <font color='#ff7777'>"+DRed+"%</font>. <br>";
					}else if (Octet.length == 16){
						if (myArr[8] == "131072"){
							var AType = HextoDec(Octet.substr(0, 4), 4, 0, true);
							var DMinGrd = HextoDec(Octet.substr(4, 4), 4, 0, true);
							var DMaxGrd = HextoDec(Octet.substr(8, 4), 4, 0, true);
							var Dmg = HextoDec(Octet.substr(12, 4), 4, 0, true);
							STxt = STxt + "Weapon Grade: <font color='#7777ff'>"+DMinGrd+"-"+DMaxGrd+"</font> <br>";
							if (DType == 0){
								STxt = STxt + "Add additional <font color='#ff7777'>"+Dmg+"</font> physical<br>";
							}else{ 
								STxt = STxt + "Add additional <font color='#ff7777'>"+Dmg+"</font> magical<br>";
							}
							STxt = STxt + "damage when attack.<br>";
					
						}else{
							var Amount = HextoDec(Octet.substr(0, 8), 8, 0, true);
							var Act = parseInt(parseFloat('0x'+ReverseNumber(Octet.substr(8, 8)))*100, 10);		
							if (myArr[8] == "1048576"){
								STxt = STxt + "Fully recover your HP</font><br>";
								STxt = STxt + "when current HP go below <font color='#ffff77'>"+Act+"%</font>.<br>";
								STxt = STxt + "Limit: <font color='#ff7700'>"+Amount+"</font> HP<br>";
								STxt = STxt + "Cooldown: <font color='#ffff77'>10 sec</font><br>";
							}else{ 
								STxt = STxt + "Fully recover your MP instantly</font><br>";
								STxt = STxt + "when current MP go below <font color='#ffff77'>"+Act+"%</font>.<br>";
								STxt = STxt + "Limit: <font color='#0077ff'>"+Amount+"</font> MP<br>";
								STxt = STxt + "Cooldown: <font color='#ffff77'>10 sec</font><br>";
							}

								
						}
					}
				}else if (SIType == 6){
						if (Octet.length > 103){
							var LvReq = HextoDec(Octet.substr(0, 4), 4, 0, true);
							var ReqCls = HextoDec(Octet.substr(4, 4), 4, 0, true);
							var StrReq = HextoDec(Octet.substr(8, 4), 4, 0, true);
							var ConReq = HextoDec(Octet.substr(12, 4), 4, 0, true);
							var AgiReq = HextoDec(Octet.substr(16, 4), 4, 0, true);
							var IntReq = HextoDec(Octet.substr(20, 4), 4, 0, true);
							var Dur1 = parseInt(HextoDec(Octet.substr(24, 8), 8, 0, true)/100, 10);
							var Dur2 = parseInt(HextoDec(Octet.substr(32, 8), 8, 0, true)/100, 10);
							var Unknown1 = HextoDec(Octet.substr(40, 8), 8, 0, true);
							var AmmoId = HextoDec(Octet.substr(48, 8), 8, 0, true);
							var Dmg = HextoDec(Octet.substr(56, 8), 8, 0, true);
							var Unknown2 = HextoDec(Octet.substr(64, 8), 8, 0, true);
							var MinWGr = HextoDec(Octet.substr(72, 8), 8, 0, true);
							var MaxWGr = HextoDec(Octet.substr(80, 8), 8, 0, true);
							var Unknown3 = HextoDec(Octet.substr(88, 8), 8, 0, true); //probabil socket what allway 0
							var AddonC = HextoDec(Octet.substr(96, 8), 8, 0, true); 
							if (LvReq > 0){
								STxt = STxt + "Level Required: "+LvReq+"<br>";
							}				
							if (maxClass > ReqCls){
								STxt = STxt + "Class Required: "+ItemClassReq(ReqCls)+"<br>";
							}	
							if (StrReq > 0){
								STxt = STxt + "Strength Req.: "+StrReq+"<br>";
							}					
							if (AgiReq > 0){
								STxt = STxt + "Agility Req.: "+AgiReq+"<br>";
							}					
							if (IntReq > 0){
								STxt = STxt + "Intelligent Req.: "+IntReq+"<br>";
							}					
							if (ConReq > 0){
								STxt = STxt + "Constitution Req.: "+ConReq+"<br>";
							}						
							STxt = STxt + "Weapon Grade: <font color='#ffff77'>"+MinWGr+"-"+MaxWGr+"</font> <br>";			
							STxt = STxt + "Physical Attack: <font color='#ff7777'>+"+Dmg+"</font> <br>";	
							var tAddon ="";					
							if (AddonC>0){
								var AHex;
								var shift = 0;
								var aType;
								var bAddon;
								var vAddon;
								
								for (var x=0; x<AddonC; x++){
									AHex = ReverseNumber(Octet.substr(104+x*16+shift, 8));
									AHex = AHex.replace(/^0+/, '').trim();
									//2 normal addon, 4 special or refine, a is socket addon
									if (AHex.length % 2 == 0){
										aType = AHex.substr(0,1);
										if (aType=="4"){
											//special weapon addons
											tAddon = tAddon + "&nbsp;&nbsp;" + SearchAddonNameValue(HextoDec(AHex, 8, aType, false), HextoDec(Octet.substr(104+x*16+shift+8, 8), 8, 0, true) +" "+ HextoDec(Octet.substr(104+x*16+shift+16, 8), 8, 0, true))+"<br>";
											shift = shift + 8;
										}else{
											//normal addon
											bAddon = ReverseNumber(AHex, 4);
											vAddon = HextoDec(Octet.substr(104+x*16+shift+8, 8), 8, 0, true);
											if (bAddon == "2c21"){
												vAddon = parseFloat('0x'+ReverseNumber(Octet.substr(104+x*16+shift+8, 8))).toFixed(2);
											}else if (bAddon == "7c22"){
												vAddon = parseFloat('0x'+ReverseNumber(Octet.substr(104+x*16+shift+8, 8))).toFixed(2);
											}
											tAddon = tAddon + "&nbsp;&nbsp;" + SearchAddonNameValue(HextoDec(AHex, 8, aType, false), vAddon)+"<br>";
										}
									}	
								}	
								STxt = STxt + "<font color='#7777ff'>"+tAddon+"</font>";
							}
						}				
				}else if (SIType == 7){
					var LvReq = HextoDec(Octet.substr(0, 8), 8, 0, true);
					STxt = STxt + "This potion need <font color='#aaaaff'>"+LvReq+"</font> level<br> to able to use<br>";
					STxt = STxt + rctu;
				}else if (SIType == 8){
					var QuestId = HextoDec(Octet.substr(0, 8), 8, 0, true);
					STxt = STxt + "This task dice item activate <br> the a Quest <font color='#aaaaff'>["+QuestId+"]</font>.<br>";
					STxt = STxt + rctu;			
				}else if (SIType == 9){
					var FType = "Unknown";
					var GType = HextoDec(Octet.substr(0, 8), 8, 0, true);
					var Loyality = HextoDec(Octet.substr(8, 8), 8, 0, true);
					if (GType == 1){
						FType="Fodder";
					}else if (GType == 2){
						FType="Meat";
					}else if (GType == 3){
						FType="Cookie";
					}else if (GType == 4){
						FType="Mushroom";
					}else if (GType == 8){
						FType="Fruit or Seed";
					}else if (GType == 16){
						FType="Water or Oil";
					}
					STxt = STxt + "This <font color='#ff7777'>"+FType+"</font> kind pet food<br> recover <font color='#ff7777'>"+Loyality+"</font> loyality to pet.<br>";
					STxt = STxt + rctu;			
				}else if (SIType == 10){	
					var AddonC1 = HextoDec(Octet.substr(0, 8), 8, 0, true);
					var AddonId1 = HextoDec(Octet.substr(8, 8), 8, 2, true);
					var AddonVal1 = HextoDec(Octet.substr(16, 8), 8, 0, true);
					var AddonC2 = HextoDec(Octet.substr(24, 8), 8, 0, true);
					var AddonId2 = HextoDec(Octet.substr(32, 8), 8, 2, true);
					var AddonVal2 = HextoDec(Octet.substr(40, 8), 8, 0, true);
					var weapTxt = SearchSocketNameValue(1, myArr[7], AddonId1 +"#"+ AddonVal1).split(":");
					var armrTxt = SearchSocketNameValue(2, myArr[7], AddonId2 +"#"+ AddonVal2).split(":");
					STxt = STxt + "<br><font color='#f77'>Weapon:</font> "+weapTxt[1]+"<br>";
					STxt = STxt + "<font color='#77f'>Armor:</font> "+armrTxt[1]+"<br>";
					STxt = STxt + "<br><font color='yellow'><i>Effect is depend if you insert into<br> weapon or armor (at <font color='#f77'>blacksmith</font> or <font color='#77f'>tailor</font>)</i></font><br>";
				}else if (SIType == 11){	
					var Orders=["Corona", "Shroud", "Luminance"];
					var CPrest=HextoDec(Octet.substr(0, 8), 8, 0, true);
					var Order = HextoDec(Octet.substr(8, 8), 8, 0, true);
					var Loss = HextoDec(Octet.substr(16, 8), 8, 0, true);
					STxt = STxt + "<font color='#77f'>Order:</font> "+Orders[(parseInt(Order,10)-1004)]+"<br>";
					STxt = STxt + "<font color='#77f'>Prestige:</font> "+CPrest+"/"+myArr[18]+"<br>";
					//alert(myArr);
					STxt = STxt + "<br><br><font color='#f77'>Lose</font> "+Loss+"% prestige,<br> if leave the order.<br><br>";
				}else if (SIType == 12){
					STxt = STxt + "Level Req.: 100<br>";
					var SCBSA=[];
					var SCFSA=[];
					var SCXp = parseInt(HextoDec(Octet.substr(0, 8), 8, 0, true), 10);
					var SCLv = parseInt(HextoDec(Octet.substr(8, 2), 2, 0, true), 10)+1;
					var SCCmb = parseInt(HextoDec(Octet.substr(10, 4), 4, 0, true), 10);
					var StarArr=[];
					//birthstar apt
					for (var i=1; i<=5; i++){
						SCBSA[i] = (parseInt(HextoDec(Octet.substr(10+i*4, 4), 4, 0, true), 10)/100).toFixed(2);
						StarArr[i]="<font color='#aaf'>Birthstar #"+i+":</font> "+SCBSA[i];
					}
					//fatestar apt
					for (var i=1; i<=5; i++){
						if (i > 1){
							SCFSA[i]=(Number(SCBSA[i-1])+Number(SCBSA[i])).toFixed(2);
						}else{
							SCFSA[i]=(Number(SCBSA[5])+Number(SCBSA[i])).toFixed(2);
						}
						StarArr[i+5]="<font color='#f99'>Fatestar #"+i+":</font> "+SCFSA[i];
					}
					STxt = STxt + "Level: "+SCLv+"/50<br>";
					if (SCLv<50){
						STxt = STxt + "Exp: "+SCXp+"/"+StarChartLv[SCLv-1][0];
					}else{
						STxt = STxt + "Exp: Max";
					}

					var tmp="-SCAddon-";
					var SCRate=[];
					var SCAddn=[];
					var SCNR=0;
					var SCInd = parseInt(HextoDec(Octet.substr(34, 8), 8, 0, true), 10);
					for (var i=0; i<SCInd; i++){
						SCAddn[i+1]=parseInt(HextoDec(Octet.substr(42+i*16, 8), 8, 2, true), 10);
						SCRate[i+1]=parseFloat('0x'+ReverseNumber(Octet.substr(50+i*16, 8))).toFixed(2);
					}
					//handle combo and personalize opened/closed stars
					var Cmb=SCCmb;
					var SCOS=[];
					var selO;
					var pw;
					for (var i=1; i<11; i++){
						pw=Math.pow(2, 10-i);
						SCNR=Math.floor((11-i+1)/2);	
						if (Cmb>=pw){
							Cmb=Cmb-pw;
							SCOS[11-i]=true;
							if (((11-i) % 2) != 0){
								StarArr[SCNR+5]="<b>"+StarArr[SCNR+5]+"</b>"+tmp;
							}else{
								StarArr[SCNR]="<b>"+StarArr[SCNR]+"</b>"+tmp;
							}
						}else{
							SCOS[11-i]=false;
							if (((11-i) % 2) != 0){
								StarArr[SCNR+5]="<span style='opacity:0.5;'><i>"+StarArr[SCNR+5]+"</i></span>";
							}else{
								StarArr[SCNR]="<span style='opacity:0.5;'><i>"+StarArr[SCNR]+"</i></span>";
							}							
						}
					}
					
					if (SCInd > 0){
						var SCAC=1;
						var SCST=5;
						var val;
						var scApt;
						var scAddTxt;
						for (var i=1; i<11; i++){
							SCNR=Math.floor((i+1)/2);	
							SCST=0;
							if ((i % 2)!=0){
								SCST=5;
								scApt=SCFSA[SCNR];
							}else{
								scApt=SCBSA[SCNR];
							}
							
							if (SCOS[i] != null){
								if (SCOS[i] !== false){
									if (SCAC <= SCInd){
										scAddTxt="";
										if ((SCAddn[SCAC] > 3159) && (SCAddn[SCAC] < 3195)){
											for (var x=0; x<StarChartAddon.length; x++){
												if (StarChartAddon[x][0] == SCAddn[SCAC]){
													val = Math.floor(SCRate[SCAC]*(25+scApt*SCLv));
													scAddTxt=GetAddonString(StarChartAddon[x][1],val);
													break;
												}
											}
										}
										if (scAddTxt == ""){
											scAddTxt="Unknown";
										}
										StarArr[(SCNR+SCST)]=StarArr[(SCNR+SCST)].replace(tmp, "<br>&nbsp;&nbsp;&nbsp;&nbsp;<span style='font-size:13px;color:#ff7;'>"+scAddTxt+"</span>");
									}
									SCAC++;
								}
							}
						}
					}		
					STxt = STxt + StarArr.join("<br>");
				}
			}else if (MIType == "U"){
					STxt = STxt + GearType[5][SIType]+"<br>";
					if (SIType == 1){
						STxt = STxt + "<font color='#ffa'>" +GetTomeInfo(myArr[7])+"</font>";
					}else if (SIType == 6){
						STxt = STxt + "Used for dye fashions<br>";
					}else if (SIType == 7){
						STxt = STxt + "Create firework on sky<br>";
						STxt = STxt + rctu;	
					}else if (SIType == 10){
						STxt = STxt + "With scrolls pet can learn new skills<br>";
					}else if (SIType == 11){
						STxt = STxt + "For fun character look like<br>";
					}else if (SIType == 12){
						STxt = STxt + "Grade <font color='#ff7777'>"+myArr[18]+"</font> fuel crystal<br>Used for special Att/Deff hieros<br>and it is fuel for Flyer and Elf<br>";
					}else if (SIType == 13){
						STxt = STxt + "For a good instance run<br>";
					}else if (SIType == 14){	
						for (var i = 0; i < ElfGearList.length; i++) {
							if (ElfGearList[i][1] == myArr[7]){
								var eGearStat;
								STxt = STxt + "<br>";
								if (ElfGearList[i][2].indexOf("*") != -1){
									var sGearStat = ElfGearList[i][2].split("*");
									for (var a = 0; a < sGearStat.length; a++) {
										eGearStat = sGearStat[a].split(",");
										STxt = STxt + "<font color='#77f'>Bonus stat:</font> "+GetAddonString(eGearStat[1], eGearStat[0])+"<br>";
									}
								}else{
									eGearStat = ElfGearList[i][2].split(",");
									STxt = STxt + "<font color='#77f'>Bonus stat:</font> "+GetAddonString(eGearStat[1], eGearStat[0])+"<br>";
								}
								STxt = STxt + "<font color='#f77'>Level Required:</font> "+ElfGearList[i][3]+"<br>";
							//break;
							}
						}
					}else if (SIType == 15){
						var RuneData=GetRuneItemData(myArr[7]);
						if (RuneData != null){
							STxt = STxt + "<font color='#f77'>Req. Equipment Level:</font> "+RuneData[1]+"<br>";
							var rtimeMin = parseInt(RuneData[4],10);
							var rtime = "";
							if ((rtimeMin/1440)>=1){
								rtime = (rtimeMin/1440) + " day ";
								rtimeMin = rtimeMin % 1440;
							}
							if ((rtimeMin/60)>=1){
								rtime = rtime + (rtimeMin/60) + " hour ";
								rtimeMin = rtimeMin % 60;
							}
							if (rtimeMin>=1){
								rtime = rtime + rtimeMin + " min ";
							}
							var CBonus = RuneData[2];
							var tmp;
							var tTxt="";
							if (CBonus.indexOf("*") != -1){
								CBonus = CBonus.split('*');
								for(var i=0;i<CBonus.length;i++){
									tmp = CBonus[i].split(',')
									tTxt=tTxt+GetAddonString(parseInt(tmp[0], 10), tmp[1])+"<br>";
								}			
							}else{
								CBonus = CBonus.split(',');
								tTxt = GetAddonString(parseInt(CBonus[0], 10), CBonus[1])+"<br>";			
							}	
							STxt = STxt +"<font color='#77f'>"+tTxt+"</font>";
							STxt = STxt + "<br><font color='#ff7'>Duration:</font> "+rtime+"<br>";
						}
					}
			}else if (MIType == "M"){
				STxt = STxt + GearType[6][SIType]+"<br>";
				STxt = STxt + "<br>";
				if (SIType == 1){
					STxt = STxt + "Used to craft basic equipments or molders.<br>Few of you can get via killing monsters,<br> few of them can harvestable with shovel.<br>";
				}else if (SIType == 2){
					STxt = STxt + "Jades used to craft basic equipments,<br> buyable from Merchandiser NPC.<br>";
				}else if (SIType == 3){
					STxt = STxt + "Herbs are used for craft special potions,<br> you can harvest with shovel/pickaxe.<br>";
				}else if (SIType == 4){
					STxt = STxt + "Holy Hall of Dusk are stronger instance,<br>Drop mats often used for<br> crafting green or yellow equipments.<br>";
				}else if (SIType == 5){
					STxt = STxt + "HH Souledges you can get<br> from decompose the HH equipments<br>Usefull for craft higher level HH equipment.<br>";
				}else if (SIType == 6){
					STxt = STxt + "Frost Walk City is a strong instance,<br> drop from this instance anybody<br> craft strong equipments.<br>";
				}else if (SIType == 7){
					STxt = STxt + "Crescent Valley is a very dangerous place,<br> from this place you can grind mats<br> for strong yellow gear.<br>";
				}else if (SIType == 8){
					STxt = STxt + "Molders are the main mats for<br> crafting several strong equipment<br> with basic mats, most of them dropable<br> from various instances boss.<br>";
				}else if (SIType == 9){
					STxt = STxt + "Tear of Heaven, is a place in present and past,<br> from these mats craftable several common item<br> and chance for few good addon.<br>";
				}else if (SIType == 10){
					STxt = STxt + "A huge collection of most strangest and rarest mats.<br>";
				}
			}else if (MIType == "C"){
				document.getElementById('CardClass').style.display='block';
				if (Octet.length == 64){
					var cardId = SearchCardId(parseInt(myArr[7], 10));
					var cTyp = HextoDec(Octet.substr(0, 8), 8, 0, true);
					var cGra = HextoDec(Octet.substr(8, 8), 8, 0, true);
					var cLvR = HextoDec(Octet.substr(16, 8), 8, 0, true);
					var cLea = HextoDec(Octet.substr(24, 8), 8, 0, true);
					var cMxL = HextoDec(Octet.substr(32, 8), 8, 0, true);
					var cCLv = HextoDec(Octet.substr(40, 8), 8, 0, true);
					var cExp = HextoDec(Octet.substr(48, 8), 8, 0, true);
					var cReb = HextoDec(Octet.substr(56, 8), 8, 0, true);		
					var cTxt = GetCardInfo(cardId,cTyp,cGra,cLvR,cCLv,cMxL,cExp,cLea,cReb)
					STxt = cTxt;
				}
			}else if (MIType == "F"){
				STxt = STxt + "Fashion<br><br>";		
				var LvReq = HextoDec(Octet.substr(0, 8), 8, 0, true);
				var HFCol = Octet.substr(8, 4);
				//var FColor = HextoDec(HFCol, 4, 0, true);
				var Fgender = HextoDec(Octet.substr(12, 4), 4, 0, true);
				var Unknown = HextoDec(Octet.substr(16, 8), 8, 0, true);
				if (LvReq > 0){
					STxt = STxt + "Level Required: <font color='#ff9999'>"+LvReq+"</font><br>";
				}
				var Gen;
				if (Fgender == 0){
					Gen = "<font color='#7777ff'>Male</font>";	
				}else{
					Gen = "<font color='#ff7777'>Female</font>";	
				}
				STxt = STxt + "Gender: "+Gen+"<br>";	
				if (SIType < 11){
					STxt = STxt + "Color: <font color='#77ffee'>"+(SearchFashionColor(HFCol))+"</font><br>";
				}else{
					var fwm=myArr[18];
					STxt = STxt + "Useable with: <font color='#55eedd'>"+(GetWFashReqWeapon(parseInt(fwm,10)||0))+".</font><br>";
				}
				
			}
			
			//1712-1751 pattack 1752-1771 mattck 1772-1831 hp 1832-1851 pdef 1852-1871 mdef 1872-1891 dodge	
			if (myArr[3] != ""){
				if (myArr[3].indexOf("*") != -1){
					STxt = STxt + "<font color='#ffff77'>" + myArr[3].split('*').join("<br>")+"</font><br>";
				}else{
					STxt = STxt + "<font color='#ffff77'>" + myArr[3]+"</font><br>";
				}
			}
			if (procType != ""){		
				STxt = STxt + "<font color='#ff7777'><b>Limits:</b></font> <br><font color='#77ffee'>"+ procType+"</font><br>";	
			}	
			
			var Discount = ShopItemDiscVerification(myArr[19]);
			var Price = 0;
			
			if (myArr[19] != null){
				if (myArr[19].indexOf(" ") != -1){
					var ShopTD=myArr[19].split(" ");
					if (ShopTD[0] != 0){
						if (ShopTD[0] == 1){
							STxt = STxt + "<br> <font color='#77f'><b>Buy limit:</b></font> <font color='#ffff77'>"+NumToDate(parseInt(ShopTD[2],10))+" - "+NumToDate(parseInt(ShopTD[3],10))+"</font><br>";
						}else if (ShopTD[0] == 2){
							STxt = STxt + "<br> <font color='#77f'><b>Buy between:</b></font> <font color='#ffff77'>"+ShopTD[2]+" - "+ShopTD[3]+"</font> daily<br>";
						}else if (ShopTD[0] == 3){
							if (Discount > 0){
								STxt = STxt + "<br> <font color='#77f'><b>Promotion:</b></font> <font color='#ffff77'>"+NumToDate(parseInt(ShopTD[2],10))+" - "+NumToDate(parseInt(ShopTD[3],10))+"<br><font color='#77f'><b>Discount:</b></font> -"+ShopTD[4]+"% from price</font><br>";
							}
						}
					}
				}
			}		
			
			
			if ((myArr[0] > 0) && (myArr[1] == 0)){
				Price=parseInt(myArr[0],10);
				if (Discount != 0) {Price=Price-parseInt(Price*Discount/100);}
				if (Price < 1){Price=1;}
				STxt = STxt + "<br> Price: <font color='#ffff77'>"+Price+"</font> Coin<br>";
			}else if ((myArr[1] > 0) && (myArr[0] == 0)){
				Price=parseInt(myArr[1],10);
				if (Discount != 0) {Price=Price-parseInt(Price*Discount/100);}
				if (Price < 1){Price=1;}			
				STxt = STxt + "<br> Price: <font color='#ffff77'>"+Price+"</font> Point<br>";
			}else{
				Price=parseInt(myArr[0],10);
				var Price1=parseInt(myArr[1],10);
				if (Discount != 0) {Price=Price-parseInt(Price*Discount/100);}
				if (Price < 1){Price=1;}
				if (Discount != 0) {Price1=Price1-parseInt(Price1*Discount/100);}
				if (Price1 < 1){Price1=1;}
				STxt = STxt + "<br> Price: <font color='#ffff77'>"+Price+"</font> Coin or <font color='#ffff77'>"+Price1+"</font> Point<br>";
			}
			document.getElementById('itmData').innerHTML = STxt;
		}
	}
}

function ItemClassReq(cMask){
	var cM = cMask;
	if (maxClass == cM){
		return "All Class";
	}else{
		var ClassList = [];
		ClassList[0] = "Warrior";
		ClassList[1] = "Magician";
		ClassList[2] = "Assassin";
		ClassList[3] = "Werefox";
		ClassList[4] = "Werebeast";
		ClassList[5] = "Psychick";
		ClassList[6] = "Archer";
		ClassList[7] = "Priest";
		ClassList[8] = "Seeker";
		ClassList[9] = "Mystic";
		ClassList[10] = "Dusk Blade";
		ClassList[11] = "Stormbringer";
		var max=8;
		if (maxClass > 1023){
			max=12;
		}else if (maxClass > 255){
			max=10;
		}else if (maxClass > 219){
			max=8;
		}else{
			ClassList[2] = "";
			ClassList[5] = "";
		}
			
		var x;
		var p;
		var output = "";
		for (var i = 0; i < max; i++) {
			x = max - 1 - i;
			p = Math.pow(2, x);
			if ((cM-p) >= 0){
				cM = cM - p;
				if (ClassList[x] != ""){
					if (output != ""){
						output = output+", "+ClassList[x];
					}else{
						output = output+ClassList[x];
					}
				}
			}
		}
		if (output == ""){output="Not useable";}
		return output;
	}
}

function GearProcTypeToStr(Proct){
	var cM = Proct;
	if (Proct == 0){
		return "";
	}else{
		var ProcTList = [];
		ProcTList[0] = "Cannot lose if die";
		ProcTList[1] = "Cannot drop";
		ProcTList[2] = "Cannot sell";
		ProcTList[3] = "";
		ProcTList[4] = "Cannot trade";
		ProcTList[5] = "Cannot refine";
		ProcTList[6] = "Visible bind";
		ProcTList[7] = "";
		ProcTList[8] = "Lose if leave zone";
		ProcTList[9] = "Use if pick up";
		ProcTList[10] = "Drop if die";
		ProcTList[11] = "Lose if log off";
		ProcTList[12] = "Cannot repair";
		ProcTList[13] = "Damaged";
		ProcTList[14] = "";
		ProcTList[15] = "Soulbound";
		var max=ProcTList.length;
		var x;
		var n = 0;
		var s = ", ";
		var p;
		var output = "";
		for (var i = 0; i < max; i++) {
			x = max - 1 - i;
			p = Math.pow(2, x);
			if ((cM-p) >= 0){
				cM = cM - p;
				if (ProcTList[x] != ""){
					if (output != ""){
						s = ", ";
						if (n > 1){n=0;s = "<br>";}
					}else{
						s = "";
					}
					output = output+s+ProcTList[x];
					n++;
				}
			}
		}
		return output;
	}
}

function ShopItemFilter(fltr){
	var bubb;
	var myArr;
	var icat;
	if (LockItem !== true){
		document.getElementById("TheItemDiv").style.display="none";
		document.getElementById("BuyWindow").style.display="none";
	}
	TmdII = 0;
	var sId=0;
	PITCA=false;
	for (var i = 1; i <= ShopInd; i++) {
		bubb = document.getElementById("iconId"+i);
		myArr = ShopItm[i].split("#");
		icat = myArr[16];
		if (fltr[0] == icat[0]){
			if (fltr[0] == 0){
				if (ShopItemTimeVerification(myArr[19], i) !== false){
					bubb.style.display = "inline-block";
					if (sId==0){
						sId=i;
					}
				}else{
					bubb.style.display = "none";
				}
			}else{
				if (fltr[1] == 0){
					if (ShopItemTimeVerification(myArr[19], i) !== false){
						bubb.style.display = "inline-block";
						if (sId==0){
							sId=i;
						}						
					}else{
						bubb.style.display = "none";
					}
				}else if (fltr[1] == icat[1]){
					if ((fltr[2] == icat[2])||(fltr[2] == 0)){
						if (ShopItemTimeVerification(myArr[19], i) !== false){
							bubb.style.display = "inline-block";
							if (sId==0){
								sId=i;
							}							
						}else{
							bubb.style.display = "none";
						}
					}else{
						bubb.style.display = "none";
					}
				}else{
					bubb.style.display = "none";
				}
			}
	
		}else{
			bubb.style.display = "none";
		}
	}
	if ((LockItem !== true)&&(sId>0)){
		CreateBubble(sId);
	}
	PITCA=true;
}

function ShopItemTimeVerification(iDate, id){
	if (iDate.indexOf(" ") != -1){
		var ISDate = iDate.split(" ");
		if ((ISDate[0]==0)||(ISDate[0]==3)){
			return true;
		}else{
			if (ISDate[1]==0){
				return true;
			}else{
				var d = new Date();
				if (id>0){
					TmdII++;
					TmdItm[TmdII] = id;
				}
				if (ISDate[0]==1){
					//added server time too
					var cTimeStamp=Math.floor(Date.now()/1000+SrvrTmZone+d.getTimezoneOffset()*60);
					if ((cTimeStamp<parseInt(ISDate[2],10))||(cTimeStamp>parseInt(ISDate[3],10))){
						return false;
					}else{
						return true;
					}
				}else if (ISDate[2]){
					var hour1 = ISDate[2].split(":");
					var hour2 = ISDate[3].split(":");
					var dtime1 = parseInt(hour1[0],10)*60+parseInt(hour1[1],10);
					var dtime2 = parseInt(hour2[0],10)*60+parseInt(hour2[1],10);
					//---------added serve time
					var dtime3 = parseInt(d.getHours(),10)*60+parseInt(d.getMinutes(),10)+SrvrTmZone/60+d.getTimezoneOffset();
					if ((dtime3<dtime1)||(dtime3>dtime2)){
						return false;
					}else{
						return true;
					}
				}
			}
		}
	}
	return false;
}

function ShopItemDiscVerification(iDate){
	var d = new Date();
	if (iDate.indexOf(" ") != -1){
		var ISDate = iDate.split(" ");
		if (ISDate[0]==3){
			var cTimeStamp=Math.floor(Date.now()/1000+SrvrTmZone+d.getTimezoneOffset()*60);
			if ((cTimeStamp<parseInt(ISDate[2],10))||(cTimeStamp>parseInt(ISDate[3],10))){
				return 0;
			}else{
				return parseInt(ISDate[4], 10);
			}
		}
	}
	return 0;
}

function BuyWindowManager(id){
	var myArr = ShopItm[id].split("#");
	var div = document.getElementById("BuyWindow");
	var h = 90;
	var d1 = document.getElementById("Buy_Price_Gold");
	var d2 = document.getElementById("Buy_Price_Point");
	var d3 = document.getElementById("Buy_Cost1");
	var d4 = document.getElementById("Buy_Cost2");
	div.style.display="none";
	var Discount = ShopItemDiscVerification(myArr[19]);
	
	document.getElementById("Buy_ShopId").value=id;
	document.getElementById("Buy_Name").innerHTML=myArr[2];
	if (myArr[11] > 1){
		document.getElementById("Buy_Amount").style.display="inline";
		h = h + 20;
	}else{
		document.getElementById("Buy_Amount").style.display="none";
	}
	document.getElementById("Buy_MaxQty").innerHTML=myArr[11];
	document.getElementById("Buy_QTY").value=myArr[10];
	var cost;
	if (myArr[0] > 0){
		cost = parseInt(myArr[0]) - (parseInt(myArr[0])*Discount/100);
		document.getElementById("Buy_Price1").style.display="inline";
		d1.checked=true;
		document.getElementById("Buy_FPrice1").innerHTML=cost*myArr[10];
		
		d4.style.display="none";
		if (myArr[1] == 0){
			d3.style.display="inline";
			d1.style.display="none";
			d2.style.display="none";
		}else{
			d1.style.display="inline";
			d2.style.display="inline";
			d3.style.display="none";
		}
	}else{
		document.getElementById("Buy_Price1").style.display="none";
		d1.checked=false;
		d4.style.display="inline";
		d3.style.display="none";
		d1.style.display="none";
		d2.style.display="none";
	}
	if (myArr[1] > 0){
		cost = parseInt(myArr[1]) - (parseInt(myArr[1])*Discount/100);
		document.getElementById("Buy_Price2").style.display="inline";
		d2.checked=true;
		document.getElementById("Buy_FPrice2").innerHTML=cost*myArr[10];
	}else{
		document.getElementById("Buy_Price2").style.display="none";
		d2.checked=false;
	}

	if ((myArr[0] > 0)&&(myArr[1] > 0)){
		div.style.height="130px";
		h = h + 20;
	}
	div.style.height=h+"px";
	div.style.display="block";
}

function BuyThisItem(){
	var qty = document.getElementById("Buy_QTY");
	var amount = parseInt(qty.value, 10) || 0;
	var id = parseInt(document.getElementById("Buy_ShopId").value, 10) || 0;
	var buyWith = 1;
	var price = 0;
	var balance = 0;
	var transWith = 1; //1=via mail sending
	var sr = document.getElementById("Sel_Role");
	var roleId = 0;
	var roleName = 0;
	if (sr != null) {
		roleId = sr.options[sr.selectedIndex].value;
		roleName = sr.options[sr.selectedIndex].text;
	}
	if (ShopItm[id] != null){
		if ((MReady !== false) && (transWith == 1)){
			if (roleId > 0){
				var myArr = ShopItm[id].split("#");
				var Discount = ShopItemDiscVerification(myArr[19]);
				if (amount == 0){
					amount = 1;
					qty.value = 1;
				}
				if (amount > myArr[11]){
					qty.value = myArr[11];
					amount = myArr[11];
				}
				if ((myArr[0] > 0)&&(document.getElementById("Buy_Price_Gold").checked !== false)){
					price=parseInt(myArr[0],10)*amount-(parseInt(myArr[0],10)*amount*Discount/100);
					balance=RMoney-price;
				}
				if ((myArr[1] > 0)&&(document.getElementById("Buy_Price_Point").checked !== false)){
					price=parseInt(myArr[1],10)*amount-(parseInt(myArr[1],10)*amount*Discount/100);
					balance=UPoint-price;
				}		
				if (document.getElementById("Buy_Price_Point").checked !== false){
					buyWith=2;
				}
				if ((price > 0) && (balance >=0 )){
					var ndata=ShopItm[id].replace(/#/g, '|').replace(/\+/g,'@');
					var RuneL=GetRuneData(ShopItm[id]);
					var obj = {
						"IData":ndata, "Amount":amount, "buyWith":buyWith,
						"transWith":transWith, "shopid":id, "roleid":roleId,
						"rolename":roleName, "RuneList":RuneL
					};
					BuyItemWithAjax(2, obj);
				}else{
					alert('Insufficient fund for buy this item!');
				}
			}else{
				alert('Server is offline or you have no character!');
			}
		}else{
			alert('Please wait until other mail sent!');
		}
	}
}

function GetRuneData(ndata){
	var myArr = ndata.split("#");
	var MIType = myArr[4].substr(0, 1);
	var SIType = parseInt(myArr[4].substr(1), 10);
	var Octet = myArr[15];
	var addonStr = "";
	var NameLen;
	var SocketC;
	var AddonC=0;
	var n;
	var RuneIDCol = [];
	var c=0;
	if (MIType == "W"){
		if (Octet.length > 151){
			NameLen = parseInt(HextoDec(Octet.substr(46, 2), 2, 0, true)/2, 10);
			SocketC = HextoDec(Octet.substr(136+NameLen*4, 8), 8, 0, true);
			n = NameLen*4+SocketC*8+144;
			AddonC=HextoDec(Octet.substr(n, 8), 8, 0, true);
			if (AddonC >0){
				addonStr = Octet.substr(n+8);
			}
		}			
	}else if ((MIType == "A")||((MIType == "O")&&(SIType==3))){
		if (Octet.length > 135){
			NameLen = parseInt(HextoDec(Octet.substr(46, 2), 2, 0, true)/2, 10);
			SocketC = HextoDec(Octet.substr(120+NameLen*4, 8), 8, 0, true);
			n = NameLen*4+SocketC*8+128;
			AddonC=HextoDec(Octet.substr(n, 8), 8, 0, true);
			if (AddonC >0){
				addonStr = Octet.substr(n+8);
			}
		}		
	}else if (MIType == "J"){
		if (Octet.length > 135){
			NameLen = parseInt(HextoDec(Octet.substr(46, 2), 2, 0, true)/2, 10);
			SocketC = HextoDec(Octet.substr(120+NameLen*4, 8), 8, 0, true);
			n = NameLen*4+SocketC*8+128;
			AddonC=HextoDec(Octet.substr(n, 8), 8, 0, true);
			if (AddonC >0){
				addonStr = Octet.substr(n+8);
			}
		}	
	}else if ((MIType == "O")&&(SIType==6)){
		AddonC=HextoDec(Octet.substr(96, 8), 8, 0, true);
		if (AddonC >0){
			addonStr = Octet.substr(104);
		}
	}	
	
	//search after run addon ids and collect them
	if (AddonC > 0){
		var AHex=0;
		var AHex1=0;
		var aType=0;;
		var shift=0;
		var AddonId=0;
		
		for (var x=0; x<AddonC; x++){
			AHex = addonStr.substr(x*16+shift, 8);
			AHex1 = ReverseNumber(AHex).replace(/^0+/, '').trim();
			if (AHex1.length % 2 == 0){
				aType = AHex1.substr(0,1);
				AddonId=HextoDec(AHex1, 8, aType, false);
				if (aType=="4"){
					AddonId=HextoDec(AHex1, 8, aType, false);
					if (isRune(AddonId)!==false){
						RuneIDCol[c]=AHex;
						c++;
					}
					shift = shift + 8;
				}
			}	
		}		
		return RuneIDCol.join("*");
	}
	return "";
}

function ChangeBuyingAmount(){
	var qty = document.getElementById("Buy_QTY");
	var amount = parseInt(qty.value, 10) || 0;
	var id = parseInt(document.getElementById("Buy_ShopId").value, 10) || 0;
	
	if (ShopItm[id] != null){
		var myArr = ShopItm[id].split("#");
		var Discount = ShopItemDiscVerification(myArr[19]);
		if (amount == 0){
			amount = 1;
			qty.value = 1;
		}
		if (amount > myArr[11]){
			qty.value = myArr[11];
			amount = myArr[11];
		}
		if (myArr[0] > 0){
			document.getElementById("Buy_FPrice1").innerHTML=myArr[0]*amount - (parseInt(myArr[0])*amount*Discount/100);
		}
		if (myArr[1] > 0){
			document.getElementById("Buy_FPrice2").innerHTML=myArr[1]*amount - (parseInt(myArr[1])*amount*Discount/100);
		}
	}
}	

function GetTomeInfo(TomeID){
	for(var i=1;i<=TomeStat.length;i++){
		if (TomeID==TomeStat[i][1]){
			var tTxt = "";
			if (TomeStat[i][2] != ""){
				var tmp;
				var CBonus = TomeStat[i][2];
				if (CBonus.indexOf("*") != -1){
					CBonus = CBonus.split('*');
					for(var i=0;i<CBonus.length;i++){
						tmp = CBonus[i].split(',')
						tTxt=tTxt+GetAddonString(parseInt(tmp[0], 10), tmp[1])+"<br>";
					}			
				}else{
					CBonus = CBonus.split(',');
					tTxt = GetAddonString(parseInt(CBonus[0], 10), CBonus[1])+"<br>";			
				}
			}
			return tTxt;
		}
	}	
	return "Unknown Tome<br>We cannot get info";
}

function GetCardInfo(CardID, sct, cGrade,cLvR , cLv, MaxLv, cExp, cLead, cRebirth){
	var CData = CardStat[CardID];
	var SelO = document.getElementById('Inp_C_Clss');
	var SlCl = parseInt(SelO.options[SelO.selectedIndex].value,10);
	var MExp = 0;
	var LExp = 0;
	document.getElementById('itmName').innerHTML = "<font color='"+CardCol[cGrade]+"'>"+CData[0]+"</font>";
	var BaseDesc = "Type: "+CardType[sct]+"<br>";
	var output = "";
	if (cLv != MaxLv){
		MExp = CardMExp[cGrade][cLv-1][0];
	}

	if ((sct == 0)||(sct == 1)){
		var dmg = Math.round((CData[2]+CData[3]*(cLv-1))*CardRol[SlCl][sct]*CardRBn[cRebirth]);
		BaseDesc = BaseDesc + "Physical Attack +"+dmg+"<br>Magic Attack +"+dmg+"<br>";
	}else if (sct == 2){
		var par1 = Math.round((CData[2][0]+CData[3][0]*(cLv-1))*CardRol[SlCl][sct]*CardRBn[cRebirth]);
		var par2 = Math.round((CData[2][1]+CData[3][1]*(cLv-1))*CardRol[SlCl][sct]*CardRBn[cRebirth]);
		BaseDesc = BaseDesc + "HP +"+par1+"<br>Phys. Defense +"+par2+"<br>";
	}else if (sct == 3){
		var par1 = Math.round((CData[2][0]+CData[3][0]*(cLv-1))*CardRol[SlCl][sct]*CardRBn[cRebirth]);
		var par2 = Math.round((CData[2][1]+CData[3][1]*(cLv-1))*CardRol[SlCl][sct]*CardRBn[cRebirth]);
		BaseDesc = BaseDesc + "HP +"+par1+"<br>Metal Def. +"+par2+"<br>Wood Def. +"+par2+"<br>Water Def. +"+par2+"<br>Fire Def. +"+par2+"<br>Earth Def. +"+par2+"<br>";
	}else if ((sct == 4)||(sct == 5)){
		var spr = Math.round((CData[2]+CData[3]*(cLv-1))*CardRol[SlCl][sct]*CardRBn[cRebirth]);
		BaseDesc = BaseDesc + "Spirit +"+spr+"<br>";
	}

	
	if (cLv > 1){
		LExp = CardMExp[cGrade][cLv-1][1];
	}
	var DevXP = (CardDev[cGrade]+cExp+LExp)+(cRebirth*(CardMExp[cGrade][MaxLv-1][1]));

	BaseDesc = BaseDesc + "Level: "+cLv+"/"+MaxLv+ "<br>Exp: "+cExp+"/"+MExp+"<br>Class: " + ClassIdToClassName(SlCl) + "<br>Level Req.: " + cLvR + "<br>Leadship Req.: " + cLead + "<br>";
	BaseDesc = BaseDesc + "If devoured: "+DevXP+ " EXP";
	output = BaseDesc+"<br>";
	var CBonDiv = document.getElementById("CardBonus");
	
	var CbonTxt = "";
	if (CData[5] != ""){
		var CBonus = CData[5];
		if (CBonus.indexOf("*") != -1){
			CBonus = CBonus.split('*');
			for(var i=0;i<CBonus.length;i++){
				tmp = CBonus[i].split(',')
				CbonTxt=CbonTxt+GetAddonString(parseInt(tmp[0], 10), tmp[1])+"<br>";
			}			
		}else{
			CBonus = CBonus.split(',');
			CbonTxt = GetAddonString(parseInt(CBonus[0], 10), CBonus[1])+"<br>";			
		}
	}	
	
	output = output + "<font color='#77f'>"+CbonTxt+"</font>";
	var CSetTxt = "";
	if (CData[6] != 0){
		var CSetId = CData[6];
		var CSet = CardSet[CSetId];
		var CInSet = CSet[2].length;
		var CSetTxt = "<br><font color='#eebb00'>Set Bonus: "+(CSet[1]*100)+"%</font><br><font color='yellow'>"+CSet[0]+" (1/"+(CInSet)+")</font><br>";
		for(var i=0;i<CInSet;i++){
			if (CardID == CSet[2][i]){
				CSetTxt += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='#ccff00'>"+CardStat[CSet[2][i]][0]+"</font><br>";
			}else{
				CSetTxt += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='#7777ff'>"+CardStat[CSet[2][i]][0]+"</font><br>";
			}
		}
	}

	output = output + CSetTxt;
	return output;
}

function GetElfUsedPoint(estr, eagi, eint, econ){
	var usdpnt=0;
	if (estr > 40){
		usdpnt=usdpnt+(estr-40)*2+40;
	}else{
		usdpnt=usdpnt+estr;
	}
	if (eagi > 40){
		usdpnt=usdpnt+(eagi-40)*2+40;
	}else{
		usdpnt=usdpnt+eagi;
	}
	if (eint > 40){
		usdpnt=usdpnt+(eint-40)*2+40;
	}else{
		usdpnt=usdpnt+eint;
	}
	if (econ > 40){
		usdpnt=usdpnt+(econ-40)*2+40;
	}else{
		usdpnt=usdpnt+econ;
	}
	return usdpnt;
}

function GetRuneItemData(RuneID){
	for(var i=0;i<RuneItems.length;i++){
		if (RuneItems[i][0]==RuneID){
			return RuneItems[i];
		}
	}
	return null;
}

var TimerHolder = setInterval(myTimer, 10000);
function myTimer() {
	if (PITCA !== false){
		var myArr;
		var bubb;
		for(var i=1;i<=TmdII;i++){
			bubb = document.getElementById("iconId"+TmdItm[i]);
			myArr=ShopItm[TmdItm[i]].split("#");
			if ((LockItem !== false) &&(LastId == TmdItm[i])){LockItem = false;CreateBubble(0);}
			if (ShopItemTimeVerification(myArr[19], 0) !== false){
				bubb.style.display = "inline-block";
			}else{
				bubb.style.display = "none";
			}
		}
	}
}

function roleGoldHandler(userData){
	var userD=JSON.parse(userData);
	if (userD[0]["error"]!=""){
		alert(userD[0]["error"]);
	}else{
		var gold=parseInt(userD[0]["gold"],10)||0;
		RMoney = gold;
		document.getElementById('Role_Gold').innerHTML = gold;
	}	
}

function MailSenderHandler(userData){
	var userD=JSON.parse(userData);
	if (userD[0]["error"]!=""){
		alert(userD[0]["error"]);
	}else{
		if (userD[0]["success"]!=""){
			alert(userD[0]["success"]);
			if (userD[0]["gold"]!=""){
				RMoney=userD[0]["gold"];
				document.getElementById('Role_Gold').innerHTML = RMoney;
			}
			if (userD[0]["point"]!=""){
				UPoint=userD[0]["point"];
				document.getElementById('User_Point').innerHTML = UPoint;
			}	
			document.getElementById('BuyWindow').style.display='none';
			LockItem=false;	
		}
	}	
	SelectIcon(0);
}

function BuyItemWithAjax(typ, obj) {
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
					roleGoldHandler(JSON.stringify(fdbck));
				}else{
					alert("Cannot load user data!");
				}
			}else if(typ==2){
				if (fdbck != ""){
					MailSenderHandler(JSON.stringify(fdbck));
				}else{
					alert("Cannot load user list!");
				}
			}
        }
    };
	if (typ==1){
		xmlhttp.open("POST", "../php/loadrolegold.php", false);
	}else if (typ==2){
		xmlhttp.open("POST", "../php/buyitem.php", false);
	}
	xmlhttp.setRequestHeader("Content-type", "application/json");	
	
	var myJSON = JSON.stringify(obj);
    xmlhttp.send(myJSON);
	return false;
}
