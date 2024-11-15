class GuildService {
    constructor() {
        this.path = '../php/guilds.php?action';
    } 

    async getMyGuilds() {
        const guildsResponse = await fetch(`${this.path}=my-guilds`);
        const guilds = await guildsResponse.json();
        return guilds;
    }

    async getGuild(id) {
        const guildResponse = await fetch(`${this.path}=guild&id=${id}`);
        const guild = await guildResponse.json();
        return guild;
    }

    async getGuilds() {
        const guildsResponse = await fetch(`${this.path}=list`);
        const guilds = await guildsResponse.json();
        return guilds;
    }
}

class GuildWindowComponent {
    constructor(containerSelector) {
		this.containerSelector = containerSelector;
		this.sort = 'name'; // name/post/title/lv/job
		this.sortDirection = 'ASC'; // ASC/DESC
    }

    getWindowCSS() {
        return `
            .guild-window-container {
                position: relative;
                box-sizing: border-box;
                background-image: url("../images/in-game/guild-window.webp");
                width: 541px;
                height: 423px;
            }
            .guild-window-container .close {
                position: absolute;
                box-sizing: border-box;
                top: 30px;
                right: 44px;
                width: 28px;
                height: 26px;
                cursor: pointer;
                border: 1px solid transparent;
            }
            .guild-window-container .refresh-list {
                position: absolute;
                box-sizing: border-box;
                top: 56px;
                right: 23px;
                width: 20px;
                height: 21px;
                cursor: pointer;
                border: 1px solid transparent;
            }
            .guild-window-container .guild-name {
                position: absolute;
                box-sizing: border-box;
                top: 33px;
                left: 50%;
                transform: translateX(-50%);
                min-width: 20px;
                margin-left: 2px;
                height: 26px;
                border: 1px solid transparent;
				text-shadow: none;
				color: yellow;
            }
            .guild-window-container .info-line {
                position: absolute;
                box-sizing: border-box;
                bottom: 118px;
                left: 34px;
                right: 46px;
                height: 20px;
				text-shadow: none;
				color: white;
                border: 1px solid transparent;
				font-size: 14px;
            }
            .guild-window-container .manifesto {
                position: absolute;
                box-sizing: border-box;
                bottom: 80px;
                left: 34px;
                right: 44px;
                height: 32px;
				text-shadow: none;
				color: white;
                border: 1px solid transparent;
            }
			/* width */
			.guild-window-container .member-list::-webkit-scrollbar {
				width: 16px;
			}

			/* Track */
			.guild-window-container .member-list::-webkit-scrollbar-track {
				background: linear-gradient(to left, #444, #111, #444); 
			}
			 
			/* Handle */
			.guild-window-container .member-list::-webkit-scrollbar-thumb {
				background: linear-gradient(to left, #7c410e, #bd804a, #7c410e); 

			}

			/* Handle on hover */
			.guild-window-container .member-list::-webkit-scrollbar-thumb:hover {
				background: linear-gradient(to left, #b55, #d88, #b55); 
				cursor: grab;
			}
			.guild-window-container .header-name {
				position: absolute;
                box-sizing: border-box;
                top: 58px;
                left: 27px;
                width: 125px;
                height: 20px;
                border: 1px solid transparent;
				cursor: pointer;
			}
			.guild-window-container .header-post {
				position: absolute;
                box-sizing: border-box;
                top: 58px;
                left: 155px;
                width: 125px;
                height: 20px;
                border: 1px solid transparent;
				cursor: pointer;
			}
			.guild-window-container .header-title {
				position: absolute;
                box-sizing: border-box;
                top: 58px;
                left: 284px;
                width: 54px;
                height: 20px;
                border: 1px solid transparent;
				cursor: pointer;
			}
			.guild-window-container .header-level {
				position: absolute;
                box-sizing: border-box;
                top: 58px;
                left: 341px;
                width: 42px;
                height: 20px;
                border: 1px solid transparent;
				cursor: pointer;
			}
			.guild-window-container .header-job {
				position: absolute;
                box-sizing: border-box;
                top: 58px;
                left: 387px;
                width: 41px;
                height: 20px;
                border: 1px solid transparent;
				cursor: pointer;
			}
            .guild-window-container .member-list {
                position: absolute;
                box-sizing: border-box;
                top: 82px;
                bottom: 140px;
                left: 34px;
                right: 24px;
                border: 1px solid transparent;
				overflow-y: auto;
				text-shadow: none;
				color: white;
				font-size: 14px;
            }
        `;
    }

