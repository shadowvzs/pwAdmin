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
	var tab = null;
	var i = 1;
	for (; i < 6; i++){
		tab = document.getElementById('MainTab'+i);
		console.log(tab, 'MainTab'+i);
		if (tab != null){
			if (i == id){
				tab.style.display="block";
			}else{
				tab.style.display="none";
			}
		}else{
			break;
		}
	}
}

let roleLoadTimestamp = 0;

async function updateRoleList() {
	if (roleLoadTimestamp && (Date.now() - roleLoadTimestamp) < 3000) { return; }
	roleLoadTimestamp = Date.now();
	const rolesResponse = await fetch('../php/online_roles.php');
	const roles = await rolesResponse.json();
	console.log('Roles:', roles);
	const fragment = document.createDocumentFragment();
	roles.sort((a, b) => {
		if (b.level === a.level) {
			b.exp - a.exp;
		}
		return b.level - a.level;
	});
	const htmlDivList = roles.forEach(role => {
		const div = document.createElement('div');
		const nameContainer = document.createElement('span');
		const infoContainer = document.createElement('span');
		nameContainer.textContent = role.name;
		infoContainer.textContent = ` [Lv. ${role.level}]`;
		nameContainer.style.color = '#ffff00';
		infoContainer.style.fontStyle = "italic";
		infoContainer.style.color = '#7777ff';
		div.appendChild(nameContainer);
		div.appendChild(infoContainer);
		fragment.appendChild(div);
	});
	const rolesList = document.getElementById('role-list');
	rolesList.innerHTML = '';
	rolesList.appendChild(fragment);
	
	const playerCounter = document.getElementById('statusPlayerCount');
	if (playerCounter) {
		playerCounter.textContent = roles.length;
	}
	
}

function initNewsPage() {
	const guildService = new GuildService();
	const guildListCmp = new GuildListComponent('#guild-list');
	guildService.getGuilds().then((guilds) => {
		console.log(guilds);
		guildListCmp.init(guilds);
	});

	updateRoleList('#role-list');
	
	setInterval(updateRoleList, 60000);
	/*
	fetch('../data/online_roles.json')
		.then(r => r.json())
		.then(r => console.log(r));*/
}

var RandomCode=0;
var SrvrTmZone=0;