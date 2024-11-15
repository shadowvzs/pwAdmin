class RoleService {
    constructor() {
        this.path = '../php/roles.php?action';
    } 

    async getMyRoles() {
        const rolesResponse = await fetch(`${this.path}=my-roles`);
        const roles = await rolesResponse.json();
        return roles;
    }
	
	async getRole(id) {
        const roleResponse = await fetch(`${this.path}=role&id=${id}`);
        const rols = await roleResponse.json();
        return role;
    }
	
	async userRoles(id) {
        const rolesResponse = await fetch(`${this.path}=list&user-id=${id}`);
        const roles = await rolesResponse.json();
        return roles;
	}
}

class RoleListComponent {
	constructor(containerSelector, onclick) {
		this.containerSelector = containerSelector;
		this.init = this.init.bind(this);
		this.getFragment = this.getFragment.bind(this);
		this.onclick = onclick;
	}
	
	getFragment(roles) {
		const fragment = document.createDocumentFragment();
		const htmlDivList = roles.forEach(role => {
			const div = document.createElement('div');
			const nameContainer = document.createElement('span');
			const infoContainer = document.createElement('span');
			nameContainer.textContent = role.rolename;
			infoContainer.textContent = ` [Lv${role.rolelevel}]`;
			nameContainer.style.color = '#ffff00';
			infoContainer.style.fontStyle = "italic";
			infoContainer.style.color = '#7777ff';
			div.appendChild(nameContainer);
			div.appendChild(infoContainer);
			fragment.appendChild(div);
			if (this.onclick) {
				nameContainer.onclick = () => this.onclick(role);
			}
		});
		
		return fragment;
	}
	
	init(roles) {
		const listContainer = document.querySelector(this.containerSelector);
		listContainer.appendChild(this.getFragment(roles));
		return this;
	}
}