	onSortClick(ev) {
		const [place, sortField] = ev.target.className.split('-');
		if (sortField === this.sort) {
			this.sortDirection = this.sortDirection === 'ASC' ? 'DESC' : 'ASC';
		} else {
			this.sort = sortField;
			this.sortDirection = 'ASC';
		}
		
		this.setGuildInformation(this.guildData);
	}


    createWindow() {
		this.onSortClick = this.onSortClick.bind(this);
        this.elements = {
            styleElement: document.createElement('style'),
            root: this.createElement('div', { className: 'guild-window-container' }),
            close: this.createElement('div', { className: 'close' }),
            headerJob: this.createElement('div', { className: 'header-job', onclick: this.onSortClick }),
            headerLevel: this.createElement('div', { className: 'header-level', onclick: this.onSortClick }),
            headerTitle: this.createElement('div', { className: 'header-title', onclick: this.onSortClick }),
            headerPost: this.createElement('div', { className: 'header-post', onclick: this.onSortClick }),
            headerName: this.createElement('div', { className: 'header-name', onclick: this.onSortClick }),
            refreshList: this.createElement('div', { className: 'refresh-list' }),
            guildName: this.createElement('div', { className: 'guild-name' }),
            infoLine: this.createElement('div', { className: 'info-line' }),
            manifesto: this.createElement('div', { className: 'manifesto' }),
            memberList: this.createElement('div', { className: 'member-list' }),
        }
        this.elements.root.appendChild(this.elements.close);
        this.elements.root.appendChild(this.elements.headerJob);
        this.elements.root.appendChild(this.elements.headerLevel);
        this.elements.root.appendChild(this.elements.headerTitle);
        this.elements.root.appendChild(this.elements.headerPost);
        this.elements.root.appendChild(this.elements.headerName);
        this.elements.root.appendChild(this.elements.refreshList);
        this.elements.root.appendChild(this.elements.guildName);
        this.elements.root.appendChild(this.elements.infoLine);
        this.elements.root.appendChild(this.elements.manifesto);
        this.elements.root.appendChild(this.elements.memberList);
        this.elements.close.onclick = this.hide.bind(this);
        this.elements.refreshList.onclick = this.refreshList.bind(this);
        this.elements.styleElement.type = 'text/css';
        this.elements.styleElement.appendChild(document.createTextNode(this.getWindowCSS()));
        return this.elements.root;
    }

    init() {
		this.containerElement = document.querySelector(this.containerSelector);
        const window = this.createWindow();
        document.head.appendChild(this.elements.styleElement);
        this.containerElement.appendChild(window);
        return this;
    }
	
	sortMembers(members, sortField, sortDirection) {
		this.sort = sortField;
		this.sortDirection = sortDirection;
		const direction = sortDirection === 'ASC' ? 1 : -1;
		if (sortField === 'name') {
			members.sort((a, b) => {
				return a.name.localeCompare(b.name) * direction;
			});
		} else if (sortField === 'title') {
			members.sort((a, b) => {
				return a.title.localeCompare(b.title) * direction;
			});
		} else if (sortField === 'post') {
			members.sort((a, b) => {
				return (a.rank - b.rank) * direction;
			});
		} else if (sortField === 'level') {
			members.sort((a, b) => {
				return (b.level - a.level) * direction;
			});
		} else if (sortField === 'job') {
			members.sort((a, b) => {
				return (b.cls - a.cls) * direction;
			});
		}
		return members;
	}

