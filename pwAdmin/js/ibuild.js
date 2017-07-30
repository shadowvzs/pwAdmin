var TCS = 0; //this is the difference betwee client (=your browser) snd server (where is the script executed) timezone 
var SrvrTmZone=0;
var maxMask = 0;
var WData = [];
var AData = [];
var JData = [];
var EData = [];
var FData = [];
var SIData = [];
var SIInd = 0;
var WSIData = [];
var WSIInd = 0;
var SendReq = [];
var SendMax = 0;
var SendInd = 0;
var CIType = "W";	//main type
var SIType = 1;
var AddInd = 0;
var Addons = [];
var ESInd = 0;
var ESkill = [];
var PSInd = 0;
var PSkill = [];
var SckInd = 0;
var SckAddons = [];
var SckSel = 1;
var MailReady = true;
var LastOctet = "";
var LastItemList = "";
var itmCol = [];
var TempItemData;

function GenerateClassMask(){
	var result = 0;
	var chckboxId = "";
	var chckbox;
	var resBox = document.getElementById('ClassMask');
	var resBox2;
	if ((CIType == "W")||(CIType == "A")||(CIType == "J")){
		resBox2 = document.getElementById('Inp_'+CIType+'_Class');
	}else if (CIType == "O"){
		if (SIType == 2) {
			resBox2 = document.getElementById('Inp_P_Class2');
		}else if (SIType == 3) {
			resBox2 = document.getElementById('Inp_B_Class');
		}else if (SIType == 6) {
			resBox2 = document.getElementById('Inp_M_Class');
		}
	}
	
	for (var i = 1; i <= 12; i++) { 
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
	if (resBox2 != null){
		resBox2.value = resBox.value;
	}
}

function GenerateProcType(){
	var result = 0;
	var chckboxId = "";
	var chckbox;
	var max=17;
	var resBox = document.getElementById('Sel_ProcType2');
	for (var i = 0; i <= max; i++) { 
		chckboxId = "ProcTyp"+i;
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

function ArrangeProcCheckboxs(){
	var max=17;
	var chckbox;
	var chckboxId = "";
	var x=0;
	var nr=parseInt(document.getElementById('Sel_ProcType2').value, 10)||0;
	for (var i = 0; i <= max; i++) { 
		chckboxId = "ProcTyp"+(17-i);
		chckbox = document.getElementById(chckboxId);
		if (chckbox){		
			x=parseInt(chckbox.value, 10)||0;
			if (nr >=x){
				chckbox.checked=true;
				nr=nr-x;
			}else{
				chckbox.checked=false;
			}
			
		}
	}
}

function RefreshAddonList(){
	if (CIType == "O"){
		if (SIType == 3){
			var div = document.getElementById('AddonListDivB');
		}else if (SIType == 6){
			var div = document.getElementById('AddonListDivM');		
		}
	}else{
		var div = document.getElementById('AddonListDiv'+CIType);
	}
	var lnbrk="";
	var ADataArr = [];
	div.innerHTML = "";
	for (i = 1; i <= AddInd; i++) {
		if (i>1){
			lnbrk ="<br>";
		}
		ADataArr = Addons[i].split("#");
		div.innerHTML = div.innerHTML+lnbrk+"&nbsp;&nbsp;&nbsp;&nbsp;"+ADataArr[1]+"&nbsp;<span style='position: absolute; right:10px;'><a href='javascript:void(0);'  onclick='RemoveAddonFromList("+i+");'><button> X </button></a></span>";
	}
}

function RefreshElfSkillList(){
	var div = document.getElementById('LearnedElfSkills');
	var lnbrk="";
	var ADataArr = [];
	div.innerHTML = "";
	
	document.getElementById('E_LSkills').innerHTML = ESInd;
	for (i = 1; i <= ESInd; i++) {
		if (i>1){
			lnbrk ="<br>";
		}
		ADataArr = ESkill[i].split("#");
		div.innerHTML = div.innerHTML+lnbrk+"<a title='"+ADataArr[4]+" (lv. "+ADataArr[0]+"): "+ADataArr[5]+"'><b>"+ADataArr[4]+" [Lv. "+ADataArr[0]+"]</b></a> "+" &nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);'  onclick='RemoveElfSkill("+i+");'><button> X </button></a></span>";
	}
}

function RefreshPetSkillList(){
	var div = document.getElementById('LearnedPetSkills');
	var lnbrk="";
	var ADataArr = [];
	div.innerHTML = "";
	for (i = 1; i <= PSInd; i++) {
		if (i>1){
			lnbrk ="<br>";
		}
		ADataArr = PSkill[i].split("#");
		div.innerHTML = div.innerHTML+lnbrk+"<a title='"+ADataArr[2]+" (lv. "+ADataArr[0]+"): "+ADataArr[5]+"'><b>"+ADataArr[2]+" [Lv. "+ADataArr[0]+"]</b></a> "+" &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);'  onclick='RemovePetSkill("+i+");'><button> X </button></a>";
	}
}

function RefreshSckAddonList(){
	if (CIType == "O"){
		var div = document.getElementById('SocketBSack');
	}else{
		var div = document.getElementById('Socket'+CIType+'Sack');
	}
	var lnbrk="";
	var ADataArr = [];
	div.innerHTML = "";
		
	for (i = 1; i <= SckInd; i++) {
		if (i>1){
			lnbrk ="<br>";
		}
		ADataArr = SckAddons[i].split("#");
		div.innerHTML = div.innerHTML+lnbrk+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>"+ADataArr[4]+" [gr. "+ADataArr[5]+"]</b> "+ADataArr[3]+" &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);'  onclick='RemoveSckAddonFromList("+i+");'><button> X </button></a> ";
	}
}

function AddNewAddon (){
	var RuneDur;
	var AType;
	var ats;
	var RTH;
	var RTxt;
	if (CIType == "O"){
		if (SIType == 3){
			var div1 = document.getElementById('Inp_B_AddonType');
			var div2 = document.getElementById('Inp_B_AddonAmount');	
		}else if(SIType == 6){
			var div1 = document.getElementById('Inp_M_AddonType');
			var div2 = document.getElementById('Inp_M_AddonAmount');	
		}		
	}else{
		var div1 = document.getElementById('Inp_'+CIType+'_AddonType');
		var div2 = document.getElementById('Inp_'+CIType+'_AddonAmount');		
	}
	var Adata = div1.value;
	var AAmount = div2.value;
	var myArr = Adata.split("#");
	var skillId = parseInt(myArr[0],10);
	if (isRune(skillId)!==false){
		RuneDur=GetInpRuneTime();
		if (RuneDur< 1){RuneDur=1;}
		RTH=DectoRevHex(RuneDur,8,0);
		RTxt="[Rune] ";
		AType=1;
		ats=4;
	}else{
		RTH="";
		RTxt="";
		RuneDur=0;
		AType=0;
		ats=2;
	}
	if ((AAmount>0)||(myArr[2] == "S")){
		if (AddInd < 12){
			AddInd++;
			if (myArr[2] == "H"){
				AAmount = parseInt(AAmount);
				myArr[0]=DectoRevHex(skillId,8,ats)+DectoRevHex(AAmount,8,0);
			}else if(myArr[2] == "S"){
				var specA = [];
				var specA = myArr[0].split(" ");
				myArr[0] = "";
				for (i = 0; i < specA.length; i++) {
					if (i==0){
						myArr[0] = myArr[0]+DectoRevHex(specA[i],8,4)
					}else{
						myArr[0] = myArr[0]+DectoRevHex(specA[i],8,0)
					}
				}
			}else if (myArr[2] = "F"){
				if (skillId == 300){
					myArr[0]=DectoRevHex(skillId,8,ats)+numToFloat32Hex(AAmount/100, true);
				}else{
					myArr[0]=DectoRevHex(skillId,8,ats)+numToFloat32Hex(AAmount, true);
				}
			}
			var aId = parseInt(myArr[1],10);
			if (myArr[2]!= "S"){
				if (myArr[2] == "H"){
					var AAAmount=parseInt(AAmount,10);
				}else if(myArr[2] == "F"){
					var AAAmount=AAmount;
				}
				if (skillId == 300){
					AAAmount=parseInt(AAmount,10)*0.01;
					}
				myArr[1]=StatName[aId];
				if (myArr[1]!=null){
					myArr[1]=GetAddonString(aId, AAAmount);
				}else{
					myArr[1]="Addon["+aId+"]: "+AAAmount;
				}
			}
			Addons[AddInd] = myArr[0]+RTH+"#"+RTxt+myArr[1]+"#"+myArr[2]+"#"+myArr[3]+"#"+AAmount+"#"+AType+"#"+RuneDur;
			RefreshAddonList();
		}else{
			alert("Too much addon...");
		}
	}else{
		alert("Write how much bonus you want!");
	}
}

function GetInpRuneTime(){
	var RTS = document.getElementById('Rune_Size');
	var RTX = RTS.options[RTS.selectedIndex].value;	
	var RT = parseInt(document.getElementById('Inp_Rune_Time').value,10)||0;
	return RT*RTX;	
}

function AddNewElfSkill(){
	var sklS = document.getElementById('Inp_E_SkillData');
	var SkillSel = sklS.options[sklS.selectedIndex].value;	
	var sklS = document.getElementById('Inp_E_LSkillLv');
	var SkillLvSel = sklS.options[sklS.selectedIndex].value;	
	var lvl = parseInt(document.getElementById("Inp_E_Lv").value, 10);
	var LPoint = parseInt(document.getElementById("Inp_E_LuckPoint").value, 10);	
	var MaxSkill = GetMaxSkill(LPoint);
	var skillId = "";
	var SkillName = "";
	if (ESInd >= MaxSkill){
		alert('Cannot learn more skill, it is already '+ESInd+"/"+MaxSkill+"!");
	}else{
		var myArr = SkillSel.split("#");
		var SkillName = myArr[3];
		var SkillLevel = myArr[1];
		if (SkillLevel==0){
			SkillLvSel=1;
		}
		var SkillTReq = myArr[2];
		skillId = myArr[0];
		var elfTalent = [];
		var TalentName = [];
		TalentName[0] = "Metal";
		TalentName[1] = "Wood";
		TalentName[2] = "Water";
		TalentName[3] = "Fire";
		TalentName[4] = "Earth";
		
		var errCount = 0;
		var errorMsg = "Those talents needed: ";
		for (i = 0; i < 5; i++) {
			elfTalent[i] = document.getElementById("Inp_E_"+TalentName[i]);
			if (parseInt(elfTalent[i].value, 10) < parseInt(SkillTReq[i], 10)){
				if ((parseInt(SkillTReq[i]), 10) > 0){
					errCount++;
					errorMsg = errorMsg + SkillTReq[i] +" "+TalentName[i]+", ";
				}
			}
		}
		
		if (errCount > 0){
			errorMsg = errorMsg.substr(0, (errorMsg.length-2))+"!";
			alert(errorMsg);
		}else{
			var sklExist = 0;
			for (i = 1; i <= ESInd; i++) {
				myArr = ESkill[i].split("#");
				if (myArr[1] == skillId){sklExist++;}
			}
			if (sklExist == 0){
				ESInd++;
				ESkill[ESInd] = SkillLvSel+"#"+SkillSel;
			}else{
				alert(SkillName+" already learned!");
			}
		}
	}
	RefreshElfSkillList();
}

function AddNewPetSkill(){
	var sklS = document.getElementById('Inp_P_SkillData');
	var SkillSel = sklS.options[sklS.selectedIndex].value;	
	var SkillLvSel = document.getElementById('Inp_P_LSkillLv').value;
	var MaxSkill = 4;
	var MaxSkillLv = 1;
	var skillId = 0;
	var SkillName = "";

	if (PSInd < MaxSkill){
		var myArr = SkillSel.split("#");
		SkillName = myArr[1];
		skillId = myArr[0];
		MaxSkillLv = parseInt(myArr[2], 10);
		if (SkillLvSel > MaxSkillLv){
			alert("Max skill level to this skill is Level: "+MaxSkillLv+"!");
		}else{
			var sklExist = 0;
			for (i = 1; i <= PSInd; i++) {
				myArr = PSkill[i].split("#");
				if (myArr[1] == skillId){sklExist++;}
			}
			if (sklExist == 0){
				PSInd++;
				PSkill[PSInd] = SkillLvSel+"#"+SkillSel;
			}else{
				alert(SkillName+" already learned!");
			}			
		}
	}else{
		alert("Cannot learn more than 4 skill per pet!");
	}
	RefreshPetSkillList();
}

function AddNewSckAddon (){
	var div0 = document.getElementById('Inp_StoneId'); 
	var div1 = document.getElementById('Inp_StoneAddon'); 
	var div2 = document.getElementById('Inp_StoneValue'); 
	var SckId = parseInt(div0.value);
	var SckAdd = parseInt(div1.value);
	var SckVal = parseInt(div2.value);
	var sSc = document.getElementById('StoneType'+SckSel);
	var SckBigData = sSc.options[sSc.selectedIndex].value;	
	var myArr = SckBigData.split("#");
	if (CIType == "O"){
		if (SIType == 3){
			var sSc = document.getElementById('Inp_B_Socket');
		}
	}else{
		var sSc = document.getElementById('Inp_'+CIType+'_Socket');
	}
	var maxStone = sSc.options[sSc.selectedIndex].value;

	var StoneName = myArr[5];
	var StoneGrade = myArr[6];
	var AddonTxt ="";
	if ((SckVal>0)&&(SckAdd > 0)&&(SckId > 0)){
		if (SckInd < maxStone){
			SckInd++;
			if (CIType == "W"){
				AddonTxt = myArr[3];
			}else if ((CIType == "A")||((CIType == "O")&&(SIType == 3))){
				AddonTxt = myArr[4];
			}

			var StnData = AddonTxt.split(',');
			AddonTxt=GetAddonString(parseInt(StnData[0], 10), StnData[1]);
			if (StoneName.indexOf("%") != -1){
				StoneName = StoneName.split("%").join(" ");
			}			

			SckAddons[SckInd] = SckId+"#"+SckAdd+"#"+SckVal+"#"+AddonTxt+"#"+StoneName+"#"+StoneGrade;
			RefreshSckAddonList();
		}else{
			if (maxStone > 0){
				alert("You can insert more stone, all "+maxStone+" socket is filled...");
			}else{
				alert("Change item socket to atleast 1 socket...");
			}
		}
	}else{
		alert("Write how much bonus you want!");
	}
}

function RemoveAddonFromList(ind){
	if ((ind > -1) && (ind <= Addons.length)){
		Addons.splice( ind, 1 );
		AddInd--;
		RefreshAddonList();
	}
}

function RemoveElfSkill(ind){
	if ((ind > -1) && (ind <= ESkill.length)){
		ESkill.splice( ind, 1 );
		ESInd--;
		RefreshElfSkillList();
	}
}

function RemovePetSkill(ind){
	if ((ind > -1) && (ind <= PSkill.length)){
		PSkill.splice( ind, 1 );
		PSInd--;
		RefreshPetSkillList();
	}
}

function RemoveSckAddonFromList(ind){
	if ((ind > -1) && (ind <= SckAddons.length)){
		SckAddons.splice( ind, 1 );
		SckInd--;
		RefreshSckAddonList();
	}
}

function CalcStatReq(){
	var STyp = document.getElementById('Sel_Item_Type');
	var MTyp = STyp.options[STyp.selectedIndex].value;
	var div1 = document.getElementById('Inp_'+MTyp+'_STR');
	var div2 = document.getElementById('Inp_'+MTyp+'_AGI');
	var div3 = document.getElementById('Inp_'+MTyp+'_INT');
	var div4 = document.getElementById('Inp_'+MTyp+'_LvReq');
	var Lr = parseInt(div4.value);

	if (MTyp == "W"){
		STyp = document.getElementById('Sel_WSub_Type');
		var STyp = STyp.options[STyp.selectedIndex].value;
		if (Lr > 1){
			if ((STyp > 0)&&(STyp < 5)){
				div1.value = parseInt(Lr*3)+2;
				div2.value = parseInt(Lr/2)+4;
				div3.value = "0";
			}else if ((STyp > 4)&&(STyp < 9)){
				div1.value = parseInt(Lr*2.5);
				div2.value = parseInt(Lr*1.08)+6;
				div3.value = "0";
			}else if ((STyp > 8)&&(STyp < 13)){
				div1.value = parseInt(Lr*2)+3;
				div2.value = parseInt(Lr*1.5)+3;
				div3.value = "0";	
			}else if ((STyp == 13)||(STyp == 14)){
				div1.value = parseInt(Lr*1.6);
				div2.value = parseInt(Lr*2);
				div3.value = "0";	
			}else if ((STyp == 15)||(STyp == 16)||(STyp == 17)||(STyp == 22)||(STyp == 24)){
				div1.value = parseInt(Lr/2)+4;
				div2.value = parseInt(Lr*3)+2;
				div3.value = "0";
			}else if ((STyp == 18)||(STyp == 19)||(STyp == 20)){
				div1.value = parseInt(Lr/2)+4;
				div2.value = "0";
				div3.value = parseInt(Lr*3);
			}else if (STyp == 21){
				div1.value = parseInt(Lr/2)+6;
				div2.value = "0";
				div3.value = parseInt(Lr*2.8);					
			}else if ((STyp == 23)||(STyp == 25)){
				div1.value = parseInt(Lr*0.44);
				div2.value = "0";
				div3.value = parseInt(Lr*3)+4;
			}
		}else{
			if ((STyp > 17) && (STyp < 22)||(STyp==23)||(STyp==25)){
				div1.value = "5";
				div2.value = "0";
				div3.value = "5";				
			}else{
				div1.value = "5";
				div2.value = "5";
				div3.value = "0";
			}
		}
	}
}

function ChangeMaskType(){
	var SelM = document.getElementById('Sel_Mask1');
	var IMas = SelM.options[SelM.selectedIndex].value;
	document.getElementById('Sel_Mask2').value = IMas;
}

function ChangeOtherType(){
	document.getElementById('Sel_Count').value=1;
	var SOST = document.getElementById('Sel_OSub_Type');
	var SelM = document.getElementById('Sel_Mask1');
	var OST = SOST.options[SOST.selectedIndex].value;
	ChangeItemList(CIType, SIType, "O", OST);
	document.getElementById('FlyerInputDiv').style.display="none";
	document.getElementById('ElfInputDiv').style.display="none";
	document.getElementById('HieroInputDiv').style.display="none";
	document.getElementById('PetEggInputDiv').style.display="none";
	document.getElementById('BBoxInputDiv').style.display="none";
	document.getElementById('PotionInputDiv').style.display="none";
	document.getElementById('TaskDiceInputDiv').style.display="none";
	document.getElementById('socketStonesDiv').style.display='none';
	document.getElementById('GrassInputDiv').style.display="none";
	document.getElementById('StoneInputDiv').style.display="none";
	document.getElementById('MoraiOrderDiv').style.display="none";
	document.getElementById('StarChartDiv').style.display="none";
	document.getElementById("MiscQStack").style.display="none";
	document.getElementById("Sel_ProcType1").selectedIndex=0;
	document.getElementById("Sel_ProcType2").value=0;
	document.getElementById('AmmoInputDiv').style.display='none';
	SIType = OST;
	AddInd = 0;
	if (OST == 1){
		SelM.selectedIndex = 12;
		document.getElementById('FlyerInputDiv').style.display="block";
		document.getElementById("Sel_ProcType1").selectedIndex=2;
		document.getElementById("Sel_ProcType2").value=8;
		document.getElementById('Sel_MaxCount').value = 1;
	}else if (OST == 2){
		document.getElementById('PetEggInputDiv').style.display="block";
		SelM.selectedIndex = 12;
		document.getElementById('Sel_MaxCount').value = 1;
	}else if (OST == 3){
		document.getElementById('AddonListDivB').innerHTML='';
		document.getElementById('socketStonesDiv').style.display='block';
		document.getElementById('BBoxInputDiv').style.display="block";
		SelM.selectedIndex = 22;
		document.getElementById('Sel_MaxCount').value = 1;
		StoneSelection(1);
	}else if (OST == 4){
		SelM.selectedIndex = 23;
		document.getElementById('ElfInputDiv').style.display="block";
		document.getElementById("Sel_ProcType2").value=23;
		document.getElementById('Sel_MaxCount').value = 1;
	}else if (OST == 5){
		document.getElementById('HieroInputDiv').style.display="block";
		SelM.selectedIndex = 19;
	}else if (OST == 6){
		document.getElementById('AddonListDivM').innerHTML='';
		document.getElementById('AmmoInputDiv').style.display='block';
		SelM.selectedIndex = 21;
	}else if (OST == 7){
		document.getElementById('PotionInputDiv').style.display="block";
		SelM.selectedIndex = 0;
	}else if (OST == 8){
		document.getElementById('TaskDiceInputDiv').style.display="block";	
		SelM.selectedIndex = 0;		
	}else if (OST == 9){
		SelM.selectedIndex = 0;	
		document.getElementById('GrassInputDiv').style.display="block";
	}else if (OST == 10){
		SelM.selectedIndex = 0;	
		document.getElementById('StoneInputDiv').style.display="block";		
	}else if (OST == 11){
		SelM.selectedIndex = 26;	
		document.getElementById('MoraiOrderDiv').style.display="block";			
	}else if (OST == 12){
		SelM.selectedIndex = 29;	
		document.getElementById('StarChartDiv').style.display="block";			
	}
	ChangeMaskType();
	getPItemData(document.getElementById('SItmC4S'+OST));
}

function ChangeUtilType(){
	var SelM = document.getElementById('Sel_Mask1');
	var SOST = document.getElementById('Sel_USub_Type');
	var OST = SOST.options[SOST.selectedIndex].value;
	ChangeItemList(CIType, SIType, "U", OST);
	SIType = OST;
	getPItemData(document.getElementById('SItmC5S'+OST));
	if (SIType == 1){
		SelM.selectedIndex = 11;
	}else if (SIType == 4){
		SelM.selectedIndex = 17;		
	}else{
		SelM.selectedIndex = 0;
	}
	ChangeMaskType();
	document.getElementById('Sel_Count').value=1;
}

function ChangeMatType(){
	document.getElementById('Sel_Count').value=1;
	var SOST = document.getElementById('Sel_MSub_Type');
	var OST = SOST.options[SOST.selectedIndex].value;
	ChangeItemList(CIType, SIType, "M", OST);
	SIType = OST;
	getPItemData(document.getElementById('SItmC6S'+OST));	
}

function ChangeCardType(){
	document.getElementById('Sel_Count').value=1;
	var SOST = document.getElementById('Sel_CSub_Type');
	var OST = SOST.options[SOST.selectedIndex].value;
	ChangeItemList(CIType, SIType, "C", OST);
	SIType = OST;
	getPItemData(document.getElementById('SItmC8S'+OST));
	document.getElementById('Sel_Mask1').selectedIndex = 28;
	document.getElementById('Sel_Mask2').value = document.getElementById('Sel_Mask1').options[28].value;
}

function ChangeFashType(){
	document.getElementById('Sel_Count').value=1;
	var SOST = document.getElementById('Sel_FSub_Type');
	var OST = SOST.options[SOST.selectedIndex].value;
	ChangeItemList(CIType, SIType, "F", OST);
	SIType = OST;
	getPItemData(document.getElementById('SItmC7S'+OST));
	var fTyp=0;
	if ((SIType == 1) || (SIType == 2)){
		fTyp = 13;
	}else if ((SIType == 3) || (SIType == 4)){
		fTyp = 14;
	}else if ((SIType == 5) || (SIType == 6)){
		fTyp = 15;
	}else if ((SIType == 7) || (SIType == 8)){
		fTyp = 16;
	}else if ((SIType == 9) || (SIType == 10)){
		fTyp = 25;
	}else if ((SIType == 11) || (SIType == 12)){
		fTyp = 26;		
	}
	document.getElementById('Sel_Mask1').selectedIndex = fTyp;
	document.getElementById('Sel_Mask2').value = document.getElementById('Sel_Mask1').options[fTyp].value;
}

function ChangeArmorType(){
	var SAST = document.getElementById('Sel_ASub_Type');
	var SelM = document.getElementById('Sel_Mask1');
	var AST = SAST.options[SAST.selectedIndex].value;
	ChangeItemList(CIType, SIType, CIType, AST);
	SIType = AST;
	if ((AST == 1)||(AST == 2)||(AST == 3)){
		SelM.selectedIndex = 2;
	}else if ((AST == 4)||(AST == 5)||(AST == 6)){
		SelM.selectedIndex = 3;
	}else if ((AST == 7)||(AST == 8)||(AST == 9)){
		SelM.selectedIndex = 4;
	}else if ((AST == 10)||(AST == 11)||(AST == 12)){
		SelM.selectedIndex = 5;
	}else if ((AST == 13)||(AST == 14)){
		SelM.selectedIndex = 6;
	}else if (AST == 15){
		SelM.selectedIndex = 7;
	}
	var inp1 = document.getElementById('Inp_A_HP');
	var inp2 = document.getElementById('Inp_A_MP');
	var inp3 = document.getElementById('Inp_A_Dodge');
	var inp4 = document.getElementById('Inp_A_PDef');
	var inp5 = document.getElementById('Inp_A_Metal');
	var inp6 = document.getElementById('Inp_A_Wood');
	var inp7 = document.getElementById('Inp_A_Water');
	var inp8 = document.getElementById('Inp_A_Fire');
	var inp9 = document.getElementById('Inp_A_Earth');	
	var inp10 = document.getElementById('Inp_A_STR');	
	var inp11 = document.getElementById('Inp_A_AGI');	
	var inp12 = document.getElementById('Inp_A_INT');	
	inp1.value = 0;
	inp2.value = 0;
	inp3.value = 0;
	inp4.value = 0;
	inp5.value = 0;
	inp6.value = 0;
	inp7.value = 0;
	inp8.value = 0;
	inp9.value = 0;
	inp1.style.backgroundColor = "#fff";
	inp2.style.backgroundColor = "#fff";
	inp3.style.backgroundColor = "#fff";
	inp4.style.backgroundColor = "#fff";
	inp5.style.backgroundColor = "#fff";
	inp6.style.backgroundColor = "#fff";
	inp7.style.backgroundColor = "#fff";
	inp8.style.backgroundColor = "#fff";
	inp9.style.backgroundColor = "#fff";
	inp10.style.backgroundColor = "#fff";
	inp11.style.backgroundColor = "#fff";
	inp12.style.backgroundColor = "#fff";
	if ((AST == 1)||(AST == 4)||(AST == 7)||(AST == 10)){
		inp4.value = 15;
		inp5.value = 5;
		inp6.value = 5;
		inp7.value = 5;
		inp8.value = 5;
		inp9.value = 5;
		inp4.style.backgroundColor = "#ffc";
		inp5.style.backgroundColor = "#ffc";
		inp6.style.backgroundColor = "#ffc";
		inp7.style.backgroundColor = "#ffc";
		inp8.style.backgroundColor = "#ffc";
		inp9.style.backgroundColor = "#ffc";	
		inp10.style.backgroundColor = "#ffc";
		inp11.style.backgroundColor = "#ffc";
	}else if ((AST == 2)||(AST == 5)||(AST == 8)||(AST == 11)){
		inp4.value = 10;
		inp5.value = 9;
		inp6.value = 9;
		inp7.value = 9;
		inp8.value = 9;
		inp9.value = 9;
		inp4.style.backgroundColor = "#ffc";
		inp5.style.backgroundColor = "#ffc";
		inp6.style.backgroundColor = "#ffc";
		inp7.style.backgroundColor = "#ffc";
		inp8.style.backgroundColor = "#ffc";
		inp9.style.backgroundColor = "#ffc";	
		inp10.style.backgroundColor = "#ffc";
		inp11.style.backgroundColor = "#ffc";
	}else if ((AST == 3)||(AST == 6)||(AST == 9)||(AST == 12)){
		inp4.value = 7;
		inp5.value = 15;
		inp6.value = 15;
		inp7.value = 15;
		inp8.value = 15;
		inp9.value = 15;
		inp4.style.backgroundColor = "#ffc";
		inp5.style.backgroundColor = "#ffc";
		inp6.style.backgroundColor = "#ffc";
		inp7.style.backgroundColor = "#ffc";
		inp8.style.backgroundColor = "#ffc";
		inp9.style.backgroundColor = "#ffc";	
		inp10.style.backgroundColor = "#ffc";
		inp12.style.backgroundColor = "#ffc";
	}else if (AST == 13){
		inp1.value = 10;
		inp1.style.backgroundColor = "#ffc";
		inp10.style.backgroundColor = "#ffc";
	}else if (AST == 14){
		inp2.value = 10;
		inp2.style.backgroundColor = "#ffc";
		inp10.style.backgroundColor = "#ffc";
	}else if (AST == 15){
		inp3.value = 10;
		inp3.style.backgroundColor = "#ffc";	
	}	
	ChangeMaskType();
}

function ChangeJewelType(){
	var SJST = document.getElementById('Sel_JSub_Type');
	var SelM = document.getElementById('Sel_Mask1');
	var JST = SJST.options[SJST.selectedIndex].value;
	ChangeItemList(CIType, SIType, CIType, JST);
	SIType = JST;
	if ((JST == 1)||(JST == 2)||(JST == 3)){
		SelM.selectedIndex = 8;
	}else if ((JST == 4)||(JST == 5)||(JST == 6)){
		SelM.selectedIndex = 9;
	}else if ((JST == 7)||(JST == 8)){
		SelM.selectedIndex = 10;
	}else if (JST == 9){
		SelM.selectedIndex = 11;
	}
	var inp1 = document.getElementById('Inp_J_PAtt');
	var inp2 = document.getElementById('Inp_J_MAtt');
	var inp3 = document.getElementById('Inp_J_Dodge');
	var inp4 = document.getElementById('Inp_J_PDef');
	var inp5 = document.getElementById('Inp_J_Metal');
	var inp6 = document.getElementById('Inp_J_Wood');
	var inp7 = document.getElementById('Inp_J_Water');
	var inp8 = document.getElementById('Inp_J_Fire');
	var inp9 = document.getElementById('Inp_J_Earth');
	inp1.value = 0;
	inp2.value = 0;
	inp3.value = 0;
	inp4.value = 0;
	inp5.value = 0;
	inp6.value = 0;
	inp7.value = 0;
	inp8.value = 0;
	inp9.value = 0;
	inp1.style.backgroundColor = "#fff";
	inp2.style.backgroundColor = "#fff";
	inp3.style.backgroundColor = "#fff";
	inp4.style.backgroundColor = "#fff";
	inp5.style.backgroundColor = "#fff";
	inp6.style.backgroundColor = "#fff";
	inp7.style.backgroundColor = "#fff";
	inp8.style.backgroundColor = "#fff";
	inp9.style.backgroundColor = "#fff";
	if ((JST == 1)||(JST == 4)){
		inp4.style.backgroundColor = "#ffc";
		inp4.value = 6;
	}else if ((JST == 2)||(JST == 5)){
		inp3.value = 6;
		inp3.style.backgroundColor = "#ffc";
	}else if ((JST == 3)||(JST == 6)){
		inp5.value = 5;
		inp6.value = 5;
		inp7.value = 5;
		inp8.value = 5;
		inp9.value = 5;
		inp5.style.backgroundColor = "#ffc";
		inp6.style.backgroundColor = "#ffc";
		inp7.style.backgroundColor = "#ffc";
		inp8.style.backgroundColor = "#ffc";
		inp9.style.backgroundColor = "#ffc";
	}else if (JST == 7){
		inp1.value = 4;
		inp1.style.backgroundColor = "#ffc";
	}else if (JST == 8){
		inp2.value = 4;
		inp2.style.backgroundColor = "#ffc";
	}
	
	ChangeMaskType();
}

function ChangeProcType(){
	var SelPr = document.getElementById('Sel_ProcType1');
	var IProc = SelPr.options[SelPr.selectedIndex].value;
	document.getElementById('Sel_ProcType2').value = IProc;
}

function ChangeGrade(){
	var SelPr = document.getElementById('Inp_Grade1');
	var IProc = SelPr.options[SelPr.selectedIndex].value;
	document.getElementById('Inp_Grade2').value = IProc;
}
 
function StoneSelection(st){
	 SckSel = st;
	 document.getElementById('SelStone1').checked=false;
	 document.getElementById('SelStone2').checked=false;
	 document.getElementById('SelStone3').checked=false;
	 document.getElementById('SelStone'+st).checked=true; 
	 var sSc = document.getElementById('StoneType'+st);
	 var SckBigData = sSc.options[sSc.selectedIndex].value;
	 var SckData = [];
	 
	 SckData = SckBigData.split("#");
	 document.getElementById('Inp_StoneId').value = SckData[0];
	 var StnAddon ="";
	 if (CIType == "W"){
		document.getElementById('Inp_StoneAddon').value = SckData[1];
		StnAddon = SckData[3];
	 }else if ((CIType == "A")||((CIType == "O")&&(SIType==3))){
		document.getElementById('Inp_StoneAddon').value = SckData[2];
		StnAddon = SckData[4];
	}
	if (StnAddon != ""){
		var StnData = StnAddon.split(',');
		document.getElementById('Inp_StoneValue').value = StnData[1];
		document.getElementById('SockAddTxt').innerHTML = GetAddonString(parseInt(StnData[0], 10), StnData[1])+"<br>";	
	}else{
		document.getElementById('SockAddTxt').innerHTML = "";
	}
 }

function ChangeItemType(){
	var SWE = document.getElementById('Sel_Item_Type');
	var wst = SWE.options[SWE.selectedIndex].value;	
	document.getElementById('Sel_Count').value=1;
	document.getElementById("Sel_ProcType1").selectedIndex=0;
	document.getElementById("Sel_ProcType2").value=0;
	document.getElementById('WeaponInputDiv').style.display='none';
	document.getElementById('ArmorInputDiv').style.display='none';
	document.getElementById('JewelInputDiv').style.display='none';
	document.getElementById('ElfInputDiv').style.display='none';
	document.getElementById('AmmoInputDiv').style.display='none';
	document.getElementById('CardInputDiv').style.display='none';
	document.getElementById('FlyerInputDiv').style.display='none';
	document.getElementById('HieroInputDiv').style.display="none";
	document.getElementById('PetEggInputDiv').style.display="none";
	document.getElementById('BBoxInputDiv').style.display="none";
	document.getElementById('PotionInputDiv').style.display="none";
	document.getElementById('TaskDiceInputDiv').style.display="none";
	document.getElementById('GrassInputDiv').style.display="none";
	document.getElementById('StoneInputDiv').style.display="none";
	document.getElementById('MoraiOrderDiv').style.display="none";
	document.getElementById('StarChartDiv').style.display="none";	
	document.getElementById('MiscInputDiv').style.display='none';
	document.getElementById('FashInputDiv').style.display='none';
	document.getElementById('Sel_WSub_Type').style.display='none';
	document.getElementById('Sel_ASub_Type').style.display='none';
	document.getElementById('Sel_JSub_Type').style.display='none';
	document.getElementById('Sel_OSub_Type').style.display='none';
	document.getElementById('Sel_MSub_Type').style.display='none';
	document.getElementById('Sel_USub_Type').style.display='none';
	document.getElementById('Sel_FSub_Type').style.display='none';
	document.getElementById('Sel_CSub_Type').style.display='none';
	document.getElementById('RuneTimeDiv').style.display='none';
	document.getElementById("MiscQStack").style.display="none";
	document.getElementById('socketStonesDiv').style.display='none';
	document.getElementById("Inp_Grade").style.display='none';
	document.getElementById('Inp_StoneId').value = "0";
	document.getElementById('Inp_StoneAddon').value = "0";
	document.getElementById('Inp_StoneValue').value = "0";	
	document.getElementById('SockAddTxt').innerHTML = "";
	AddInd = 0;
	SckInd = 0;	
	document.getElementById('octet1').innerHTML = "";
	document.getElementById('Inp_Octet').value = "";
	ChangeItemList(CIType, SIType, wst, 1);
	if (wst == "W"){
		CalcStatReq();
		document.getElementById('WeaponInputDiv').style.display='inline';
		document.getElementById('Sel_WSub_Type').style.display='inline';
		document.getElementById('Sel_WSub_Type').selectedIndex=0;
		document.getElementById('socketStonesDiv').style.display='block';
		document.getElementById('Sel_Mask1').selectedIndex = 1;
		document.getElementById('Sel_Mask2').value = document.getElementById('Sel_Mask1').options[1].value;
		document.getElementById('SItmC1S1').selectedIndex = 1;
		document.getElementById('SItmC1S1').style.display = "inline-block";
		document.getElementById("Inp_Grade").style.display="inline";
		document.getElementById('Sel_WSub_Type').selectedIndex=1;
		document.getElementById('Sel_MaxCount').value = 1;
		SIType = 1;
	}else if(wst == "A"){
		CalcStatReq();
		document.getElementById('ArmorInputDiv').style.display='inline';
		document.getElementById('Sel_ASub_Type').style.display='inline';
		document.getElementById('Sel_ASub_Type').selectedIndex=0;
		document.getElementById('socketStonesDiv').style.display='block';
		document.getElementById('Sel_Mask1').selectedIndex = 2;
		document.getElementById('Sel_Mask2').value = document.getElementById('Sel_Mask1').options[2].value;
		document.getElementById('SItmC2S1').selectedIndex = 1;
		document.getElementById('SItmC2S1').style.display = "inline-block";
		document.getElementById("Inp_Grade").style.display="inline";
		document.getElementById('Sel_MaxCount').value = 1;
		SIType = 1;
	}else if(wst == "J"){
		document.getElementById('JewelInputDiv').style.display='inline';
		document.getElementById('Sel_JSub_Type').style.display='inline';
		document.getElementById('Sel_JSub_Type').selectedIndex=0;
		document.getElementById('Sel_Mask1').selectedIndex = 8;
		document.getElementById('Sel_Mask2').value = document.getElementById('Sel_Mask1').options[8].value;
		document.getElementById('SItmC3S1').selectedIndex = 1;
		document.getElementById('SItmC3S1').style.display = "inline-block";
		document.getElementById("Inp_Grade").style.display="inline";
		document.getElementById('Sel_MaxCount').value = 1;
		SIType = 1;
	}else if(wst == "O"){
		document.getElementById('FlyerInputDiv').style.display='inline';
		document.getElementById('Sel_OSub_Type').style.display='inline';
		document.getElementById('Sel_OSub_Type').selectedIndex=0;
		document.getElementById('Sel_Mask1').selectedIndex = 12;
		document.getElementById('Sel_Mask2').value = document.getElementById('Sel_Mask1').options[12].value;
		SIType = 1;
		document.getElementById("Sel_ProcType1").selectedIndex=2;
		document.getElementById("Sel_ProcType2").value=8;
	}else if(wst == "U"){
		document.getElementById('MiscInputDiv').style.display='inline';
		document.getElementById('Sel_USub_Type').style.display='inline';
		document.getElementById('Sel_USub_Type').selectedIndex=0;
		document.getElementById('Sel_Mask1').selectedIndex = 0;
		document.getElementById('Sel_Mask2').value = 0;
		document.getElementById('Sel_Mask1').selectedIndex = 11;
		document.getElementById('Sel_Mask2').value = document.getElementById('Sel_Mask1').options[11].value;
		SIType = 1;		
	}else if(wst == "M"){
		document.getElementById('MiscInputDiv').style.display='inline';
		document.getElementById('Sel_MSub_Type').style.display='inline';
		document.getElementById('Sel_MSub_Type').selectedIndex=0;
		document.getElementById('Sel_Mask1').selectedIndex = 0;
		document.getElementById('Sel_Mask2').value = 0;
		SIType = 1;
	}else if(wst == "F"){
		document.getElementById('FashInputDiv').style.display='inline';
		document.getElementById('Sel_FSub_Type').style.display='inline';
		document.getElementById('Sel_FSub_Type').selectedIndex=0;
		document.getElementById('Sel_Mask1').selectedIndex = 13;
		document.getElementById('Sel_Mask2').value = document.getElementById('Sel_Mask1').options[13].value;
		SIType = 1;
	}else if(wst == "C"){
		document.getElementById('CardInputDiv').style.display='inline';
		document.getElementById('Sel_CSub_Type').style.display='inline';
		document.getElementById('Sel_CSub_Type').selectedIndex=0;
		document.getElementById('Sel_Mask1').selectedIndex = 28;
		document.getElementById('Sel_Mask2').value = document.getElementById('Sel_Mask1').options[28].value;
		SIType = 1;
	}
	CIType = wst;
	if (wst == "W"){
		ChangeWeaponType();
		StoneSelection(SckSel);
	}else if (wst == "A"){
		StoneSelection(SckSel);
	}
}

function ChangeItemList(icat, isubc, newic, newisc){
	var mcat = 0;
	var isc = parseInt(isubc);
	var st;
	if (icat == "W"){
		mcat = 1;
	}else if (icat == "A"){
		mcat = 2;
	}else if (icat == "J"){		
		mcat = 3;
	}else if (icat == "O"){		
		mcat = 4;
	}else if (icat == "U"){		
		mcat = 5;
	}else if (icat == "M"){		
		mcat = 6;
	}else if (icat == "F"){		
		mcat = 7;
	}else if (icat == "C"){		
		mcat = 8;
	}
	if (mcat > 0){
		if (document.getElementById('SItmC'+mcat+'S'+isc) !== null){
			document.getElementById('SItmC'+mcat+'S'+isc).style.display = "none";
		}
	}
	mcat = 0;
	if (newic == "W"){
		mcat = 1;
	}else if (newic == "A"){
		mcat = 2;
	}else if (newic == "J"){		
		mcat = 3;
	}else if (newic == "O"){	
		mcat = 4;
	}else if (newic == "U"){		
		mcat = 5;
	}else if (newic == "M"){		
		mcat = 6;
	}else if (newic == "F"){		
		mcat = 7;
	}else if (newic == "C"){		
		mcat = 8;
	}
	if (mcat > 0){
		var tmp = 'SItmC'+mcat+'S'+newisc;
		if (document.getElementById(tmp) !== null){
			var elem = document.getElementById(tmp);
			elem.style.display = "inline-block";
			getPItemData(elem);
		}
	}
}

function ChangeWeaponType(){
	var SWE = document.getElementById('Sel_WSub_Type');
	var wst = SWE.options[SWE.selectedIndex].value;
	var d0 = document.getElementById('Inp_W_Type');
	var d1 = document.getElementById('Inp_W_AttRate1');
	var d2 = document.getElementById('Inp_W_Range1');
	var d3 = document.getElementById('Inp_W_MinRange1');
	var d4 = document.getElementById('Inp_W_Ranged');
	var d5 = document.getElementById('Inp_W_Ammo');
	var d6 = document.getElementById('Inp_W_PDmg1');
	var d7 = document.getElementById('Inp_W_PDmg2');
	var d8 = document.getElementById('Inp_W_MDmg1');
	var d9 = document.getElementById('Inp_W_MDmg2');
	var d10 = document.getElementById('Inp_W_Class');
	var d11 = document.getElementById('Inp_W_AGI');
	var d12 = document.getElementById('Inp_W_INT');
	d6.style.backgroundColor = "#fff";
	d7.style.backgroundColor = "#fff";
	d8.style.backgroundColor = "#fff";
	d9.style.backgroundColor = "#fff";
	d11.style.backgroundColor = "#fff";
	d12.style.backgroundColor = "#fff";
	
    ChangeItemList(CIType, SIType, CIType, wst);
	SIType = wst;
	if ((wst == 1)||(wst == 2)){
		d0.selectedIndex = 0;
		d1.selectedIndex = 0;
		d2.selectedIndex = 0;
		d3.selectedIndex = 0;
		d4.selectedIndex = 0;
		d5.selectedIndex = 0;
		d6.value = 2;
		d7.value = 10;
		d8.value = 0;
		d9.value = 0;		
		d10.value = maxMask;
	}else if ((wst == 3)||(wst == 4)){
		d0.selectedIndex = 0;
		d1.selectedIndex = 1;
		d2.selectedIndex = 0;
		d3.selectedIndex = 0;
		d4.selectedIndex = 0;
		d5.selectedIndex = 0;
		d6.value = 2;
		d7.value = 10;
		d8.value = 0;
		d9.value = 0;
		d10.value = maxMask;
	}else if ((wst == 5)||(wst == 6)){
		d0.selectedIndex = 1;
		d1.selectedIndex = 2;
		d2.selectedIndex = 1;
		d3.selectedIndex = 0;
		d4.selectedIndex = 0;
		d5.selectedIndex = 0;
		d6.value = 3;
		d7.value = 8;
		d8.value = 0;
		d9.value = 0;
		d10.value = maxMask;
	}else if ((wst == 7)||(wst == 8)){
		d0.selectedIndex = 1;
		d1.selectedIndex = 2;
		d2.selectedIndex = 2;
		d3.selectedIndex = 0;
		d4.selectedIndex = 0;
		d5.selectedIndex = 0;
		d6.value = 3;
		d7.value = 8;
		d8.value = 0;
		d9.value = 0;
		d10.value = maxMask;
	}else if ((wst == 9)||(wst == 10)){
		d0.selectedIndex = 2;
		d1.selectedIndex = 3;
		d2.selectedIndex = 3;
		d3.selectedIndex = 0;
		d4.selectedIndex = 0;
		d5.selectedIndex = 0;
		d6.value = 4;
		d7.value = 7;
		d8.value = 0;
		d9.value = 0;
		d10.value = maxMask;
	}else if ((wst == 11)||(wst == 12)){
		d0.selectedIndex = 2;
		d1.selectedIndex = 4;
		d2.selectedIndex = 3;
		d3.selectedIndex = 0;
		d4.selectedIndex = 0;
		d5.selectedIndex = 0;
		d6.value = 4;
		d7.value = 7;
		d8.value = 0;
		d9.value = 0;
		d10.value = maxMask;
	}else if ((wst == 13)||(wst == 14)){
		d0.selectedIndex = 3;
		d1.selectedIndex = 5;
		d2.selectedIndex = 4;
		d3.selectedIndex = 0;
		d4.selectedIndex = 0;
		d5.selectedIndex = 0;
		d6.value = 5;
		d7.value = 6;
		d8.value = 0;
		d9.value = 0;
		d10.value = maxMask;
	}else if (wst == 15){
		d0.selectedIndex = 4;
		d1.selectedIndex = 6;
		d2.selectedIndex = 5;
		d3.selectedIndex = 1;
		d4.selectedIndex = 1;
		d5.selectedIndex = 1;
		d6.value = 3;
		d7.value = 7;
		d8.value = 0;
		d9.value = 0;
		d10.value = maxMask;
	}else if (wst == 16){
		d0.selectedIndex = 4;
		d1.selectedIndex = 7;
		d2.selectedIndex = 5;
		d3.selectedIndex = 2;
		d4.selectedIndex = 1;
		d5.selectedIndex = 2;
		d6.value = 2;
		d7.value = 10;
		d8.value = 0;
		d9.value = 0;
		d10.value = 64;
	}else if (wst == 17){
		d0.selectedIndex = 4;
		d1.selectedIndex = 8;
		d2.selectedIndex = 5;
		d3.selectedIndex = 3;
		d4.selectedIndex = 1;
		d5.selectedIndex = 3;
		d6.value = 5;
		d7.value = 6;
		d8.value = 0;
		d9.value = 0;
		d10.value = 64;
	}else if (wst == 18){
		d0.selectedIndex = 5;
		d1.selectedIndex = 9;
		d2.selectedIndex = 6;
		d3.selectedIndex = 0;
		d4.selectedIndex = 0;
		d5.selectedIndex = 0;
		d6.value = 1;
		d7.value = 2;
		d8.value = 4;
		d9.value = 6;
		d10.value = maxMask;
	}else if (wst == 19){
		d0.selectedIndex = 5;
		d1.selectedIndex = 9;
		d2.selectedIndex = 6;
		d3.selectedIndex = 0;
		d4.selectedIndex = 0;
		d5.selectedIndex = 0;
		d6.value = 1;
		d7.value = 2;
		d8.value = 3;
		d9.value = 7;
		d10.value = maxMask;
	}else if (wst == 20){
		d0.selectedIndex = 5;
		d1.selectedIndex = 9;
		d2.selectedIndex = 6;
		d3.selectedIndex = 0;
		d4.selectedIndex = 0;
		d5.selectedIndex = 0;
		d6.value = 1;
		d7.value = 2;
		d8.value = 4;
		d9.value = 7;
		d10.value = maxMask;
	}else if (wst == 21){
		d0.selectedIndex = 5;
		d1.selectedIndex = 10;
		d2.selectedIndex = 6;
		d3.selectedIndex = 0;
		d4.selectedIndex = 0;
		d5.selectedIndex = 0;
		d6.value = 3;
		d7.value = 5;
		d8.value = 2;
		d9.value = 8;
		d10.value = maxMask;
	}else if (wst == 22){
		d0.selectedIndex = 6;
		d1.selectedIndex = 11;
		d2.selectedIndex = 7;
		d3.selectedIndex = 0;
		d4.selectedIndex = 2;
		d5.selectedIndex = 0;
		d6.value = 4;
		d7.value = 6;
		d8.value = 0;
		d9.value = 0;
		d10.value = 4;
	}else if (wst == 23){
		d0.selectedIndex = 7;
		d1.selectedIndex = 12;
		d2.selectedIndex = 8;
		d3.selectedIndex = 0;
		d4.selectedIndex = 0;
		d5.selectedIndex = 0;
		d6.value = 3;
		d7.value = 5;
		d8.value = 4;
		d9.value = 7;		
		d10.value = 32;
	}else if (wst == 24){
		d0.selectedIndex = 8;
		d1.selectedIndex = 13;
		d2.selectedIndex = 9;
		d3.selectedIndex = 0;
		d4.selectedIndex = 2;
		d5.selectedIndex = 0;
		d6.value = 3;
		d7.value = 7;
		d8.value = 0;
		d9.value = 0;
		d10.value = 1024;
	}else if (wst == 25){
		d0.selectedIndex = 9;
		d1.selectedIndex = 14;
		d2.selectedIndex = 10;
		d3.selectedIndex = 0;
		d4.selectedIndex = 0;
		d5.selectedIndex = 0;
		d6.value = 3;
		d7.value = 5;
		d8.value = 4;
		d9.value = 6;	
		d10.value = 2048;
	}
	if ((wst == 25)||(wst == 23)||((wst < 22)&&(wst > 17))){
		d8.style.backgroundColor = "#ffc";
		d9.style.backgroundColor = "#ffc";
		d12.style.backgroundColor = "#ffc";
	}else{
		d6.style.backgroundColor = "#ffc";
		d7.style.backgroundColor = "#ffc";	
		d11.style.backgroundColor = "#ffc";		
	}
	SCInp(99);
	CalcStatReq();
}

function SCInp(id){
	var d1, d2;
	if (id==1){
		d1 = document.getElementById('Inp_W_AttRate1');
		d2 = document.getElementById('Inp_W_AttRate2');
	}else if (id==2){
		d1 = document.getElementById('Inp_W_Range1');
		d2 = document.getElementById('Inp_W_Range2');
	}else if (id==3){
		d1 = document.getElementById('Inp_W_MinRange1');
		d2 = document.getElementById('Inp_W_MinRange2');
	}else if (id==99){
		d1 = document.getElementById('Inp_W_MinRange1');
		d2 = document.getElementById('Inp_W_MinRange2');
		d2.value = d1.options[d1.selectedIndex].value;	
		d1 = document.getElementById('Inp_W_Range1');
		d2 = document.getElementById('Inp_W_Range2');	
		d2.value = d1.options[d1.selectedIndex].value;
		d1 = document.getElementById('Inp_W_AttRate1');
		d2 = document.getElementById('Inp_W_AttRate2');		
	}
	d2.value = d1.options[d1.selectedIndex].value;
}

function ChangeElfLevel(){
	var lvl = parseInt(document.getElementById("Inp_E_Lv").value, 10);
	document.getElementById("Inp_E_Lv").value = lvl;
	if (lvl > 105) {lvl = 105;document.getElementById("Inp_E_Lv").value = "105";}
	var lpoint = document.getElementById("Inp_E_LuckPoint");
	var rndPoint = 0;
	var maxPoint = document.getElementById("MaxElfPoint");
	var TotalPoint = document.getElementById("TotalElfPoint");
	var MinLucky = 0;
	var MaxLucky = 0;
	var x = 0;
	var talent = document.getElementById("Inp_E_Talent");
	var talentp = 0;
	var elfStr = document.getElementById("Inp_E_STR");
	var elfAgi = document.getElementById("Inp_E_AGI");
	var elfInt = document.getElementById("Inp_E_INT");
	var elfCon = document.getElementById("Inp_E_CON");
	var Metal = document.getElementById("Inp_E_Metal");
	var Wood = document.getElementById("Inp_E_Wood");
	var Water = document.getElementById("Inp_E_Water");
	var Fire = document.getElementById("Inp_E_Fire");
	var Earth = document.getElementById("Inp_E_Earth");
	var MaxSkill = 4;

	if (lvl > 100){
		talentp = 20 + lvl - 100 + 1;
	}else{
		talentp = Math.floor(lvl / 5) + 1;
	}
	if (talentp > 26){
		talentp = 26;
	}
	var fTalent = talentp - parseInt(Metal.value, 10)+parseInt(Wood.value, 10)+parseInt(Water.value, 10)+parseInt(Fire.value, 10)+parseInt(Earth.value, 10);
	if (fTalent < 0){
		Metal.value = 0;
		Wood.value = 0;
		Water.value = 0;
		Fire.value = 0;
		Earth.value = 0;
		fTalent = talentp;
	}
	elfStr.value = 0;
	elfAgi.value = 0;
	elfCon.value = 0;
	elfInt.value = 0;
	talent.innerHTML = talentp;
	MinLucky = Math.floor(lvl / 10);
	MaxLucky = Math.floor(lvl / 10) * 10;
	rndPoint = getRndInt(MinLucky, MaxLucky);  
	lpoint.value = rndPoint;
	TotalPoint.innerHTML = rndPoint+lvl-1;
	maxPoint.innerHTML = MaxLucky;
	document.getElementById("RemainElfTalent").innerHTML = fTalent;
	document.getElementById("RemainElfPoint").innerHTML = rndPoint+lvl-1;	
	document.getElementById("E_MaxSkill").innerHTML = GetMaxSkill(rndPoint);
}

function GetMaxSkill(point){
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

function getRndInt(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min;
}

function ChangeElfPointTalent(){
	var elfLevel = parseInt(document.getElementById("Inp_E_Lv").value, 10) || 0;
	var elfMinPoint = 0;
	var elfMaxPoint = 0; 
	var tmp, tmp1;
	var elfTalentP;
	if (elfLevel > 100){
		elfTalentP = 20 + elfLevel - 100 + 1;
	}else{
		elfTalentP = Math.floor(elfLevel / 5) + 1;
	}
	
	tmp = Math.floor(elfLevel / 10);
	elfMinPoint = tmp;
	elfMaxPoint = tmp * 10;
	var elfLuckPoint = document.getElementById("Inp_E_LuckPoint");
	tmp = parseInt(elfLuckPoint.value, 10);
	elfLuckPoint.value = tmp;
	var elfLuckPointP = parseInt(elfLuckPoint.value, 10);
	if (elfLuckPointP > elfMaxPoint){
		elfLuckPoint.value = elfMaxPoint;
	}else if (elfLuckPointP < elfMinPoint){
		elfLuckPoint.value = elfMinPoint;
	}
	
	var elfTotalPoint = elfLuckPointP + elfLevel -1;
	document.getElementById("TotalElfPoint").innerHTML = elfTotalPoint;
	document.getElementById("E_MaxSkill").innerHTML = GetMaxSkill(elfTotalPoint);
	var elfStat = [];
	elfStat[1] = document.getElementById("Inp_E_STR");
	elfStat[2] = document.getElementById("Inp_E_AGI");
	elfStat[3] = document.getElementById("Inp_E_INT");
	elfStat[4] = document.getElementById("Inp_E_CON");
	tmp = 0;
	var statC;
	var statX;
	var pointL = elfTotalPoint;
	var statI;
	var ep;
	for (i = 1; i <= 4; i++) {
		ep = elfStat[i].value.replace(/^0+/, '');
		if (ep == ""){
			elfStat[i].value=0;
			ep = 0;
		}
		if (parseInt(elfStat[i].value, 10) > 0){
			elfStat[i].value = elfStat[i].value.replace(/^0+/, '');
		}
		
		elfStat[i].value = ep;
		statI = elfStat[i].value;
		if (statI > 40){
			statC = 40 + (statI-40) * 2;
			if (statC > pointL){
				statX = Math.floor(pointL/2);
				elfStat[i].value = statX;
				tmp = pointL - statX;
				pointL = pointL - statX;
			}else{
				elfStat[i].value = statI;
				pointL = pointL - statC;				
			}
		}else{
			if (statI > pointL){
				elfStat[i].value = pointL;
				pointL = 0;
			}else{
				elfStat[i].value = statI;
				pointL = pointL - statI;
			}
		}
		tmp = statI;
	}
	document.getElementById("RemainElfPoint").innerHTML = pointL;
	
	var elfTalent = [];
	elfTalent[1] = document.getElementById("Inp_E_Metal");
	elfTalent[2] = document.getElementById("Inp_E_Wood");
	elfTalent[3] = document.getElementById("Inp_E_Water");
	elfTalent[4] = document.getElementById("Inp_E_Fire");
	elfTalent[5] = document.getElementById("Inp_E_Earth");
	
	tmp = elfTalentP;
	for (i = 1; i <= 5; i++) {
		tmp1 = parseInt(elfTalent[i].value, 10); 
		if (tmp1 > 8){
			tmp1 = 8;
			elfTalent[i].value = tmp1;
		}else if (tmp1 < 0){
			tmp1 = 0;
			elfTalent[i].value = 0;		
		}else{
			elfTalent[i].value = tmp1;
		}
		
		if (tmp > 0 ){
			if ((tmp - tmp1) > 0){
				elfTalent[i].value = tmp1;
				tmp = tmp - tmp1;
			}else{
				elfTalent[i].value = tmp;
				tmp = 0;
			}
		}else{
			elfTalent[i].value = 0;
		}
	}
	document.getElementById("RemainElfTalent").innerHTML = tmp;
}

var intervalID = setInterval(function(){
	if (CIType == "W"){
		var fix = parseInt(document.getElementById('Inp_W_CurDur').value);
		var e;
		var wType = document.getElementById('Inp_W_Type').options[document.getElementById('Inp_W_Type').selectedIndex].value;
		if (fix < 1){
			document.getElementById('Inp_W_CurDur').value = 1;
		}else if(fix > 9999){
			document.getElementById('Inp_W_CurDur').value = 9999;
		}
		
		fix = parseInt(document.getElementById('Inp_W_MaxDur').value);
		if (fix < 1){
			document.getElementById('Inp_W_MaxDur').value = 1;
		}else if(fix > 9999){
			document.getElementById('Inp_W_MaxDur').value = 9999;
		}
		
		fix = parseInt(document.getElementById('Inp_W_LvReq').value);
		if(fix > 150){
			document.getElementById('Inp_W_LvReq').value = 150;
		}
		
		fix = parseInt(document.getElementById('Inp_W_STR').value);
		if(fix > 65535){
			document.getElementById('Inp_W_STR').value = 65535;
		}
		fix = parseInt(document.getElementById('Inp_W_AGI').value);
		if(fix > 65535){
			document.getElementById('Inp_W_AGI').value = 65535;
		}
		fix = parseInt(document.getElementById('Inp_W_CON').value);
		if(fix > 65535){
			document.getElementById('Inp_W_CON').value = 65535;
		}
		fix = parseInt(document.getElementById('Inp_W_INT').value);
		if(fix > 65535){
			document.getElementById('Inp_W_INT').value = 65535;
		}
		
		WData[1] = "<a title='Level requirement: "+parseInt(document.getElementById('Inp_W_LvReq').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_W_LvReq').value),4,0)+"</a>";
		WData[2] = "<a title='Class requirement: "+parseInt(document.getElementById('Inp_W_Class').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_W_Class').value),4,0)+"</a>";
		WData[3] = "<a title='Strength requirement: "+parseInt(document.getElementById('Inp_W_STR').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_W_STR').value),4,0)+"</a>";
		WData[4] = "<a title='Constitution requirement: "+parseInt(document.getElementById('Inp_W_CON').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_W_CON').value),4,0)+"</a>";
		WData[5] = "<a title='Agility requirement: "+parseInt(document.getElementById('Inp_W_AGI').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_W_AGI').value),4,0)+"</a>";
		WData[6] = "<a title='Intelligence requirement: "+parseInt(document.getElementById('Inp_W_INT').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_W_INT').value),4,0)+"</a>";
		WData[7] = "<a title='Item duratibility: "+parseInt(document.getElementById('Inp_W_CurDur').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_W_CurDur').value)*100,8,0)+"</a>";
		WData[8] = "<a title='Item max duratibility: "+parseInt(document.getElementById('Inp_W_MaxDur').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_W_MaxDur').value)*100,8,0)+"</a>";
		WData[9] = "<a title='Item Type: "+document.getElementById('Inp_W_ItType').value+"'>"+document.getElementById('Inp_W_ItType').value+"</a>";
		WData[10] = "<a title='Item Flag: "+document.getElementById('Inp_W_ItFlag').value+"'>"+document.getElementById('Inp_W_ItFlag').value+"</a>";
		
		e = document.getElementById('Inp_W_Crafter');
		fix = e.value;

		if (fix.length > 32){
			e.value = fix.substr(32);
		}
		if (fix == ""){
			WData[11] = "<a title='Name length is 0'>00</a>";
		}else{
			WData[11] = "<a title='Name length "+fix.length+"'>"+DectoRevHex(fix.length*2,2,0)+"</a><a title='Transformed name from "+fix+"'>"+NametoHex(fix)+"</a>";
		}
		var RangeType = [];
		RangeType[0] = "Weapon physical damage based on STR [Melee]";
		RangeType[1] = "Weapon physical damage based on AGI [Ranged]";
		RangeType[2] = "Weapon physical damage based on AGI [Melee]";
		WData[13] = "<a title='Range Type: "+parseInt(document.getElementById('Inp_W_Ranged').value)+" = "+RangeType[parseInt(document.getElementById('Inp_W_Ranged').value)]+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_W_Ranged').value),8,0)+"</a>";
		WData[14] = "<a title='Weapon Type: "+parseInt(wType)+"'>"+DectoRevHex(parseInt(wType),8,0)+"</a>";

		fix = parseInt(document.getElementById('Inp_Grade2').value);
		if (fix > 999){
			document.getElementById('Inp_Grade2').value = 999;
		}
		var WGrade = parseInt(document.getElementById('Inp_Grade2').value);
		WData[15] = "<a title='Item Grade: "+WGrade+"'>"+DectoRevHex(WGrade,8,0)+"</a>";		
		WData[16] = "<a title='Ammo type: "+parseInt(document.getElementById('Inp_W_Ammo').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_W_Ammo').value),8,0)+"</a>";		
		
		fix = parseInt(document.getElementById('Inp_W_PDmg1').value);
		if (fix > 999999999){
			document.getElementById('Inp_W_PDmg1').value = 999999999;
		}
		fix = parseInt(document.getElementById('Inp_W_PDmg2').value);
		if (fix > 999999999){
			document.getElementById('Inp_W_PDmg2').value = 999999999;
		}
		if (parseInt(document.getElementById('Inp_W_PDmg1').value) > parseInt(document.getElementById('Inp_W_PDmg2').value)){
			document.getElementById('Inp_W_PDmg2').value = parseInt(document.getElementById('Inp_W_PDmg1').value);
		}
		
		fix = parseInt(document.getElementById('Inp_W_MDmg1').value);
		if (fix > 999999999){
			document.getElementById('Inp_W_MDmg1').value = 999999999;
		}
		fix = parseInt(document.getElementById('Inp_W_MDmg2').value);
		if (fix > 999999999){
			document.getElementById('Inp_W_MDmg2').value = 999999999;
		}
		if (parseInt(document.getElementById('Inp_W_MDmg1').value) > parseInt(document.getElementById('Inp_W_MDmg2').value)){
			document.getElementById('Inp_W_MDmg2').value = parseInt(document.getElementById('Inp_W_MDmg1').value);
		}		
		
		WData[17] = "<a title='Physical Min damage: "+parseInt(document.getElementById('Inp_W_PDmg1').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_W_PDmg1').value),8,0)+"</a>";
		WData[18] = "<a title='Physical Max damage: "+parseInt(document.getElementById('Inp_W_PDmg2').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_W_PDmg2').value),8,0)+"</a>";		
		WData[19] = "<a title='Magic Min damage: "+parseInt(document.getElementById('Inp_W_MDmg1').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_W_MDmg1').value),8,0)+"</a>";		
		WData[20] = "<a title='Magic Max damage: "+parseInt(document.getElementById('Inp_W_MDmg2').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_W_MDmg2').value),8,0)+"</a>";	
		fix = parseInt(document.getElementById('Inp_W_AttRate2').value);
		if (fix > 100){
			document.getElementById('Inp_W_AttRate2').value = 100;
		}		
		WData[21] = "<a title='Attack Rate (20:X=Rate): "+parseInt(document.getElementById('Inp_W_AttRate2').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_W_AttRate2').value),8,0)+"</a>";	
		
		fix = document.getElementById('Inp_W_Range2').value;
		if (fix > 100){
			document.getElementById('Inp_W_Range2').value = 100.00;
		}	
		WData[22]="<a title='Range: "+document.getElementById('Inp_W_Range2').value+"'>"+numToFloat32Hex(document.getElementById('Inp_W_Range2').value, true)+"</a>";

		fix = document.getElementById('Inp_W_MinRange1').value;
		if (fix > 1000){
			document.getElementById('Inp_W_MinRange1').value = 1000.00;
		}	
		WData[23]="<a title='Minimum Effective Range: "+document.getElementById('Inp_W_MinRange1').value+"'>"+numToFloat32Hex(document.getElementById('Inp_W_MinRange1').value, true)+"</a>";

		var sockQty = document.getElementById('Inp_W_Socket').options[document.getElementById('Inp_W_Socket').selectedIndex].value;
		var refQty = document.getElementById('Inp_W_RefLv').options[document.getElementById('Inp_W_RefLv').selectedIndex].value;
		var AddonCount = AddInd;
		var SockAddons = "";
		var InsStones = 0;
		var InsStoneTxt = "";
		var tmpArr = [];

		WData[24] = "<a title='How much socket: "+sockQty+"'>"+DectoRevHex(sockQty,8,0)+"</a>";	
		for (i = 1; i <= sockQty; i++) {
			fix = 0;
			if (SckInd < i){
				WData[24] = WData[24] + " <a title='Socket #"+i+": Empty'>"+DectoRevHex(0,8,0)+"</a>";
			}else if (parseInt(tmpArr[0])==0){
				WData[24] = WData[24] + " <a title='Socket #"+i+": Empty'>"+DectoRevHex(0,8,0)+"</a>";
			}else{
				tmpArr = SckAddons[i].split("#");
				fix = parseInt(tmpArr[0]);
				WData[24] = WData[24] + " <a title='Socket #"+i+": "+tmpArr[4]+" gr."+tmpArr[5]+" ["+fix+"]'>"+DectoRevHex(fix,8,0)+"</a>";
				if ((fix > 0)&&(parseInt(tmpArr[1]) > 0)&&(parseInt(tmpArr[2]) > 0)){
					SockAddons = SockAddons + " <a title='Details from socket #"+i+": "+tmpArr[4]+" gr."+tmpArr[5]+" ["+fix+"], addon id: "+parseInt(tmpArr[1])+", value: "+parseInt(tmpArr[2])+" === "+tmpArr[3]+"'>"+DectoRevHex(parseInt(parseInt(tmpArr[1])),8,"a")+DectoRevHex(parseInt(parseInt(tmpArr[2])),8,"0")+"</a>";
					AddonCount++;
					InsStones++;
				}			
			}
		}

		var OtherAddon = "";
		var bpDmg1 = 0;
		var bpDmg2 = 0;
		var bmDmg1 = 0;
		var bmDmg2 = 0;
		var bRange =0;
		var bmaxDur =0;
		var AddonType = 0;
		var RefAddons = "";
		var RefineOn = 0;
		var RefLevel = document.getElementById('Inp_W_RefLv').options[document.getElementById('Inp_W_RefLv').selectedIndex].value;
		var RefCat;
		var ADataArr = [];
		if ((RefLevel > 0)&&(WGrade< 21)&&(WGrade > 0)){
			RefineOn=1;
			if ((wType == 9)||(wType == 5)||(wType == 1)||(wType == 44878)){
				RefCat = 1;		
			}else if ((wType == 182)||(wType == 23749)){
				RefCat = 2;
			}else if (wType == 13){
				RefCat = 3;
			}else if ((wType == 292)||(wType == 25333)||(wType == 44967)){
				RefCat = 4;
			}
			var aArr = refBase[1][RefCat][WGrade].split("#");
			fix = parseInt(parseInt(aArr[0])*refMulti[RefLevel]);
			RefAddons = " <a title='Refined: +"+RefLevel+"(+"+fix+")'>"+DectoRevHex(parseInt(aArr[1]),8,4)+DectoRevHex(fix,8,0)+DectoRevHex(RefLevel,8,0)+"</a>";
		}

		
		
		for (i = 1; i <= AddInd; i++) {
			ADataArr = Addons[i].split("#");
			AddonType=ADataArr[0].substr(0, 4);
			if (AddonType == "f023"){
				bpDmg1 = bpDmg1 + parseInt(ADataArr[4],10);
				bpDmg2 = bpDmg2 + parseInt(ADataArr[4],10);
			}else if (AddonType == "ec23"){
				bpDmg2 = bpDmg2 + parseInt(ADataArr[4],10);
			}else if (AddonType == "fb23"){
				bmDmg1 = bmDmg1 + parseInt(ADataArr[4],10);
				bmDmg2 = bmDmg2 + parseInt(ADataArr[4],10);
			}else if (AddonType == "a721"){
				bmDmg2 = bmDmg2 + parseInt(ADataArr[4],10);
			}else if (AddonType == "d721"){
				bRange = bRange + parseInt(ADataArr[4]*100, 10)/100;
			}else if (AddonType == "2c21"){
				bmaxDur = bmaxDur + parseInt(ADataArr[4],10);
			}
			
			OtherAddon = OtherAddon+" <a title='Addon: "+ADataArr[1]+"'>"+ADataArr[0]+"</a>";
		}
		
		fix = parseInt(parseInt(document.getElementById('Inp_W_PDmg1').value, 10)+bpDmg1);
		WData[17] = "<a title='Physical Min damage: "+parseInt(document.getElementById('Inp_W_PDmg1').value, 10)+" +"+bpDmg1+"'>"+DectoRevHex(fix,8,0)+"</a>";
		fix = parseInt(parseInt(document.getElementById('Inp_W_PDmg2').value, 10)+bpDmg2);
		WData[18] = "<a title='Physical Max damage: "+parseInt(document.getElementById('Inp_W_PDmg2').value, 10)+" +"+bpDmg2+"'>"+DectoRevHex(fix,8,0)+"</a>";		
		fix = parseInt(parseInt(document.getElementById('Inp_W_MDmg1').value, 10)+bmDmg1);
		WData[19] = "<a title='Magic Min damage: "+parseInt(document.getElementById('Inp_W_MDmg1').value, 10)+" +"+bmDmg1+"'>"+DectoRevHex(fix,8,0)+"</a>";		
		fix = parseInt(parseInt(document.getElementById('Inp_W_MDmg2').value, 10)+bmDmg2);
		WData[20] = "<a title='Magic Max damage: "+parseInt(document.getElementById('Inp_W_MDmg2').value, 10)+" +"+bmDmg2+"'>"+DectoRevHex(fix,8,0)+"</a>";		
		fix = ((document.getElementById('Inp_W_Range2').value*100+bRange*100)/100);
		WData[22]="<a title='Range: "+document.getElementById('Inp_W_Range2').value+" +"+bRange+"'>"+numToFloat32Hex(fix, true)+"</a>";
		if (bmaxDur > 0){
			fix = parseInt(parseInt(document.getElementById('Inp_W_MaxDur').value) * (100+bmaxDur))
			WData[8] = "<a title='Item max duratibility: "+parseInt(document.getElementById('Inp_W_MaxDur').value)+" +"+bmaxDur+"%'>"+DectoRevHex(fix,8,0)+"</a>";

		}
		InsStoneTxt = " <a title='addons[socket, normal, special, refine]: "+(InsStones+AddInd+RefineOn)+"'>"+DectoRevHex((InsStones+AddInd+RefineOn),8,0);
		
		WData[24] = WData[24]+InsStoneTxt+OtherAddon+RefAddons+SockAddons;
		WData[25] = "";
		if (LastOctet != WData.join("")) {
			document.getElementById('octet1').innerHTML = WData.join(" ");
			document.getElementById('Inp_Octet').value = WData.join(" ").replace(/<\/?[^>]+(>|$)/g, "").replace(/ /g,"");//WData.join("");
		}
		LastOctet = WData.join("");
	
	}else if (CIType == "A"){
		var ADataArr = [];
		var aType = document.getElementById('Sel_ASub_Type').options[document.getElementById('Sel_ASub_Type').selectedIndex].value;
		AData[1] = "<a title='Level requirement: "+parseInt(document.getElementById('Inp_A_LvReq').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_A_LvReq').value),4,0)+"</a>";
		AData[2] = "<a title='Class requirement: "+parseInt(document.getElementById('Inp_A_Class').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_A_Class').value),4,0)+"</a>";
		AData[3] = "<a title='Strength requirement: "+parseInt(document.getElementById('Inp_A_STR').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_A_STR').value),4,0)+"</a>";
		AData[4] = "<a title='Constitution requirement: "+parseInt(document.getElementById('Inp_A_CON').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_A_CON').value),4,0)+"</a>";
		AData[5] = "<a title='Agility requirement: "+parseInt(document.getElementById('Inp_A_AGI').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_A_AGI').value),4,0)+"</a>";
		AData[6] = "<a title='Intelligence requirement: "+parseInt(document.getElementById('Inp_A_INT').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_A_INT').value),4,0)+"</a>";
		AData[7] = "<a title='Item duratibility: "+parseInt(document.getElementById('Inp_A_CurDur').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_A_CurDur').value)*100,8,0)+"</a>";
		AData[8] = "<a title='Item max duratibility: "+parseInt(document.getElementById('Inp_A_MaxDur').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_A_MaxDur').value)*100,8,0)+"</a>";
		AData[9] = "<a title='Item Type: "+document.getElementById('Inp_A_ItType').value+"'>"+document.getElementById('Inp_A_ItType').value+"</a>";
		AData[10] = "<a title='Item Flag: "+document.getElementById('Inp_A_ItFlag').value+"'>"+document.getElementById('Inp_A_ItFlag').value+"</a>";
		var e = document.getElementById('Inp_A_Crafter');
		var fix = e.value;

		if (fix.length > 32){
			e.value = fix.substr(32);
		}
		if (fix == ""){
			AData[11] = "<a title='Name length is 0'>00</a>";
		}else{
			AData[11] = "<a title='Name length "+fix.length+"'>"+DectoRevHex(fix.length*2,2,0)+"</a><a title='Transformed name from "+fix+"'>"+NametoHex(fix)+"</a>";
		}	
		AData[12] = "<a title='Physical Defense: "+parseInt(document.getElementById('Inp_A_PDef').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_A_PDef').value),8,0)+"</a>";
		AData[13] = "<a title='Dodge: "+parseInt(document.getElementById('Inp_A_Dodge').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_A_Dodge').value),8,0)+"</a>";
		AData[14] = "<a title='Hit Point: "+parseInt(document.getElementById('Inp_A_HP').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_A_HP').value),8,0)+"</a>";
		AData[15] = "<a title='Mana Point: "+parseInt(document.getElementById('Inp_A_MP').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_A_MP').value),8,0)+"</a>";
		AData[16] = "<a title='Metal Defense: "+parseInt(document.getElementById('Inp_A_Metal').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_A_Metal').value),8,0)+"</a>";
		AData[17] = "<a title='Wood Defense: "+parseInt(document.getElementById('Inp_A_Wood').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_A_Wood').value),8,0)+"</a>";
		AData[18] = "<a title='Water Defense: "+parseInt(document.getElementById('Inp_A_Water').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_A_Water').value),8,0)+"</a>";
		AData[19] = "<a title='Fire Defense: "+parseInt(document.getElementById('Inp_A_Fire').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_A_Fire').value),8,0)+"</a>";
		AData[20] = "<a title='Earth Defense: "+parseInt(document.getElementById('Inp_A_Earth').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_A_Earth').value),8,0)+"</a>";
	
		var sockQty = document.getElementById('Inp_A_Socket').options[document.getElementById('Inp_A_Socket').selectedIndex].value;
		var refQty = document.getElementById('Inp_A_RefLv').options[document.getElementById('Inp_A_RefLv').selectedIndex].value;
		var AddonCount = AddInd;
		var SockAddons = "";
		var InsStones = 0;
		var InsStoneTxt = "";
		var tmpArr = [];

		AData[21] = " <a title='How much socket: "+sockQty+"'>"+DectoRevHex(sockQty,8,0)+"</a>";	
		for (i = 1; i <= sockQty; i++) {
			fix = 0;
			if (SckInd < i){
				AData[21] = AData[21] + " <a title='Socket #"+i+": Empty'>"+DectoRevHex(0,8,0)+"</a>";
			}else if (parseInt(tmpArr[0])==0){
				AData[21] = AData[21] + " <a title='Socket #"+i+": Empty'>"+DectoRevHex(0,8,0)+"</a>";
			}else{
				tmpArr = SckAddons[i].split("#");
				fix = parseInt(tmpArr[0]);
				AData[21] = AData[21] + " <a title='Socket #"+i+": "+tmpArr[4]+" gr."+tmpArr[5]+" ["+fix+"]'>"+DectoRevHex(fix,8,0)+"</a>";
				if ((fix > 0)&&(parseInt(tmpArr[1]) > 0)&&(parseInt(tmpArr[2]) > 0)){
					SockAddons = SockAddons + " <a title='Details from socket #"+i+": "+tmpArr[4]+" gr."+tmpArr[5]+" ["+fix+"], addon id: "+parseInt(tmpArr[1])+", value: "+parseInt(tmpArr[2])+" === "+tmpArr[3]+"'>"+DectoRevHex(parseInt(parseInt(tmpArr[1])),8,"a")+DectoRevHex(parseInt(parseInt(tmpArr[2])),8,"0")+"</a>";
					AddonCount++;
					InsStones++;
				}			
			}
		}		
		
		var OtherAddon = "";
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
		var AddonType = 0;
		var RefAddons = "";
		var RefineOn = 0;
		var AGrade = parseInt(document.getElementById('Inp_Grade2').value);
		var RefLevel = document.getElementById('Inp_A_RefLv').options[document.getElementById('Inp_A_RefLv').selectedIndex].value;
		var RefCat;

		if ((RefLevel > 0)&&(AGrade< 21)&&(AGrade > 0)){
			RefineOn = 1;
			//----calculate what refine needed
			if ((aType == 1)||(aType == 4)||(aType == 7)||(aType == 10)||(aType == 13)){
				RefCat = 1;		
			}else if((aType == 2)||(aType == 5)||(aType == 8)||(aType == 11)||(aType == 15)){
				RefCat = 2;
			}else if ((aType == 3)||(aType == 6)||(aType == 9)||(aType == 12)||(aType == 14)){
				RefCat = 3;
			}
			var aArr = refBase[2][RefCat][AGrade].split("#");
			fix = parseInt(parseInt(aArr[0])*refMulti[RefLevel]);
			RefAddons = " <a title='Refined: +"+RefLevel+"(+"+fix+")'>"+DectoRevHex(parseInt(aArr[1]),8,4)+DectoRevHex(fix,8,0)+DectoRevHex(RefLevel,8,0)+"</a>";
			//----------------------------------
		}
		
		
		
		for (i = 1; i <= AddInd; i++) {
			ADataArr = Addons[i].split("#");
			AddonType=ADataArr[0].substr(0, 4);
			if (AddonType == "1421"){
				bHP = bHP + parseInt(ADataArr[4]);
			}else if (AddonType == "7322"){
				bMP = bMP + parseInt(ADataArr[4]);
			}else if (AddonType == "db20"){
				bPdef = bPdef + parseInt(ADataArr[4]);
			}else if (AddonType == "3221"){
				bMetal = bMetal + parseInt(ADataArr[4]);
				bWood = bWood + parseInt(ADataArr[4]);
				bWater = bWater + parseInt(ADataArr[4]);
				bFire = bFire + parseInt(ADataArr[4]);
				bEarth = bEarth + parseInt(ADataArr[4]);
			}else if (AddonType == "9e22"){
				bDodge = bDodge + parseInt(ADataArr[4]);
			}else if (AddonType == "6d21"){
				bMetal = bMetal + parseInt(ADataArr[4]);
			}else if (AddonType == "7021"){
				bWood = bWood + parseInt(ADataArr[4]);
			}else if (AddonType == "7321"){
				bWater = bWater + parseInt(ADataArr[4]);
			}else if (AddonType == "7621"){
				bFire = bFire + parseInt(ADataArr[4]);
			}else if (AddonType == "7921"){
				bEarth = bEarth + parseInt(ADataArr[4]);
			}else if (AddonType == "2c21"){
				bmaxDur = bmaxDur + parseInt(ADataArr[4]);
			}
			
			OtherAddon = OtherAddon+" <a title='Addon: "+ADataArr[1]+"'>"+ADataArr[0]+"</a>";
		}
		fix = parseInt(parseInt(document.getElementById('Inp_A_PDef').value)+bPdef);
		AData[12] = "<a title='Physical Defense: "+parseInt(document.getElementById('Inp_A_PDef').value)+" +"+bPdef+"'>"+DectoRevHex(fix,8,0)+"</a>";
		fix = parseInt(parseInt(document.getElementById('Inp_A_Dodge').value)+bDodge);
		AData[13] = "<a title='Dodge: "+parseInt(document.getElementById('Inp_A_Dodge').value)+" +"+bDodge+"'>"+DectoRevHex(fix,8,0)+"</a>";
		fix = parseInt(parseInt(document.getElementById('Inp_A_HP').value)+parseInt(bHP));
		AData[14] = "<a title='Hit Point: "+parseInt(document.getElementById('Inp_A_HP').value)+" +"+bHP+"'>"+DectoRevHex(fix,8,0)+"</a>";
		fix = parseInt(parseInt(document.getElementById('Inp_A_MP').value)+bMP);
		AData[15] = "<a title='Mana Point: "+parseInt(document.getElementById('Inp_A_MP').value)+" +"+bMP+"'>"+DectoRevHex(fix,8,0)+"</a>";
		fix = parseInt(parseInt(document.getElementById('Inp_A_Metal').value)+bMetal);
		AData[16] = "<a title='Metal Defense: "+parseInt(document.getElementById('Inp_A_Metal').value)+" +"+bMetal+"'>"+DectoRevHex(fix,8,0)+"</a>";
		fix = parseInt(parseInt(document.getElementById('Inp_A_Wood').value)+bWood);
		AData[17] = "<a title='Wood Defense: "+parseInt(document.getElementById('Inp_A_Wood').value)+" +"+bWood+"'>"+DectoRevHex(fix,8,0)+"</a>";
		fix = parseInt(parseInt(document.getElementById('Inp_A_Water').value)+bWater);
		AData[18] = "<a title='Water Defense: "+parseInt(document.getElementById('Inp_A_Water').value)+" +"+bWater+"'>"+DectoRevHex(fix,8,0)+"</a>";
		fix = parseInt(parseInt(document.getElementById('Inp_A_Fire').value)+bFire);
		AData[19] = "<a title='Fire Defense: "+parseInt(document.getElementById('Inp_A_Fire').value)+" +"+bFire+"'>"+DectoRevHex(fix,8,0)+"</a>";
		fix = parseInt(parseInt(document.getElementById('Inp_A_Earth').value)+bEarth);
		AData[20] = "<a title='Earth Defense: "+parseInt(document.getElementById('Inp_A_Earth').value)+" +"+bEarth+"'>"+DectoRevHex(fix,8,0)+"</a>";
		if (bmaxDur > 0){
			fix = parseInt(parseInt(document.getElementById('Inp_A_MaxDur').value) * (100+bmaxDur))
			AData[8] = "<a title='Item max duratibility: "+parseInt(document.getElementById('Inp_A_MaxDur').value)+" +"+bmaxDur+"%'>"+DectoRevHex(fix,8,0)+"</a>";

		}
		InsStoneTxt = " <a title='addons[socket, normal, refine]: "+(InsStones+AddInd+RefineOn)+"'>"+DectoRevHex((InsStones+AddInd+RefineOn),8,0);
		AData[21] = AData[21]+InsStoneTxt+OtherAddon+RefAddons+SockAddons;
		AData[22] = "";
		if (LastOctet != AData.join("")) {
			document.getElementById('octet1').innerHTML = AData.join(" ");
			document.getElementById('Inp_Octet').value = AData.join(" ").replace(/<\/?[^>]+(>|$)/g, "").replace(/ /g,"");
		}
		LastOctet = AData.join("");	
	}else if (CIType == "J"){
		var jType = document.getElementById('Sel_JSub_Type').options[document.getElementById('Sel_JSub_Type').selectedIndex].value;
		JData[1] = "<a title='Level requirement: "+parseInt(document.getElementById('Inp_J_LvReq').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_J_LvReq').value),4,0)+"</a>";
		JData[2] = "<a title='Class requirement: "+parseInt(document.getElementById('Inp_J_Class').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_J_Class').value),4,0)+"</a>";
		JData[3] = "<a title='Strength requirement: "+parseInt(document.getElementById('Inp_J_STR').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_J_STR').value),4,0)+"</a>";
		JData[4] = "<a title='Constitution requirement: "+parseInt(document.getElementById('Inp_J_CON').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_J_CON').value),4,0)+"</a>";
		JData[5] = "<a title='Agility requirement: "+parseInt(document.getElementById('Inp_J_AGI').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_J_AGI').value),4,0)+"</a>";
		JData[6] = "<a title='Intelligence requirement: "+parseInt(document.getElementById('Inp_J_INT').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_J_INT').value),4,0)+"</a>";
		JData[7] = "<a title='Item duratibility: "+parseInt(document.getElementById('Inp_J_CurDur').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_J_CurDur').value)*100,8,0)+"</a>";
		JData[8] = "<a title='Item max duratibility: "+parseInt(document.getElementById('Inp_J_MaxDur').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_J_MaxDur').value)*100,8,0)+"</a>";
		JData[9] = "<a title='Item Type: "+document.getElementById('Inp_J_ItType').value+"'>"+document.getElementById('Inp_J_ItType').value+"</a>";
		JData[10] = "<a title='Item Flag: "+document.getElementById('Inp_J_ItFlag').value+"'>"+document.getElementById('Inp_J_ItFlag').value+"</a>";

		var e = document.getElementById('Inp_J_Crafter');
		var fix = e.value;

		if (fix.length > 32){
			e.value = fix.substr(32);
		}
		if (fix == ""){
			JData[11] = "<a title='Name length is 0 '>00</a>";
		}else{
			JData[11] = "<a title='Name length "+fix.length+"'>"+DectoRevHex(fix.length*2,2,0)+"</a><a title='Transformed name from "+fix+"'>"+NametoHex(fix)+"</a>";
		}	
		JData[12] = "<a title='Physical Attack: "+parseInt(document.getElementById('Inp_J_PAtt').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_J_PAtt').value),8,0)+"</a>";
		JData[13] = "<a title='Magic Attack: "+parseInt(document.getElementById('Inp_J_MAtt').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_J_MAtt').value),8,0)+"</a>";
		JData[14] = "<a title='Physical Defense: "+parseInt(document.getElementById('Inp_J_PDef').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_J_PDef').value),8,0)+"</a>";
		JData[15] = "<a title='Dodge: "+parseInt(document.getElementById('Inp_J_Dodge').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_J_Dodge').value),8,0)+"</a>";
		JData[16] = "<a title='Metal Defense: "+parseInt(document.getElementById('Inp_J_Metal').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_J_Metal').value),8,0)+"</a>";
		JData[17] = "<a title='Wood Defense: "+parseInt(document.getElementById('Inp_J_Wood').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_J_Wood').value),8,0)+"</a>";
		JData[18] = "<a title='Water Defense: "+parseInt(document.getElementById('Inp_J_Water').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_J_Water').value),8,0)+"</a>";
		JData[19] = "<a title='Fire Defense: "+parseInt(document.getElementById('Inp_J_Fire').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_J_Fire').value),8,0)+"</a>";
		JData[20] = "<a title='Earth Defense: "+parseInt(document.getElementById('Inp_J_Earth').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_J_Earth').value),8,0)+"</a>";
		var AddonCount = AddInd;
		var OtherAddon = "";
		var bPdef = 0;
		var bPattack = 0;
		var bMattack = 0;
		var bDodge = 0;
		var bMetal = 0;
		var bWood = 0;
		var bWater = 0;
		var bFire = 0;
		var bEarth = 0;
		var bmaxDur = 0;
		var AddonType = 0;
		var RefAddons = "";
		var RefineOn = 0;
		var JGrade = parseInt(document.getElementById('Inp_Grade2').value);
		var RefLevel = document.getElementById('Inp_J_RefLv').options[document.getElementById('Inp_J_RefLv').selectedIndex].value;
		var RefCat;
		if ((RefLevel > 0)&&(JGrade< 21)&&(JGrade > 0)){
			RefineOn = 1;
			if ((jType == 1)||(jType == 4)||(jType == 7)){
				RefCat = 1;		
			}else if((jType == 2)||(jType == 5)||(jType == 8)){
				RefCat = 2;
			}else if (jType == 3){
				RefCat = 3;
			}
			var aArr = refBase[3][RefCat][JGrade].split("#");
			fix = parseInt(parseInt(aArr[0])*refMulti[RefLevel]);
			RefAddons = " <a title='Refined: +"+RefLevel+"(+"+fix+")'>"+DectoRevHex(parseInt(aArr[1]),8,4)+DectoRevHex(fix,8,0)+DectoRevHex(RefLevel,8,0)+"</a>";
		}

		
		for (i = 1; i <= AddInd; i++) {
			JDataArr = Addons[i].split("#");
			AddonType=JDataArr[0].substr(0, 4);
			if (AddonType == "f023"){
				bPattack = bPattack + parseInt(JDataArr[4]);
			}else if (AddonType == "fb23"){
				bMattack = bMattack + parseInt(JDataArr[4]);
			}else if (AddonType == "db20"){
				bPdef = bPdef + parseInt(JDataArr[4]);
			}else if (AddonType == "3221"){
				bMetal = bMetal + parseInt(JDataArr[4]);
				bWood = bWood + parseInt(JDataArr[4]);
				bWater = bWater + parseInt(JDataArr[4]);
				bFire = bFire + parseInt(JDataArr[4]);
				bEarth = bEarth + parseInt(JDataArr[4]);
			}else if (AddonType == "9e22"){
				bDodge = bDodge + parseInt(JDataArr[4]);
			}else if (AddonType == "6d21"){
				bMetal = bMetal + parseInt(JDataArr[4]);
			}else if (AddonType == "7021"){
				bWood = bWood + parseInt(JDataArr[4]);
			}else if (AddonType == "7321"){
				bWater = bWater + parseInt(JDataArr[4]);
			}else if (AddonType == "7621"){
				bFire = bFire + parseInt(JDataArr[4]);
			}else if (AddonType == "7921"){
				bEarth = bEarth + parseInt(JDataArr[4]);
			}else if (AddonType == "2c21"){
				bmaxDur = bmaxDur + parseInt(JDataArr[4]);
			}
			
			OtherAddon = OtherAddon+" <a title='Addon: "+JDataArr[1]+"'>"+JDataArr[0]+"</a>";
		}
		fix = parseInt(parseInt(document.getElementById('Inp_J_PAtt').value)+parseInt(bPattack));
		JData[12] = "<a title='Physical Attack: "+parseInt(document.getElementById('Inp_J_PAtt').value)+" +"+bPattack+"'>"+DectoRevHex(fix,8,0)+"</a>";
		fix = parseInt(parseInt(document.getElementById('Inp_J_MAtt').value)+bMattack);
		JData[13] = "<a title='Magic Attack: "+parseInt(document.getElementById('Inp_J_MAtt').value)+" +"+bMattack+"'>"+DectoRevHex(fix,8,0)+"</a>";
		fix = parseInt(parseInt(document.getElementById('Inp_J_PDef').value)+bPdef);
		JData[14] = "<a title='Physical Defense: "+parseInt(document.getElementById('Inp_A_PDef').value)+" +"+bPdef+"'>"+DectoRevHex(fix,8,0)+"</a>";
		fix = parseInt(parseInt(document.getElementById('Inp_J_Dodge').value)+bDodge);
		JData[15] = "<a title='Dodge: "+parseInt(document.getElementById('Inp_A_Dodge').value)+" +"+bDodge+"'>"+DectoRevHex(fix,8,0)+"</a>";
		fix = parseInt(parseInt(document.getElementById('Inp_J_Metal').value)+bMetal);
		JData[16] = "<a title='Metal Defense: "+parseInt(document.getElementById('Inp_J_Metal').value)+" +"+bMetal+"'>"+DectoRevHex(fix,8,0)+"</a>";
		fix = parseInt(parseInt(document.getElementById('Inp_J_Wood').value)+bWood);
		JData[17] = "<a title='Wood Defense: "+parseInt(document.getElementById('Inp_J_Wood').value)+" +"+bWood+"'>"+DectoRevHex(fix,8,0)+"</a>";
		fix = parseInt(parseInt(document.getElementById('Inp_J_Water').value)+bWater);
		JData[18] = "<a title='Water Defense: "+parseInt(document.getElementById('Inp_J_Water').value)+" +"+bWater+"'>"+DectoRevHex(fix,8,0)+"</a>";
		fix = parseInt(parseInt(document.getElementById('Inp_J_Fire').value)+bFire);
		JData[19] = "<a title='Fire Defense: "+parseInt(document.getElementById('Inp_J_Fire').value)+" +"+bFire+"'>"+DectoRevHex(fix,8,0)+"</a>";
		fix = parseInt(parseInt(document.getElementById('Inp_J_Earth').value)+bEarth);
		JData[20] = "<a title='Earth Defense: "+parseInt(document.getElementById('Inp_J_Earth').value)+" +"+bEarth+"'>"+DectoRevHex(fix,8,0)+"</a>";
		if (bmaxDur > 0){
			fix = parseInt(parseInt(document.getElementById('Inp_J_MaxDur').value) * (100+bmaxDur))
			JData[8] = "<a title='Item max duratibility: "+parseInt(document.getElementById('Inp_J_MaxDur').value)+" +"+bmaxDur+"%'>"+DectoRevHex(fix,8,0)+"</a>";

		}
		var AddonsTxt = " <a title='addons[normal, refine]: "+(AddInd+RefineOn)+"'>"+DectoRevHex((AddInd+RefineOn),8,0);
		JData[21] = " <a title='Socket normally it is 0 else crash, rumour say in 1.5.3 its work like at armor'>00000000</a>"+AddonsTxt+OtherAddon+RefAddons;
		if (LastOctet != JData.join("")) {
			document.getElementById('octet1').innerHTML = JData.join(" ");
			document.getElementById('Inp_Octet').value = JData.join(" ").replace(/<\/?[^>]+(>|$)/g, "").replace(/ /g,"");
		}
		
		LastOctet = JData.join("");	
	}else if (CIType == "O"){
		if (SIType == 1){
			var fix= document.getElementById("Inp_F_Race");
			var reqLv = parseInt(document.getElementById("Inp_F_ReqLv").value, 10);
			var FRace = parseInt(fix.options[fix.selectedIndex].value, 10);
			var FGrade = parseInt(document.getElementById("Inp_F_Grade").value, 10);
			var FFuel1 = parseInt(document.getElementById("Inp_F_Fuel1").value, 10);
			var FFuel2 = parseInt(document.getElementById("Inp_F_Fuel2").value, 10);
			var FSpeed1 = document.getElementById("Inp_F_Speed1").value;
			var FSpeed2 = document.getElementById("Inp_F_Speed2").value;
			FData[1] = "<a title='Current fuel: "+FFuel1+"'>"+DectoRevHex(FFuel1,8,0)+"</a>";
			FData[2] = "<a title='Max fuel: "+FFuel2+"'>"+DectoRevHex(FFuel2,8,0)+"</a>";
			FData[3] = "<a title='Required level: "+reqLv+"'>"+DectoRevHex(reqLv,4,0)+"</a>";
			FData[4] = "<a title='Grade: "+FGrade+"'>"+DectoRevHex(FGrade,4,0)+"</a>";
			FData[5] = "<a title='Race: "+FRace+"'>"+DectoRevHex(FRace,8,0)+"</a>";
			FData[6] = "<a title='unknown: "+15+"'>"+DectoRevHex(15,8,0)+"</a>";
			FData[7] = "<a title='Normal Speed: "+FSpeed1+"'>"+numToFloat32Hex(FSpeed1, true)+"</a>";
			FData[8] = "<a title='Tweeked Speed: "+FSpeed2+"'>"+numToFloat32Hex(FSpeed2, true)+"</a>";
			FData[9] = "<a title='unknown: "+3+"'>"+DectoRevHex(3,4,0)+"</a>";
			fix = 155;
			FData[10] = "<a title='unknown: "+fix+"'>"+DectoRevHex(fix,2,0)+"</a>";
			FData[11] = "<a title='unknown: "+195+"'>"+DectoRevHex(195,2,0)+"</a>";
			if (LastOctet != FData.join("")) {
				document.getElementById('octet1').innerHTML = FData.join(" ");
				document.getElementById('Inp_Octet').value = FData.join(" ").replace(/<\/?[^>]+(>|$)/g, "").replace(/ /g,"");
			}			
		}else if (SIType == 2){	
			var fix;
			var reqClass = parseInt(document.getElementById("Inp_P_Class2").value, 10);
			var reqLv = parseInt(document.getElementById("Inp_P_ReqLv").value, 10);
			var PType = parseInt(document.getElementById("Inp_P_PetType2").value, 10);
			var SkillCount = 0;
			var EggId = parseInt(document.getElementById("Inp_P_EggId").value, 10);
			var PetId = parseInt(document.getElementById("Inp_P_PetId").value, 10);
			var Loyal = parseInt(document.getElementById("Inp_P_Loyality").value, 10);
			var PetLv = parseInt(document.getElementById("Inp_P_Lv").value, 10);
			var PetExp = parseInt(document.getElementById("Inp_P_Exp").value, 10);
			var PData = [];
			var MaxLv = 1;
			if (PType == 0){
				MaxLv = 11;
			}else if (PType == 1){
				MaxLv = 150;
			}
			
			if (PetLv < 1){
				PetLv = 1;
				document.getElementById("Inp_P_Lv").value = PetLv;
			}
			if (PetLv > MaxLv){
				PetLv = MaxLv;
				document.getElementById("Inp_P_Lv").value = PetLv;
			}			
			
			PData[1] = "<a title='Level Required: "+reqLv+"'>"+DectoRevHex(reqLv,8,0)+"</a>";
			PData[2] = "<a title='Class [Mask] for Pet: "+reqClass+"'>"+DectoRevHex(reqClass,8,0)+"</a>";
			PData[3] = "<a title='Loyality after hatch: "+Loyal+"'>"+DectoRevHex(Loyal,8,0)+"</a>";
			PData[4] = "<a title='Pet Id [from elements.data]: "+PetId+"'>"+DectoRevHex(PetId,8,0)+"</a>";
			PData[5] = "<a title='Unknown: 0'>"+DectoRevHex(0,8,0)+"</a>";
			PData[6] = "<a title='Egg Id [from elements.data]: "+EggId+"'>"+DectoRevHex(EggId,8,0)+"</a>";
			PData[7] = "<a title='Pet Type [0=mount, 1=battle, 2=baby]: "+PType+"'>"+DectoRevHex(PType,8,0)+"</a>";
			PData[8] = "<a title='Pet Level: "+PetLv+"'>"+DectoRevHex(PetLv,8,0)+"</a>";
			PData[9] = "<a title='Unknown: 0'>"+DectoRevHex(0,8,0)+"</a>";
			PData[10] = "<a title='Unknown: 0'>"+DectoRevHex(0,8,0)+"</a>";
			PData[11] = "<a title='Unknown: 0'>"+DectoRevHex(0,4,0)+"</a>";

			if (PType==1){
				SkillCount = PSInd;
			}
			PData[12] = "<a title='Pet Skills [only for battle pets]: "+SkillCount+"'>"+DectoRevHex(SkillCount,4,0)+"</a>";
			PData[13] = "<a title='Unknown: 0'>"+DectoRevHex(0,8,0)+"</a>";
			PData[14] = "<a title='Unknown: 0'>"+DectoRevHex(0,8,0)+"</a>";
			PData[15] = "<a title='Unknown: 0'>"+DectoRevHex(0,8,0)+"</a>";
			PData[16] = "<a title='Unknown: 0'>"+DectoRevHex(0,8,0)+"</a>";
			
			PData[17] = "";
			if (SkillCount > 0){
				var ADataArr = [];
				for (i = 1; i <= PSInd; i++) {
				ADataArr = PSkill[i].split("#");
				PData[17] = PData[17] + " <a title='Pet skill: "+ADataArr[2]+" ["+ADataArr[1]+"]'>"+DectoRevHex(ADataArr[1],8,0)+"</a>" + "<a title='Skill Level: "+ADataArr[0]+" ["+ADataArr[2]+"]'>"+DectoRevHex(ADataArr[0],8,0)+"</a>";
				}
			}
			
			if (LastOctet != PData.join("")) {
				document.getElementById('octet1').innerHTML = PData.join(" ");
				document.getElementById('Inp_Octet').value = PData.join(" ").replace(/<\/?[^>]+(>|$)/g, "").replace(/ /g,"");
			}		
			LastOctet = PData.join("");	
		}else if (SIType == 3){	
			var ADataArr = [];
			var BData = [];
			BData[1] = "<a title='Level requirement: "+parseInt(document.getElementById('Inp_B_LvReq').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_B_LvReq').value),4,0)+"</a>";
			BData[2] = "<a title='Class requirement: "+parseInt(document.getElementById('Inp_B_Class').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_B_Class').value),4,0)+"</a>";
			BData[3] = "<a title='Strength requirement: "+parseInt(document.getElementById('Inp_B_STR').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_B_STR').value),4,0)+"</a>";
			BData[4] = "<a title='Constitution requirement: "+parseInt(document.getElementById('Inp_B_CON').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_B_CON').value),4,0)+"</a>";
			BData[5] = "<a title='Agility requirement: "+parseInt(document.getElementById('Inp_B_AGI').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_B_AGI').value),4,0)+"</a>";
			BData[6] = "<a title='Intelligence requirement: "+parseInt(document.getElementById('Inp_B_INT').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_B_INT').value),4,0)+"</a>";
			BData[7] = "<a title='Item duratibility: "+parseInt(document.getElementById('Inp_B_CurDur').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_B_CurDur').value)*100,8,0)+"</a>";
			BData[8] = "<a title='Item max duratibility: "+parseInt(document.getElementById('Inp_B_MaxDur').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_B_MaxDur').value)*100,8,0)+"</a>";
			BData[9] = "<a title='Item Type: "+document.getElementById('Inp_B_ItType').value+"'>"+document.getElementById('Inp_B_ItType').value+"</a>";
			BData[10] = "<a title='Item Flag: "+document.getElementById('Inp_B_ItFlag').value+"'>"+document.getElementById('Inp_B_ItFlag').value+"</a>";
			var e = document.getElementById('Inp_B_Crafter');
			var fix = e.value;

			if (fix.length > 32){
				e.value = fix.substr(32);
			}
			if (fix == ""){
				BData[11] = "<a title='Name length is 0'>00</a>";
			}else{
				BData[11] = "<a title='Name length "+fix.length+"'>"+DectoRevHex(fix.length*2,2,0)+"</a><a title='Transformed name from "+fix+"'>"+NametoHex(fix)+"</a>";
			}	
			BData[12] = "<a title='Physical Defense: "+parseInt(document.getElementById('Inp_B_PDef').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_B_PDef').value),8,0)+"</a>";
			BData[13] = "<a title='Dodge: "+parseInt(document.getElementById('Inp_B_Dodge').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_B_Dodge').value),8,0)+"</a>";
			BData[14] = "<a title='Hit Point: "+parseInt(document.getElementById('Inp_B_HP').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_B_HP').value),8,0)+"</a>";
			BData[15] = "<a title='Mana Point: "+parseInt(document.getElementById('Inp_B_MP').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_B_MP').value),8,0)+"</a>";
			BData[16] = "<a title='Metal Defense: "+parseInt(document.getElementById('Inp_B_Metal').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_B_Metal').value),8,0)+"</a>";
			BData[17] = "<a title='Wood Defense: "+parseInt(document.getElementById('Inp_B_Wood').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_B_Wood').value),8,0)+"</a>";
			BData[18] = "<a title='Water Defense: "+parseInt(document.getElementById('Inp_B_Water').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_B_Water').value),8,0)+"</a>";
			BData[19] = "<a title='Fire Defense: "+parseInt(document.getElementById('Inp_B_Fire').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_B_Fire').value),8,0)+"</a>";
			BData[20] = "<a title='Earth Defense: "+parseInt(document.getElementById('Inp_B_Earth').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_B_Earth').value),8,0)+"</a>";
		
			var sockQty = document.getElementById('Inp_B_Socket').options[document.getElementById('Inp_B_Socket').selectedIndex].value;
			var refQty = document.getElementById('Inp_B_RefLv').options[document.getElementById('Inp_B_RefLv').selectedIndex].value;
			var AddonCount = AddInd;
			var SockAddons = "";
			var InsStones = 0;
			var InsStoneTxt = "";
			var tmpArr = [];

			BData[21] = " <a title='How much socket: "+sockQty+"'>"+DectoRevHex(sockQty,8,0)+"</a>";	
			for (i = 1; i <= sockQty; i++) {
				fix = 0;
				if (SckInd < i){
					BData[21] = BData[21] + " <a title='Socket #"+i+": Empty'>"+DectoRevHex(0,8,0)+"</a>";
				}else if (parseInt(tmpArr[0])==0){
					BData[21] = BData[21] + " <a title='Socket #"+i+": Empty'>"+DectoRevHex(0,8,0)+"</a>";
				}else{
					tmpArr = SckAddons[i].split("#");
					fix = parseInt(tmpArr[0]);
					BData[21] = BData[21] + " <a title='Socket #"+i+": "+tmpArr[4]+" gr."+tmpArr[5]+" ["+fix+"]'>"+DectoRevHex(fix,8,0)+"</a>";
					if ((fix > 0)&&(parseInt(tmpArr[1]) > 0)&&(parseInt(tmpArr[2]) > 0)){
						SockAddons = SockAddons + " <a title='Details from socket #"+i+": "+tmpArr[4]+" gr."+tmpArr[5]+" ["+fix+"], addon id: "+parseInt(tmpArr[1])+", value: "+parseInt(tmpArr[2])+" === "+tmpArr[3]+"'>"+DectoRevHex(parseInt(parseInt(tmpArr[1])),8,"a")+DectoRevHex(parseInt(parseInt(tmpArr[2])),8,"0")+"</a>";
						AddonCount++;
						InsStones++;
					}			
				}
			}		
			
			var OtherAddon = "";
			var bmaxDur = 0;
			var AddonType = 0;
			var RefAddons = "";
			var RefineOn = 0;
			var aType = document.getElementById('Inp_B_RefType').options[document.getElementById('Inp_B_RefType').selectedIndex].value;
			var AGrade = document.getElementById('Inp_B_RefGr').options[document.getElementById('Inp_B_RefGr').selectedIndex].value;
			var RefLevel = document.getElementById('Inp_B_RefLv').options[document.getElementById('Inp_B_RefLv').selectedIndex].value;
			var refTypeTxt = "";
			if (aType == 11){
				refTypeTxt = "Pattack [Axe, Sword, Spear etc]";
			}else if (aType == 12){
				refTypeTxt = "Pattack [Fist, Claw, Dagger]";
			}else if (aType == 13){
				refTypeTxt = "Pattack [Ranged Weapons]";
			}else if (aType == 14){
				refTypeTxt = "Mattack [Any magic weapon]";
			}else if (aType == 21){
				refTypeTxt = "HP [Heavy Armor]";
			}else if (aType == 22){
				refTypeTxt = "HP [Light Armor]";
			}else if (aType == 23){
				refTypeTxt = "HP [Magic Armor]";
			}else if (aType == 31){
				refTypeTxt = "Pdef [Neck, Belt, Ring]";
			}else if (aType == 32){
				refTypeTxt = "Mdef [Neck, Belt, Ring]";
			}else if (aType == 33){
				refTypeTxt = "Dodge [Neck, Belt]";
			}
			if (RefLevel > 0){
				RefineOn = 1;
				var aArr = refBase[aType[0]][aType[1]][AGrade].split("#");
				fix = parseInt(parseInt(aArr[0])*refMulti[RefLevel]);
				RefAddons = " <a title='Refined: +"+RefLevel+" (+"+fix+", RefType: "+refTypeTxt+")'>"+DectoRevHex(parseInt(aArr[1]),8,4)+DectoRevHex(fix,8,0)+DectoRevHex(RefLevel,8,0)+"</a>";
			}
			
			var bPdef = 0;
			var bHP = 0;
			var bMP = 0;
			var bDodge = 0;
			var bMetal = 0;
			var bWood = 0;
			var bWater = 0;
			var bFire = 0;
			var bEarth = 0;		
			for (i = 1; i <= AddInd; i++) {
				ADataArr = Addons[i].split("#");
				AddonType=ADataArr[0].substr(0, 4);
				if (AddonType == "1421"){
					bHP = bHP + parseInt(ADataArr[4]);
				}else if (AddonType == "7322"){
					bMP = bMP + parseInt(ADataArr[4]);
				}else if (AddonType == "db20"){
					bPdef = bPdef + parseInt(ADataArr[4]);
				}else if (AddonType == "3221"){
					bMetal = bMetal + parseInt(ADataArr[4]);
					bWood = bWood + parseInt(ADataArr[4]);
					bWater = bWater + parseInt(ADataArr[4]);
					bFire = bFire + parseInt(ADataArr[4]);
					bEarth = bEarth + parseInt(ADataArr[4]);
				}else if (AddonType == "9e22"){
					bDodge = bDodge + parseInt(ADataArr[4]);
				}else if (AddonType == "6d21"){
					bMetal = bMetal + parseInt(ADataArr[4]);
				}else if (AddonType == "7021"){
					bWood = bWood + parseInt(ADataArr[4]);
				}else if (AddonType == "7321"){
					bWater = bWater + parseInt(ADataArr[4]);
				}else if (AddonType == "7621"){
					bFire = bFire + parseInt(ADataArr[4]);
				}else if (AddonType == "7921"){
					bEarth = bEarth + parseInt(ADataArr[4]);
				}else if (AddonType == "2c21"){
					bmaxDur = bmaxDur + parseInt(ADataArr[4]);
				}
				
				OtherAddon = OtherAddon+" <a title='Addon: "+ADataArr[1]+"'>"+ADataArr[0]+"</a>";
			}
			fix = parseInt(parseInt(document.getElementById('Inp_B_PDef').value, 10)+bPdef, 10);
			BData[12] = "<a title='Physical Defense: "+parseInt(document.getElementById('Inp_B_PDef').value)+" +"+bPdef+"'>"+DectoRevHex(fix,8,0)+"</a>";
			fix = parseInt(parseInt(document.getElementById('Inp_B_Dodge').value)+bDodge);
			BData[13] = "<a title='Dodge: "+parseInt(document.getElementById('Inp_B_Dodge').value)+" +"+bDodge+"'>"+DectoRevHex(fix,8,0)+"</a>";
			fix = parseInt(parseInt(document.getElementById('Inp_B_HP').value)+parseInt(bHP));
			BData[14] = "<a title='Hit Point: "+parseInt(document.getElementById('Inp_B_HP').value)+" +"+bHP+"'>"+DectoRevHex(fix,8,0)+"</a>";
			fix = parseInt(parseInt(document.getElementById('Inp_B_MP').value)+bMP);
			BData[15] = "<a title='Mana Point: "+parseInt(document.getElementById('Inp_B_MP').value)+" +"+bMP+"'>"+DectoRevHex(fix,8,0)+"</a>";
			fix = parseInt(parseInt(document.getElementById('Inp_B_Metal').value)+bMetal);
			BData[16] = "<a title='Metal Defense: "+parseInt(document.getElementById('Inp_B_Metal').value)+" +"+bMetal+"'>"+DectoRevHex(fix,8,0)+"</a>";
			fix = parseInt(parseInt(document.getElementById('Inp_B_Wood').value)+bWood);
			BData[17] = "<a title='Wood Defense: "+parseInt(document.getElementById('Inp_B_Wood').value)+" +"+bWood+"'>"+DectoRevHex(fix,8,0)+"</a>";
			fix = parseInt(parseInt(document.getElementById('Inp_B_Water').value)+bWater);
			BData[18] = "<a title='Water Defense: "+parseInt(document.getElementById('Inp_B_Water').value)+" +"+bWater+"'>"+DectoRevHex(fix,8,0)+"</a>";
			fix = parseInt(parseInt(document.getElementById('Inp_B_Fire').value)+bFire);
			BData[19] = "<a title='Fire Defense: "+parseInt(document.getElementById('Inp_B_Fire').value)+" +"+bFire+"'>"+DectoRevHex(fix,8,0)+"</a>";
			fix = parseInt(parseInt(document.getElementById('Inp_B_Earth').value)+bEarth);
			BData[20] = "<a title='Earth Defense: "+parseInt(document.getElementById('Inp_B_Earth').value)+" +"+bEarth+"'>"+DectoRevHex(fix,8,0)+"</a>";

			
			if (bmaxDur > 0){
				fix = parseInt(parseInt(document.getElementById('Inp_B_MaxDur').value) * (100+bmaxDur))
				AData[8] = "<a title='Item max duratibility: "+parseInt(document.getElementById('Inp_B_MaxDur').value)+" +"+bmaxDur+"%'>"+DectoRevHex(fix,8,0)+"</a>";

			}
			InsStoneTxt = "<a title='addons[socket, normal, refine]: "+(InsStones+AddInd+RefineOn)+"'>"+DectoRevHex((InsStones+AddInd+RefineOn),8,0);
			BData[21] = BData[21]+InsStoneTxt+OtherAddon+RefAddons+SockAddons;
			BData[22] = "";
			if (LastOctet != BData.join("")) {
				document.getElementById('octet1').innerHTML = BData.join(" ");
				document.getElementById('Inp_Octet').value = BData.join(" ").replace(/<\/?[^>]+(>|$)/g, "").replace(/ /g,"");
			}
			LastOctet = BData.join("");			
		}else if (SIType == 4){
			var fix;
			var TradeStat = "Not Tradeable";
			var elfLv = parseInt(document.getElementById('Inp_E_Lv').value, 10);
			
			EData[1] = "<a title='Current Experience: "+parseInt(document.getElementById('Inp_E_Exp').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_E_Exp').value),8,0)+"</a>";
			EData[2] = "<a title='Elf Level: "+elfLv+"'>"+DectoRevHex(elfLv,4,0)+"</a>";
			var elfTalent = Math.floor(elfLv/5)+1;
			if (elfLv > 100){
				elfTalent = elfTalent + (elfLv - 100);
				if (elfTalent > 26){elfTalent=26;}
			}
			var elfPoint = parseInt(document.getElementById('Inp_E_LuckPoint').value, 10)+elfLv-1;
			EData[3] = "<a title='Elf Points: "+elfPoint+"'>"+DectoRevHex(elfPoint,4,0)+"</a>";
			EData[4] = "<a title='How much point was spent to Strength: "+parseInt(document.getElementById('Inp_E_STR').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_E_STR').value),4,0)+"</a>";
			EData[5] = "<a title='How much point was spent to Agility: "+parseInt(document.getElementById('Inp_E_AGI').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_E_AGI').value),4,0)+"</a>";
			EData[6] = "<a title='How much point was spent to Constitution: "+parseInt(document.getElementById('Inp_E_CON').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_E_CON').value),4,0)+"</a>";
			EData[7] = "<a title='How much point was spent to Intelligence: "+parseInt(document.getElementById('Inp_E_INT').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_E_INT').value),4,0)+"</a>";
			
			EData[8] = "<a title='Talent points: "+elfTalent+"'>"+DectoRevHex(elfTalent,4,0)+"</a>";
			EData[9] = "<a title='Metal Talent: "+document.getElementById('Inp_E_Metal').value+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_E_Metal').value),4,0)+"</a>";
			EData[10] = "<a title='Wood Talent: "+document.getElementById('Inp_E_Wood').value+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_E_Wood').value),4,0)+"</a>";
			EData[11] = "<a title='Water Talent: "+document.getElementById('Inp_E_Water').value+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_E_Water').value),4,0)+"</a>";
			EData[12] = "<a title='Fire Talent: "+document.getElementById('Inp_E_Fire').value+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_E_Fire').value),4,0)+"</a>";
			EData[13] = "<a title='Earth Talent: "+document.getElementById('Inp_E_Earth').value+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_E_Earth').value),4,0)+"</a>";
			fix = document.getElementById('Inp_E_RefLv').options[document.getElementById('Inp_E_RefLv').selectedIndex].value;
			EData[14] = "<a title='Elf Refine Level: "+fix+"'>"+DectoRevHex(fix,4,0)+"</a>";
			EData[15] = "<a title='Elf Current Stamina: "+document.getElementById('Inp_E_Stamina').value+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_E_Stamina').value),8,0)+"</a>";
			fix = document.getElementById('Inp_E_Trade').options[document.getElementById('Inp_E_Trade').selectedIndex].value;
		
			if (fix == "c7a24c4f"){TradeStat = "Tradeable";}
			EData[16] = "<a title='Elf Trade Stat: "+TradeStat+"'>"+fix+"</a>";
			var gearData;
			var EGInd = 0;
			var GearArr = [];
			var gearAddon="";
			for (i = 1; i <= 4; i++) {
				gearData = document.getElementById('Inp_E_GearId'+i).value + "#" + document.getElementById('Inp_E_GearName'+i).value;
				if (document.getElementById('Inp_E_GearId'+i).value != "0"){
					GearArr = gearData.split("#");
					EGInd++;
					gearAddon = gearAddon + " <a title='Equiped Gear: "+GearArr[1]+" [id: "+GearArr[0]+"]'>"+DectoRevHex(parseInt(GearArr[0],10),8,0)+"</a>";
				}
			}
					
			EData[17] = " <a title='Elf Gear Counter: "+EGInd+"'>"+DectoRevHex(EGInd,8,0)+"</a>" + gearAddon;
			EData[18] = " <a title='Elf Skill Counter: "+ESInd+"'>"+DectoRevHex(ESInd,8,0)+"</a>";
			var skillAddon="";
			var ADataArr = [];
			if (ESInd > 0){
				for (i = 1; i <= ESInd; i++) {
					ADataArr = ESkill[i].split("#");
					skillAddon = skillAddon + " <a title='Elf Skill: "+ADataArr[4]+" [id: "+ADataArr[1]+"] and level "+ADataArr[0]+" ("+ADataArr[5]+")'>"+DectoRevHex(ADataArr[1],4,0) + DectoRevHex(ADataArr[0],4,0)+"</a>";
				}
			}
			EData[18] = EData[18] + skillAddon;
			if (LastOctet != EData.join("")) {
				document.getElementById('octet1').innerHTML = EData.join(" ");
				document.getElementById('Inp_Octet').value = EData.join(" ").replace(/<\/?[^>]+(>|$)/g, "").replace(/ /g,"");
			}
			LastOctet = EData.join("");	
		}else if(SIType == 5){
			var HData = [];
			if (document.getElementById('RadHiero1').checked === true){
				var HAmount = parseInt(document.getElementById('Inp_H_Amount').value ,10);
				var HAct = parseInt(document.getElementById('Inp_H_Act').value ,10);
				if (HAct < 0) {HAct = 0;}
				if (HAct > 100) {HAct = 100;}
				HAct = HAct / 100.00;
				if (HAmount < 0) {HAmount = 0;}
				HData [1] = "<a title='Remaing amount on hiero: "+HAmount+"'>"+DectoRevHex(HAmount,8,0)+"</a>";
				HData [2] = "<a title='Activated when your hp or mp lower than : "+HAct+"% (50% = 0.5, float)'>"+numToFloat32Hex(HAct, true)+"</a>";
			}else if (document.getElementById('RadHiero2').checked === true){
				var el = document.getElementById('Inp_H_AType');
				var HType = parseInt(el.options[el.selectedIndex].value ,10);
				var HDmg = parseInt(document.getElementById('Inp_H_Damage').value ,10);
				var HMin = parseInt(document.getElementById('Inp_H_DMinGrd').value ,10);
				var HMax = parseInt(document.getElementById('Inp_H_DMaxGrd').value ,10);
				if (HType < 0) {HType = 0;}
				if (HDmg < 0) {HDmg = 0;}
				if (HDmg > 65535) {HDmg = 65535;}
				if (HType > 1) {HType = 1;}
				if (HMin < 0) {HMin = 0;}
				if (HMin > 20) {HMin = 20;}
				if (HMax < 0) {HMax = 0;}
				if (HMax > 20) {HMax = 20;}			
				HData [1] = "<a title='Attack Hiero Type: "+HType+" (0=physical, 1=magical)'>"+DectoRevHex(HType,4,0)+"</a>";
				HData [2] = "<a title='Min Weapon Grade for Hiero: "+HMin+" (else unuseable)'>"+DectoRevHex(HMin,4,0)+"</a>";
				HData [3] = "<a title='Max Weapon Grade for Hiero: "+HMax+" (else unuseable)'>"+DectoRevHex(HMax,4,0)+"</a>";
				HData [4] = "<a title='Attack damage: "+HDmg+"'>"+DectoRevHex(HDmg,4,0)+"</a>";
			}else if (document.getElementById('RadHiero3').checked === true){
				var el = document.getElementById('Inp_H_DType');
				var HType = parseInt(el.options[el.selectedIndex].value ,10);
				var HMin = parseInt(document.getElementById('Inp_H_DMinLv').value ,10);
				var HMax = parseInt(document.getElementById('Inp_H_DMaxLv').value ,10);			
				if (HMin < 1) {HMin = 1;}
				if (HMin > 150) {HMin = 150;}
				if (HMax < 1) {HMax = 1;}
				if (HMax > 150) {HMax = 150;}					
				var HRed = parseInt(document.getElementById('Inp_H_Defence').value ,10);
				if (HRed < 0) {HRed = 0;}
				if (HRed > 100) {HRed = 100;}
				HRed = HRed / 100.00;
				HData [1] = "<a title='Defence Hiero Type: "+HType+" (0=physical dmg reduction or 1=magical dmg reduction)'>"+DectoRevHex(HType,8,0)+"</a>";
				HData [2] = "<a title='Max level: "+HMax+" (above it unuseable)'>"+DectoRevHex(HMax,8,0)+"</a>";
				HData [3] = "<a title='Damage reduction: "+HRed+"% (50% = 0.5, float)'>"+numToFloat32Hex(HRed, true)+"</a>";
				HData [4] = "<a title='Min level: "+HMin+" (above it unuseable)'>"+DectoRevHex(HMin,8,0)+"</a>";//"<a title='Max level: "+HType+" (above it unuseable)'>"+DectoRevHex(HType,8,0)+"</a>";
			}
			
			if (LastOctet != HData.join("")) {
				document.getElementById('octet1').innerHTML = HData.join(" ");
				document.getElementById('Inp_Octet').value = HData.join(" ").replace(/<\/?[^>]+(>|$)/g, "").replace(/ /g,"");
			}
			LastOctet = HData.join("");				
		}else if(SIType == 6){
			var ADataArr = [];
			var MData = [];
			var el = document.getElementById('Inp_M_Ammo');
			var fix = el.options[el.selectedIndex].value;
			MData[1] = "<a title='Level requirement: "+parseInt(document.getElementById('Inp_M_LvReq').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_M_LvReq').value),4,0)+"</a>";
			MData[2] = "<a title='Class requirement: "+parseInt(document.getElementById('Inp_M_Class').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_M_Class').value),4,0)+"</a>";
			MData[3] = "<a title='Strength requirement: "+parseInt(document.getElementById('Inp_M_STR').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_M_STR').value),4,0)+"</a>";
			MData[4] = "<a title='Constitution requirement: "+parseInt(document.getElementById('Inp_M_CON').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_M_CON').value),4,0)+"</a>";
			MData[5] = "<a title='Agility requirement: "+parseInt(document.getElementById('Inp_M_AGI').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_M_AGI').value),4,0)+"</a>";
			MData[6] = "<a title='Intelligence requirement: "+parseInt(document.getElementById('Inp_M_INT').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_M_INT').value),4,0)+"</a>";
			MData[7] = "<a title='Item duratibility: "+parseInt(document.getElementById('Inp_M_Dur1').value)+" (anyway have no effect)'>"+DectoRevHex(parseInt(document.getElementById('Inp_M_Dur1').value)*100,8,0)+"</a>";
			MData[8] = "<a title='Item max duratibility: "+parseInt(document.getElementById('Inp_M_Dur2').value)+"' (anyway have no effect)>"+DectoRevHex(parseInt(document.getElementById('Inp_M_Dur2').value)*100,8,0)+"</a>";
			MData[9] = "<a title='Unknown: 20 (all 3 type ammo got same value)'>"+DectoRevHex(20,8,0)+"</a>";
			MData[10] = "<a title='Ammo Type: "+fix+"'>"+DectoRevHex(fix,8,0)+"</a>";
			MData[11] = "<a title='Physical Damage: "+parseInt(document.getElementById('Inp_M_Damage').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_M_Damage').value),8,0)+"</a>";
			MData[12] = "<a title='Unknown: 0'>"+DectoRevHex(0,8,0)+"</a>";
			MData[13] = "<a title='Req. Min. Weapon Grade: "+parseInt(document.getElementById('Inp_M_Grade1').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_M_Grade1').value),8,0)+"</a>";
			MData[14] = "<a title='Req. Max. Weapon Grade: "+parseInt(document.getElementById('Inp_M_Grade2').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_M_Grade2').value),8,0)+"</a>";
			MData[15] = "<a title='Unknown: 0 (probabil socket hat must be 0)'>"+DectoRevHex(0,8,0)+"</a>";
			var OtherAddon = "";
			var AddonType = 0;
			for (i = 1; i <= AddInd; i++) {
				ADataArr = Addons[i].split("#");
				AddonType=ADataArr[0].substr(0, 4);
				OtherAddon = OtherAddon+" <a title='Addon: "+ADataArr[1]+"'>"+ADataArr[0]+"</a> ";
			}
			MData[16] = " <a title='Addons: "+(AddInd)+"'> "+DectoRevHex(AddInd,8,0)+OtherAddon;
			if (LastOctet != MData.join("")) {
				document.getElementById('octet1').innerHTML = MData.join(" ");
				document.getElementById('Inp_Octet').value = MData.join(" ").replace(/<\/?[^>]+(>|$)/g, "").replace(/ /g,"");
			}
			LastOctet = MData.join("");	
		}else if(SIType == 7){			
			var ADataArr = [];
			var PData = [];
			var amount = document.getElementById('Inp_X_AStack');
			PData[1] = "<a title='Level requirement: "+parseInt(document.getElementById('Inp_X_LvReq').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_X_LvReq').value),8,0)+"</a>";
			PData[2] = "<a title='Potion effect ID: "+parseInt(document.getElementById('Inp_X_Id').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_X_Id').value),8,0)+"</a>";
			PData[3] = "<a title='Potion Effect Level: "+parseInt(document.getElementById('Inp_X_Lv').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_X_Lv').value),8,0)+"</a>";
			if (parseInt(amount.value, 10) > 100){
				amount.value = 100;
				document.getElementById('Sel_Count').value=amount.value;
			}
			document.getElementById('Sel_Count').value=document.getElementById('Inp_X_AStack').value;
			if (LastOctet != PData.join("")) {
				document.getElementById('octet1').innerHTML = PData.join(" ");
				document.getElementById('Inp_Octet').value = PData.join(" ").replace(/<\/?[^>]+(>|$)/g, "").replace(/ /g,"");
			}
			LastOctet = PData.join("");	

		}else if(SIType == 8){			
			var PData = [];
			PData[1] = "<a title='When you use this item then this quest activated: "+parseInt(document.getElementById('Inp_T_Quest').value)+"'>"+DectoRevHex(parseInt(document.getElementById('Inp_T_Quest').value),8,0)+"</a>";
			if (LastOctet != PData.join("")) {
				document.getElementById('octet1').innerHTML = PData.join(" ");
				document.getElementById('Inp_Octet').value = PData.join(" ").replace(/<\/?[^>]+(>|$)/g, "").replace(/ /g,"");
			}
			LastOctet = PData.join("");	
		}else if(SIType == 9){			
			var PData = [];
			var elem = document.getElementById('Inp_G_Type');
			var gType = elem.options[elem.selectedIndex].value;
			var loyal = document.getElementById('Inp_G_Loyal');
			if (parseInt(loyal.value,10) < 0){
				loyal.value = 0;
			}else if (parseInt(loyal.value,10) > 999){
				loyal.value = 999;
			}
			PData[1] = "<a title='Grass Type: "+gType+"'>"+DectoRevHex(parseInt(gType, 10),8,0)+"</a>";
			PData[2] = "<a title='Grass give that much loyality: "+loyal.value+"'>"+DectoRevHex(parseInt(loyal.value, 10),8,0)+"</a>";
			if (LastOctet != PData.join("")) {
				document.getElementById('octet1').innerHTML = PData.join(" ");
				document.getElementById('Inp_Octet').value = PData.join(" ").replace(/<\/?[^>]+(>|$)/g, "").replace(/ /g,"");
			}
			LastOctet = PData.join("");	
		}else if(SIType == 10){			
			var PData = [];
			var weaId = parseInt(document.getElementById('Inp_S_WeaponId').value, 10);
			var weaVal = parseInt(document.getElementById('Inp_S_WeaponVal').value, 10);
			var armId = parseInt(document.getElementById('Inp_S_ArmorId').value, 10);
			var armVal = parseInt(document.getElementById('Inp_S_ArmorVal').value, 10);
			PData[1] = "<a title='Before addons have a 1, maybe because can attach multiple addon to 1 item? i not know'>"+DectoRevHex(1,8,0)+"</a>";
			PData[2] = "<a title='Addon ID for weapon is: "+weaId+"'>"+DectoRevHex(weaId,8,2)+"</a>";
			PData[3] = "<a title='Addon value for weapon is: "+weaVal+"'>"+DectoRevHex(weaVal,8,0)+"</a>";
			PData[4] = "<a title='Before addons have a 1, maybe because can attach multiple addon to 1 item? i not know'>"+DectoRevHex(1,8,0)+"</a>";
			PData[5] = "<a title='Addon ID for armor is: "+armId+"'>"+DectoRevHex(armId,8,2)+"</a>";
			PData[6] = "<a title='Addon value for armor is: "+armVal+"'>"+DectoRevHex(armVal,8,0)+"</a>";
			if (LastOctet != PData.join("")) {
				document.getElementById('octet1').innerHTML = PData.join(" ");
				document.getElementById('Inp_Octet').value = PData.join(" ").replace(/<\/?[^>]+(>|$)/g, "").replace(/ /g,"");
			}
			LastOctet = PData.join("");	
		}else if(SIType == 11){		
			var PData = [];
			var osel=document.getElementById('Inp_MO_Clss');
			var Prestige = parseInt(document.getElementById('Inp_MO_Prest').value, 10);
			var Order = parseInt(osel.options[osel.selectedIndex].value, 10);
			var Loss = parseInt(document.getElementById('Inp_MO_PLose').value, 10);	
			PData[1] = "<a title='Current prestige "+Prestige+"'>"+DectoRevHex(Prestige,8,0)+"</a>";
			PData[2] = "<a title='Order: "+Order+"'>"+DectoRevHex(Order,8,0)+"</a>";
			PData[3] = "<a title='Lose "+Loss+"% prestige, if leave the order'>"+DectoRevHex(Loss,8,0)+"</a>";
			if (LastOctet != PData.join("")) {
				document.getElementById('octet1').innerHTML = PData.join(" ");
				document.getElementById('Inp_Octet').value = PData.join(" ").replace(/<\/?[^>]+(>|$)/g, "").replace(/ /g,"");
			}
			LastOctet = PData.join("");				
		}else if(SIType == 12){	
			var PData = [];
			var tApt;
			var tmp;
			var Apt;
			var BSApt=[];
			var aDat=[];
			var AdRate=[];
			var AdData=[];
			var rt;
			var FSC = [0,1,4,16,64,256];
			var BSC = [0,2,8,32,128,512];
			var sCombo = 0;
			var weaId = parseInt(document.getElementById('Inp_S_WeaponId').value, 10);
			var selO = document.getElementById('Inp_SC_Clss');
			var Clss=parseInt(selO.options[selO.selectedIndex].value, 10);
			var Lv=parseInt(document.getElementById('Inp_SC_CurLv').value, 10);
			if (Lv<1){Lv=1;}else if(Lv>50){Lv=50;}
			var Exp=parseInt(document.getElementById('Inp_SC_CurExp').value, 10);
			var mExp=StarChartLv[Lv-1][0];
			var AdInd=0;
			if (Exp<0){Exp=0;}else if(Exp>mExp){Exp=mExp;}
			
			PData[1] = "<a title='StarChart experience: "+Exp+"'>"+DectoRevHex(Exp,8,0)+"</a>";
			PData[2] = "<a title='StarChart level: "+Lv+"'>"+DectoRevHex((Lv-1),2,0)+"</a>";
			
			for (var x=1; x<=5; x++){
				selO = document.getElementById('Inp_SC_FStarAddon'+x);
				tmp = selO.options[selO.selectedIndex].value;
				rt=document.getElementById('Inp_SC_FStarRate'+x).value;
				if (tmp!="0"){
					sCombo=sCombo+FSC[x];
					AdInd++;
					AdData[AdInd]=tmp;
					if (isNumber(rt)){
						AdRate[AdInd]=Number(rt);
					}else{
						AdRate[AdInd]=0;
					}					
				}				
				selO = document.getElementById('Inp_SC_BStarAddon'+x);
				tmp = selO.options[selO.selectedIndex].value;
				rt=document.getElementById('Inp_SC_BStarRate'+x).value;
				Apt=0;
				if (tmp!="0"){
					sCombo=sCombo+BSC[x];
					AdInd++;
					AdData[AdInd]=tmp;
					if (isNumber(rt)){
						AdRate[AdInd]=Number(rt);
					}else{
						AdRate[AdInd]=0;
					}
				}
				tApt = document.getElementById('Inp_SC_BStarApt'+x).value;
				if (isNumber(tApt)){
					if (Number(tApt)>655.35){
						Apt=655.35;
					}else if (Number(tApt)>0){
						Apt=Number(tApt);
					}
				}				
				if (Number(Apt)>0){
					Apt = Math.floor(Number(Apt)*100);
				}
				PData[3+x]="<a title='#"+x+" Birtstar Aptitude: "+(Apt/100).toFixed(2)+"'>"+DectoRevHex(Apt,4,0)+"</a>";
			}
			PData[3] = "<a title='StarChart star combo, each star have a value: "+sCombo+"'>"+DectoRevHex(sCombo,4,0)+"</a>";
			PData[9] = "<a title='How much addon or opened star: "+AdInd+"'>"+DectoRevHex(AdInd,8,0)+"</a>";
			
			if (AdInd>0){
				for (var x=1; x<=AdInd; x++){
					aDat=AdData[x].split("#");
					PData[8+x*2] = "<a title='#"+x+" Addon is: "+aDat[3]+" [id:"+aDat[0]+"]'>"+DectoRevHex(aDat[0],8,2)+"</a>";
					PData[9+x*2] = "<a title='#"+x+" Rate is: "+AdRate[x]+"'>"+numToFloat32Hex(AdRate[x],8)+"</a>";
				}
			}
			if (LastOctet != PData.join("")) {
				document.getElementById('octet1').innerHTML = PData.join(" ");
				document.getElementById('Inp_Octet').value = PData.join(" ").replace(/<\/?[^>]+(>|$)/g, "").replace(/ /g,"");
			}
			LastOctet = PData.join("");		
		}
	}else if ((CIType == "U")||(CIType == "M")){
			var PData = [];
			var chunks = [];
			var txt = document.getElementById('Inp_Ms_Octet').value
			for (var i = 0, charsLength = txt.length; i < charsLength; i += 8) {
				chunks.push(txt.substring(i, i + 8));
			}
			PData[1] = 	chunks.join(" ");
			if (LastOctet != PData.join("")) {
				document.getElementById('octet1').innerHTML = PData.join(" ");
				document.getElementById('Inp_Octet').value = PData.join(" ").replace(/<\/?[^>]+(>|$)/g, "").replace(/ /g,"");
			}
			LastOctet = PData.join("");
	}else if (CIType == "F"){
			var PData = [];
			var lvReq = document.getElementById('Inp_Fs_LvReq');
			var FashCol = document.getElementById('Inp_Fs_Color2').value;
			var fashGender = 0;
			var FGender = [];
			FGender[0]="Male";
			FGender[1]="Female";
			if (document.getElementById('FashFemale').checked === true){fashGender = 1;}
			if (parseInt(lvReq.value,10) < 0){
				lvReq.value = 0;
			}else if (parseInt(lvReq.value,10) > 150){
				lvReq.value = 150;
			}
			var ColSel = document.getElementById('Inp_Fs_Color1');
			var ColName = ColSel.options[ColSel.selectedIndex].text;
			PData[1] = "<a title='Level Requirement: "+gType+"'>"+DectoRevHex(parseInt(lvReq.value, 10),8,0)+"</a>";
			PData[2] = "<a title='Color: "+ColName+" [it is in octet: "+FashCol+"]'>"+FashCol+"</a>";
			PData[3] = "<a title='Gender: "+FGender[fashGender]+" ["+fashGender+"]'>"+DectoRevHex(fashGender,4,0)+"</a>";
			PData[4] = "<a title='Unknown: 3 (this fixed)'>"+DectoRevHex(3,8,0)+"</a>";
			if (LastOctet != PData.join("")) {
				document.getElementById('octet1').innerHTML = PData.join(" ");
				document.getElementById('Inp_Octet').value = PData.join(" ").replace(/<\/?[^>]+(>|$)/g, "").replace(/ /g,"");
			}
			LastOctet = PData.join("");	
	}else if (CIType == "C"){
			var CData = [];
			var SelO = document.getElementById('Inp_C_Type');
			var cTyp = parseInt(SelO.options[SelO.selectedIndex].value, 10);
			SelO = document.getElementById('Inp_C_Grade');
			var cGra = parseInt(SelO.options[SelO.selectedIndex].value, 10);
			var cLvR = parseInt(document.getElementById('Inp_C_LvReq').value, 10);
			var cLea = parseInt(document.getElementById('Inp_C_Lead').value, 10);
			var cMxL = parseInt(document.getElementById('Inp_C_MaxLv').value, 10);
			var cCLv = parseInt(document.getElementById('Inp_C_CurLv').value, 10);
			var cExp = parseInt(document.getElementById('Inp_C_ExpLv').value, 10);
			SelO = document.getElementById('Inp_C_Reawake');
			var cReb = parseInt(SelO.options[SelO.selectedIndex].value, 10);
			CData[1] = "<a title='Card Type: "+CardType[cTyp]+" ["+cTyp+"]'>"+DectoRevHex(cTyp,8,0)+"</a>";
			CData[2] = "<a title='Card Grade: "+CardGrade[cGra]+" ["+cGra+"]'>"+DectoRevHex(cGra,8,0)+"</a>";
			CData[3] = "<a title='Card Level Req.: "+cLvR+"'>"+DectoRevHex(cLvR,8,0)+"</a>";
			CData[4] = "<a title='Req Leadership: "+cLea+"'>"+DectoRevHex(cLea,8,0)+"</a>";
			CData[5] = "<a title='Card Max Level: "+cMxL+"'>"+DectoRevHex(cMxL,8,0)+"</a>";
			CData[6] = "<a title='Card Level: "+cCLv+"'>"+DectoRevHex(cCLv,8,0)+"</a>";
			CData[7] = "<a title='Card Current EXP: "+cExp+"'>"+DectoRevHex(cExp,8,0)+"</a>";
			CData[8] = "<a title='Card Rebirth: "+cReb+"'>"+DectoRevHex(cReb,8,0)+"</a>";
			if (LastOctet != CData.join("")) {
				document.getElementById('octet1').innerHTML = CData.join(" ");
				document.getElementById('Inp_Octet').value = CData.join(" ").replace(/<\/?[^>]+(>|$)/g, "").replace(/ /g,"");
			}
			LastOctet = CData.join("");		
			
	}
	
	if (document.getElementById("Expire_Act").checked !== true){
		document.getElementById("Sel_Expire").value = 0;
	}else if (document.getElementById("Expire_Stop").checked !== false){
		//we don't change the expiration field
	}else{
		var tmp = document.getElementById("Cur_PlusType");
		var tmpnr = (Date.now() / 1000 | 0) + TCS ;
		var tmpdiff = (tmp.options[tmp.selectedIndex].value* (parseInt(document.getElementById("Cur_PlusNr").value,10) || 0));
		document.getElementById("Cur_Time").value = tmpnr;
		document.getElementById("Sel_Expire").value = tmpnr+tmpdiff;
	}
}, 1000);

function ChangeElfGearIcon(id){
	document.getElementById("elfGearId").innerHTML=id;
	for(var i=1;i<5;i++){
		if (i==id){
			document.getElementById("Inp_E_Gear"+i).style.display="inline";
			document.getElementById("Inp_E_GearId"+i).style.display="inline";
		}else{
			document.getElementById("Inp_E_Gear"+i).style.display="none";
			document.getElementById("Inp_E_GearId"+i).style.display="none";
		}
	}

}

function ChangeElfGear(id){
	var e = document.getElementById("Inp_E_Gear"+id);
	var GearData = e.options[e.selectedIndex].value;
	var myArr = [];
	var elfLevel = parseInt(document.getElementById("Inp_E_Lv").value, 10);
	myArr = GearData.split("#");
	var reqLv = parseInt(myArr[1], 10);
	if (elfLevel < reqLv){
		e.selectedIndex = 0;
		document.getElementById("EGearImg"+id).src="../images/icons/slot.gif";
		document.getElementById("Inp_E_GearId"+id).value = 0;
		document.getElementById('Inp_E_GearName'+id).value = "";
		alert("This item need level "+reqLv+", but your genie is level "+elfLevel+"!");
	}else{
		if (GearData != "0"){
			document.getElementById("Inp_E_GearId"+id).value = myArr[0];
			document.getElementById("Inp_E_GearName"+id).value = myArr[2];
			document.getElementById("EGearImg"+id).src="../images/icons/"+myArr[0]+".gif";
		}else{
			document.getElementById("Inp_E_GearId"+id).value = 0;
			document.getElementById("Inp_E_GearName"+id).value = "";
			document.getElementById("EGearImg"+id).src="../images/icons/slot.gif";
		}
	}
}

function ChangeCardLevel(val){
	var cLv = document.getElementById('Inp_C_CurLv');
	var cLvV = parseInt(cLv.value, 10);
	var cmLv = document.getElementById('Inp_C_MaxLv').value;
	if (val == -999){
		cLv.value = 1;
	}else if (val == -1){
		if (cLvV > 1){
			cLv.value = cLvV - 1;
		}
	}else if (val == 1){
		if (cLvV < cmLv){
			cLv.value = cLvV + 1;
		}		
	}else if (val == 999){
		cLv.value = cmLv;
	}
	GetCardInfo();
}

function GetCardInfo(){
	var CardID = parseInt(document.getElementById('CardID').value, 10);
	var CData = CardStat[CardID];
	var SelO = document.getElementById('Inp_C_Clss');
	var SlCl = parseInt(SelO.options[SelO.selectedIndex].value,10);
	SelO = document.getElementById('Inp_C_Type');
	var sct = parseInt(SelO.options[SelO.selectedIndex].value,10);
	SelO = document.getElementById('Inp_C_Grade');
	var cGrade = parseInt(SelO.options[SelO.selectedIndex].value,10);
	var cLv = parseInt(document.getElementById('Inp_C_CurLv').value,10);
	var MaxLv = parseInt(document.getElementById('Inp_C_MaxLv').value,10);

	if (cLv < 1){
		document.getElementById('Inp_C_CurLv').value=1;
		cLv=1;
	}else if(cLv>MaxLv){
		document.getElementById('Inp_C_CurLv').value=MaxLv;
		cLv=MaxLv;		
	}
	var cExp = parseInt(document.getElementById('Inp_C_ExpLv').value,10);
	var MExp = CardMExp[cGrade][cLv-1][0];	
	if (cExp < 0){
		document.getElementById('Inp_C_ExpLv').value=0;
		cExp=0;
	}else if(cExp>MExp){
		document.getElementById('Inp_C_ExpLv').value=MExp;
		cExp=MExp;		
	}	
	if (cLv == MaxLv){
		document.getElementById('Inp_C_ExpLv').value = 0;
		cExp = 0;
	}
	SelO = document.getElementById('Inp_C_Reawake');
	var BaseDesc = "<font color='"+CardCol[cGrade]+"'>"+CData[0]+"</font><br>Type: "+CardType[sct]+"<br>";
	var cRebirth = parseInt(SelO.options[SelO.selectedIndex].value,10);	
	var cLead = document.getElementById('Inp_C_Lead').value;
	if (cLead < 0){
		document.getElementById('Inp_C_Lead').value=0;
		cLead=0;
	}	
	var cLvR = document.getElementById('Inp_C_LvReq').value;
	if (cLvR < 1){
		document.getElementById('Inp_C_LvReq').value=1;
		cLvR=1;
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
	
	document.getElementById('Inp_C_MaxExp').innerHTML = MExp;
	var LExp = 0;
	if (cLv > 1){
		LExp = CardMExp[cGrade][cLv-1][1];
	}
	var DevXP = (CardDev[cGrade]+cExp+LExp)+(cRebirth*(CardMExp[cGrade][MaxLv-1][1]));

	BaseDesc = BaseDesc + "Level: "+cLv+"/"+MaxLv+ "<br>Exp: "+cExp+"/"+MExp+"<br>Class: " + ClassIdToClassName(SlCl) + "<br>Level Req.: " + cLvR + "<br>Leadship Req.: " + cLead + "<br>";
	//cExp cardLevels
	BaseDesc = BaseDesc + "If devoured: "+DevXP+ " EXP";
	document.getElementById("CardBase").innerHTML = BaseDesc;
	var CBonDiv = document.getElementById("CardBonus");
	var tmp;
	if (CData[5] != ""){
		var CBonus = CData[5];
		if (CBonus.indexOf("*") != -1){
			var txt="";
			CBonus = CBonus.split('*');
			for(var i=0;i<CBonus.length;i++){
				tmp = CBonus[i].split(',')
				txt=txt+GetAddonString(parseInt(tmp[0], 10), tmp[1])+"<br>";
			}
			CBonDiv.innerHTML = txt;
		}else{
			CBonus = CBonus.split(',');
			CBonDiv.innerHTML = GetAddonString(parseInt(CBonus[0], 10), CBonus[1])+"<br>";
		}
		CBonDiv.style.display = 'block';
	}else{
		CBonDiv.innerHTML = "";
	}	
	
	var CSetDiv = document.getElementById("CardSet");
	var CSetTxt = "";
	if (CData[6] != 0){
		var CSetId = CData[6];
		var CSet = CardSet[CSetId];
		var CInSet = CSet[2].length;
		var CSetTxt = "<font color='#ffcc00'>Set Bonus: "+(CSet[1]*100)+"%</font><br><font color='yellow'>"+CSet[0]+" (1/"+(CInSet)+")</font><br>";
		for(var i=0;i<CInSet;i++){
			if (CardID == CSet[2][i]){
				CSetTxt += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='#ccff00'>"+CardStat[CSet[2][i]][0]+"</font><br>";
			}else{
				CSetTxt += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='#7777ff'>"+CardStat[CSet[2][i]][0]+"</font><br>";
			}
		}
	}
	CSetDiv.innerHTML = CSetTxt;
}

function getPItemData(e){
	var myArr = [];
	var cat;
	var sct;
	var iCol;
	var cINM;
	var ItmTit;
	myArr = e.id.split("C");
	myArr = myArr[1].split("S");
	cat = myArr[0];
	sct = myArr[1];
	var eId = document.getElementById("Sel_Item_Id");
	myArr = e.value.split("#");
	iCol = parseInt(myArr[3], 10);
	cINM="<font color='"+itmCol[iCol]+"'>"+myArr[0]+"</font>";
	document.getElementById("Inp_Color").value = iCol;
	document.getElementById("Inp_Color2").innerHTML = itmCol[iCol];
	document.getElementById("Inp_Description").value = '';
	var itmId = myArr[1];
	var tmpdesc = "";
	if (myArr[5] != null){
		tmpdesc=myArr[5];
	}
	eId.value = itmId;
	document.getElementById('item_icon').src = "../images/icons/"+itmId+".gif";
	//eId.value = itmId.replace(/'/g, "&#39;");
	if ((cat == "1")||(cat == "2")||(cat == "3")){
		ItmTit = document.getElementById("Gear"+cat+"_Name");
		document.getElementById('Inp_Title').value=myArr[0];
		var e1 = document.getElementById("Inp_Grade1");
		e1.selectedIndex = myArr[2]-1;
		ChangeGrade();
	}else if (cat == "4"){
		if (sct==1){
			var reqLv = document.getElementById("Inp_F_ReqLv");
			var FRace = document.getElementById("Inp_F_Race");
			var FGrade = document.getElementById("Inp_F_Grade");
			var FFuel1 = document.getElementById("Inp_F_Fuel1");
			var FFuel2 = document.getElementById("Inp_F_Fuel2");
			var FSpeed1 = document.getElementById("Inp_F_Speed1");
			var FSpeed2 = document.getElementById("Inp_F_Speed2");
			ItmTit = document.getElementById("Flyer_Name");
			document.getElementById('Inp_Title').value=myArr[0];
			myArr = myArr[4].split(" ");
			reqLv.value = myArr[1];
			FGrade.value = myArr[0];
			FRace.selectedIndex = myArr[2];
			FSpeed1.value = myArr[3];
			FSpeed2.value = myArr[4];
			FFuel1.value = parseInt(parseInt(myArr[5], 10)/2);
			FFuel1.value = myArr[5];
		}else if (sct==2){
			ItmTit = document.getElementById("Pet_Name");
			document.getElementById('Inp_Title').value=myArr[0];
			document.getElementById("Inp_P_EggId").value = myArr[1];
			myArr = myArr[4].split(" ");
			var petType = parseInt(myArr[1],10);
			document.getElementById("Inp_P_PetId").value = myArr[0];
			document.getElementById("Inp_P_PetType1").selectedIndex = petType;
			document.getElementById("Inp_P_PetType2").value = petType;
			if (petType == 1){
				document.getElementById("Inp_P_ReqLv").max = 105;
				document.getElementById("Inp_P_Class1").selectedIndex = 1;
				document.getElementById("Inp_P_Class2").value = 8;
				document.getElementById("Pet_Skill_Div").style.display = "inline";
			}else{
				if (petType == 0){
					document.getElementById("Inp_P_ReqLv").max = 11;
				}else{
					document.getElementById("Inp_P_ReqLv").max = 1;
				}
				document.getElementById("Pet_Skill_Div").style.display = "none";
				var sel1 = document.getElementById("Inp_P_Class1");
				sel1.selectedIndex = 0;	
				document.getElementById("Inp_P_Class2").value = sel1.options[0].value;				
			}
			var petLv = parseInt(document.getElementById("Inp_P_Lv").value, 10);
			if (petLv < 1) {document.getElementById("Inp_P_Lv").value = 1;}
			var petMaxLv = 0;
			if (petType == 0) {
				petMaxLv = 11;
				document.getElementById("Sel_ProcType2").value=0;
			}else if (petType == 1){
				petMaxLv = 105;
				document.getElementById("Sel_ProcType2").value=0;
			}else if (petType == 2){
				petMaxLv = 1;
				document.getElementById("Sel_ProcType2").value=8;
			}
			
			if (petLv > petMaxLv){
				petLv = petMaxLv;
				document.getElementById("Inp_P_Lv").value = petLv;
			}
		}else if (sct==3){
			ItmTit = document.getElementById("BBox_Name");
			document.getElementById('Inp_Title').value=myArr[0];
		}else if (sct==4){
			ItmTit = document.getElementById("Elf_Name");
			document.getElementById('Inp_Title').value=myArr[0];
			var eStat = ElfStartStat(itmId);
			document.getElementById("E_StartStr").innerHTML = eStat[0];
			document.getElementById("E_StartAgi").innerHTML = eStat[1];
			document.getElementById("E_StartInt").innerHTML = eStat[2];
			document.getElementById("E_StartCon").innerHTML = eStat[3];
		}else if (sct==5){
			var hieroTypes = [];
			hieroTypes[1] = "HP";
			hieroTypes[2] = "MP";
			var hierName = myArr[0];
			ItmTit = document.getElementById("Hiero_Name");
			document.getElementById('Inp_Title').value=hierName;
			myArr = myArr[4].split(" ");
			var hierotyp = parseInt(myArr[0], 10);
			if ((hierotyp == 1)||(hierotyp==2)){
				document.getElementById("HieroTypeSpan1").innerHTML = hieroTypes[hierotyp];
				document.getElementById("HieroTypeSpan2").innerHTML = hieroTypes[hierotyp];
				document.getElementById("HieroCooldown").innerHTML = parseInt(myArr[3], 10);
				document.getElementById("HieroMaxSpan").innerHTML = parseInt(myArr[1], 10);
				document.getElementById("Inp_H_Act").value = parseInt(myArr[2], 10);	
				document.getElementById("Inp_H_Amount").value = parseInt(myArr[1], 10);	
				document.getElementById('Sel_Mask1').selectedIndex = (18+hierotyp);
				document.getElementById('Sel_Mask2').value = document.getElementById('Sel_Mask1').options[(18+hierotyp)].value;
				document.getElementById('RadHiero1').checked = true;
				document.getElementById('Sel_Count').value=1;
				document.getElementById('Sel_MaxCount').value=1;
			}else if (hierotyp == 0){
				document.getElementById('Sel_MaxCount').value=parseInt(myArr[6], 10);
				document.getElementById('Sel_Mask1').selectedIndex = (18+hierotyp);
				document.getElementById('Sel_Mask2').value = document.getElementById('Sel_Mask1').options[(18+hierotyp)].value;
				var subHieroTyp = parseInt(myArr[1],10);
				var DmgTyp = parseInt(myArr[2],10);
				var hValue = parseInt(myArr[3],10);
				var hMin = parseInt(myArr[4],10);
				var hMax = parseInt(myArr[5],10);
				if (subHieroTyp==1){
					document.getElementById('RadHiero2').checked = true;
					document.getElementById('Inp_H_AType').selectedIndex = DmgTyp;
					document.getElementById('Inp_H_Damage').value = hValue;
					document.getElementById('Inp_H_DMinGrd').value = hMin;
					document.getElementById('Inp_H_DMaxGrd').value = hMax;
				}else if(subHieroTyp==2){
					document.getElementById('RadHiero3').checked = true;
					document.getElementById('Inp_H_DType').selectedIndex = DmgTyp;
					document.getElementById('Inp_H_Defence').value = hValue;
					document.getElementById('Inp_H_DMinLv').value = hMin;
					document.getElementById('Inp_H_DMaxLv').value = hMax;
				}
			}
		}else if (sct==6){
			ItmTit = document.getElementById("Ammo_Name");
			document.getElementById('Inp_Title').value=myArr[0];
			myArr = myArr[4].split(" ");
			document.getElementById("Inp_M_Ammo").selectedIndex = parseInt(myArr[1], 10);
			document.getElementById("Inp_M_Damage").value = parseInt(myArr[2], 10);
			document.getElementById('Sel_MaxCount').value = myArr[3];
		}else if (sct==7){
			ItmTit = document.getElementById("Potion_Name");
			document.getElementById('Inp_Title').value=myArr[0];
			myArr = myArr[4].split(" ");
			document.getElementById("Inp_X_LvReq").value = parseInt(myArr[0], 10);
			document.getElementById("Inp_X_Id").value = parseInt(myArr[1], 10);
			document.getElementById("Inp_X_Lv").value = parseInt(myArr[2], 10);
			document.getElementById('Sel_MaxCount').value=1;
			document.getElementById('Sel_MaxCount').value = myArr[3];
		}else if (sct==8){	
			ItmTit = document.getElementById("TaskDice_Name");
			document.getElementById('Inp_Title').value=myArr[0];
			myArr = myArr[4].split(" ");
			document.getElementById("Inp_T_Quest").value = parseInt(myArr[0], 10);
			var elem = document.getElementById("Inp_T_Amount");
			if (parseInt(elem.value, 10) > parseInt(myArr[1], 10)){
				elem.value = myArr[1];
			}
			document.getElementById('Sel_MaxCount').value = myArr[1];
			elem.min = parseInt(myArr[1], 10);
		}else if (sct==9){	
			ItmTit = document.getElementById("Grass_Name");
			document.getElementById('Inp_Title').value=myArr[0];
			myArr = myArr[4].split(" ");
			document.getElementById("Inp_G_Loyal").value = parseInt(myArr[2], 10);
			var gType = parseInt(myArr[1], 10)
			var sel = document.getElementById("Inp_G_Type");
			selI = 0;
			for (i = 0; i < sel.length; i++) {
				if (sel.options[i].value == gType){
					selI = i;
				}
			}
			sel.selectedIndex = selI;
			var elem = document.getElementById("Inp_G_Amount");
			if ((parseInt(elem.value, 10)) > (parseInt(myArr[0], 10))){
				elem.value = myArr[0];
			}
			elem.min = parseInt(myArr[0], 10);
			document.getElementById('Sel_MaxCount').value = myArr[0];
		}else if (sct==10){	
			ItmTit = document.getElementById("Stone_Name");
			document.getElementById('Inp_Title').value=myArr[0];
			var stnData = myArr[5].split("=");
			var stnAddIds = stnData[0].split("-");
			var stnAddTxt = stnData[1].split("-");
			stnAddTxt[0] = stnAddTxt[0].split(",");
			stnAddTxt[1] = stnAddTxt[1].split(",");
			var stnDesc = "<font color=red>Weapon: <font color='#007'>"+GetAddonString(stnAddTxt[0][0],stnAddTxt[0][1])+"</font><br>";
			stnDesc = stnDesc+"<font color=blue>Armor:</font> <font color='#007'>"+GetAddonString(stnAddTxt[1][0],stnAddTxt[1][1])+"</font><br>";
			document.getElementById("OctDesc"+sct).innerHTML = stnDesc;
			document.getElementById("Inp_S_WeaponVal").value = stnAddTxt[0][1];
			document.getElementById("Inp_S_ArmorVal").value = stnAddTxt[1][1];
			document.getElementById("Inp_S_WeaponId").value = stnAddIds[0];
			document.getElementById("Inp_S_ArmorId").value = stnAddIds[1];
			myArr = myArr[4].split(" ");
			var elem = document.getElementById("Inp_S_Amount");
			if ((parseInt(elem.value, 10)) > (parseInt(myArr[0], 10))){
				elem.value = myArr[0];
			}
			elem.min = parseInt(myArr[0], 10);
			document.getElementById('Sel_MaxCount').value = myArr[0];
		}else if (sct==11){	
			ItmTit = document.getElementById("Order_Name");
			document.getElementById('Inp_Title').value=myArr[0];
			document.getElementById('Sel_MaxCount').value = 1;	
			document.getElementById('Sel_Count').value = 1;	
			document.getElementById("Sel_ProcType2").value=147475;
			document.getElementById('Sel_Mask1').selectedIndex = 27;
			document.getElementById('Sel_Mask2').value = document.getElementById('Sel_Mask1').options[27].value;
			var odata = myArr[4].split(" ");
			document.getElementById('Inp_MO_Clss').selectedIndex=(parseInt(odata[3],10)-1004);
			document.getElementById('Max_Prestige').innerHTML=myArr[2];
			document.getElementById('Inp_MO_Prest').value=0;
			document.getElementById('Inp_MO_PLose').value=5;
			document.getElementById('Inp_Description').value=myArr[5];
			document.getElementById("Inp_Grade2").value=myArr[2];
		}else if (sct==12){
			ItmTit = document.getElementById("StarChart_Name");
			document.getElementById('Inp_Title').value=myArr[0];
			document.getElementById('Sel_MaxCount').value = 1;	
			document.getElementById('Sel_Count').value = 1;	
			document.getElementById("Sel_ProcType2").value=16407;
			document.getElementById('Sel_Mask1').selectedIndex = 30;
			document.getElementById('Sel_Mask2').value = document.getElementById('Sel_Mask1').options[30].value;
			if (myArr[5] != null){
				document.getElementById('Inp_SC_Clss').selectedIndex=parseInt(myArr[5], 10) || 0;
			}
			for (var a = 1; a <= 5; a++) {
				document.getElementById('Inp_SC_BStarAddon'+a).selectedIndex=0;
				document.getElementById('Inp_SC_BStarApt'+a).value="1.00";
				document.getElementById('Inp_SC_FStarAddon'+a).selectedIndex=0;
				document.getElementById('Inp_SC_FStarApt'+a).innerHTML="2.00";	
				document.getElementById('Inp_SC_BStarRate'+a).value="";
				document.getElementById('Inp_SC_FStarRate'+a).value="0";		
				document.getElementById('Inp_SC_BStarRate'+a).style.boxShadow='none';				
				document.getElementById('Inp_SC_FStarRate'+a).style.boxShadow='none';	
				document.getElementById('Inp_SC_FStarRate'+a).disabled=true;
			}
			document.getElementById('SCAddonList').innerHTML="";
			ChangeSCAddons((parseInt(myArr[5], 10) || 0));
		}
		if ((sct == 8)||(sct == 9)||(sct == 7)||(sct == 6)||(sct == 2)||(sct == 11)){
			if (tmpdesc != ""){
				if (tmpdesc.indexOf("*") != -1){
					document.getElementById("OctDesc"+sct).innerHTML = tmpdesc.split("*").join("<br>");
				}else{
					document.getElementById("OctDesc"+sct).innerHTML = tmpdesc;
				}
			}else{
				document.getElementById("OctDesc"+sct).innerHTML = "";
			}
		}
	}else if ((cat == "5")||(cat == "6")||(cat == "8")){
		document.getElementById("MiscQStack").style.display="none";
		var desc;
		var idesc = document.getElementById("Inp_Description");
		if (cat == "8") {
			desc = document.getElementById("CardBonus");
			ItmTit = document.getElementById("Card_Name");
		}else{
			desc = document.getElementById("MiscDesc");
			ItmTit = document.getElementById("Misc_Name");
			if ((cat == "5")&&((sct==1)||(sct==16))){
				if (isNumber(myArr[5])){
					var tId = parseInt(myArr[5],10);
					var tTxt = "";
					var tmp;
					var CBonus = TomeStat[tId][2];
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
					desc.innerHTML="<font color='#77f'>"+tTxt+"</font>";
				}else{
					idesc.value = myArr[5];
					if (myArr[5].indexOf("*") != -1){
						desc.innerHTML = "<font color='#33f'>"+myArr[5].split("*").join("<br>")+"</font>";	
					}else{
						desc.innerHTML = "<font color='#33f'>"+myArr[5]+"</font>";						
					}
				}
			}
		}

		document.getElementById('Inp_Title').value=myArr[0];

		if (cat == "8"){
			var cGrade = parseInt(myArr[2], 10)-1;
			var CData = [];
			CData = CardStat[parseInt(myArr[5],10)];
			document.getElementById('CardID').value = myArr[5];
			document.getElementById('Inp_C_Grade').selectedIndex = cGrade;
			document.getElementById('Inp_C_MaxExp').innerHTML = CardMExp[cGrade][0][0];
			document.getElementById('Inp_C_Lead').value = CardLea[cGrade];
			document.getElementById('Inp_C_MaxLv').value = CData[4];
			document.getElementById("Sel_ProcType2").value=8;
			document.getElementById('Inp_Title').value=myArr[0];
			if ((sct > 0) && (sct < 7)){
				document.getElementById("Inp_C_Type").selectedIndex = (sct-1);
			}
			document.getElementById("Inp_C_Grade").selectedIndex = (parseInt(myArr[2] ,10)-1);			
			myArr = myArr[4].split(" ");
			document.getElementById('Sel_MaxCount').value = 1;
			document.getElementById('Sel_Count').value = 1;	
			document.getElementById('Inp_C_LvReq').value = myArr[0];
			document.getElementById("Sel_ProcType2").value=16407;
			GetCardInfo();
		}else{
			if ((cat == "5")&&(sct == 15)){	
				var CBonus = myArr[5];
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
				var rtimeMin = parseInt(myArr[6],10);
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
				desc.innerHTML = "<font color='#fff'>Req. gear rank: "+myArr[2]+"</font><br>"+tTxt+"<br><font color='#fff'>for last " +rtime+ "</font>";			
			}else if ((cat == "5")&&(sct == 14)){
				var CBonus = myArr[4];
				var tmp;
				var tTxt="";
				if (CBonus.indexOf("*") != -1){
					CBonus = CBonus.split('*');
					for(var i=0;i<CBonus.length;i++){
						tmp = CBonus[i].split(',')
						tTxt=tTxt+GetAddonString(parseInt(tmp[1], 10), tmp[0])+"<br>";
					}			
				}else{
					CBonus = CBonus.split(',');
					tTxt = GetAddonString(parseInt(CBonus[1], 10), CBonus[0])+"<br>";			
				}				
				desc.innerHTML = tTxt;
				document.getElementById("Inp_Mss_Amount").value = 1;
				document.getElementById('Sel_MaxCount').value = 1;
				document.getElementById('Sel_Count').value = 1;
				document.getElementById("Inp_Ms_Amount").value = 1;		
				document.getElementById("Inp_Ms_Octet").value = "";		
				document.getElementById("Sel_ProcType2").value=0;
			}else if ((cat == "5")&&(sct == 16)){				
				document.getElementById('Sel_MaxCount').value = 1;	
				document.getElementById('Sel_Count').value = 1;	
				document.getElementById("Sel_ProcType2").value=16407;
				document.getElementById('Sel_Mask1').selectedIndex = 29;
				document.getElementById('Sel_Mask2').value = document.getElementById('Sel_Mask1').options[29].value;	
		
			}else{
				if ((cat == "6")||((cat == "5")&&(sct != 14)&&(sct != 15)&&(sct != 1))){
					if (tmpdesc.indexOf("*") != -1){
						desc.innerHTML = tmpdesc.split("*").join("<br>");
					}else{
						desc.innerHTML = tmpdesc;
					}
					idesc.value=tmpdesc;
				}
				myArr = myArr[4].split(" ");
				document.getElementById("Inp_Mss_Amount").value = parseInt(myArr[0], 10);
				document.getElementById('Sel_MaxCount').value = parseInt(myArr[0], 10);
				document.getElementById('Sel_Count').value = 1;
				document.getElementById("Inp_Ms_Amount").value = 1;
				var qsid = parseInt(myArr[2], 10) || 0;
				
				if ((cat==5)&&(sct==11)&&(qsid<0)){
					document.getElementById("MiscQStack").style.display="table-row";
					qsid=parseInt(qsid)*-1;
					var qss;
					for (i = 1; i <= 8192; i++) {
						qss=document.getElementById("QuestStack"+i);
						if (qss != null){
							qss.style.display="none";
						}else{
							break;
						}
					}

					qss=document.getElementById("QuestStack"+qsid);

					qss.style.display="block";
					qss.selectedIndex=0;
					document.getElementById("Inp_Ms_Octet").value=DectoRevHex(qss.options[qss.selectedIndex].value,8,0);
					
				}else{
					var eOctet = parseInt(myArr[2], 10);
					if (eOctet > 0){
						document.getElementById("Inp_Ms_Octet").value = myArr[3];
					}else{
						document.getElementById("Inp_Ms_Octet").value = "";
					}
				}
				document.getElementById("Sel_ProcType2").value=myArr[1];
			}
		}
	}else if (cat == "7"){
		ItmTit = document.getElementById("Fash_Name");
		document.getElementById('Inp_Title').value=myArr[0];
		myArr = myArr[4].split(" ");
		document.getElementById('Sel_MaxCount').value = 1;
		document.getElementById('Sel_Count').value = 1;
		document.getElementById("Sel_ProcType2").value=8;
		document.getElementById("Inp_Fs_LvReq").value=myArr[0];
		if (sct < 11){
			if (myArr[1] > 0){
				document.getElementById("FashDesc").innerHTML = "<center><i>Probabil need "+myArr[1]+" dye</i></center>";
				document.getElementById("Inp_Description").value = "Dyeing required "+myArr[1]+" pigment.";
			}else{
				document.getElementById("Inp_Description").value = "Fixed color.";
				document.getElementById("FashDesc").innerHTML = "<center><i>Probabil fixed color</i></center>";
			}	
		}else{
			var FashMask=parseInt(myArr[2],10)||0;
			document.getElementById("FashDesc").innerHTML = "<center><i>"+GetWFashReqWeapon(FashMask)+"</i></center>";
			document.getElementById("Inp_Grade2").value = FashMask;
			document.getElementById("Inp_Description").value = "";			
		}
		if ((sct == 1)||(sct == 3)||(sct == 5)||(sct == 7)||(sct == 9)||(sct == 11)){
			document.getElementById('FashFemale').checked = false;
			document.getElementById('FashMale').checked = true;
		}else{
			document.getElementById('FashFemale').checked = true;
			document.getElementById('FashMale').checked = false;
		}
	}
	ItmTit.innerHTML = cINM;
	ArrangeProcCheckboxs();
}

function SelChckboxToggle(id){
	var c=0;
	var div;
	if (id != ""){
		div = document.getElementById("SelItmChBox"+id);
		if (div != null){
			if (div.checked===true){
				div.checked = false;
			}else{
				div.checked = true;
			}
		}
	}
	for (i = 1; i <= SIInd; i++) {
		div = document.getElementById("SelItmChBox"+i);
		if (div != null){
			if (div.checked !== false){
				c++;
			}
		}
	}		

	div = document.getElementById("SelMultiTool");
	if (c > 0){
		div.style.display="inline";
	}else{
		div.style.display="none";
		document.getElementById("Inp_SelAll").checked = false;
	}
}

function ShopChckboxToggle(id){
	var c=0;
	var div;
	if (id != ""){
		div = document.getElementById("ShopItmChBox"+id);
		if (div != null){
			if (div.checked===true){
				div.checked = false;
			}else{
				div.checked = true;
			}
		}
	}
	for (i = 1; i <= WSIInd; i++) {
		div = document.getElementById("ShopItmChBox"+i);
		if (div != null){
			if (div.checked !== false){
				c++;
			}
		}
	}		

	div = document.getElementById("ShopMultiTool");
	if (c > 0){
		div.style.display="inline";
	}else{
		div.style.display="none";
		document.getElementById("Inp_ShopAll").checked = false;
	}
}

function MarkAllPacketItem(){
	var bool = false;
	var div = document.getElementById("Inp_SelAll");
	if (div.checked !== false){
		bool = true;
		document.getElementById("SelMultiTool").style.display="inline";
	}else{
		document.getElementById("SelMultiTool").style.display="none";
	}
	for (i = 1; i <= SIInd; i++) {
		document.getElementById("SelItmChBox"+i).checked = bool;
	}				
}

function MarkAllShopItem(){
	var bool = false;
	var div = document.getElementById("Inp_ShopAll");
	if (div.checked !== false){
		bool = true;
		document.getElementById("ShopMultiTool").style.display="inline";
	}else{
		document.getElementById("ShopMultiTool").style.display="none";
	}
	for (i = 1; i <= WSIInd; i++) {
		div = document.getElementById("ShopItmChBox"+i);
		if (div != null){
			div.checked = bool;
		}
	}				
}

function RemoveSelectedItem(id){
	if ((id > -1) && (id <= SIData.length)){
		SIData.splice(id, 1);
		SIInd--;
		refreshSelectedItems();
		if (SIInd == 0){
			document.getElementById("SelMultiTool").style.display="none";
		}
		var resp = document.getElementById("MailResponse");
		resp.innerHTML = "<font color='red'><b><i> Item removed...</i></b></font>";
	}	
}


function GroupResetSelectedItem(){
	var roleid = parseInt(document.getElementById("Inp_RoleId").value, 10) || 0;
	var resp = document.getElementById("MailResponse");
	var myArr = [];
	var c=0;
	if (roleid < 1){
		resp.innerHTML = "<font color='red'><b><i> Invalid role id!</i></b></font>";
		document.getElementById("Inp_RoleId").style.backgroundColor="#faa";
		document.getElementById("Inp_Money").style.backgroundColor="#fff";
	}else{
		for (i = 1; i <= SIInd; i++) {
			myArr = SIData[i].split("#");
			myArr[0]=roleid;
			SIData[i]=myArr.join("#");
			c++;
		}
		resp.innerHTML = "<font color='red'><b><i>Role id reseted! ["+c+"x]</i></b></font>";
		refreshSelectedItems();
	}
}

function GroupResetTSelectedItem(){
	var expire = parseInt(document.getElementById("Sel_Expire").value, 10) || 0;
	var resp = document.getElementById("MailResponse");
	var myArr = [];
	var c=0;
	for (i = 1; i <= SIInd; i++) {
		myArr = SIData[i].split("#");
		myArr[14]=expire;
		SIData[i]=myArr.join("#");
		c++;
	}
	resp.innerHTML = "<font color='red'><b><i>Expiration time reseted! ["+c+"x]</i></b></font>";
	refreshSelectedItems();
}
	
function GroupRemoveSelectedItem(){
	var div;
	var c=0;
	var idlist=getSelectedItems()[1];
	var max=idlist.length;
	for (var i = 0; i < max; i++) {
		c=idlist[i];
		if (c < SIInd){
			SIData[c] = SIData[SIInd];
			document.getElementById("SelItmChBox"+c).checked = document.getElementById("SelItmChBox"+SIInd).checked;
		}
		SIInd--;
	}
	
	if (SIInd == 0){
		document.getElementById("SelMultiTool").style.display="none";
	}	
	var resp = document.getElementById("MailResponse");
	resp.innerHTML = "<font color='red'><b><i> "+max+" item removed...</i></b></font>";
	refreshSelectedItems();
}

function UpdateSelItemChanges(){
	var myArr = [];
	var id = parseInt(document.getElementById('SE_PacketId').value, 10) || 0;
	myArr[0] = document.getElementById('SE_RoleId').value;
	myArr[1] = document.getElementById('SE_Money').value;
	myArr[2] = document.getElementById('SE_Title').value;
	myArr[3] = document.getElementById('SE_Body').value;
	myArr[4] = document.getElementById('SE_CAT').value;
	myArr[7] = document.getElementById('SE_ItemId').value;
	myArr[8] = document.getElementById('SE_Mask').value;
	myArr[9] = document.getElementById('SE_ProcType').value;
	myArr[10] = document.getElementById('SE_Stack').value;
	myArr[11] = document.getElementById('SE_MaxStack').value;
	myArr[12] = document.getElementById('SE_Guid1').value;
	myArr[13] = document.getElementById('SE_Guid2').value;
	myArr[14] = document.getElementById('SE_Expire').value;
	myArr[15] = document.getElementById('SE_Octet').value;
	myArr[5] = document.getElementById('SE_ItemName').value;
	myArr[6] = GetSqlDate();
	SIData[id]=myArr.join("#");
	refreshSelectedItems();
	document.getElementById('SelectEditWindow').style.display='none';
	document.getElementById("MailResponse").innerHTML = "<font color='blue'><b><i> Item info updated! </i></b></font>";
}

function EditSelectedItem(id){
	if (SIData[id] !== null){
		var myArr = SIData[id].split("#");
		document.getElementById('SE_PacketId').value = id;
		document.getElementById('SE_RoleId').value = myArr[0];
		document.getElementById('SE_Money').value = myArr[1];
		document.getElementById('SE_Title').value = myArr[2];
		document.getElementById('SE_Body').value = myArr[3];
		document.getElementById('SE_CAT').value = myArr[4];
		document.getElementById('SE_ItemId').value = myArr[7];
		document.getElementById('SE_Mask').value = myArr[8];
		document.getElementById('SE_ProcType').value = myArr[9];
		document.getElementById('SE_Stack').value = myArr[10];
		document.getElementById('SE_MaxStack').value = myArr[11];
		document.getElementById('SE_Guid1').value = myArr[12];
		document.getElementById('SE_Guid2').value = myArr[13];
		document.getElementById('SE_Expire').value = myArr[14];
		document.getElementById('SE_Octet').value = myArr[15];
		document.getElementById('SE_ItemName').value = myArr[5];
		document.getElementById('SelectEditWindow').style.display='block';
	}
}

function EditShopItem(id){
	if (WSIData[id] !== null){
		var myArr = WSIData[id].split("#");
		document.getElementById('WSE_PacketId').value = id;
		document.getElementById('WSE_Price1').value = myArr[0];
		document.getElementById('WSE_Price2').value = myArr[1];
		document.getElementById('WSE_Title').value = myArr[2];
		document.getElementById('WSE_Description').value = myArr[3];
		document.getElementById('WSE_CAT').value = myArr[4];
		document.getElementById('WSE_ItemId').value = myArr[7];
		document.getElementById('WSE_Mask').value = myArr[8];
		document.getElementById('WSE_ProcType').value = myArr[9];
		document.getElementById('WSE_Stack').value = myArr[10];
		document.getElementById('WSE_MaxStack').value = myArr[11];
		document.getElementById('WSE_Guid1').value = myArr[12];
		document.getElementById('WSE_Guid2').value = myArr[13];
		document.getElementById('WSE_Expire').value = myArr[14];
		document.getElementById('WSE_Octet').value = myArr[15];
		if (myArr[18] != null){
			document.getElementById('WSE_Grade').value = myArr[18];
		}
		document.getElementById('WSE_ItemName').value = myArr[5];
		document.getElementById('ShopItemEditWindow').style.display='block';
		var selO = document.getElementById('ItmShpCat');
		for (i = 0; i < selO.length; i++) {
			if (selO.options[i].value == myArr[16]){
				selO.selectedIndex = i;
			}
		}
		selO = document.getElementById('ItmShpCol');
		for (i = 0; i < selO.length; i++) {
			if (selO.options[i].value == myArr[17]){
				selO.selectedIndex = i;
			}
		}
		var TimeData=myArr[19].split(" ");
		document.getElementById('WSE_SType').selectedIndex=parseInt(TimeData[0],10);
		EditItemDate();
		if (TimeData[1]==1) {
			document.getElementById('WSE_AutoHideItem').checked=true;
		}else{
			document.getElementById('WSE_AutoHideItem').checked=false;
		}
		if (TimeData[0]==2){
			document.getElementById('WSE_Start_Hour').value=TimeData[2];
			document.getElementById('WSE_End_Hour').value=TimeData[3];
		}else if((TimeData[0]==1)||(TimeData[0]==3)){
			document.getElementById('WSE_Start_Date').value=NumToDate(TimeData[2]);
			document.getElementById('WSE_End_Date').value=NumToDate(TimeData[3]);
			if 	(TimeData[0]==3){
				document.getElementById('WSE_Discount').value=TimeData[4];
			}
		}
	}
}

function refreshSelectedItems(){
	var div = document.getElementById("SelItemList");
	document.getElementById("Inp_SelAll").checked=false;
	document.getElementById("SelMultiTool").style.display = "none";
	if (SIInd == 0){
		div.innerHTML = "<center><i>List is Empty</i></center>";
	}else{
		div.innerHTML = "";
		var myArr = [];
		var lnbrk;
		var txt = "";
		for (i = 1; i <= SIInd; i++) {
			if (i == 1){
				lnbrk = "";
			}else{
				lnbrk ="<br>";
			}
			myArr=SIData[i].split("#");
			txt = txt+lnbrk+"<input type='checkbox' style='vertical-align: middle;' value='"+SIData[i]+"' id='SelItmChBox"+i+"' onClick=SelChckboxToggle('');><a title='Role Id: "+myArr[0]+"\nMoney: "+myArr[1]+"\nTitle: "+myArr[2]+"\nMessage: "+myArr[3]+"\nItemId: "+myArr[7]+"\nItem Mask:"+myArr[8]+"\nProcType: "+myArr[9]+"\nStack: "+myArr[10]+"/"+myArr[11]+"\nGuid1: "+myArr[12]+"\nGuid2: "+myArr[13]+"\nExpire: "+myArr[14]+"\nOctet: "+myArr[15]+"\n\nClick: Select or Unselect\nDouble click: load the item octet for edit' onClick=SelChckboxToggle('"+i+"'); ondblclick='OctSelectedItem("+i+", 1);'><b>"+myArr[5]+"</b> - "+myArr[6]+"</a> "+" <span style='float:right;clear: both;'> <a href='javascript:void(0);'  onclick='EditSelectedItem("+i+");'><button style='vertical-align: middle;'> Nfo </button></a> <a href='javascript:void(0);'  onclick='SendSelectedItem("+i+");'><button style='vertical-align: middle;'> Send </button></a> <a href='javascript:void(0);'  onclick='RemoveSelectedItem("+i+");'><button style='vertical-align: middle;'> Del </button></a></span>";
		}
		div.innerHTML = txt;
	}
}

function refreshShopItems(){
	var div = document.getElementById("ShopItemList");
	var db="";
	document.getElementById("Inp_ShopAll").checked=false;
	document.getElementById("ShopMultiTool").style.display = "none";
	if (WSIInd == 0){
		div.innerHTML = "<center><i>List is Empty</i></center>";
	}else{
		div.innerHTML = "";
		var myArr=[];
		var myArr2=[];
		var lnbrk;
		var ShpTime="Shop Type: Permanent";
		var txt = "";
		for (i = 1; i <= WSIInd; i++) {
			if (i == 1){
				lnbrk = "";
			}else{
				lnbrk ="<br>";
			}
			myArr=WSIData[i].split("#");
			myArr2=myArr[19].split(" ");
			if (myArr2[0] > 0){ShpTime="Shop Type: Not permanent";}
			txt = txt+lnbrk+"<input type='checkbox' style='vertical-align: middle;' value='"+WSIData[i]+"' id='ShopItmChBox"+i+"' onClick=ShopChckboxToggle('');><a title='Item Title: "+myArr[2]+"\nDescripton: "+myArr[3]+"\nItem Color: "+itmCol[myArr[17]]+"\nCategory ID: "+myArr[16]+"\nPrice (Gold): "+myArr[0]+"\nPrice (WebPoint): "+myArr[1]+"\nItemId: "+myArr[7]+"\nItem Mask:"+myArr[8]+"\nProcType: "+myArr[9]+"\nStack: "+myArr[10]+"/"+myArr[11]+"\nGuid1: "+myArr[12]+"\nGuid2: "+myArr[13]+"\nExpire: "+myArr[14]+"\n"+ShpTime+"\nStorage ID: "+db+"\nOctet: "+myArr[15]+"\n\nClick: Select or Unselect\nDouble click: load the item octet for edit' onClick=ShopChckboxToggle('"+i+"'); ondblclick='OctSelectedItem("+i+", 2);'><b>"+myArr[2]+"</b> - "+myArr[6]+"</a> "+" <span style='float:right;clear: both;'> <a href='javascript:void(0);'  onclick='EditShopItem("+i+");'><button style='vertical-align: middle;'> Nfo </button></a><a href='javascript:void(0);'  onclick='DeleteShopItem("+i+");'><button style='vertical-align: middle;'> Del </button></a></span>";
		}
		div.innerHTML = txt;
	}
}

function SelectNewItem (){
	var nData = [];
	var cData1 = [];
	var cData2 = [];
	var CInt = CType2CTypeInt(CIType);
	var selList = document.getElementById("SItmC"+CInt+"S"+SIType);
	var IExist = false;
	var roleid = parseInt(document.getElementById("Inp_RoleId").value, 10) || 0;
	var money = parseInt(document.getElementById("Inp_Money").value, 10) || 0;
	var MTitle = document.getElementById("Inp_MailTitle").value;
	var MBody = document.getElementById("Inp_MailBody").value;
	nData[0] = roleid;
	nData[1] = money;
	nData[2] = MTitle;
	nData[3] = MBody;
	nData[4] = CIType+SIType;
	nData[5] = selList.options[selList.selectedIndex].text;
	nData[6] = GetSqlDate();
	nData[7] = parseInt(document.getElementById("Sel_Item_Id").value);
	nData[8] = parseInt(document.getElementById("Sel_Mask2").value);
	nData[9] = parseInt(document.getElementById("Sel_ProcType2").value);
	nData[10] = parseInt(document.getElementById("Sel_Count").value);
	nData[11] = parseInt(document.getElementById("Sel_MaxCount").value);
	nData[12] = parseInt(document.getElementById("Sel_Guid1").value);
	nData[13] = parseInt(document.getElementById("Sel_Guid2").value);
	nData[14] = parseInt(document.getElementById("Sel_Expire").value);
	nData[15] = document.getElementById("Inp_Octet").value;
	for (i = 1; i <= SIInd; i++) {
		cData1 = SIData[i].split("#");
		cData1[2] = "";
		cData1[3] = "";
		cData1[6] = "";
		cData2 = nData.slice(0);
		cData2[2] = "";
		cData2[3] = "";
		cData2[6] = "";
		if (cData1.join("#") == cData2.join("#")){
			IExist = true;
		}
	}
	var resp = document.getElementById("MailResponse");
	if (IExist !== true){
		var resp = document.getElementById("MailResponse");
		resp.innerHTML = "";
		if (money > 200000000){
			resp.innerHTML = "<font color='red'><b><i> Cannot send over 200 mil!</i></b></font>";
			document.getElementById("Inp_Money").style.backgroundColor="#faa";
			document.getElementById("Inp_RoleId").style.backgroundColor="#fff";
		}else{
			if (roleid < 1024){
				resp.innerHTML = "<font color='red'><b><i> Invalid role id!</i></b></font>";
				document.getElementById("Inp_RoleId").style.backgroundColor="#faa";
				document.getElementById("Inp_Money").style.backgroundColor="#fff";
			}else{
				document.getElementById("Inp_RoleId").style.backgroundColor="#fff";
				document.getElementById("Inp_Money").style.backgroundColor="#fff";
				SIInd++;
				SIData[SIInd] = nData.join("#");
				resp.innerHTML = "<font color='blue'><b><i> "+nData[5]+" <font color='red'>added...</font></i></b></font>";
				if (document.getElementById("Inp_SelAll").style.display == "none"){
					document.getElementById("Inp_SelAll").style.display="block";
				}
				refreshSelectedItems();
			}
		}		
		

	}else{
		resp.innerHTML = "<font color='red'><b><i> This item already on list...</font></i></b></font>";
	}
}

function ConvOldToNewCat(oldC, Octet){
	var newC;
	var m = oldC[0];
	var s = parseInt(oldC.substr(1), 10);
	if (m == "W"){
		newC = "1";
		if ((s < 15)||(s == 22)||(s == 24)){
			newC = newC + "" + "1";
			if ((s > 0)&&(s < 5)){
				newC = newC + "" + "1";
			}else if ((s > 4)&&(s < 9)){
				newC = newC + "" + "2";
			}else if ((s > 8)&&(s < 13)){
				newC = newC + "" + "3";
			}else if ((s == 13)||(s == 14)){
				newC = newC + "" + "4";
			}else if (s == 22){
				newC = newC + "" + "5";
			}else if (s == 24){
				newC = newC + "" + "6";
			}
		}else if ((s > 14)&&(s < 18)){
			newC = newC + "" + "2";
			newC = newC + "" + (s-14);
		}else if (((s > 17)&&(s < 22))||(s == 23)||(s == 25)){
			newC = newC + "" + "3";
			if ((s > 17)&&(s < 22)){
				newC = newC + "" + (s-17);
			}else if (s == 23){
				newC = newC + "" + "5";
			}else if (s == 25){
				newC = newC + "" + "6";
			}
		}else{
			newC = newC + "" + "0";
		}
	}else if (m == "A"){
		newC = "2";
		if ((s > 0)&&(s < 4)){
			newC = newC + "" + "1";
			newC = newC + "" + s;
		}else if ((s > 3)&&(s < 7)){
			newC = newC + "" + "2";
			newC = newC + "" + (s-3);
		}else if ((s > 6)&&(s < 10)){
			newC = newC + "" + "3";
			newC = newC + "" + (s-6);			
		}else if ((s > 9)&&(s < 13)){
			newC = newC + "" + "4";
			newC = newC + "" + (s-9);			
		}else if ((s > 12)&&(s < 15)){
			newC = newC + "" + "5";
			newC = newC + "" + (s-12);
		}else if (s == 15){
			newC = newC + "" + "6";
			newC = newC + "" + "0";			
		}
	}else if (m == "J"){
		newC = "3";
		if ((s > 0)&&(s < 4)){
			newC = "31" + "" + s;
		}else if ((s > 3)&&(s < 7)){
			newC = "32" + "" + (s-3);
		}else if ((s > 6)&&(s < 9)){
			newC = "33" + "" + (s-6);
		}		
	}else if ((m == "U")||(m == "O")){
		if (m == "O"){
			if (s == 1){
				newC = "4" + "" + "1";
				var cRace = HextoDec(Octet.substr(24, 8), 8, 0, true);
				if (cRace == 3){
					newC = newC + "" + "1";
				}else if (cRace == 24){
					newC = newC + "" + "2";
				}else if (cRace == 192){
					newC = newC + "" + "3";
				}else if (cRace == 36){
					newC = newC + "" + "4";
				}else if (cRace == 768){
					newC = newC + "" + "5";
				}else if (cRace == 3072){
					newC = newC + "" + "6";
				}
			}else if (s == 2){
				newC = "4" + "" + "2";
				var PetType = HextoDec(Octet.substr(48, 8), 8, 0, true);
				newC = newC + "" + (PetType+1);
			}else if (s == 3){
				newC = "3" + "" + "4";
				newC = newC + "" + "2";			
			}else if (s == 4){
				newC = "3" + "" + "4";
				newC = newC + "" + "3";	
			}else if (s == 5){
				newC = "4" + "" + "3";
				newC = newC + "" + "1";			
			}else if (s == 6){
				newC = "1" + "" + "2";
				newC = newC + "" + "4";			
			}else if (s == 7){
				newC = "4" + "" + "3";
				newC = newC + "" + "2";			
			}else if (s == 8){
				newC = "4" + "" + "4";
				newC = newC + "" + "1";			
			}else if (s == 9){
				newC = "4" + "" + "2";
				newC = newC + "" + "5";	
			}else if (s == 10){
				newC = "4" + "" + "5";
				newC = newC + "" + "3";	
			}else if (s == 11){
				newC = "3" + "" + "4";
				newC = newC + "" + "4";					
			}else if (s == 12){
				newC = "3" + "" + "4";
				newC = newC + "" + "6";					
			}				
		}else if (m == "U"){
			if (s == 1){
				newC = "3" + "" + "4";
				newC = newC + "" + "1";
			}else if (s == 2){
				newC = "4" + "" + "5";
				newC = newC + "" + "2";
			}else if (s == 3){
				newC = "4" + "" + "4";
				newC = newC + "" + "3";				
			}else if (s == 4){
				newC = "4" + "" + "4";
				newC = newC + "" + "4";
			}else if (s == 5){
				newC = "4" + "" + "5";
				newC = newC + "" + "1";
			}else if (s == 6){
				newC = "5" + "" + "7";
				newC = newC + "" + "0";
			}else if (s == 7){
				newC = "4" + "" + "6";
				newC = newC + "" + "1";
			}else if (s == 8){
				newC = "6" + "" + "6";
				newC = newC + "" + "0";
			}else if (s == 9){
				newC = "6" + "" + "6";
				newC = newC + "" + "0";
			}else if (s == 10){
				newC = "4" + "" + "2";
				newC = newC + "" + "4";				
			}else if (s == 11){
				newC = "4" + "" + "6";
				newC = newC + "" + "2";
			}else if (s == 12){
				newC = "4" + "" + "4";
				newC = newC + "" + "2";
			}else if (s == 13){
				newC = "6" + "" + "6";
				newC = newC + "" + "0";
			}else if (s == 14){
				newC = "4" + "" + "5";
				newC = newC + "" + "4";	
			}else if (s == 15){
				newC = "4" + "" + "5";
				newC = newC + "" + "5";
			}else if (s == 16){
				newC = "3" + "" + "4";
				newC = newC + "" + "5";					
			}
		}
		
	}else if (m == "F"){
		newC = "5";
			if ((s == 1)||(s == 2)){
				newC = newC + "" + "1";
				newC = newC + "" + s;
			}else if ((s == 3)||(s == 4)){
				newC = newC + "" + "2";
				newC = newC + "" + (s-2);
			}else if ((s == 5)||(s == 6)){
				newC = newC + "" + "3";
				newC = newC + "" + (s-4);				
			}else if ((s == 7)||(s == 8)){
				newC = newC + "" + "4";
				newC = newC + "" + (s-6);
			}else if ((s == 9)||(s == 10)){
				newC = newC + "" + "5";
				newC = newC + "" + (s-8);
			}else if ((s == 11)||(s == 12)){
				//here the category is different
				var fwm=parseInt(document.getElementById("Inp_Grade2").value,10)||0;
				var n;
				newC = newC + "" + "6";
				if (fwm == 114688){
					n=2;
				}else if(fwm == 2097152){
					n=3;
				}else if(fwm == 4194304){
					n=4;
				}else if(fwm == 8388608){
					n=5;
				}else if(fwm == 16777216){
					n=6;
				}else if(fwm == 33566720){
					n=1;
				}else if(fwm == 35536895){
					n=7;					
				}else{
					n=0;
				}
				newC = newC + "" + n;				
			}
	}else if (m == "C"){
		newC = "35"+ s;
	}else if (m == "M"){
		newC = "6";
			if (s == 1){
				newC = newC+ "" + "1";
				newC = newC + "" + "1";
			}else if (s == 2){
				newC = newC + "" + "1";
				newC = newC + "" + "1";
			}else if (s == 3){
				newC = newC + "" + "1";
				newC = newC + "" + "3";				
			}else if (s == 4){
				newC = newC + "" + "2";
				newC = newC + "" + "1";	
			}else if (s == 5){
				newC = newC + "" + "2";
				newC = newC + "" + "2";	
			}else if (s == 6){
				newC = newC + "" + "2";
				newC = newC + "" + "3";	
			}else if (s == 7){
				newC = newC + "" + "2";
				newC = newC + "" + "4";	
			}else if (s == 8){
				newC = newC + "" + "1";
				newC = newC + "" + "2";
			}else if (s == 9){
				newC = newC + "" + "2";
				newC = newC + "" + "5";	
			}else if (s == 10){
				newC = newC + "" + "3";
				newC = newC + "" + "0";	
			}else if (s == 11){	
				newC = newC + "" + "4";
				newC = newC + "" + "2";	
			}else if (s == 12){	
				newC = newC + "" + "4";
				newC = newC + "" + "1";					
			}
	}
	
	return newC;
}


function SearchHiero(nr){
	var selList = document.getElementById("SItmC4S5");
	var lMax = selList.length;
	var myArr = [];
	var ind = 0;
	var aId = 0;
	var tmp = 0;
	var e1 = document.getElementById("Sel_Item_Id");
	if (nr == 0){
		selList.selectedIndex = 0;
		myArr = selList.options[0].value.split("#");
		e1.value = myArr[1];
		myArr = myArr[4].split(" ");
		tmp = parseInt(myArr[0]);
		document.getElementById('Sel_Mask1').selectedIndex = (18+tmp);
		document.getElementById('Sel_Mask2').value = document.getElementById('Sel_Mask1').options[(18+tmp)].value;
	}else if (nr == 1){
		document.getElementById('Sel_Mask1').selectedIndex = 18;
		document.getElementById('Sel_Mask2').value = document.getElementById('Sel_Mask1').options[18].value;
		for (i = 0; i < lMax; i++) {
			if (ind == 0){
				myArr = selList.options[i].value.split("#");
				tmp = myArr[1];
				myArr = myArr[4].split(" ");
				if (myArr[1] == 1){
					ind = i;
					aId = tmp;
				}
			}
		}
		if (ind > 0){
			selList.selectedIndex = ind;
			e1.value = aId;
		}
	}else if (nr == 2){
		document.getElementById('Sel_Mask1').selectedIndex = 18;
		document.getElementById('Sel_Mask2').value = document.getElementById('Sel_Mask1').options[18].value;
		for (i = 0; i < lMax; i++) {
			if (ind == 0){
				myArr = selList.options[i].value.split("#");
				tmp = myArr[1];
				myArr = myArr[4].split(" ");
				if (myArr[1] == 2){
					ind = i;
					aId = tmp;
				}
			}
		}
		if (ind > 0){
			selList.selectedIndex = ind;
			e1.value = aId;
		}
	}
	getPItemData(selList);
}

function MathConvDec (){
	var Dec = document.getElementById('Math_Dec').value;
	document.getElementById('Math_Date').value = NumToDate(Dec);
	if ((isNumber(Dec) !== true)||(Dec=="")){Dec=0;}
	var HTyp = document.getElementById('Math_Type');
	var HType = HTyp.options[HTyp.selectedIndex].value;
	var HLe = document.getElementById('Math_HexLen');
	var HLen = parseInt(HLe.options[HLe.selectedIndex].value)*2;
	document.getElementById('Math_Float').value = numToFloat32Hex(Dec, false);
	document.getElementById('Math_RFloat').value = numToFloat32Hex(Dec, true);
	if (Dec < 0){
		Dec = Dec * -1;
	}
	if (Dec % 1 == 0){
		document.getElementById('Math_Hex').value = DectoHex(parseInt(Dec,10),HLen,HType);
		document.getElementById('Math_RHex').value = DectoRevHex(parseInt(Dec,10),HLen,HType);
	}else{
		document.getElementById('Math_Hex').value = DectoHex(0,HLen,HType);
		document.getElementById('Math_RHex').value = DectoRevHex(0,HLen,HType);		
	}
}

function MathConvHex (){
	var Hex = document.getElementById('Math_Hex').value;
	var HTyp = document.getElementById('Math_Type');
	var HType = HTyp.options[HTyp.selectedIndex].value;
	var HLe = document.getElementById('Math_HexLen');
	var HLen = parseInt(HLe.options[HLe.selectedIndex].value)*2;
	if (ishex(Hex) !== true){Hex=0;}
	var Dec = HextoDec(Hex, HLen, HType, false);
	document.getElementById('Math_Dec').value = Dec;
	document.getElementById('Math_Date').value = NumToDate(Dec);
	document.getElementById('Math_Float').value = numToFloat32Hex(Dec, false);
	document.getElementById('Math_RFloat').value = numToFloat32Hex(Dec, true);
	document.getElementById('Math_RHex').value = DectoRevHex(parseInt(Dec,10),HLen,HType);
}

function MathConvRHex (){
	var RHex = document.getElementById('Math_RHex').value;
	var HTyp = document.getElementById('Math_Type');
	var HType = HTyp.options[HTyp.selectedIndex].value;
	var HLe = document.getElementById('Math_HexLen');
	var HLen = parseInt(HLe.options[HLe.selectedIndex].value)*2;
	var Dec = HextoDec(RHex, HLen, HType, true);
	document.getElementById('Math_Dec').value = Dec;
	document.getElementById('Math_Date').value = NumToDate(Dec);
	document.getElementById('Math_Float').value = numToFloat32Hex(Dec, false);
	document.getElementById('Math_RFloat').value = numToFloat32Hex(Dec, true);
	document.getElementById('Math_Hex').value = DectoHex(parseInt(Dec,10),HLen,HType);
}

function MathConvFloat(){
	var Float = document.getElementById('Math_Float').value;
	var HTyp = document.getElementById('Math_Type');
	var HType = HTyp.options[HTyp.selectedIndex].value;
	var HLe = document.getElementById('Math_HexLen');
	var HLen = parseInt(HLe.options[HLe.selectedIndex].value)*2;
	var FPre = document.getElementById('Math_FPrec');
	var FPrec = parseInt(FPre.options[FPre.selectedIndex].value, 10);
	if (Float.length == 8)	{
		var Dec = parseFloat('0x'+Float);
		var rDec = Dec.toFixed(FPrec);
		document.getElementById('Math_Dec').value = rDec;
		document.getElementById('Math_Date').value = NumToDate(rDec);
		document.getElementById('Math_RFloat').value=ReverseNumber(Float);
		if (Dec % 1 == 0){
			document.getElementById('Math_Hex').value = DectoHex(parseInt(Dec,10),HLen,HType);
			document.getElementById('Math_RHex').value = DectoRevHex(parseInt(Dec,10),HLen,HType);
		}else{
			document.getElementById('Math_Hex').value = DectoHex(0,HLen,HType);
			document.getElementById('Math_RHex').value = DectoRevHex(0,HLen,HType);
		}
	}
}

function MathConvRFloat(){
	var RFloat = document.getElementById('Math_RFloat').value;
	var Float = ReverseNumber(RFloat);
	if (Float.length == 8)	{
		var HTyp = document.getElementById('Math_Type');
		var HType = HTyp.options[HTyp.selectedIndex].value;
		var HLe = document.getElementById('Math_HexLen');
		var HLen = parseInt(HLe.options[HLe.selectedIndex].value)*2;
		var FPre = document.getElementById('Math_FPrec');
		var FPrec = parseInt(FPre.options[FPre.selectedIndex].value, 10);
		var Dec = parseFloat('0x'+Float);
		var rDec = Dec.toFixed(FPrec);
		document.getElementById('Math_Dec').value = rDec;
		document.getElementById('Math_Date').value = NumToDate(rDec);
		document.getElementById('Math_Float').value=ReverseNumber(RFloat);
		if (Dec % 1 == 0){
			document.getElementById('Math_Hex').value = DectoHex(parseInt(Dec,10),HLen,HType);
			document.getElementById('Math_RHex').value = DectoRevHex(parseInt(Dec,10),HLen,HType);
		}else{
			document.getElementById('Math_Hex').value = DectoHex(0,HLen,HType);
			document.getElementById('Math_RHex').value = DectoRevHex(0,HLen,HType);
		}
	}
}

function SearchAddonNameValue(EQType, AddonId, Val){
	var AddonDat = "";
	var tmp;
	var tmp2;
	var tmp3;
	Val=Val.toString();
	var ValS = Val.indexOf(" ") >= 0;
	if (EQType == 1){
		var SelOpt = document.getElementById('Inp_W_AddonType');
	}else if(EQType == 2){
		var SelOpt = document.getElementById('Inp_A_AddonType');
	}else if(EQType == 3){
		var SelOpt = document.getElementById('Inp_J_AddonType');
	}else if(EQType == 43){
		var SelOpt = document.getElementById('Inp_B_AddonType');
	}else if(EQType == 46){
		var SelOpt = document.getElementById('Inp_M_AddonType');
	}
		for (i=0; i<SelOpt.length; i++){
			tmp = SelOpt.options[i].value.split("#");
			var LId = tmp[0];
			if (tmp[0].indexOf(" ") != -1){
				tmp2=tmp[0].split(" ");
				LId = tmp2[0];
			}
			if (LId == AddonId){
				var AddTit = "";
				if (tmp[2]!= "S"){
					tmp3=StatName[parseInt(tmp[1],10)];
					if (tmp3!=null){
						AddTit=GetAddonString(parseInt(tmp[1],10), Val);
					}else{
						AddTit="Addon["+AddonId+"]: "+Val;
					}
				}else{
					AddTit = tmp[1];
				}				
				if (ValS){
					tmp2=Val.split(" ");
					tmp3="";
					for (var i=0; i<tmp2.length; i++){
						tmp3 = tmp3 + DectoRevHex(tmp2[i],8,0);
					}	
					AddonDat = DectoRevHex(AddonId,8,4)+tmp3;
				}else{
					if (tmp[2]== "F"){
						AddonDat = DectoRevHex(AddonId,8,2)+numToFloat32Hex(Val, true);
					}else{
						AddonDat = DectoRevHex(AddonId,8,2)+DectoRevHex(Val,8,0);
					}
				}
				AddonDat = AddonDat+"#"+AddTit+"#"+tmp[2]+"#WAJB#"+Val;
				return AddonDat;
			}
		}
		
		if (AddonDat == ""){
			if (Val.indexOf(" ") != -1){
				tmp2=Val.split(" ");
				tmp3=DectoRevHex(AddonId,8,0);
				for (i=0; i<tmp2.length; i++){
					tmp3 = tmp3 + DectoRevHex(tmp2[i],8,0);
				}	
				AddonDat = tmp3+"#Addon ["+AddonId+"] +"+Val+"#H#WAJB#"+Val;			
			}else{
				AddonDat = DectoRevHex(AddonId,8,0)+DectoRevHex(Val,8,0)+"#Addon ["+AddonId+"] +"+Val+"#H#WAJB#"+Val;
			}
		}
	
	return AddonDat;
}

function SearchRuneNameValue(EQType, AddonId, Val){
	var AddonDat = "";
	var tmp;
	var tmp2;
	var tmp3;
	Val=Val.toString();
	var ValS = Val.indexOf("#") >= 0;
	
	if (EQType == 1){
		var SelOpt = document.getElementById('Inp_W_AddonType');
	}else if(EQType == 2){
		var SelOpt = document.getElementById('Inp_A_AddonType');
	}else if(EQType == 3){
		var SelOpt = document.getElementById('Inp_J_AddonType');
	}else if(EQType == 43){
		var SelOpt = document.getElementById('Inp_B_AddonType');
	}else if(EQType == 46){
		var SelOpt = document.getElementById('Inp_M_AddonType');
	}
	
	for (i=0; i<SelOpt.length; i++){
		tmp = SelOpt.options[i].value.split("#");
		var LId = tmp[0];
		if (tmp[0].indexOf("#") != -1){
			tmp2=tmp[0].split(" ");
			LId = tmp2[0];
		}
		if (LId == AddonId){
			var rData=Val.split(" ");
			var RVal;
			var AddTit = "";
			if (tmp[2]== "F"){
				rData[0]=parseFloat("0x"+DectoHex(rData[0], 8, 0)).toFixed(2);
			}
				
			tmp3=StatName[parseInt(tmp[1],10)];
			if (tmp3!=null){
				AddTit="Rune: "+GetAddonString(parseInt(tmp[1],10), rData[0])+" ("+rData[1]+"m)";
			}else{
				AddTit="Rune Addon["+AddonId+"]: "+rData[0];
			}
			
			
			if (tmp[2]== "F"){
				AddonDat = DectoRevHex(AddonId,8,4)+numToFloat32Hex(rData[0], true)+DectoRevHex(rData[1],8,0);
			}else if(tmp[2]== "H"){
				AddonDat = DectoRevHex(AddonId,8,4)+DectoRevHex(rData[0],8,0)+DectoRevHex(rData[1],8,0);
			}

			
			AddonDat = AddonDat+"#"+AddTit+"#"+tmp[2]+"#WAJB#"+rData[0]+"#1#"+rData[1];
			return AddonDat;
			
		}
	}
	
	if (AddonDat == ""){
		if (Val.indexOf(" ") != -1){
			tmp2=Val.split(" ");
			tmp3=DectoRevHex(AddonId,8,0);
			for (i=0; i<tmp2.length; i++){
				tmp3 = tmp3 + DectoRevHex(tmp2[i],8,0);
			}	
			//AddonDat = tmp3+"#Addon ["+AddonId+"] +"+Val+"#H#WAJB#"+Val.replace();			
		}else{
			//AddonDat = DectoRevHex(AddonId,8,4)+DectoRevHex(Val,8,0)+"#Addon ["+AddonId+"] +"+Val+"#H#WAJB#"+Val;
		}
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
	
	for (var i=1; i<4; i++){
		var SelOpt = document.getElementById('StoneType'+i);
		for (var a=0; a<SelOpt.length; a++){
			tmp = SelOpt.options[a].value.split("#");
			if (tmp[0] == StoneId){
				var StnData = tmp[APos].split(',');
				AddonTxt = GetAddonString(parseInt(StnData[0], 10), StnData[1]);
				FullStData = StoneId+"#"+StAddon+"#"+StAddVal+"#"+ AddonTxt +"#"+ tmp[5] +"#"+tmp[6];
				return FullStData;
			}
		}
	}
	
	if (FullStData == ""){
		FullStData = StoneId+"#"+StAddon+"#"+StAddVal+"#Unknown: +"+StAddVal+"#No Name#? grade";
		return FullStData;
	}
}

function SearchPetSkillName(SkillId, SkillLv){
	var SelOpt = document.getElementById('Inp_P_SkillData');
	var myArr = [];
	for (var i=0; i<SelOpt.length; i++){
		myArr = SelOpt.options[i].value.split("#");
		if (myArr[0] == SkillId){
			return SkillLv+"#"+SelOpt.options[i].value;
		}
	}
	return (SkillLv+"#"+SkillId+"#Unknown ["+SkillId+"]#5#0#Unknown pet skill");
}

function SearchElfGearName(GearId){
	var SelOpt;
	var myArr = [];
	for (var x=1; x<5; x++){
		SelOpt = document.getElementById('Inp_E_Gear'+x);
		for (var i=0; i<SelOpt.length; i++){
			myArr = SelOpt.options[i].value.split("#");
			if (myArr[0] == GearId){
				SelOpt.selectedIndex = i;
				return GearId+"#"+SelOpt.options[i].value;
			}
		}
	}
	return (GearId+"#"+"Unknown ["+GearId+"]");
}

function SearchElfSkillData(SkillId, SkillLv){
	var SelOpt = document.getElementById('Inp_E_SkillData');
	var myArr = [];
	for (var i=0; i<SelOpt.length; i++){
		myArr = SelOpt.options[i].value.split("#");
		if (myArr[0] == SkillId){
			return SkillLv+"#"+SelOpt.options[i].value;
		}
	}
	return (SkillLv+"#"+SkillId+"#1#00000#Unknown Skill#No info about this skill");	
}

function LoadOctet(n){
	var AddonId;
	if (n==1){
		var LOctet = document.getElementById('Inp_W_NewOctet').value;
		if (ishex(LOctet) !== false){
			if (LOctet.length > 151){
				var LvReq = HextoDec(LOctet.substr(0, 4), 4, 0, true);
				var ClReq = HextoDec(LOctet.substr(4, 4), 4, 0, true);
				var StrReq = HextoDec(LOctet.substr(8, 4), 4, 0, true);
				var ConReq = HextoDec(LOctet.substr(12, 4), 4, 0, true);
				var AgiReq = HextoDec(LOctet.substr(16, 4), 4, 0, true);
				var IntReq = HextoDec(LOctet.substr(20, 4), 4, 0, true);
				var Dur1 = parseInt(HextoDec(LOctet.substr(24, 8), 8, 0, true)/100, 10);
				var Dur2 = parseInt(HextoDec(LOctet.substr(32, 8), 8, 0, true)/100, 10);
				var EqType = HextoDec(LOctet.substr(40, 4), 4, 0, true);
				var ItFlag = HextoDec(LOctet.substr(44, 2), 2, 0, true);
				var CNameL = parseInt(HextoDec(LOctet.substr(46, 2), 2, 0, true)/2, 10);
				var Cname = "";
				var str;
				var n = 48;
				for (var i=0; i<CNameL; i++){
					str = LOctet.substr((48+i*4), 4);
					n = HextoDec(str.substr(0, 2), 2, 0, true);
					Cname = Cname + String.fromCharCode(n);
					n = 48+i*4+4;
				}
				var RangeT = HextoDec(LOctet.substr(n, 8), 8, 0, true);
				var WeaponT = HextoDec(LOctet.substr(n+8, 8), 8, 0, true);
				var WeapSel = document.getElementById('Sel_WSub_Type');
				var GradeT = HextoDec(LOctet.substr(n+16, 8), 8, 0, true);
				var AmmoT = HextoDec(LOctet.substr(n+24, 8), 8, 0, true);
				var PDmg1 = parseInt(HextoDec(LOctet.substr(n+32, 8), 8, 0, true), 10);
				var PDmg2 = parseInt(HextoDec(LOctet.substr(n+40, 8), 8, 0, true), 10);
				var MDmg1 = parseInt(HextoDec(LOctet.substr(n+48, 8), 8, 0, true), 10);
				var MDmg2 = parseInt(HextoDec(LOctet.substr(n+56, 8), 8, 0, true), 10);
				var AttRate = HextoDec(LOctet.substr(n+64, 8), 8, 0, true);
				var Range = parseFloat('0x'+ReverseNumber(LOctet.substr(n+72, 8))).toFixed(2);
				var MinRange = parseFloat('0x'+ReverseNumber(LOctet.substr(n+80, 8))).toFixed(2);
				var WSI = WeapSel.selectedIndex;
				var RWT = false;
				var AutoCat = document.getElementById('Inp_W_AutoOctet');
				if (AutoCat.checked !== false){
					if (WeaponT == 1){
						if ((WSI < 8)||(WSI > 11)){
							WSI = 8;
							RWT = true;
						}
					}else if(WeaponT == 5){
						if ((WSI < 3)||(WSI > 7)){
							WSI = 4;
							RWT = true;
						}					
					}else if(WeaponT == 9){
						if (WSI > 3){
							WSI = 0;
							RWT = true;
						}					
					}else if(WeaponT == 13){
						if (AmmoT == 8547){
							if (WSI != 15){
								WSI = 15;	
								RWT = true;
							}
						}else if (AmmoT == 8548){
							if (WSI != 16){
								WSI = 16;													
								RWT = true;
							}
						}else{
							if (WSI != 14){
								WSI = 14;	
								RWT = true;
							}
						}
					}else if(WeaponT == 182){
						if ((WSI != 12)&&(WSI != 13)){
							WSI = 12;	
							RWT = true;
						}
					}else if(WeaponT == 292){
						if ((WSI < 17)||(WSI > 20)){
							WSI = 17;	
							RWT = true;
						}
					}else if(WeaponT == 23749){
						if (WSI != 21){
							WSI = 21;	
							RWT = true;
						}
					}else if(WeaponT == 25333){
						if (WSI != 22){
							WSI = 22;	
							RWT = true;
						}
					}else if(WeaponT == 44878){
						if (WSI != 23){
							WSI = 23;	
							RWT = true;
						}
					}else if(WeaponT == 44967){
						if (WSI != 24){
							WSI = 24;	
							RWT = true;
						}
					}
					if (RWT === true){
						WeapSel.selectedIndex = WSI;
						ChangeWeaponType();
						var selITL = document.getElementById("SItmC1S"+(WSI+1));
						selITL.selectedIndex = 0;
						getPItemData(selITL);
					}					
				}
				var bpDmg1 = 0;
				var bpDmg2 = 0;
				var bmDmg1 = 0;
				var bmDmg2 = 0;
				var bRange =0;
				var bmaxDur =0;				
				document.getElementById('Inp_W_LvReq').value = LvReq;
				document.getElementById('Inp_W_Class').value = ClReq;
				document.getElementById("ClassMask").value = ClReq;
				document.getElementById('Inp_Grade2').value = GradeT;
				document.getElementById('Inp_W_STR').value = StrReq;
				document.getElementById('Inp_W_AGI').value = AgiReq;
				document.getElementById('Inp_W_CON').value = ConReq;
				document.getElementById('Inp_W_INT').value = IntReq;
				document.getElementById('Inp_W_CurDur').value = Dur1;
				document.getElementById('Inp_W_Ranged').selectedIndex = RangeT;
				document.getElementById('Inp_W_AttRate2').value = AttRate;
				document.getElementById('Inp_W_Range2').value = Range;
				document.getElementById('Inp_W_MinRange2').value = MinRange;
				document.getElementById('Inp_W_Crafter').value = Cname;
				var SocketC = HextoDec(LOctet.substr(n+88, 8), 8, 0, true);
				var RefLv = 0;
				if (SocketC>0){
					var SocketSt = [];
					for (var i=1; i<=SocketC; i++){
						SocketSt[i] = HextoDec(LOctet.substr(n+88+i*8, 8), 8, 0, true);
					}	
				}
				n = n+SocketC*8;
				var AddonC = HextoDec(LOctet.substr(n+96, 8), 8, 0, true);
				if (AddonC>0){
					var AHex;
					var tmp;
					var shift = 0;
					AddInd = 0;
					SckInd = 0;
					var aType;
					var bAddon;
					var vAddon;
					
					for (var i=0; i<AddonC; i++){
						AHex = ReverseNumber(LOctet.substr(n+104+i*16+shift, 8));
						AHex = AHex.replace(/^0+/, '').trim();
						//2 normal addon, 4 special or refine, a is socket addon
						if (AHex.length % 2 == 0){
							aType = AHex.substr(0,1);
							if (aType=="4"){
								tmp = HextoDec(AHex, 8, aType, false);
								if ((tmp > 1691) && (tmp < 1892)){
									//these addon id range is refine addons
									RefLv = HextoDec(LOctet.substr(n+104+i*16+shift+16, 8), 8, 0, true);
								}else{
									AddonId=HextoDec(AHex, 8, aType, false);
									if (isRune(AddonId)!==false){
										AddInd++;
										Addons[AddInd] = SearchRuneNameValue(1, AddonId, HextoDec(LOctet.substr(n+104+i*16+shift+8, 8), 8, 0, true) +" "+ HextoDec(LOctet.substr(n+104+i*16+shift+16, 8), 8, 0, true));
									}else{
										//special weapon addons
										AddInd++;
										Addons[AddInd] = SearchAddonNameValue(1, AddonId, HextoDec(LOctet.substr(n+104+i*16+shift+8, 8), 8, 0, true) +" "+ HextoDec(LOctet.substr(n+104+i*16+shift+16, 8), 8, 0, true));
									}
								}
								shift = shift + 8;
							}else{
								if (aType=="a"){
									//socket addon
									SckInd++;
									SckAddons[SckInd] = SearchSocketNameValue(1, SocketSt[SckInd], HextoDec(AHex, 8, aType, false) +"#"+ HextoDec(LOctet.substr(n+104+i*16+shift+8, 8), 8, 0, true));
								}else{
									
									//normal addon
									AddInd++;
									bAddon = ReverseNumber(AHex, 4);
									vAddon = HextoDec(LOctet.substr(n+104+i*16+shift+8, 8), 8, 0, true);
									if (bAddon == "f023"){
										bpDmg1 = bpDmg1 + parseInt(vAddon, 10);
										bpDmg2 = bpDmg2 + parseInt(vAddon, 10);
									}else if (bAddon == "ec23"){
										bpDmg2 = bpDmg2 + parseInt(vAddon, 10);
									}else if (bAddon == "fb23"){
										bmDmg1 = bmDmg1 + parseInt(vAddon, 10);
										bmDmg2 = bmDmg2 + parseInt(vAddon, 10);
									}else if (bAddon == "a721"){
										bmDmg2 = bmDmg2 + parseInt(vAddon, 10);
									}else if (bAddon == "d721"){
										vAddon = parseFloat('0x'+ReverseNumber(LOctet.substr(n+104+i*16+shift+8, 8))).toFixed(2);
										bRange = bRange + vAddon;
									}else if (bAddon == "2c21"){
										vAddon = parseFloat('0x'+ReverseNumber(LOctet.substr(n+104+i*16+shift+8, 8))).toFixed(2);
										bmaxDur = bmaxDur + parseInt(vAddon*100);
									}else if (bAddon == "7c22"){
										vAddon = parseFloat('0x'+ReverseNumber(LOctet.substr(n+104+i*16+shift+8, 8))).toFixed(2);
									}
									Addons[AddInd] = SearchAddonNameValue(1, HextoDec(AHex, 8, aType, false), vAddon)+"#"+vAddon;
								}
							}
						}	
					}	
				}	
				document.getElementById('Inp_W_PDmg1').value = PDmg1 - bpDmg1;
				document.getElementById('Inp_W_PDmg2').value = PDmg2 - bpDmg2;
				document.getElementById('Inp_W_MDmg1').value = MDmg1 - bmDmg1;
				document.getElementById('Inp_W_MDmg2').value = MDmg2 - bmDmg2;
				document.getElementById('Inp_W_Range2').value = (Range - bRange).toFixed(2);
				document.getElementById('Inp_W_Socket').selectedIndex = SocketC;
				document.getElementById('Inp_W_RefLv').selectedIndex = RefLv;
				RefreshAddonList();
				RefreshSckAddonList();
				if (bmaxDur > 0){
					document.getElementById('Inp_W_MaxDur').value = parseInt(Dur2 * 100 / (100+parseInt(bmaxDur, 10)));
				}else{
					document.getElementById('Inp_W_MaxDur').value = Dur2;
				}					
			}
		}
	}else if (n==2){
		var LOctet = document.getElementById('Inp_A_NewOctet').value;
		if (ishex(LOctet) !== false){
			if (LOctet.length > 135){
				var LvReq = HextoDec(LOctet.substr(0, 4), 4, 0, true);
				var ClReq = HextoDec(LOctet.substr(4, 4), 4, 0, true);
				var StrReq = HextoDec(LOctet.substr(8, 4), 4, 0, true);
				var ConReq = HextoDec(LOctet.substr(12, 4), 4, 0, true);
				var AgiReq = HextoDec(LOctet.substr(16, 4), 4, 0, true);
				var IntReq = HextoDec(LOctet.substr(20, 4), 4, 0, true);
				var Dur1 = parseInt(HextoDec(LOctet.substr(24, 8), 8, 0, true)/100, 10);
				var Dur2 = parseInt(HextoDec(LOctet.substr(32, 8), 8, 0, true)/100, 10);
				var EqType = HextoDec(LOctet.substr(40, 4), 4, 0, true);
				var ItFlag = HextoDec(LOctet.substr(44, 2), 2, 0, true);
				var CNameL = parseInt(HextoDec(LOctet.substr(46, 2), 2, 0, true)/2, 10);
				var Cname = "";
				var str;
				var n = 48;
				for (var i=0; i<CNameL; i++){
					str = LOctet.substr((48+i*4), 4);
					n = HextoDec(str.substr(0, 2), 2, 0, true);
					Cname = Cname + String.fromCharCode(n);
					n = 48+i*4+4;
				}
				var Pdef = HextoDec(LOctet.substr(n, 8), 8, 0, true);
				var Dodge = HextoDec(LOctet.substr(n+8, 8), 8, 0, true);
				var HitPoint = HextoDec(LOctet.substr(n+16, 8), 8, 0, true);
				var Mana = HextoDec(LOctet.substr(n+24, 8), 8, 0, true);
				var Metal = HextoDec(LOctet.substr(n+32, 8), 8, 0, true);
				var Wood = HextoDec(LOctet.substr(n+40, 8), 8, 0, true);
				var Water = HextoDec(LOctet.substr(n+48, 8), 8, 0, true);
				var Fire = HextoDec(LOctet.substr(n+56, 8), 8, 0, true);
				var Earth = HextoDec(LOctet.substr(n+64, 8), 8, 0, true);
				var ArType = document.getElementById('Sel_ASub_Type');
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
				var SocketC = HextoDec(LOctet.substr(n+72, 8), 8, 0, true);
				var RefLv = 0;
				if (SocketC>0){
					var SocketSt = [];
					for (var i=1; i<=SocketC; i++){
						SocketSt[i] = HextoDec(LOctet.substr(n+72+i*8, 8), 8, 0, true);
					}	
				}
				n = n+SocketC*8;
				var AddonC = HextoDec(LOctet.substr(n+80, 8), 8, 0, true);
				if (AddonC>0){
					var AHex;
					var tmp;
					var shift = 0;
					AddInd = 0;
					SckInd = 0;
					var aType;
					var bAddon;
					var vAddon;
					for (var i=0; i<AddonC; i++){
						AHex = ReverseNumber(LOctet.substr(n+88+i*16+shift, 8));
						AHex = AHex.replace(/^0+/, '').trim();
						//2 normal addon, 4 special or refine, a is socket addon
						if (AHex.length % 2 == 0){
							aType = AHex.substr(0,1);
							if (aType=="4"){
								tmp = HextoDec(AHex, 8, aType, false);
								if ((tmp > 1691) && (tmp < 1892)){
									//these addon id range is refine addons
									RefLv = HextoDec(LOctet.substr(n+88+i*16+shift+16, 8), 8, 0, true);
								}else{
									//special weapon addons
									AddonId=HextoDec(AHex, 8, aType, false);
									if (isRune(AddonId)!==false){
										AddInd++;
										Addons[AddInd] = SearchRuneNameValue(2, AddonId, HextoDec(LOctet.substr(n+88+i*16+shift+8, 8), 8, 0, true) +" "+ HextoDec(LOctet.substr(n+88+i*16+shift+16, 8), 8, 0, true));
									}else{
										//special weapon addons
										AddInd++;
										Addons[AddInd] = SearchAddonNameValue(2, AddonId, HextoDec(LOctet.substr(n+88+i*16+shift+8, 8), 8, 0, true) +" "+ HextoDec(LOctet.substr(n+88+i*16+shift+16, 8), 8, 0, true));
									}
								}
								shift = shift + 8;
							}else{
								if (aType=="a"){
									//socket addon
									SckInd++;
									SckAddons[SckInd] = SearchSocketNameValue(2, SocketSt[SckInd], HextoDec(AHex, 8, aType, false) +"#"+ HextoDec(LOctet.substr(n+88+i*16+shift+8, 8), 8, 0, true));
								}else{
									
									//normal addon
									AddInd++;
									bAddon = ReverseNumber(AHex, 4);
									vAddon = HextoDec(LOctet.substr(n+88+i*16+shift+8, 8), 8, 0, true);
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
										vAddon = parseFloat('0x'+ReverseNumber(LOctet.substr(n+88+i*16+shift+8, 8))).toFixed(2);
										bmaxDur = bmaxDur + parseInt(vAddon*100);
									}else if (bAddon == "7c22"){
										vAddon = parseFloat('0x'+ReverseNumber(LOctet.substr(n+88+i*16+shift+8, 8))).toFixed(2);
									}
									Addons[AddInd] = SearchAddonNameValue(2, HextoDec(AHex, 8, aType, false), vAddon);
								}
							}
						}	
					}	
				}	
				var AutoCat = document.getElementById('Inp_A_AutoOctet');
				var scat = 0;
				var sscat = ArType.selectedIndex;
				var RWT = false;
				if (AutoCat.checked !== false){
					if ((parseInt(Dodge, 10) - bDodge) > 0){
						if (sscat != 14){
							scat = 14;	//manteau
							RWT = true;
						}
					}else if ((parseInt(Mana, 10) - bMP) > 0){
						if (sscat != 13){
							scat = 13;	//mp helm
							RWT = true;
						}
					}else if ((parseInt(HitPoint, 10) - bHP) > 0){
						if (sscat != 12){
							scat = 12;	//hp helm
							RWT = true;
						}
					}else{
						var tmdef = (parseInt(Metal, 10) - bMetal + parseInt(Wood, 10) - bWood + parseInt(Water, 10) - bWater + parseInt(Fire, 10) - bFire + parseInt(Earth, 10) - bEarth)/5;
						var tpdef = parseInt(Pdef, 10) - bPdef;
						var defRat = tmdef / tpdef;
						var sshift;
						if (defRat < 1){
							sshift = 0; //heavy armor
						}else if (defRat < 4){
							sshift = 1; //light armor
						}else{
							sshift = 2; //magic armor
						}
						if (sscat < 3){
							if (sscat != (sshift)){
								scat = 0+sshift;
								RWT = true;
							}
						}else if(sscat < 6){
							if (sscat != (3+sshift)){
								scat = 3+sshift;
								RWT = true;
							}
						}else if(sscat < 9){
							if (sscat != (6+sshift)){
								scat = 6+sshift;
								RWT = true;
							}
						}else if(sscat < 12){
							if (sscat != (9+sshift)){
								scat = 9+sshift;
								RWT = true;
							}
						}
					}
					if (RWT !== false){
						ArType.selectedIndex = scat;
						ChangeArmorType();
						var selITL = document.getElementById("SItmC2S"+(scat+1));
						selITL.selectedIndex = 0;
						getPItemData(selITL);	
					}
				}
				document.getElementById('Inp_A_LvReq').value = LvReq;
				document.getElementById('Inp_A_Class').value = ClReq;
				document.getElementById("ClassMask").value = ClReq;
				document.getElementById('Inp_A_STR').value = StrReq;
				document.getElementById('Inp_A_AGI').value = AgiReq;
				document.getElementById('Inp_A_CON').value = ConReq;
				document.getElementById('Inp_A_INT').value = IntReq;
				document.getElementById('Inp_A_CurDur').value = Dur1;
				document.getElementById('Inp_A_Crafter').value = Cname;
				document.getElementById('Inp_A_PDef').value = parseInt(Pdef, 10) - bPdef;
				document.getElementById('Inp_A_Dodge').value = parseInt(Dodge, 10) - bDodge;
				document.getElementById('Inp_A_HP').value = parseInt(HitPoint, 10) - bHP;
				document.getElementById('Inp_A_MP').value = parseInt(Mana, 10) - bMP;
				document.getElementById('Inp_A_Metal').value = parseInt(Metal, 10) - bMetal;
				document.getElementById('Inp_A_Wood').value = parseInt(Wood, 10) - bWood;
				document.getElementById('Inp_A_Water').value = parseInt(Water, 10) - bWater;
				document.getElementById('Inp_A_Fire').value = parseInt(Fire, 10) - bFire;
				document.getElementById('Inp_A_Earth').value = parseInt(Earth, 10) - bEarth;
				document.getElementById('Inp_A_Socket').selectedIndex = SocketC;		
				document.getElementById('Inp_A_RefLv').selectedIndex = RefLv;

				RefreshAddonList();
				RefreshSckAddonList();				
				if (bmaxDur > 0){
					document.getElementById('Inp_A_MaxDur').value = parseInt(Dur2 * 100 / (100+parseInt(bmaxDur, 10)));
				}else{
					document.getElementById('Inp_A_MaxDur').value = parseInt(Dur2, 10);
				}	
			}
		}
	}else if (n==3){
		var LOctet = document.getElementById('Inp_J_NewOctet').value;
		if (ishex(LOctet) !== false){
			if (LOctet.length > 135){
				var LvReq = HextoDec(LOctet.substr(0, 4), 4, 0, true);
				var ClReq = HextoDec(LOctet.substr(4, 4), 4, 0, true);
				var StrReq = HextoDec(LOctet.substr(8, 4), 4, 0, true);
				var ConReq = HextoDec(LOctet.substr(12, 4), 4, 0, true);
				var AgiReq = HextoDec(LOctet.substr(16, 4), 4, 0, true);
				var IntReq = HextoDec(LOctet.substr(20, 4), 4, 0, true);
				var Dur1 = parseInt(HextoDec(LOctet.substr(24, 8), 8, 0, true)/100, 10);
				var Dur2 = parseInt(HextoDec(LOctet.substr(32, 8), 8, 0, true)/100, 10);
				var EqType = HextoDec(LOctet.substr(40, 4), 4, 0, true);
				var ItFlag = HextoDec(LOctet.substr(44, 2), 2, 0, true);
				var CNameL = parseInt(HextoDec(LOctet.substr(46, 2), 2, 0, true)/2, 10);
				var Cname = "";
				var str;
				var n = 48;
				for (var i=0; i<CNameL; i++){
					str = LOctet.substr((48+i*4), 4);
					n = HextoDec(str.substr(0, 2), 2, 0, true);
					Cname = Cname + String.fromCharCode(n);
					n = 48+i*4+4;
				}
				var Pattack = HextoDec(LOctet.substr(n, 8), 8, 0, true);
				var Mattack = HextoDec(LOctet.substr(n+8, 8), 8, 0, true);
				var Pdef = HextoDec(LOctet.substr(n+16, 8), 8, 0, true);
				var Dodge = HextoDec(LOctet.substr(n+24, 8), 8, 0, true);
				var Metal = HextoDec(LOctet.substr(n+32, 8), 8, 0, true);
				var Wood = HextoDec(LOctet.substr(n+40, 8), 8, 0, true);
				var Water = HextoDec(LOctet.substr(n+48, 8), 8, 0, true);
				var Fire = HextoDec(LOctet.substr(n+56, 8), 8, 0, true);
				var Earth = HextoDec(LOctet.substr(n+64, 8), 8, 0, true);
				var JType = document.getElementById('Sel_JSub_Type');
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
				
				var SocketC = HextoDec(LOctet.substr(n+72, 8), 8, 0, true);
				var RefLv = 0;
				if (SocketC>0){
					var SocketSt = [];
					for (var i=1; i<=SocketC; i++){
						SocketSt[i] = HextoDec(LOctet.substr(n+72+i*8, 8), 8, 0, true);
					}	
				}
				n = n+SocketC*8;
				var AddonC = HextoDec(LOctet.substr(n+80, 8), 8, 0, true);
				if (AddonC>0){
					var AHex;
					var tmp;
					var shift = 0;
					AddInd = 0;
					SckInd = 0;
					var aType;
					var bAddon;
					var vAddon;
					for (var i=0; i<AddonC; i++){
						AHex = ReverseNumber(LOctet.substr(n+88+i*16+shift, 8));
						AHex = AHex.replace(/^0+/, '').trim();
						//2 normal addon, 4 special or refine, a is socket addon
						if (AHex.length % 2 == 0){
							aType = AHex.substr(0,1);
							if (aType=="4"){
								tmp = HextoDec(AHex, 8, aType, false);
								if ((tmp > 1691) && (tmp < 1892)){
									//these addon id range is refine addons
									RefLv = HextoDec(LOctet.substr(n+88+i*16+shift+16, 8), 8, 0, true);
								}else{
									//special weapon addons
									AddonId=HextoDec(AHex, 8, aType, false);
									if (isRune(AddonId)!==false){
										AddInd++;
										Addons[AddInd] = SearchRuneNameValue(3, AddonId, HextoDec(LOctet.substr(n+88+i*16+shift+8, 8), 8, 0, true) +" "+ HextoDec(LOctet.substr(n+88+i*16+shift+16, 8), 8, 0, true));
									}else{
										//special weapon addons
										AddInd++;
										Addons[AddInd] = SearchAddonNameValue(3, AddonId, HextoDec(LOctet.substr(n+88+i*16+shift+8, 8), 8, 0, true) +" "+ HextoDec(LOctet.substr(n+88+i*16+shift+16, 8), 8, 0, true));
									}
								}
								shift = shift + 8;
							}else{
								if (aType=="a"){
									//socket addon
									SckInd++;
									SckAddons[SckInd] = SearchSocketNameValue(3, SocketSt[SckInd], HextoDec(AHex, 8, aType, false) +"#"+ HextoDec(LOctet.substr(n+88+i*16+shift+8, 8), 8, 0, true));
								}else{
									
									//normal addon
									AddInd++;
									bAddon = ReverseNumber(AHex, 4);
									vAddon = HextoDec(LOctet.substr(n+88+i*16+shift+8, 8), 8, 0, true);
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
										vAddon = parseFloat('0x'+ReverseNumber(LOctet.substr(n+88+i*16+shift+8, 8))).toFixed(2);
										bmaxDur = bmaxDur + parseInt(vAddon*100);
									}else if (bAddon == "7c22"){
										vAddon = parseFloat('0x'+ReverseNumber(LOctet.substr(n+88+i*16+shift+8, 8))).toFixed(2);
									}
									Addons[AddInd] = SearchAddonNameValue(3, HextoDec(AHex, 8, aType, false), vAddon);
								}
							}
						}	
					}	
				}	
				var AutoCat = document.getElementById('Inp_J_AutoOctet');
				var scat = 0;
				var sscat = JType.selectedIndex;
				var RWT = false;
				if (AutoCat.checked !== false){
					if ((parseInt(Pattack, 10) - bPattack) > 0){
						if (sscat != 6){
							scat = 6;	//phys ring
							RWT = true;
						}
					}else if ((parseInt(Mattack, 10) - bMattack) > 0){
						if (sscat != 7){
							scat = 7;	//magic ring
							RWT = true;
						}
					}else{
						var sshift;
						if ((parseInt(Pdef, 10) - bPdef) > 0){
							sshift = 0;
						}else if ((parseInt(Dodge, 10) - bDodge) > 0){
							sshift = 1;
						}else{
							sshift = 2;
						}
						
						if (sscat < 3){
							if (sscat != (sshift)){
								scat = 0+sshift;
								RWT = true;
							}
						}else if(sscat < 6){	
							if (sscat != (3+sshift)){
								scat = 3+sshift;
								RWT = true;
							}						
						}
					}
					if (RWT !== false){
						JType.selectedIndex = scat;
						ChangeJewelType();
						var selITL = document.getElementById("SItmC3S"+(scat+1));
						selITL.selectedIndex = 0;
						getPItemData(selITL);	
					}
				}
				document.getElementById('Inp_J_LvReq').value = LvReq;
				document.getElementById('Inp_J_Class').value = ClReq;
				document.getElementById("ClassMask").value = ClReq;
				document.getElementById('Inp_J_STR').value = StrReq;
				document.getElementById('Inp_J_AGI').value = AgiReq;
				document.getElementById('Inp_J_CON').value = ConReq;
				document.getElementById('Inp_J_INT').value = IntReq;
				document.getElementById('Inp_J_CurDur').value = Dur1;
				document.getElementById('Inp_J_Crafter').value = Cname;
				document.getElementById('Inp_J_PDef').value = parseInt(Pdef, 10) - bPdef;
				document.getElementById('Inp_J_Dodge').value = parseInt(Dodge, 10) - bDodge;
				document.getElementById('Inp_J_PAtt').value = parseInt(Pattack, 10) - bPattack;
				document.getElementById('Inp_J_MAtt').value = parseInt(Mattack, 10) - bMattack;
				document.getElementById('Inp_J_Metal').value = parseInt(Metal, 10) - bMetal;
				document.getElementById('Inp_J_Wood').value = parseInt(Wood, 10) - bWood;
				document.getElementById('Inp_J_Water').value = parseInt(Water, 10) - bWater;
				document.getElementById('Inp_J_Fire').value = parseInt(Fire, 10) - bFire;
				document.getElementById('Inp_J_Earth').value = parseInt(Earth, 10) - bEarth;
				document.getElementById('Inp_J_RefLv').selectedIndex = RefLv;

				RefreshAddonList();
				if (bmaxDur > 0){
					document.getElementById('Inp_J_MaxDur').value = parseInt(Dur2 * 100 / (100+parseInt(bmaxDur, 10)));
				}else{
					document.getElementById('Inp_J_MaxDur').value = parseInt(Dur2, 10);
				}	
			}
		}

	}else if (n==7){
		var LOctet = document.getElementById('Inp_F_NewOctet').value;
		if (ishex(LOctet) !== false){
			if (LOctet.length == 24){
				var LvReq = HextoDec(LOctet.substr(0, 8), 8, 0, true);
				var HFCol = LOctet.substr(8, 4);
				var FColor = HextoDec(HFCol, 4, 0, true);
				var Fgender = HextoDec(LOctet.substr(12, 4), 4, 0, true);
				var Unknown = HextoDec(LOctet.substr(16, 8), 8, 0, true);
				document.getElementById('Inp_Fs_LvReq').value = LvReq;
				document.getElementById('Inp_Fs_Color2').value = FColor;
				if (Fgender == 1){
					document.getElementById('FashFemale').checked = true;
				}else{
					document.getElementById('FashMale').checked = true;
				}
				
				var SelO = document.getElementById('Inp_X_Id');
				for (var i=0; i<SelO.length; i++){
					if (SelO.options[i].value==HFCol){
						SelO.selectedIndex = i;
					}
				}
			}
		}	
	}else if (n==8){
		var LOctet = document.getElementById('Inp_C_NewOctet').value;
		if (ishex(LOctet) !== false){
			if (LOctet.length == 64){
				var cTyp = HextoDec(LOctet.substr(0, 8), 8, 0, true);
				var cGra = HextoDec(LOctet.substr(8, 8), 8, 0, true);
				var cLvR = HextoDec(LOctet.substr(16, 8), 8, 0, true);
				var cLea = HextoDec(LOctet.substr(24, 8), 8, 0, true);
				var cMxL = HextoDec(LOctet.substr(32, 8), 8, 0, true);
				var cCLv = HextoDec(LOctet.substr(40, 8), 8, 0, true);
				var cExp = HextoDec(LOctet.substr(48, 8), 8, 0, true);
				var cReb = HextoDec(LOctet.substr(56, 8), 8, 0, true);
				document.getElementById('Inp_C_Type').selectedIndex = parseInt(cTyp, 10);
				document.getElementById('Inp_C_Grade').selectedIndex = parseInt(cGra, 10);
				document.getElementById('Inp_C_LvReq').value = cLvR;
				document.getElementById('Inp_C_Lead').value = cLea;
				document.getElementById('Inp_C_MaxLv').value = cMxL;
				document.getElementById('Inp_C_CurLv').value = cCLv;
				document.getElementById('Inp_C_ExpLv').value = cExp;
				document.getElementById('Inp_C_Reawake').selectedIndex = parseInt(cReb, 10);
				GetCardInfo();
			}
		}
	}else if (n>400){
		if (n==401){
			var LOctet = document.getElementById('Inp_O1_NewOctet').value;
			if (ishex(LOctet) !== false){
				if (LOctet.length > 63){
					var Fuel1 = HextoDec(LOctet.substr(0, 8), 8, 0, true);
					var Fuel2 = HextoDec(LOctet.substr(8, 8), 8, 0, true);
					var LvReq = HextoDec(LOctet.substr(16, 4), 4, 0, true);
					var Grade = HextoDec(LOctet.substr(20, 4), 4, 0, true);
					var Combo = HextoDec(LOctet.substr(24, 8), 8, 0, true);
					document.getElementById("ClassMask").value = Combo;
					var Unknown1 = HextoDec(LOctet.substr(32, 8), 8, 0, true);
					var Speed1 = parseFloat('0x'+ReverseNumber(LOctet.substr(40, 8), 8)).toFixed(2);
					var Speed2 = parseFloat('0x'+ReverseNumber(LOctet.substr(48, 8), 8)).toFixed(2);
					var Unknown2 = HextoDec(LOctet.substr(56, 4), 4, 0, true);					
					var Unknown3 = HextoDec(LOctet.substr(60, 2), 2, 0, true);					
					var Unknown4 = HextoDec(LOctet.substr(62, 2), 2, 0, true);	
					document.getElementById('Inp_F_ReqLv').value = LvReq;
					var race = document.getElementById('Inp_F_Race');
					document.getElementById('Inp_F_Grade').value = Grade;
					document.getElementById('Inp_F_Fuel1').value = Fuel1;
					document.getElementById('Inp_F_Fuel2').value = Fuel2;
					document.getElementById('Inp_F_Speed1').value = Speed1;
					document.getElementById('Inp_F_Speed2').value = Speed2;
					for (var i=0; i<race.length; i++){
						if (race.options[i].value == Combo){
							race.selectedIndex = i;
						}
					}
				}
			}
		}else if (n==402){
			var LOctet = document.getElementById('Inp_O2_NewOctet').value;
			if (ishex(LOctet) !== false){
				if (LOctet.length > 119){
					var LvReq = HextoDec(LOctet.substr(0, 8), 8, 0, true);
					var ReqCls = HextoDec(LOctet.substr(8, 8), 8, 0, true);
					document.getElementById("ClassMask").value = ReqCls;
					var Loyal = HextoDec(LOctet.substr(16, 8), 8, 0, true);
					var PetId = HextoDec(LOctet.substr(24, 8), 8, 0, true);
					var Unknown1 = HextoDec(LOctet.substr(32, 8), 8, 0, true);	
					var EggId = HextoDec(LOctet.substr(40, 8), 8, 0, true);		
					var PetTyp = HextoDec(LOctet.substr(48, 8), 8, 0, true);					
					var PetLv = HextoDec(LOctet.substr(56, 8), 8, 0, true);					
					var Unknown2 = HextoDec(LOctet.substr(64, 8), 8, 0, true);					
					var Unknown3 = HextoDec(LOctet.substr(72, 8), 8, 0, true);					
					var Unknown4 = HextoDec(LOctet.substr(80, 4), 4, 0, true);
					var PetSkillC = HextoDec(LOctet.substr(84, 4), 4, 0, true);	
					var Unknown5 = HextoDec(LOctet.substr(88, 8), 8, 0, true);	
					var Unknown6 = HextoDec(LOctet.substr(96, 8), 8, 0, true);	
					var Unknown7 = HextoDec(LOctet.substr(104, 8), 8, 0, true);	
					var Unknown8 = HextoDec(LOctet.substr(112, 8), 8, 0, true);	
					var SkillId;
					var SkillLv;
					for (var i=0; i<PetSkillC; i++){
						SkillId = HextoDec(LOctet.substr(120+i*16, 8), 8, 0, true);
						SkillLv = HextoDec(LOctet.substr(120+i*16+8, 8), 8, 0, true);
						PSInd++;
						PSkill[PSInd] = SearchPetSkillName(SkillId, SkillLv);
					}
					RefreshPetSkillList();
				}
			}			
		}else if (n==403){
			var LOctet = document.getElementById('Inp_O3_NewOctet').value;
			if (ishex(LOctet) !== false){
				if (LOctet.length > 135){
					var LvReq = HextoDec(LOctet.substr(0, 4), 4, 0, true);
					var ClReq = HextoDec(LOctet.substr(4, 4), 4, 0, true);
					var StrReq = HextoDec(LOctet.substr(8, 4), 4, 0, true);
					var ConReq = HextoDec(LOctet.substr(12, 4), 4, 0, true);
					var AgiReq = HextoDec(LOctet.substr(16, 4), 4, 0, true);
					var IntReq = HextoDec(LOctet.substr(20, 4), 4, 0, true);
					var Dur1 = parseInt(HextoDec(LOctet.substr(24, 8), 8, 0, true)/100, 10);
					var Dur2 = parseInt(HextoDec(LOctet.substr(32, 8), 8, 0, true)/100, 10);
					var EqType = HextoDec(LOctet.substr(40, 4), 4, 0, true);
					var ItFlag = HextoDec(LOctet.substr(44, 2), 2, 0, true);
					var CNameL = parseInt(HextoDec(LOctet.substr(46, 2), 2, 0, true)/2, 10);
					var Cname = "";
					var str;
					var n = 48;
					for (var i=0; i<CNameL; i++){
						str = LOctet.substr((48+i*4), 4);
						n = HextoDec(str.substr(0, 2), 2, 0, true);
						Cname = Cname + String.fromCharCode(n);
						n = 48+i*4+4;
					}
					var Pdef = HextoDec(LOctet.substr(n, 8), 8, 0, true);
					var Dodge = HextoDec(LOctet.substr(n+8, 8), 8, 0, true);
					var HitPoint = HextoDec(LOctet.substr(n+16, 8), 8, 0, true);
					var Mana = HextoDec(LOctet.substr(n+24, 8), 8, 0, true);
					var Metal = HextoDec(LOctet.substr(n+32, 8), 8, 0, true);
					var Wood = HextoDec(LOctet.substr(n+40, 8), 8, 0, true);
					var Water = HextoDec(LOctet.substr(n+48, 8), 8, 0, true);
					var Fire = HextoDec(LOctet.substr(n+56, 8), 8, 0, true);
					var Earth = HextoDec(LOctet.substr(n+64, 8), 8, 0, true);
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
					var SocketC = HextoDec(LOctet.substr(n+72, 8), 8, 0, true);
					var RefLv = 0;
					var RefId;
					if (SocketC>0){
						var SocketSt = [];
						for (var i=1; i<=SocketC; i++){
							SocketSt[i] = HextoDec(LOctet.substr(n+72+i*8, 8), 8, 0, true);
						}	
					}
					n = n+SocketC*8;
					var AddonC = HextoDec(LOctet.substr(n+80, 8), 8, 0, true);
					if (AddonC>0){
						var AHex;
						var tmp;
						var shift = 0;
						AddInd = 0;
						SckInd = 0;
						var aType;
						var bAddon;
						var vAddon;
						for (var i=0; i<AddonC; i++){
							AHex = ReverseNumber(LOctet.substr(n+88+i*16+shift, 8));
							AHex = AHex.replace(/^0+/, '').trim();
							//2 normal addon, 4 special or refine, a is socket addon
							if (AHex.length % 2 == 0){
								aType = AHex.substr(0,1);
								if (aType=="4"){
									tmp = HextoDec(AHex, 8, aType, false);
									if ((tmp > 1691) && (tmp < 1892)){
										//these addon id range is refine addons
										RefId = HextoDec(LOctet.substr(n+88+i*16+shift, 8), 8, 4, true);
										RefLv = HextoDec(LOctet.substr(n+88+i*16+shift+16, 8), 8, 0, true);
									}else{
										AddonId=HextoDec(AHex, 8, aType, false);
										if (isRune(AddonId)!==false){
											AddInd++;
											Addons[AddInd] = SearchRuneNameValue(43, AddonId, HextoDec(LOctet.substr(n+88+i*16+shift+8, 8), 8, 0, true) +" "+ HextoDec(LOctet.substr(n+88+i*16+shift+16, 8), 8, 0, true));
										}else{
											//special weapon addons
											AddInd++;
											Addons[AddInd] = SearchAddonNameValue(43, AddonId, HextoDec(LOctet.substr(n+88+i*16+shift+8, 8), 8, 0, true) +" "+ HextoDec(LOctet.substr(n+88+i*16+shift+16, 8), 8, 0, true));
										}									
									}
									shift = shift + 8;
								}else{
									if (aType=="a"){
										//socket addon
										SckInd++;
										SckAddons[SckInd] = SearchSocketNameValue(43, SocketSt[SckInd], HextoDec(AHex, 8, aType, false) +"#"+ HextoDec(LOctet.substr(n+88+i*16+shift+8, 8), 8, 0, true));
									}else{
										
										//normal addon
										AddInd++;
										bAddon = ReverseNumber(AHex, 4);
										vAddon = HextoDec(LOctet.substr(n+88+i*16+shift+8, 8), 8, 0, true);
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
											vAddon = parseFloat('0x'+ReverseNumber(LOctet.substr(n+88+i*16+shift+8, 8))).toFixed(2);
											bmaxDur = bmaxDur + parseInt(vAddon*100);
										}else if (bAddon == "7c22"){
											vAddon = parseFloat('0x'+ReverseNumber(LOctet.substr(n+88+i*16+shift+8, 8))).toFixed(2);
										}
										Addons[AddInd] = SearchAddonNameValue(43, HextoDec(AHex, 8, aType, false), vAddon);
									}
								}
							}	
						}	
					}	
					var refType = 0;
					var refGrd = 0;
					var arr = [];
					if (RefLv > 0){
						for (var mt=1; ((mt<refBase.length)&&(refType==0)); mt++){
							for (var st=1; ((st<refBase[mt].length)&&(refType==0)); st++){
								for (var gr=1; ((gr<refBase[mt][st].length)&&(refType==0)); gr++){
									arr = refBase[mt][st][gr].split("#");
									if (arr[1] == RefId){
										refGrd = gr;
										refType = parseInt(mt + "" + st, 10);
									}
								}
							}
						}
					}
					var SelO = document.getElementById('Inp_B_RefType');
					for (var i=0; i<SelO.length; i++){
						if (SelO.options[i].value == refType) {
							SelO.selectedIndex = i;
						}
					}
					SelO = document.getElementById('Inp_B_RefGr');
					for (var i=0; i<SelO.length; i++){
						if (SelO.options[i].value == refGrd) {
							SelO.selectedIndex = i;
						}
					}
					document.getElementById('Inp_B_LvReq').value = LvReq;
					document.getElementById('Inp_B_Class').value = ClReq;
					document.getElementById("ClassMask").value = ClReq;
					document.getElementById('Inp_B_STR').value = StrReq;
					document.getElementById('Inp_B_AGI').value = AgiReq;
					document.getElementById('Inp_B_CON').value = ConReq;
					document.getElementById('Inp_B_INT').value = IntReq;
					document.getElementById('Inp_B_CurDur').value = Dur1;
					document.getElementById('Inp_B_Crafter').value = Cname;
					document.getElementById('Inp_B_PDef').value = parseInt(Pdef, 10) - bPdef;
					document.getElementById('Inp_B_Dodge').value = parseInt(Dodge, 10) - bDodge;
					document.getElementById('Inp_B_HP').value = parseInt(HitPoint, 10) - bHP;
					document.getElementById('Inp_B_MP').value = parseInt(Mana, 10) - bMP;
					document.getElementById('Inp_B_Metal').value = parseInt(Metal, 10) - bMetal;
					document.getElementById('Inp_B_Wood').value = parseInt(Wood, 10) - bWood;
					document.getElementById('Inp_B_Water').value = parseInt(Water, 10) - bWater;
					document.getElementById('Inp_B_Fire').value = parseInt(Fire, 10) - bFire;
					document.getElementById('Inp_B_Earth').value = parseInt(Earth, 10) - bEarth;
					document.getElementById('Inp_B_Socket').selectedIndex = SocketC;		
					document.getElementById('Inp_B_RefLv').selectedIndex = RefLv;
					RefreshAddonList();
					RefreshSckAddonList();	
					if (bmaxDur > 0){
						document.getElementById('Inp_B_MaxDur').value = parseInt(Dur2 * 100 / (100+parseInt(bmaxDur, 10)));
					}else{
						document.getElementById('Inp_B_MaxDur').value = parseInt(Dur2, 10);
					}	
				}
			}			
		}else if (n==404){
			var LOctet = document.getElementById('Inp_O4_NewOctet').value;
			if (ishex(LOctet) !== false){
				if (LOctet.length > 83){
					var ElfXp = HextoDec(LOctet.substr(0, 8), 8, 0, true);
					var ElfLv = HextoDec(LOctet.substr(8, 4), 4, 0, true);
					var ElfPt = HextoDec(LOctet.substr(12, 4), 4, 0, true);
					var ElfStr = HextoDec(LOctet.substr(16, 4), 4, 0, true);
					var ElfAgi = HextoDec(LOctet.substr(20, 4), 4, 0, true);	
					var ElfCon = HextoDec(LOctet.substr(24, 4), 4, 0, true);		
					var ElfInt = HextoDec(LOctet.substr(28, 4), 4, 0, true);					
					var ElfTp  = HextoDec(LOctet.substr(32, 4), 4, 0, true);					
					var ElfMet = HextoDec(LOctet.substr(36, 4), 4, 0, true);					
					var ElfWoo = HextoDec(LOctet.substr(40, 4), 4, 0, true);					
					var ElfWat = HextoDec(LOctet.substr(44, 4), 4, 0, true);
					var ElfFir = HextoDec(LOctet.substr(48, 4), 4, 0, true);	
					var ElfEar = HextoDec(LOctet.substr(52, 4), 4, 0, true);	
					var ElfRef = HextoDec(LOctet.substr(56, 4), 4, 0, true);	
					var ElfSta = HextoDec(LOctet.substr(60, 8), 8, 0, true);	
					var ElfTra = LOctet.substr(68, 8);	
					var ElfGea = HextoDec(LOctet.substr(76, 8), 8, 0, true);	
					var ElfSki = HextoDec(LOctet.substr((84+ElfGea*8), 8), 8, 0, true);	
					document.getElementById('Inp_E_Lv').value = ElfLv;
					ChangeElfLevel();
					document.getElementById('Inp_E_Exp').value = ElfXp;
					document.getElementById('Inp_E_Stamina').value = ElfSta;
					document.getElementById('Inp_E_LuckPoint').value = ElfPt-ElfLv+1;
					ChangeElfPointTalent();					
					document.getElementById('Inp_E_STR').value = ElfStr;
					document.getElementById('Inp_E_AGI').value = ElfAgi;
					document.getElementById('Inp_E_INT').value = ElfInt;
					document.getElementById('Inp_E_CON').value = ElfCon;
					document.getElementById('Inp_E_Metal').value = ElfMet;
					document.getElementById('Inp_E_Wood').value = ElfWoo;
					document.getElementById('Inp_E_Water').value = ElfWat;
					document.getElementById('Inp_E_Fire').value = ElfFir;
					document.getElementById('Inp_E_Earth').value = ElfEar;
					ChangeElfPointTalent();
					var SelO = document.getElementById('Inp_E_Trade');
					if (ElfTra == "c7a24c4f"){SelO.selectedIndex=1;}else{SelO.selectedIndex=0;}
					if (ElfGea>0){
						var ElfG = [];
						for (var i=1; i<=ElfGea; i++){
							ElfG = SearchElfGearName(HextoDec(LOctet.substr(76+i*8, 8), 8, 0, true)).split("#");
							
							document.getElementById('Inp_E_GearId'+i).value = ElfG[0];
							document.getElementById('Inp_E_GearName'+i).value = ElfG[1];
						}	
					}
					ESInd = 0;
					if (ElfSki>0){
						var esd;
						for (var i=1; i<=ElfSki; i++){
							esd = 
							ESInd++;
							ESkill[ESInd] = SearchElfSkillData(HextoDec(LOctet.substr(84+ElfGea*8+i*8, 4), 4, 0, true), HextoDec(LOctet.substr(84+ElfGea*8+i*8+4, 4), 4, 0, true));
						}	
					}	
					RefreshElfSkillList();	
					document.getElementById('Inp_E_RefLv').selectedIndex = ElfRef;
					document.getElementById('E_LSkills').innerHTML = ElfSki;

				}
			}
		}else if (n==405){	
			var LOctet = document.getElementById('Inp_O5_NewOctet').value;
			if (ishex(LOctet) !== false){
				if (LOctet.length > 15){
					var hType = 0;
					if (LOctet.length == 16){
						var oct1 = LOctet.substr(0, 8);
						var oct2 = LOctet.substr(8, 8);
						var fou1 = oct1.substr(0, 4);
						var fou2 = oct1.substr(4, 4);
						var fou3 = oct2.substr(0, 4);
						var fou4 = oct2.substr(4, 4);
						
						if ((HextoDec(fou1, 4, 0, true) == 0) || (HextoDec(fou1, 4, 0, true) == 1)){
							var tmp = HextoDec(fou2, 4, 0, true);
							if ((tmp > -1) && (tmp < 21)){
								var tmp = HextoDec(fou3, 4, 0, true);
								if ((tmp > -1) && (tmp < 21)){
									hType = 1;
									document.getElementById('RadHiero2').checked = true;
									document.getElementById('Inp_H_AType').selectedIndex = HextoDec(fou1, 4, 0, true);
									document.getElementById('Inp_H_DMinGrd').value = HextoDec(fou2, 4, 0, true);
									document.getElementById('Inp_H_DMaxGrd').value = HextoDec(fou3, 4, 0, true);
									document.getElementById('Inp_H_Damage').value = HextoDec(fou4, 4, 0, true);
									document.getElementById('Inp_H_AStack').value = document.getElementById('Sel_Count').value;
								}
							}
						}
						if (hType == 0){
							document.getElementById('RadHiero1').checked = true;
							document.getElementById('Inp_H_Amount').value = HextoDec(oct1, 8, 0, true);
							document.getElementById('Inp_H_Act').value = parseInt(parseFloat('0x'+ReverseNumber(oct2))*100, 10);
						}
					}else{
						hType = 2;
						document.getElementById('RadHiero3').checked = true;
						document.getElementById('Inp_H_DType').selectedIndex = HextoDec(LOctet.substr(0, 8), 8, 0, true);
						document.getElementById('Inp_H_DMaxLv').value = HextoDec(LOctet.substr(8, 8), 8, 0, true);
						document.getElementById('Inp_H_Defence').value = parseInt(parseFloat('0x'+ReverseNumber(LOctet.substr(16, 8)))*100, 10);
						document.getElementById('Inp_H_DMinLv').value = HextoDec(LOctet.substr(24, 8), 8, 0, true);
						document.getElementById('Inp_H_DStack').value = document.getElementById('Sel_Count').value;
					}
				}
			}			
		}else if (n==406){	
			var LOctet = document.getElementById('Inp_O6_NewOctet').value;
			if (ishex(LOctet) !== false){
				if (LOctet.length > 103){
					var LvReq = HextoDec(LOctet.substr(0, 4), 4, 0, true);
					var ReqCls = HextoDec(LOctet.substr(4, 4), 4, 0, true);
					document.getElementById("ClassMask").value=ReqCls;
					var StrReq = HextoDec(LOctet.substr(8, 4), 4, 0, true);
					var ConReq = HextoDec(LOctet.substr(12, 4), 4, 0, true);
					var AgiReq = HextoDec(LOctet.substr(16, 4), 4, 0, true);
					var IntReq = HextoDec(LOctet.substr(20, 4), 4, 0, true);
					var Dur1 = parseInt(HextoDec(LOctet.substr(24, 8), 8, 0, true)/100, 10);
					var Dur2 = parseInt(HextoDec(LOctet.substr(32, 8), 8, 0, true)/100, 10);
					var Unknown1 = HextoDec(LOctet.substr(40, 8), 8, 0, true);
					var AmmoId = HextoDec(LOctet.substr(48, 8), 8, 0, true);
					var Dmg = HextoDec(LOctet.substr(56, 8), 8, 0, true);
					var Unknown2 = HextoDec(LOctet.substr(64, 8), 8, 0, true);
					var MinWGr = HextoDec(LOctet.substr(72, 8), 8, 0, true);
					var MaxWGr = HextoDec(LOctet.substr(80, 8), 8, 0, true);
					var Unknown3 = HextoDec(LOctet.substr(88, 8), 8, 0, true); //probabil socket what allway 0
					var AddonC = HextoDec(LOctet.substr(96, 8), 8, 0, true); 
					//-------------------
					document.getElementById('Inp_M_LvReq').value = LvReq;
					document.getElementById('Inp_M_Class').value = ReqCls;
					document.getElementById('Inp_M_Grade1').value = MinWGr;
					document.getElementById('Inp_M_Grade2').value = MaxWGr;
					document.getElementById('Inp_M_STR').value = StrReq;
					document.getElementById('Inp_M_AGI').value = AgiReq;
					document.getElementById('Inp_M_INT').value = IntReq;
					document.getElementById('Inp_M_CON').value = ConReq;
					document.getElementById('Inp_M_Dur1').value = Dur1;
					document.getElementById('Inp_M_Dur2').value = Dur2;
					document.getElementById('Inp_M_Damage').value = Dmg;
					document.getElementById('Inp_M_AStack').value = document.getElementById('Sel_Count').value;
					var SelO = document.getElementById('Inp_M_Ammo');
					SelO.selectedIndex = 0;
					for (var i=0; i<SelO.length; i++){
						if (SelO.options[i].value == AmmoId){
							SelO.selectedIndex = i;
						}
					}
					AddInd = 0;
					if (AddonC>0){
						var AHex;
						var shift = 0;
						var aType;
						var bAddon;
						var vAddon;
						for (var i=0; i<AddonC; i++){
							AHex = ReverseNumber(LOctet.substr(104+i*16+shift, 8));
							AHex = AHex.replace(/^0+/, '').trim();
							//2 normal addon, 4 special or refine, a is socket addon
							if (AHex.length % 2 == 0){
								aType = AHex.substr(0,1);
								if (aType=="4"){
									//special weapon addons
									AddonId=HextoDec(AHex, 8, aType, false);
									if (isRune(AddonId)!==false){
										AddInd++;
										Addons[AddInd] = SearchRuneNameValue(46, AddonId, HextoDec(LOctet.substr(104+i*16+shift+8, 8), 8, 0, true) +" "+ HextoDec(LOctet.substr(104+i*16+shift+16, 8), 8, 0, true));
									}else{
										//special weapon addons
										AddInd++;
										Addons[AddInd] = SearchAddonNameValue(46, AddonId, HextoDec(LOctet.substr(104+i*16+shift+8, 8), 8, 0, true) +" "+ HextoDec(LOctet.substr(104+i*16+shift+16, 8), 8, 0, true));
									}											
									shift = shift + 8;
								}else{
									//normal addon
									AddInd++;
									bAddon = ReverseNumber(AHex, 4);
									vAddon = HextoDec(LOctet.substr(104+i*16+shift+8, 8), 8, 0, true);
									if (bAddon == "2c21"){
										vAddon = parseFloat('0x'+ReverseNumber(LOctet.substr(104+i*16+shift+8, 8))).toFixed(2);
									}else if (bAddon == "7c22"){
										vAddon = parseFloat('0x'+ReverseNumber(LOctet.substr(104+i*16+shift+8, 8))).toFixed(2);
									}
									Addons[AddInd] = SearchAddonNameValue(46, HextoDec(AHex, 8, aType, false), vAddon);
								}
							}	
						}	
					}
					RefreshAddonList();
				}
			}
		}else if (n==407){	
			var LOctet = document.getElementById('Inp_O7_NewOctet').value;
			if (ishex(LOctet) !== false){
				if (LOctet.length == 24){
					var LvReq = HextoDec(LOctet.substr(0, 8), 8, 0, true);
					var EffectId = HextoDec(LOctet.substr(8, 8), 8, 0, true);
					var EffectLv = HextoDec(LOctet.substr(16, 8), 8, 0, true);
					document.getElementById('Inp_X_LvReq').value = LvReq;
					document.getElementById('Inp_X_Id').value = EffectId;
					document.getElementById('Inp_X_Lv').value = EffectLv;
				}
			}	
			document.getElementById('Inp_X_AStack').value = document.getElementById('Sel_Count').value;		
		}else if (n==408){	
			var LOctet = document.getElementById('Inp_O8_NewOctet').value;
			if (ishex(LOctet) !== false){
				if (LOctet.length == 8){
					var QuestId = HextoDec(LOctet.substr(0, 8), 8, 0, true);
					document.getElementById('Inp_T_Quest').value = QuestId;
				}
			}	
			document.getElementById('Inp_T_Amount').value = document.getElementById('Sel_Count').value;	
		}else if (n==409){	
			var LOctet = document.getElementById('Inp_O9_NewOctet').value;
			if (ishex(LOctet) !== false){
				if (LOctet.length == 16){
					var GrassType = HextoDec(LOctet.substr(0, 8), 8, 0, true);
					var Loyality = HextoDec(LOctet.substr(8, 8), 8, 0, true);
					document.getElementById('Inp_G_Loyal').value = Loyality;
					var SelO = document.getElementById('Inp_G_Type');
					SelO.selectedIndex = 0;
					for (var i=0; i<SelO.length; i++){
						if (SelO.options[i].value == GrassType){
							SelO.selectedIndex = i;
						}
					}
				}
			}	
			document.getElementById('Inp_G_Amount').value = document.getElementById('Sel_Count').value;	
		}else if (n==410){
			var LOctet = document.getElementById('Inp_O10_NewOctet').value;
			if (ishex(LOctet) !== false){
				if (LOctet.length == 48){
					//you ca make a loop till AddonC1/2 if you want add multiple addon with a stone
					var AddonC1 = HextoDec(LOctet.substr(0, 8), 8, 0, true);
					var AddonId1 = HextoDec(LOctet.substr(8, 8), 8, 2, true);
					var AddonVal1 = HextoDec(LOctet.substr(16, 8), 8, 0, true);
					var AddonC2 = HextoDec(LOctet.substr(24, 8), 8, 0, true);
					var AddonId2 = HextoDec(LOctet.substr(32, 8), 8, 2, true);
					var AddonVal2 = HextoDec(LOctet.substr(40, 8), 8, 0, true);
					document.getElementById('Inp_S_WeaponId').value = AddonId1;
					document.getElementById('Inp_S_WeaponVal').value = AddonVal1;
					document.getElementById('Inp_S_ArmorId').value = AddonId2;
					document.getElementById('Inp_S_ArmorVal').value = AddonVal2;
				}
			}				
			document.getElementById('Inp_S_Amount').value = document.getElementById('Sel_Count').value;	
		}else if (n==411){
			var LOctet = document.getElementById('Inp_O11_NewOctet').value;
			if (ishex(LOctet) !== false){
				if (LOctet.length == 24){
					var prest = HextoDec(LOctet.substr(0, 8), 8, 0, true);
					var order = HextoDec(LOctet.substr(8, 8), 8, 0, true);
					var loss = HextoDec(LOctet.substr(16, 8), 8, 0, true);
					document.getElementById('Inp_MO_Prest').value = prest;
					document.getElementById('Inp_MO_Clss').selectedIndex = parseInt(order,10)-1004;;
					document.getElementById('Inp_MO_PLose').value = loss;
				}
			}	
		}else if (n==412){		
			var LOctet = document.getElementById('Inp_O12_NewOctet').value;
			if (ishex(LOctet) !== false){
				if (LOctet.length > 41){
					var SCBSA=[];
					var SCXp = parseInt(HextoDec(LOctet.substr(0, 8), 8, 0, true), 10);
					var SCLv = parseInt(HextoDec(LOctet.substr(8, 2), 2, 0, true), 10)+1;
					var SCCmb = parseInt(HextoDec(LOctet.substr(10, 4), 4, 0, true), 10);
					for (var i=1; i<=5; i++){
						SCBSA[i] = (parseInt(HextoDec(LOctet.substr(10+i*4, 4), 4, 0, true), 10)/100).toFixed(2);
					}
					document.getElementById('Inp_SC_CurLv').value = SCLv;
					ChangeStarChartLevel();
					document.getElementById('Inp_SC_CurExp').value = SCXp;
					ChangeStarChartExp();
					for (var i=1; i<=5; i++){
						document.getElementById('Inp_SC_BStarApt'+i).value = SCBSA[i];
					}			
					SetSCAptitude();	
				
					var SCRate=[];
					var SCAddn=[];
					var SCInd = parseInt(HextoDec(LOctet.substr(34, 8), 8, 0, true), 10);
					for (var i=0; i<SCInd; i++){
						SCAddn[i+1]=parseInt(HextoDec(LOctet.substr(42+i*16, 8), 8, 2, true), 10);
						SCRate[i+1]=parseFloat('0x'+ReverseNumber(LOctet.substr(50+i*16, 8))).toFixed(2);
					}
					//handle combo
					var Cmb=SCCmb;
					var SCOS=[];
					var selO;
					var pw;
					for (var i=1; i<11; i++){
						pw=Math.pow(2, 10-i);
						if (Cmb>=pw){
							Cmb=Cmb-pw;
							SCOS[11-i]=true;
						}else{
							SCOS[11-i]=false;
						}
					}
					if (SCInd > 0){
						var SCAC=1;
						var SCST="F";
						var SCNR=0;
						var tmp;
						for (var i=1; i<11; i++){
							SCST="F";
							if ((i % 2)==0){
								SCST="B";
							}
							SCNR=Math.floor((i+1)/2);	
							if (SCOS[i] != null){
								if (SCOS[i] !== false){
									if (SCAC <= SCInd){
										selO = document.getElementById('Inp_SC_'+SCST+'StarAddon'+SCNR);
										for (var x=1; x<selO.length; x++){
											tmp=selO.options[x].value.split("#");
											if (tmp[0]==SCAddn[SCAC]){
												selO.selectedIndex=x;
												break;
											}
										}
										document.getElementById('Inp_SC_'+SCST+'StarRate'+SCNR).value=SCRate[SCAC];
									}
									SCAC++;
								}
							}
						}
						
						for (var i=1; i<11; i++){
							if (SCOS[i] != null){
								SCST="F";
								if ((i % 2)==0){
									SCST="B";
								}
								SCNR=Math.floor((i+1)/2);	
								if (SCOS[i] !== false){
									if (SCST=="B"){
										
									}
								}									
							}
							
							GetSCCharRate(SCNR);
							if (SCOS[i] != null){
								if (SCOS[i] !== false){
									document.getElementById('Inp_SC_'+SCST+'StarRate'+SCNR).style.boxShadow="0px 0px 5px blue";
								}else{
									document.getElementById('Inp_SC_'+SCST+'StarRate'+SCNR).style.boxShadow="none";
								}
							}else{
								document.getElementById('Inp_SC_'+SCST+'StarRate'+SCNR).style.boxShadow="none";
							}
						}
												
					}
				}
				
			}			
		}
	}
}

function OctSelectedItem(id, listType){
	var nextStp = false;
	var iCol;
	var	cINM;
	if ((listType == 1)&&(SIData[id] !== null)){
		var myArr = SIData[id].split("#");
		nextStp = true;
	}else if((listType == 2)&&(WSIData[id] !== null)){
		var myArr = WSIData[id].split("#");
		nextStp = true;
	}else if(listType==3){
		var txt = document.getElementById("WSLData").value;
		if (txt.indexOf("#") != -1){
			var myArr = txt.split("#");
			nextStp = true;		
			listType=2;
		}
	}
	if (nextStp !== false){
		var MType = myArr[4][0];
		var SType = parseInt(myArr[4].substr(1), 10);
		var selIT = document.getElementById("Sel_Item_Type");
		var mcat = 0;
		for (var i = 0; i < selIT.length; i++) {
			if (selIT.options[i].value == MType){
				selIT.selectedIndex = i;
			}
		}
		mcat = CType2CTypeInt(MType);
		ChangeItemType();
		var selIT = document.getElementById("Sel_"+MType+"Sub_Type");
		for (var i = 0; i < selIT.length; i++) {
			if (selIT.options[i].value == SType){
				selIT.selectedIndex = i;
			}
		}
		if (MType == "W"){
			ChangeWeaponType();
		}else if (MType == "A"){
			ChangeArmorType();
		}else if (MType == "J"){		
			ChangeJewelType();
		}else if (MType == "O"){		
			ChangeOtherType();
		}else if (MType == "U"){		
			ChangeUtilType();
		}else if (MType == "M"){		
			ChangeMatType();
		}else if (MType == "F"){		
			ChangeFashType();
		}else if (MType == "C"){		
			ChangeCardType();			
		}		
		var found=false;
		if (mcat > 0){
			var myArr2 = [];
			var fp = 0;
			selIT = document.getElementById("SItmC"+mcat+"S"+SType);
			for (var i = 0; ((i < selIT.length)&&(found !== true)); i++) {
				myArr2 = selIT.options[i].value.split("#");
				if (myArr2[1] == myArr[7]){
					fp = i;
					found = true;
				}
			}
			if (found !== true){
				selIT.selectedIndex = 0;
				getPItemData(selIT);
			}else{
				selIT.selectedIndex = fp;
				getPItemData(selIT);				
			}
			if (listType == 1){
				document.getElementById("Inp_RoleId").value=myArr[0];
				document.getElementById("Inp_Money").value=myArr[1];
				document.getElementById("Inp_MailTitle").value=myArr[2];
				document.getElementById("Inp_MailBody").value=myArr[3];
			}else if (listType == 2){
				document.getElementById("Inp_Price1").value=myArr[0];
				document.getElementById("Inp_Price2").value=myArr[1];
				document.getElementById("Inp_Title").value=myArr[2];
				document.getElementById("Inp_Description").value=myArr[3];
				document.getElementById("Inp_Color").value=myArr[17];
				document.getElementById("Inp_Grade2").value=myArr[18];
			}
			document.getElementById("Sel_Item_Id").value=myArr[7];
			document.getElementById("Sel_Mask2").value=myArr[8];
			document.getElementById("Sel_ProcType2").value=myArr[9];
			document.getElementById("Sel_Count").value=myArr[10];
			document.getElementById("Sel_MaxCount").value=myArr[11];
			document.getElementById("Sel_Guid1").value=myArr[12];
			document.getElementById("Sel_Guid2").value=myArr[13];
			document.getElementById("Expire_Act").checked = true;
			if (listType == 2){
				if (myArr[14] == 0){
					document.getElementById("Expire_Act").checked = false;
					document.getElementById("Cur_PlusNr").value = 0;
				}else{
					document.getElementById("Expire_Stop").checked = false;
					document.getElementById("Expire_Act").checked = true;
					var selO = document.getElementById("Cur_PlusType");
					var selV = 60;
					var res;
					selO.selectedIndex = 0;
					for (var i = 0; i < selO.length; i++) {
						selV = parseInt(myArr[14],10) % (parseInt(selO.options[i].value,10));
						if (selV == 0){
							selO.selectedIndex = i;
							document.getElementById("Cur_PlusNr").value = parseInt(myArr[14],10) / (parseInt(selO.options[i].value,10));
							res = (Date.now() / 1000 | 0) + TCS + parseInt(myArr[14],10);
						}
					}
					myArr[14] = res;
				}
			}else if(listType == 1){
				document.getElementById("Expire_Stop").checked = true;

			}
			document.getElementById("Sel_Expire").value=myArr[14];
			document.getElementById("Inp_Octet").value=myArr[15];
	
			if (myArr[15] != ""){
				if (mcat < 4){
					document.getElementById("Inp_"+MType+"_NewOctet").value=myArr[15];
					LoadOctet(mcat);
				}else if (mcat == 4){
					document.getElementById("Inp_O"+SType+"_NewOctet").value=myArr[15];
					LoadOctet((400+parseInt(SType,10)));		
				}else if (mcat == 7){
					document.getElementById("Inp_F_NewOctet").value=myArr[15];
					LoadOctet(mcat);
				}else if (mcat == 8){
					document.getElementById("Inp_C_NewOctet").value=myArr[15];
					LoadOctet(mcat);					
				}
			}
			var itmName;
			if (mcat < 4){
				itmName=document.getElementById("Gear"+mcat+"_Name");
			}else{
				if (mcat == 4){
					if (SType == 1){
						itmName=document.getElementById("Flyer_Name");
					}else if (SType == 2){
						itmName=document.getElementById("Pet_Name");
					}else if (SType == 3){
						itmName=document.getElementById("Elf_Name");
					}else if (SType == 4){
						itmName=document.getElementById("BBox_Name");
					}else if (SType == 5){
						itmName=document.getElementById("Hiero_Name");
					}else if (SType == 6){
						itmName=document.getElementById("Ammo_Name");
					}else if (SType == 7){
						itmName=document.getElementById("Potion_Name");
					}else if (SType == 8){
						itmName=document.getElementById("TaskDice_Name");
					}else if (SType == 9){
						itmName=document.getElementById("Grass_Name");
					}else if (SType == 10){
						itmName=document.getElementById("Stone_Name");
					}else if (SType == 11){
						itmName=document.getElementById("Order_Name");
					}else if (SType == 12){
						itmName=document.getElementById("StarChart_Name");
					}
				}else if ((mcat == 5)||(mcat == 6)){
					itmName=document.getElementById("Misc_Name");
					document.getElementById('Inp_Ms_Octet').value = myArr[15];	
					document.getElementById('Inp_Ms_Amount').value = myArr[10];
					document.getElementById('Inp_Mss_Amount').value = myArr[11];
				}else if (mcat == 7){
					itmName=document.getElementById("Fash_Name");
				}else if (mcat == 8){
					itmName=document.getElementById("Card_Name");
				}
			}
			iCol = parseInt(document.getElementById("Inp_Color").value, 10);
			
			if (listType == 2){
				myArr[5] = myArr[2];
			}			
			cINM="<font color='"+itmCol[iCol]+"'>"+myArr[5]+"</font>";
			itmName.innerHTML = cINM;
			if (myArr[10] == 1){
				document.getElementById('Inp_Title').value=myArr[5];
			}else{
				document.getElementById('Inp_Title').value=myArr[5]+" x"+myArr[10];
			}
			if (listType == 1){
				document.getElementById("SelectWindow").style.display="none";
				document.getElementById("SelectEditWindow").style.display="none";
			}else if (listType == 2){
				document.getElementById("ShopItemWindow").style.display="none";
				document.getElementById("ShopItemEditWindow").style.display="none";					
			}
		}
	}
	ArrangeProcCheckboxs();
}


function GetRoleId(){
	var RoleName = document.getElementById('Inp_RoleName').value.trim();
	var obj={"rolename":RoleName};
	if (RoleName != ""){
		ItemDataModWithAjax(1,obj);
	}
}

function CheckAddonType(s){
	var sData = s.options[s.selectedIndex].value.split("#");
	if (isRune(sData[0]) !== true){
		document.getElementById('RuneTimeDiv').style.display = "none";
	}else{
		document.getElementById('RuneTimeDiv').style.display = "block";
	}
}

function ChangeItemDate(){
	var SelO = document.getElementById('Inp_ShopType');
	var Opt = SelO.options[SelO.selectedIndex].value;
	if (Opt == 3){
		document.getElementById('Inp_ShopPromo').style.display="inline";
	}else{
		document.getElementById('Inp_ShopPromo').style.display="none";
	}
	if (Opt == 0){
		document.getElementById('Inp_AHide').style.display="none";
		document.getElementById('Inp_Interval').style.display="none";
	}else{
		document.getElementById('Inp_AHide').style.display="inline";
		document.getElementById('Inp_Interval').style.display="block";
	}
	if (Opt == 2){
		document.getElementById('Inp_AHide').style.display="inline";
		document.getElementById('Inp_Interval').style.display="none";
		document.getElementById('Inp_IntHour').style.display="block";
	}else{
		document.getElementById('Inp_IntHour').style.display="none";
	}
}

function EditItemDate(){
	var SelO = document.getElementById('WSE_SType');
	var Opt = SelO.options[SelO.selectedIndex].value;
	if (Opt == 3){
		document.getElementById('WSE_ShopPromo').style.display="block";
	}else{
		document.getElementById('WSE_ShopPromo').style.display="none";
	}
	if (Opt == 0){
		document.getElementById('WSE_AHide').style.display="none";
		document.getElementById('WSE_Interval').style.display="none";
	}else{
		document.getElementById('WSE_AHide').style.display="inline";
		document.getElementById('WSE_Interval').style.display="block";
	}
	if (Opt == 2){
		document.getElementById('WSE_AHide').style.display="inline";
		document.getElementById('WSE_Interval').style.display="none";
		document.getElementById('WSE_IntHour').style.display="block";
	}else{
		document.getElementById('WSE_IntHour').style.display="none";
	}
}

function HourValidation(ihour){
	if (ihour.length==5){
		if (ihour.substr(2, 1)==":"){
			var nr = ihour.split(":");
			var regEx = /^\d{2}$/;
			if ((nr[0].match(regEx) != null)&&(nr[1].match(regEx))){
				return true;
			}
		}		
	}
	return false;
}

function HourValidationExt(ihour1, ihour2){
	var resp = document.getElementById('MailResponse');
	if ((ihour1.length==5)&&(ihour2.length==5)){
		if ((ihour1.substr(2, 1)==":")&&(ihour2.substr(2, 1)==":")){
			var nr1 = ihour1.split(":");
			var nr2 = ihour2.split(":");
			var regEx = /^\d{2}$/;
			if ((nr1[0].match(regEx) != null)&&(nr1[1].match(regEx))&&(nr2[0].match(regEx) != null)&&(nr2[1].match(regEx))){
				var diff = ((parseInt(nr1[0]))*60+parseInt(nr1[1]))-((parseInt(nr2[0]))*60+parseInt(nr2[1]));
				if (diff < 0){
					return true;
				}else{
					resp.innerHTML = "<font color='red'><b><i> End time must be higher... </i></b></font>";
				}
			}else{
				resp.innerHTML = "<font color='red'><b><i> Invalid hour(s)... </i></b></font>";
			}
		}else{
			resp.innerHTML = "<font color='red'><b><i> Invalid hour(s) format... </i></b></font>";
		}		
	}else{
		resp.innerHTML = "<font color='red'><b><i> Invalid hour(s) format... </i></b></font>";
	}
	return false;
}

function DateValidationExt(date1, date2){
	var resp = document.getElementById('MailResponse');
	var regEx1 = /^\d{2}$/;
	var regEx2 = /^\d{4}$/;
	if ((date1.length==19)&&(date2.length==19)){
		if ((date1.substr(10, 1)==" ")&&(date2.substr(10, 1)==" ")){
			var time1 = date1.split(" ");
			var time2 = date2.split(" ");
			var ihour1 = time1[1];
			var ihour2 = time2[1];
			if ((time1[0].substr(4, 1)=="-")&&(time1[0].substr(7, 1)=="-")&&(time2[0].substr(4, 1)=="-")&&(time2[0].substr(7, 1)=="-")){
				var days1 = (time1[0]).split("-");
				var days2 = (time2[0]).split("-");
				if ((days1[0].match(regEx2) != null)&&(days1[1].match(regEx1))&&(days1[2].match(regEx1))&&(days2[0].match(regEx2) != null)&&(days2[1].match(regEx1))&&(days2[2].match(regEx1))){
					if ((time1[1].length==8)&&(time2[1].length==8)){
						if ((ihour1.substr(2, 1)==":")&&(ihour1.substr(5, 1)==":")&&(ihour2.substr(2, 1)==":")&&(ihour2.substr(5, 1)==":")){
							var nr1 = ihour1.split(":");
							var nr2 = ihour2.split(":");
							if ((nr1[0].match(regEx1) != null)&&(nr1[1].match(regEx1))&&(nr1[2].match(regEx1))&&(nr2[0].match(regEx1) != null)&&(nr2[1].match(regEx1))&&(nr2[2].match(regEx1))){
								var jsDate1 = parseInt(new Date(Date.parse(date1)).getTime(), 10);
								var jsDate2 = parseInt(new Date(Date.parse(date2)).getTime(), 10);
								if ((jsDate1-jsDate2) < 0){
									return true;
								}else{
									resp.innerHTML = "<font color='red'><b><i> End time must be higher... </i></b></font>";
								}
								
							}else{
								resp.innerHTML = "<font color='red'><b><i> Invalid hour(s)... </i></b></font>";
							}
						}else{
							resp.innerHTML = "<font color='red'><b><i> Invalid hour(s) format... </i></b></font>";
						}		
					}else{
						resp.innerHTML = "<font color='red'><b><i> Invalid hour(s) format... </i></b></font>";
					}
				}else{
					resp.innerHTML = "<font color='red'><b><i> Invalid date(s) format... </i></b></font>";
				}
			}else{
				resp.innerHTML = "<font color='red'><b><i> Invalid date(s) format... </i></b></font>";
			}
		}else{
			resp.innerHTML = "<font color='red'><b><i> Invalid date(s) format... </i></b></font>";
		}
	}else{
		resp.innerHTML = "<font color='red'><b><i> Invalid date(s) format... </i></b></font>";
	}
	return false;
}

function GetRuneData(Octet, mask){
	var NameLen;
	var SocketC;
	var AddonC=0;
	var n;
	var RuneIDCol = [];
	var c=0;
	var x=0;
	var d = new Date();
	var rdate = parseInt((new Date(Date.now()))/1000);
	if (mask == 1){
		if (Octet.length > 151){
			NameLen = parseInt(HextoDec(Octet.substr(46, 2), 2, 0, true)/2, 10);
			SocketC = HextoDec(Octet.substr(136+NameLen*4, 8), 8, 0, true);
			n = NameLen*4+SocketC*8+144;
			AddonC=HextoDec(Octet.substr(n, 8), 8, 0, true);
			if (AddonC >0){
				x=n+8;
			}
		}			
	}else if ((mask == 16)||(mask == 64)||(mask == 128)||(mask == 256)||(mask == 2)||(mask == 8)||(mask == 1077936128)){
		if (Octet.length > 135){
			NameLen = parseInt(HextoDec(Octet.substr(46, 2), 2, 0, true)/2, 10);
			SocketC = HextoDec(Octet.substr(120+NameLen*4, 8), 8, 0, true);
			n = NameLen*4+SocketC*8+128;
			AddonC=HextoDec(Octet.substr(n, 8), 8, 0, true);
			if (AddonC >0){
				x=n+8;
			}
		}		
	}else if ((mask == 4)||(mask == 32)||(mask == 1536)){
		if (Octet.length > 135){
			NameLen = parseInt(HextoDec(Octet.substr(46, 2), 2, 0, true)/2, 10);
			SocketC = HextoDec(Octet.substr(120+NameLen*4, 8), 8, 0, true);
			n = NameLen*4+SocketC*8+128;
			AddonC=HextoDec(Octet.substr(n, 8), 8, 0, true);
			if (AddonC >0){
				x=n+8;
			}
		}	
	}else if (mask == 2048){
		AddonC=HextoDec(Octet.substr(96, 8), 8, 0, true);
		if (AddonC >0){
			x=104;
		}
	}	
	
	if (x>0){
		var RuneTime=0;
		var before=Octet.substr(0,x);
		var after=Octet.substr(x);
		var nafter=after;
		//search after run addon ids and collect them
		if (AddonC > 0){
			var AHex=0;
			var AHex1=0;
			var aType=0;;
			var shift=0;
			var AddonId=0;
			for (var x=0; x<AddonC; x++){
				AHex = nafter.substr(x*16+shift, 8);
				AHex1 = ReverseNumber(AHex).replace(/^0+/, '').trim();
				if (AHex1.length % 2 == 0){
					aType = AHex1.substr(0,1);
					AddonId=HextoDec(AHex1, 8, aType, false);
					if (aType=="4"){
						AddonId=HextoDec(AHex1, 8, aType, false);
						if (isRune(AddonId)!==false){
							n=parseInt(HextoDec(nafter.substr(x*16+shift+16, 8),8,0,true),10);
							RuneTime = DectoRevHex(n*60+rdate,8,0);
							nafter=nafter.substr(0,x*16+shift+16)+RuneTime+nafter.substr(x*16+shift+24);
						}
						shift = shift + 8;
					}
				}	
			}		
			return (before+nafter);
		}
	}
	return Octet;
}

function SetSCAptitude(){
	var tApt;
	var BSApt=[];
	for (var x=1; x<=5; x++){
		tApt = document.getElementById('Inp_SC_BStarApt'+x).value;
		BSApt[x] = 0;
		if (tApt != ""){
			if (isNumber(tApt)){
				if (Number(tApt)>655.35){
					tApt=655.35;
					document.getElementById('Inp_SC_BStarApt'+x).value=655.35;
				}
				BSApt[x] = Number(tApt);
			}
		}
		
	}	
	BSApt[0]=BSApt[5];
	
	for (var x=1; x<=5; x++){
		document.getElementById('Inp_SC_FStarApt'+x).innerHTML=(Number(BSApt[x-1])+Number(BSApt[x])).toFixed(2);
	}
	RefreshStarChartAddons();
}

function HighlightRows(rId, st){
	var RArr = [];
	var RT = "B";
	var RRT = "F";
	var shft = 1;
	if (st==2){RT="F";RRT="B";shft=0;}
	var cTab1 = "SCTR_"+RT+"S";
	var cTab2 = "SCTR_"+RRT+"S";
	RArr[0]=5;
	RArr[1]=1;
	RArr[2]=2;
	RArr[3]=3;
	RArr[4]=4;
	RArr[5]=5;
	RArr[6]=1;
	for (var x=1; x<=5; x++){
		document.getElementById(cTab2+x).style.backgroundColor='#fff';
		if (x != rId){
			document.getElementById(cTab1+x).style.backgroundColor='#fff';
		}else{
			document.getElementById(cTab1+x).style.backgroundColor='#ffc';
		}
	}
	document.getElementById(cTab2+RArr[rId+shft]).style.backgroundColor='#ccf';
	document.getElementById(cTab2+RArr[rId-1+shft]).style.backgroundColor='#ccf';

}

function ChangeSCAddons(SClass){
	var d=document.getElementById('Inp_SC_BStarAddon1');
	var myArr;
	var vis;
	var bSel;
	var fSel;
	var sInd=-1;
	var max=d.length;
	for (var i=1; i<max; i++){
		myArr=d.options[i].value.split("#");
		vis = false;
		if (StarChartClass[SClass][2].indexOf(parseInt(myArr[0],10)) != -1){
			vis = true;
		}
		for (var x=1; x<=5; x++){
			bSel=document.getElementById('Inp_SC_BStarAddon'+x).options[i];
			fSel=document.getElementById('Inp_SC_FStarAddon'+x).options[i];
			if (vis !== false){
				bSel.style.display="block";
				fSel.style.display="block";
				bSel.disabled=false;
				bSel.setAttribute("hidden", false);
				fSel.setAttribute("hidden", false);
				if (sInd == -1){
					bSel.selectedIndex=i;
					fSel.selectedIndex=i;	
					sInd=i;
				}
			}else{
				bSel.style.display="none";
				fSel.style.display="none";
				bSel.disabled=true;
				bSel.setAttribute("hidden", true);
				fSel.setAttribute("hidden", true);
			}
			document.getElementById('Inp_SC_FStarAddon'+x).disabled=true;
		}
	}
	RefreshStarChartAddons();
}

function ChangeStarChartLevel(){
	var cLv=document.getElementById('Inp_SC_CurLv');
	var aLv=parseInt(cLv.value,10)||0;
	if (aLv < 1){
		aLv=1;
		cLv.value=aLv;
	}else if(aLv > 50){
		aLv=50;
		cLv.value=aLv;		
	}
	document.getElementById('Inp_SC_CurExp').value="0";
	document.getElementById('Inp_SC_MaxExp').innerHTML="<b>"+StarChartLv[aLv-1][0]+ "</b> <i>("+StarChartLv[aLv-1][1]+")</i>";
	RefreshStarChartAddons();
}

function ChangeStarChartExp(){
	var cXP=document.getElementById('Inp_SC_CurExp');
	var aXP=parseInt(cXP.value,10)||0;
	var cLv=document.getElementById('Inp_SC_CurLv');
	var aLv=parseInt(cLv.value,10)||0;
	if (aXP < 0){
		aXP=0;
		cXP.value=aXP;
	}else if(aXP > StarChartLv[aLv-1][0]){
		aXP=StarChartLv[aLv-1][0];
		cXP.value=aXP;		
	}
	document.getElementById('Inp_SC_MaxExp').innerHTML="<b>"+StarChartLv[aLv-1][0]+ "</b> <i>("+(StarChartLv[aLv-1][1]+aXP)+")</i>";
}

function RefreshStarChartAddons(){
	var cLv=document.getElementById('Inp_SC_CurLv');
	var aLv=parseInt(cLv.value,10)||0;
	var base=25;
	var txt="";
	var val;
	var tApt;
	var tRat;
	var bStarA=[];
	var BSApt=[];
	var BSRate=[];
	var fStarA=[];	
	var FSApt=[];
	var FSRate=[];
	var bSel;
	var	fSel;
	var tmp;

	for (var x=1; x<=5; x++){
		bSel=document.getElementById('Inp_SC_BStarAddon'+x);
		tmp=bSel.options[bSel.selectedIndex].value;
		bStarA[x]="";
		BSApt[x]=0;
		BSRate[x]=0;		
		if (tmp != "0"){
			if (tmp.indexOf("#") != -1){
				tApt = document.getElementById('Inp_SC_BStarApt'+x).value;
				tRat = document.getElementById('Inp_SC_BStarRate'+x).value;
				if ((isNumber(tApt))&&(isNumber(tRat))){
					BSApt[x] = Number(tApt);
					BSRate[x]= Number(tRat);
					bStarA[x]=tmp.split("#");
					val = Math.floor(BSRate[x]*(25+BSApt[x]*aLv));
					txt=txt+"<font color='blue'>Birthstar:</font> "+GetAddonString(bStarA[x][2], val)+"<br>";
				}
			}
		}
	}	
	BSApt[0]=BSApt[5];
	
	for (var x=1; x<=5; x++){
		fSel=document.getElementById('Inp_SC_FStarAddon'+x);
		tmp=fSel.options[fSel.selectedIndex].value;
		fStarA[x]="";
		FSApt[x]=0;
		FSRate[x]=0;		
		if (tmp != "0"){
			if (tmp.indexOf("#") != -1){
				tRat = document.getElementById('Inp_SC_FStarRate'+x).value;
				if (isNumber(tRat)){
					FSApt[x] = (Number(BSApt[x-1])+Number(BSApt[x])).toFixed(2);
					FSRate[x]= Number(tRat);
					fStarA[x]=tmp.split("#");
					val = Math.floor(FSRate[x]*(25+FSApt[x]*aLv));
					txt=txt+"<font color='red'>Fatestar:</font> "+GetAddonString(fStarA[x][2], val)+"<br>";
				}
			}
		}		
	}	
	
	document.getElementById('SCAddonList').innerHTML=txt;
}

function GetSCCharRate(SId){
	var selO = document.getElementById('Inp_SC_Clss');
	var cChar = parseInt(selO.options[selO.selectedIndex].value, 10)-1;
	var sTyp = "B";
	if (SId>5){
		SId=SId-5;
		sTyp = "F";
	}
	selO = document.getElementById('Inp_SC_'+sTyp+'StarAddon'+SId);
	var sData=selO.options[selO.selectedIndex].value.split("#");
	var sAddon=parseInt(sData[0], 10);
	var sRat = document.getElementById('Inp_SC_'+sTyp+'StarRate'+SId);
	if (sAddon > 0){
		sRat.style.boxShadow = "0px 0px 5px blue";
	}else{
		sRat.style.boxShadow = "none";
		document.getElementById('Inp_SC_'+sTyp+'StarRate'+SId).value="0";
	}
	
	var BSAdd=[];
	for (var x=1; x<=5; x++){
		selO = document.getElementById('Inp_SC_BStarAddon'+x);
		BSAdd[x] = parseInt(selO.options[selO.selectedIndex].value, 10);
	}
	BSAdd[0]=BSAdd[5];
	for (var x=1; x<=5; x++){
		if ((BSAdd[x-1]>0)&&(BSAdd[x]>0)){
			document.getElementById('Inp_SC_FStarAddon'+x).disabled = false;
			document.getElementById('Inp_SC_FStarRate'+x).disabled = false;
		}else{
			document.getElementById('Inp_SC_FStarAddon'+x).selectedIndex = 0;
			document.getElementById('Inp_SC_FStarAddon'+x).disabled = true;
			document.getElementById('Inp_SC_FStarRate'+x).disabled = true;
			document.getElementById('Inp_SC_FStarRate'+x).style.boxShadow = "none";
			document.getElementById('Inp_SC_FStarRate'+x).value='0';
		}
	}

	for (var x=0; x<StarChartClass[cChar][2].length; x++){
		if (StarChartClass[cChar][2][x] == sAddon){
			document.getElementById('Inp_SC_'+sTyp+'StarRate'+SId).value=StarChartClass[cChar][1][x];
			break;
		}
	}
	RefreshStarChartAddons();
}

function SetExpirTimer(opt){
	var op1=document.getElementById("Expire_Act");
	var op2=document.getElementById("Expire_Stop");
	var op3=document.getElementById("Cur_PlusNr");
	var op4=document.getElementById("Cur_PlusType");
	
	if (opt == 0){
		op1.checked = false;
		op3.value="0";
	} else if (opt == 1){
		op3.value="10";
		op4.selectedIndex=0;
		op2.checked = false;
		op1.checked = true;
	} else if (opt == 2){
		op3.value="30";
		op4.selectedIndex=0;
		op2.checked = false;
		op1.checked = true;		
	} else if (opt == 3){
		op3.value="1";
		op4.selectedIndex=1;
		op2.checked = false;
		op1.checked = true;		
	} else if (opt == 4){
		op3.value="2";
		op4.selectedIndex=1;
		op2.checked = false;
		op1.checked = true;		
	} else if (opt == 5){
		op3.value="7";
		op4.selectedIndex=2;
		op2.checked = false;
		op1.checked = true;		
	}
}

function LoadShopLogListHandler(userData){
	var userD=JSON.parse(userData);
	if (userD[0]["error"]!=""){
		alert(userD[0]["error"]);
	}else{
		var max=userD[1].length;
		var t=document.getElementById('WSLogTable');
		var wslinp=document.getElementById('WSLData');
		t.innerHTML='';
		var row;
		var	c1,c2;
		var curr=['unknown','Gold', 'Point'];
		var myArr=[];	
		
		for (var i=0;i<max;i++){
			myArr=userD[1][i].itemdata.split('#');
			row = t.insertRow(0);
			c1 = row.insertCell(0);
			c1.innerHTML="<a title='"+myArr[2]+"' onclick='document.getElementById(\"WSLData\").value=\""+userD[1][i].itemdata+"\";'><b>"+myArr[7]+"</b></a> x"+userD[1][i].amount+" <b><a title='id: "+userD[1][i].userid+"'>"+userD[1][i].uname+"</a></b> (<i><a title='"+userD[1][i].roleid+"'>"+userD[1][i].rolename+"</a></i>) for "+userD[1][i].price+" "+curr[userD[1][i].currency];
			c2 = row.insertCell(1);
			c2.innerHTML=userD[1][i].buydate;
		}
	}			
	document.getElementById("WShopLog").style.display="block";
}

function LoadShopListHandler(userData){
	var userD=JSON.parse(userData);
	if (userD[0]["error"]!=""){
		alert(userD[0]["error"]);
	}else{
		var max=userD[1].length;
		WSIInd=0;
		for (var i=0;i<max;i++){
			WSIInd++;
			WSIData[WSIInd]=userD[1][i];
		}
		refreshShopItems();
		document.getElementById('MailResponse').innerHTML = "<font color='blue'><b><i> "+WSIInd+" shop item from database loaded! </i></b></font>";
	}		
}

function LoadPacketListHandler(userData){
	var userD=JSON.parse(userData);
	if (userD[0]["error"]!=""){
		document.getElementById('MailResponse').innerHTML ="<font color='red'><b><i> "+userD[0]["error"]+"</i></b></font>";;
	}else{
		var max=userD[1].length;
		SIInd=0;
		for (var i=0;i<max;i++){
			SIInd++;
			SIData[SIInd]=userD[1][i];
		}
		refreshSelectedItems();
		document.getElementById('MailResponse').innerHTML = "<font color='blue'><b><i> "+SIInd+" packet loaded from file! </i></b></font>";
	}		
}

function roleIdHandler(userData){
	var userD=JSON.parse(userData);
	if (userD[0]["error"]!=""){
		alert(userD[0]["error"]);
	}else{
		if (userD[0]["roleid"]!=""){
			document.getElementById('Inp_RoleId').value = userD[0]["roleid"];
			document.getElementById('MailResponse').innerHTML = '<font color=blue><b><i> For this name the result is: '+userD[0]["roleid"]+'! </i></b></font>';	
		}
	}	
}

function LoadShopItems(){
	var obj={"loadshopitems":1};
	ItemDataModWithAjax(2, obj);
}
function LoadPacketItems(){
	var obj={"loadtempitems":1};
	ItemDataModWithAjax(3, obj);
}
function LoadShopLog(){
	var obj={"loadshoplog":1};
	ItemDataModWithAjax(4, obj);
}

function ExportShopDB(){
	var obj={"exportdb":1};
	ItemDataModWithAjax(5, obj);
}	

function ImportShopDB(){
	var obj={"importdb":1};
	ItemDataModWithAjax(6, obj);
}	
	
function getSelectedItems(){
	var ilist=[];
	var idlist=[];
	var x=0;
	var div;
	var max=SIInd;
	var elem='SelItmChBox';

	for (var i=1; i<=max;i++){
		div = document.getElementById(elem+i);
		if (div != null){
			if (div.checked !== false){
				ilist[x] = SIData[i];
				idlist[x]=i;
				x++;
			}
		}else{
			break;
		}
	}
	return [ilist,idlist];
}

function SaveSelectedPackets(){
	var obj={"savetempitems":1,"itemlist":getSelectedItems()[0]};
	ItemDataModWithAjax(7, obj);
}

function SendCustomItem(){
	var roleId=parseInt(document.getElementById("Inp_RoleId").value,10);
	var itmName=document.getElementById("Inp_Title").value;
	var money=document.getElementById("Inp_Money").value;
	var ItemId = parseInt(document.getElementById("Sel_Item_Id").value);
	var ItemMask = parseInt(document.getElementById("Sel_Mask2").value);
	var ItemProc = parseInt(document.getElementById("Sel_ProcType2").value);
	var QTY = parseInt(document.getElementById("Sel_Count").value);
	var MaxQTY = parseInt(document.getElementById("Sel_MaxCount").value);
	var Guid1 = parseInt(document.getElementById("Sel_Guid1").value);
	var Guid2 = parseInt(document.getElementById("Sel_Guid2").value);
	var Expire = parseInt(document.getElementById("Sel_Expire").value);
	var MTitle = document.getElementById("Inp_MailTitle").value;
	var MBody = document.getElementById("Inp_MailBody").value;
	var Octet = document.getElementById("Inp_Octet").value;
	var itemD=roleId+"#"+money+"#"+MTitle+"#"+MBody+"##"+itmName+"##"+ItemId+"#"+ItemMask+"#"+ItemProc+"#"+QTY+"#"+MaxQTY+"#"+Guid1+"#"+Guid2+"#"+Expire+"#"+Octet+"#####";
	var itemlist=[itemD];
	var obj={"sendpacket":1,"itemlist":itemlist};
	ItemDataModWithAjax(8, obj);
}
function SendSelectedItem(id){
	var IData = document.getElementById("SelItmChBox"+id).value;
	if (SIData[id] !== null){
		var itemlist=[SIData[id]];
		var obj={"sendpacket":1,"itemlist":itemlist};
		ItemDataModWithAjax(8, obj);
	}
}

function GroupSendSelectedItem(){
	var obj={"sendpacket":1,"itemlist":getSelectedItems()[0]};
	ItemDataModWithAjax(8, obj);	
}

function AddShopItemToList(id, itm){
	WSIInd++;
	WSIData[WSIInd] = itm+"#"+id;
	refreshShopItems();
}

function AddItemToShop(){
	var nData = [];
	var cData1 = [];
	var cData2 = [];
	var CInt = CType2CTypeInt(CIType);
	var selList = document.getElementById("SItmC"+CInt+"S"+SIType);
	var IExist = false;
	var ExpireT = 0;
	nData[0] = parseInt(document.getElementById("Inp_Price1").value, 10) || 0;
	nData[1] = parseInt(document.getElementById("Inp_Price2").value, 10) || 0;
	nData[2] = document.getElementById("Inp_Title").value;
	nData[3] = document.getElementById("Inp_Description").value; 
	nData[4] = CIType+SIType;
	nData[5] = selList.options[selList.selectedIndex].text;
	nData[6] = GetSqlDate();
	nData[7] = parseInt(document.getElementById("Sel_Item_Id").value);
	nData[8] = parseInt(document.getElementById("Sel_Mask2").value);
	nData[9] = parseInt(document.getElementById("Sel_ProcType2").value);
	nData[10] = parseInt(document.getElementById("Sel_Count").value);
	nData[11] = parseInt(document.getElementById("Sel_MaxCount").value);
	nData[12] = parseInt(document.getElementById("Sel_Guid1").value);
	nData[13] = parseInt(document.getElementById("Sel_Guid2").value);
	if (nData[2].trim() == ""){nData[2]=nData[5];}
	if (document.getElementById("Expire_Act").checked !== true){
		document.getElementById("Sel_Expire").value = 0;
	}else{
		var tmp = document.getElementById("Cur_PlusType");
		ExpireT = (tmp.options[tmp.selectedIndex].value*(parseInt(document.getElementById("Cur_PlusNr").value,10) || 0));
	}
	nData[14] = ExpireT;
	nData[15] = document.getElementById("Inp_Octet").value;
	nData[17] = parseInt(document.getElementById("Inp_Color").value, 10);
	if ((CIType == "W")||(CIType == "A")||(CIType == "J")||(CIType == "C")||(CIType == "F")||((CIType == "O")&&((SIType==3)||(SIType==1)||(SIType==11)))||((CIType == "U")&&(SIType==12))){
		nData[18] = document.getElementById("Inp_Grade2").value;
	}else{
		nData[18] = 0;
	}
	nData[16] = ConvOldToNewCat(nData[4], nData[15]);
	var TimeData = "";
	var SelO = document.getElementById('Inp_ShopType');
	var Opt = SelO.options[SelO.selectedIndex].value;
	var ShopTime = true;
	var AHide = 0;
	if (document.getElementById('Inp_AutoHideItem').checked!==false){AHide=1;}	
	if (Opt == 0){
		TimeData = "0 0";
	}else if(Opt==1){
		var STime = document.getElementById('Inp_Start_Date').value;
		var ETime = document.getElementById('Inp_End_Date').value;
		if (DateValidationExt(STime, ETime) !== false){
			var jsDate1 = parseInt(new Date(Date.parse(STime)).getTime(), 10) / 1000;
			var jsDate2 = parseInt(new Date(Date.parse(ETime)).getTime(), 10) / 1000;
			TimeData = Opt+" "+AHide+" "+jsDate1+" "+jsDate2;
		}else{
			ShopTime = false;
		}
	}else if(Opt==2){
		var STime = document.getElementById('Inp_Start_Hour').value;
		var ETime = document.getElementById('Inp_End_Hour').value;
		if (HourValidationExt(STime, ETime) !== false){
			TimeData = Opt+" "+AHide+" "+STime+" "+ETime;	
		}else{
			ShopTime = false;
		}		
	}else if(Opt==3){
		var STime = document.getElementById('Inp_Start_Date').value;
		var ETime = document.getElementById('Inp_End_Date').value;
		var Discount = document.getElementById('Inp_Discount').value;
		if (DateValidationExt(STime, ETime) !== false){
			if ((Discount > 0)&&(100>=Discount)){
				var jsDate1 = parseInt(new Date(Date.parse(STime)).getTime(), 10) / 1000;
				var jsDate2 = parseInt(new Date(Date.parse(ETime)).getTime(), 10) / 1000;
				TimeData = Opt+" "+AHide+" "+jsDate1+" "+jsDate2+" "+Discount;	
			}else if(Discount == 0){
				TimeData = Opt+" "+AHide+" "+STime+" "+ETime;
			}else{
				document.getElementById('MailResponse').innerHTML = "<font color='red'><b><i> Discount must be: 0 - 100... </i></b></font>";
				ShopTime = false;
			}
		}
	}
	nData[19]=TimeData;
	for (i = 1; i <= WSIInd; i++) {
		cData1 = WSIData[i].split("#").slice(0,-3);
		cData1[0] = "";
		cData1[1] = "";
		cData1[2] = "";
		cData1[3] = "";
		cData1[6] = "";
		cData1[16] = "";
		cData1[17] = "";
		cData2 = nData.slice(0);
		cData2[0] = "";
		cData2[1] = "";
		cData2[2] = "";
		cData2[3] = "";
		cData2[6] = "";
		cData2[16] = "";
		cData2[17] = "";
		if (cData1.join("#") == cData2.join("#")){
			IExist = true;
		}
	}
	var resp = document.getElementById("MailResponse");
	if (ShopTime!==false){
		if (IExist !== true){
			resp.innerHTML = "";
			if ((nData[0] < 1) && (nData[1] < 1)){
				resp.innerHTML = "<font color='red'><b><i> Change item price (min: 1)!</i></b></font>";
				document.getElementById("Inp_Price1").style.backgroundColor="#faa";
				document.getElementById("Inp_Price2").style.backgroundColor="#faa";
				document.getElementById("Inp_Title").style.backgroundColor="#fff";
				document.getElementById("Inp_Description").style.backgroundColor="#fff";
			}else{
				document.getElementById("Inp_Price1").style.backgroundColor="#fff";
				document.getElementById("Inp_Price2").style.backgroundColor="#fff";
				document.getElementById("Inp_Title").style.backgroundColor="#fff";
				document.getElementById("Inp_Description").style.backgroundColor="#fff";
				var idat=nData.join("#").replace(/'/g, "").replace(/"/g, "").replace(/=/g, "").replace(/</g, "").replace(/>/g, "");
				var obj={"saveitem2db":"1","item":idat};
				ItemDataModWithAjax(9, obj)
			}		
		}else{
			resp.innerHTML = "<font color='red'><b><i> Already on shop list...</font></i></b></font>";
		}
	}
}

function ImportDBHandler(userData){
	var userD=JSON.parse(userData);
	var x=userD[1].length;
	for (var i=0;i<x;i++){
		WSIInd++;
		WSIData[WSIInd] = userD[1][i];
		alert(WSIData[WSIInd]);
	}
	refreshShopItems();
}


function UpdateShopItem(){
	var tArr;
	var myArr = [];
	var SSC = document.getElementById('ItmShpCat');
	var CS = document.getElementById('ItmShpCol');
	var id = parseInt(document.getElementById('WSE_PacketId').value, 10) || 0;
	myArr[0] = document.getElementById('WSE_Price1').value;
	myArr[1] = document.getElementById('WSE_Price2').value;
	myArr[2] = document.getElementById('WSE_Title').value;
	myArr[3] = document.getElementById('WSE_Description').value;
	myArr[4] = document.getElementById('WSE_CAT').value;
	myArr[5] = document.getElementById('WSE_ItemName').value;
	myArr[6] = GetSqlDate();
	myArr[7] = document.getElementById('WSE_ItemId').value;
	myArr[8] = document.getElementById('WSE_Mask').value;
	myArr[9] = document.getElementById('WSE_ProcType').value;
	myArr[10] = document.getElementById('WSE_Stack').value;
	myArr[11] = document.getElementById('WSE_MaxStack').value;
	myArr[12] = document.getElementById('WSE_Guid1').value;
	myArr[13] = document.getElementById('WSE_Guid2').value;
	myArr[14] = document.getElementById('WSE_Expire').value;
	myArr[15] = document.getElementById('WSE_Octet').value;
	myArr[16] = SSC.options[SSC.selectedIndex].value;
	myArr[17] = CS.options[CS.selectedIndex].value;
	myArr[18] = document.getElementById('WSE_Grade').value;
	var TimeData = "";
	var SelO = document.getElementById('WSE_SType');
	var Opt = SelO.options[SelO.selectedIndex].value;
	var ShopTime = true;
	var AHide = 0;
	if (document.getElementById('WSE_AutoHideItem').checked!==false){AHide=1;}
	if (Opt == 0){
		TimeData = "0 0";
	}else if(Opt==1){
		var STime = document.getElementById('WSE_Start_Date').value;
		var ETime = document.getElementById('WSE_End_Date').value;
		if (DateValidationExt(STime, ETime) !== false){
			var jsDate1 = parseInt(new Date(Date.parse(STime)).getTime(), 10) / 1000;
			var jsDate2 = parseInt(new Date(Date.parse(ETime)).getTime(), 10) / 1000;
			TimeData = Opt+" "+AHide+" "+jsDate1+" "+jsDate2;
		}else{
			ShopTime = false;
		}
	}else if(Opt==2){
		var STime = document.getElementById('WSE_Start_Hour').value;
		var ETime = document.getElementById('WSE_End_Hour').value;
		if (HourValidationExt(STime, ETime) !== false){
			TimeData = Opt+" "+AHide+" "+STime+" "+ETime;	
		}else{
			ShopTime = false;
		}		
	}else if(Opt==3){
		var STime = document.getElementById('WSE_Start_Date').value;
		var ETime = document.getElementById('WSE_End_Date').value;
		var Discount = document.getElementById('WSE_Discount').value;
		if (DateValidationExt(STime, ETime) !== false){
			if ((Discount > 0)&&(100>=Discount)){
				var jsDate1 = parseInt(new Date(Date.parse(STime)).getTime(), 10) / 1000;
				var jsDate2 = parseInt(new Date(Date.parse(ETime)).getTime(), 10) / 1000;
				TimeData = Opt+" "+AHide+" "+jsDate1+" "+jsDate2+" "+Discount;	
			}else if(Discount == 0){
				TimeData = Opt+" "+AHide+" "+STime+" "+ETime;
			}else{
				document.getElementById('MailResponse').innerHTML = "<font color='red'><b><i> Discount must be: 0 - 100... </i></b></font>";
				ShopTime = false;
			}
		}
	}
	
	myArr[19]=TimeData;
	if (tArr=WSIData[id].split('#')){
		myArr[20]=tArr[20];
		myArr[21]=tArr[21];
	}

	if (ShopTime !== false){
		var itm=myArr.join("#").replace(/'/g, "").replace(/"/g, "").replace(/=/g, "").replace(/</g, "").replace(/>/g, "");
		var obj={"updateshopitem":"1","item":itm};
		ItemDataModWithAjax(10, obj);		
	}else{
		document.getElementById('MailResponse').innerHTML = "<font color='red'><b><i> Sorry but give correct time data!</i></b></font>";
	}
}


function UpdatedShopItemHandler(id, itm){
	var myArr;
	for (var i=1;i<=WSIInd;i++){
		myArr=WSIData[i].split("#");
		if (myArr[20]==id){
			WSIData[i]=itm;
			break;
		}
	}
	refreshShopItems();
	document.getElementById('ShopItemEditWindow').style.display='none';
}

function DeleteShopItemHandler(ids){
	var max = ids.length;
	var myArr;
	var ind;
	var idc=ids.length;
	if (ids.length>0){
		for (var i=1; i<=WSIInd && idc>0;i++){
			myArr=WSIData[i].split("#");
			ind=ids.indexOf(parseInt(myArr[20], 10));
			if (ind>-1){
				WSIData.splice(i, 1);
				WSIInd--;
				idc--;
				i--;
			}
		}
		refreshShopItems();
		if (WSIInd == 0){
			document.getElementById("ShopMultiTool").style.display="none";
		}	
	}
}


function DeleteShopItem(id){
	if ((id > -1) && (id <= WSIData.length)){
		var myArr=WSIData[id].split("#");
		var ids=[myArr[20]];
		var obj={"deleteshopitem":"1","itemid":ids};
		ItemDataModWithAjax(11, obj);	
	}	
}

function GroupDeleteShopItems(){
	var div;
	var c=0;
	var max=WSIInd;
	var myArr=[];
	var ids=[];
	for (var i = 1; i <= max; i++) {
		div = document.getElementById("ShopItmChBox"+i);
		if (div != null){
			if (div.checked === true){
				myArr=WSIData[i].split("#");
				ids[c]=myArr[20];
				c++;
			}
		}
	}
	var obj={"deleteshopitem":"1","itemid":ids};
	ItemDataModWithAjax(11, obj);
}


function ItemDataModWithAjax(typ, obj) {
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
			//alert("---:"+JSON.stringify(fdbck));
		    if (typ==1){
				if (fdbck != ""){
					roleIdHandler(JSON.stringify(fdbck));
				}else{
					alert("Cannot load user data!");
				}
			}else if(typ==2){
				if (fdbck != ""){
					LoadShopListHandler(JSON.stringify(fdbck));
				}else{
					alert("Cannot load shop item list!");
				}
			}else if(typ==3){
				if (fdbck != ""){
					LoadPacketListHandler(JSON.stringify(fdbck));
				}else{
					alert("Cannot load item packets!");
				}
			}else if(typ==4){
				if (fdbck != ""){
					LoadShopLogListHandler(JSON.stringify(fdbck));
				}else{
					alert("Cannot load web shop log!");
				}
			}else if(typ==5){
				if (fdbck != ""){
					if (fdbck[0]["error"]!=""){
						alert(fdbck[0]["error"]);
					}else{
						document.getElementById('MailResponse').innerHTML = "<font color='blue'><b><i> "+fdbck[0]["success"]+" </i></b></font>";
					}	
				}else{
					alert("Cannot export shop items!");
				}
			}else if(typ==6){
				if (fdbck != ""){
					if (fdbck[0]["error"]!=""){
						alert(fdbck[0]["error"]);
					}else{
						document.getElementById('MailResponse').innerHTML = "<font color='blue'><b><i> "+fdbck[0]["success"]+" </i></b></font>";
						ImportDBHandler(JSON.stringify(fdbck));
					}	
				}else{
					alert("Cannot import shop items!");
				}	
			}else if(typ==7){
				if (fdbck != ""){
					if (fdbck[0]["error"]!=""){
						alert(fdbck[0]["error"]);
					}else{
						document.getElementById('MailResponse').innerHTML = "<font color='blue'><b><i> "+fdbck[0]["success"]+" </i></b></font>";
					}	
				}else{
					alert("Cannot save packets!");
				}	
			}else if(typ==8){
				if (fdbck != ""){
					if (fdbck[0]["error"]!=""){
						alert(fdbck[0]["error"]);
					}else{
						document.getElementById('MailResponse').innerHTML = "<font color='blue'><b><i> "+fdbck[0]["success"]+" </i></b></font>";
					}	
				}else{
					document.getElementById('MailResponse').innerHTML = "<font color='red'><b><i> Error: unable send mail(s) </i></b></font>";
				}	
			}else if(typ==9){
				if (fdbck != ""){
					if (fdbck[0]["error"]!=""){
						alert(fdbck[0]["error"]);
					}else{
						AddShopItemToList(fdbck[0].id, fdbck[1][0]);
						document.getElementById('MailResponse').innerHTML = "<font color='blue'><b><i> "+fdbck[0]["success"]+" </i></b></font>";
					}	
				}else{
					document.getElementById('MailResponse').innerHTML = "<font color='red'><b><i> Error: cannot save item! </i></b></font>";
				}	
			}else if(typ==10){
				if (fdbck != ""){
					if (fdbck[0]["error"]!=""){
						alert(fdbck[0]["error"]);
					}else{
						UpdatedShopItemHandler(fdbck[0].id, fdbck[1][0]);
						document.getElementById('MailResponse').innerHTML = "<font color='blue'><b><i> "+fdbck[0]["success"]+" </i></b></font>";
					}	
				}else{
					document.getElementById('MailResponse').innerHTML = "<font color='red'><b><i> Error: cannot update item! </i></b></font>";
				}	

			}else if(typ==11){
				if (fdbck != ""){
					if (fdbck[0]["error"]!=""){
						alert(fdbck[0]["error"]);
					}else{
						DeleteShopItemHandler(fdbck[1]);
						document.getElementById('MailResponse').innerHTML = "<font color='blue'><b><i> "+fdbck[0]["success"]+" </i></b></font>";
					}	
				}else{
					document.getElementById('MailResponse').innerHTML = "<font color='red'><b><i> Error: cannot delete item(s)! </i></b></font>";
				}					
			}
        }
    };
	if (typ==1){
		xmlhttp.open("POST", "../php/loadroleid.php", false);
	}else if ((typ>1)&&(typ<5)){
		xmlhttp.open("POST", "../php/loaditemlistlog.php", false);
	}else if (typ==5){
		xmlhttp.open("POST", "../php/exportshopdb.php", false);
	}else if (typ==6){
		xmlhttp.open("POST", "../php/importshopdb.php", false);
	}else if (typ==7){
		xmlhttp.open("POST", "../php/saveitemlist.php", false);
	}else if (typ==8){
		xmlhttp.open("POST", "../php/sendpackets.php", false);	
	}else if (typ==9){
		xmlhttp.open("POST", "../php/saveshopitem.php", false);
	}else if (typ==10){
		xmlhttp.open("POST", "../php/updateshopitem.php", false);
	}else if (typ==11){
		xmlhttp.open("POST", "../php/deleteshopitem.php", false);			
	}
	xmlhttp.setRequestHeader("Content-type", "application/json");	
	
	var myJSON = JSON.stringify(obj);
    xmlhttp.send(myJSON);
	return false;
}