    async setGuildInformation(guild) {
		const maxMemberMap = {
			0: 50,
			1: 100,
			2: 200
		};
		const rankMap = {
			2: "Guild Master",
			3: "Vice Guild Master",
			4: "Commander",
			5: "Captain",
			6: "Member",
		};
		const clsMap = {
			0: "WR",
			1: "MG",
			4: "WB",
			3: "WF",
			6: "EA",
			7: "EP",
		};
        this.guildData = guild;
        this.elements.guildName.textContent = guild.name;
        this.elements.infoLine.textContent = `${guild.name} - Guild Level: ${guild.level+1} Members: ${guild.members.length}/${maxMemberMap[guild.level]}`;
        this.elements.manifesto.textContent = guild.manifesto;
		const fr = document.createDocumentFragment();

		const members = this.sortMembers(guild.members, this.sort, this.sortDirection).forEach(member => {
			const row = [
				this.createElement('div', { className: 'w-32' }, member.name),
				this.createElement('div', { className: 'w-32' }, rankMap[member.rank]),
				this.createElement('div', { className: 'w-14' }, member.title),
				this.createElement('div', { className: 'w-8 mx-1' }, member.level),
				this.createElement('div', { className: 'w-8 mx-2' }, clsMap[member.cls]),
				this.createElement('div', { className: 'w-10 mx-2' }, member.login < 3 ? 'Today' : ''),
			];
			const rowElement = this.createElement('div', { className: 'row flex' }, row);
			fr.appendChild(rowElement);
		});
		this.elements.memberList.innerHTML = '';
        this.elements.memberList.appendChild(fr);
		this.show();
        return this;
    }

    createElement(tagName, attributes, content) {
        const element = document.createElement(tagName);
		Object.entries(attributes).forEach(([key, value]) => {
			element[key] = value;
		});
		if (typeof content === 'string' || typeof content === 'number') {
			element.textContent = content;
		} else if (Array.isArray(content)) {
			content.forEach(c => element.appendChild(c));
		}
		
        return element;
    }

    show() {
        this.elements.root.style.display = 'block';
        return this;
    }

    hide() {
        this.elements.root.style.display = 'none';
        return this; 
    }

    async refreshList() {
		if (this.guildData) {
			const service = new GuildService();
			const data = await service.getGuild(this.guildData.id);
			this.setGuildInformation(data);			
		}
        return this;
    }
}

class GuildListComponent {
	constructor(containerSelector, onclick) {
		this.containerSelector = containerSelector;
		this.init = this.init.bind(this);
		this.getFragment = this.getFragment.bind(this);
		this.onclick = onclick;
	}
	
	getFragment(guilds) {
		const maxMemberMap = {
			1: 50,
			2: 100,
			3: 200
		};
		
		const fragment = document.createDocumentFragment();
		guilds.sort((a, b) => b.members - a.members)
		const htmlDivList = guilds.forEach(guild => {
			const div = document.createElement('div');
			const nameContainer = document.createElement('span');
			const infoContainer = document.createElement('span');
			nameContainer.textContent = guild.name;
			infoContainer.textContent = ` [${guild.members}/${maxMemberMap[guild.level]}]`;
			nameContainer.style.cursor = 'pointer';
			nameContainer.style.color = '#ffff00';
			infoContainer.style.fontStyle = "italic";
			infoContainer.style.color = '#7777ff';
			div.appendChild(nameContainer);
			div.appendChild(infoContainer);
			fragment.appendChild(div);
			if (this.onclick) {
				nameContainer.onclick = () => this.onclick(guild);
			}
		});
		
		return fragment;
	}
	
	init(guilds) {
		const guildListContainer = document.querySelector(this.containerSelector);
		guildListContainer.appendChild(this.getFragment(guilds));
		return this;
	}
}
