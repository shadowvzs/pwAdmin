class BaseFormController {
	constructor(id) {
		this.id = id;
	}
	
	init() {
		this.inputMap = {};
		this.form = document.getElementById(this.id);
		this.inputs = Array.from(this.form.querySelectorAll('select[name],textarea[name],input[name]'));
		this.buttons = Array.from(this.form.querySelectorAll('button'));
		this.submitBtn = this.buttons.find(btn => btn.type === 'submit');
		this.inputMap = this.inputs.reduce((obj, input) => {
			obj[input.name] = input;
			return obj;
		}, {});
	}
	
	getValues() {
		const values = this.inputs.reduce((obj, input) => {
			obj[input.name] = input.value;
			return obj;
		}, {});
		return values;
	}
}

class LoginFormController extends BaseFormController {
	constructor() {
		super('loginForm');
		this.init();
	}
	
	init() {
		super.init();
		this.onSubmit = this.onSubmit.bind(this);
		this.form.onsubmit = this.onSubmit;
		this.passwordToggleButton = this.form.querySelector('.toggle-password-btn');
		this.togglePassword = this.togglePassword.bind(this);
		this.passwordToggleButton.onclick = this.togglePassword;
	}
	
	validation(values) {
		const alphaNumRGX=/^[a-z\d]+$/;
		const passwdRGX=/^[a-z\d\.\,\@\!\$\?]+$/i;
		const emailRGX = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		const errors = [];
		
		if (!alphaNumRGX.test(values.username) || values.username.length < 6 || values.username.length > 32) {
			errors.push("Username must be between 6-32 character, lowercase letters or numbers!");
		}
		
		if (values.password.length < 6 || values.password.length > 32) {
			errors.push("Password must be between 6-32 character, lowercase, uppercase letters, number and symbols (,.!?@)!");
		}
		
		return { errors, isValid: errors.length === 0};
	}
	
	onSubmit(ev) {
		ev.preventDefault();
		const values = this.getValues();
		const { isValid, errors } = this.validation(values);
		
		if (!isValid) {
			alert(errors.join('\n\r'));
			return;
		}
		
		this.login(values);
		return false;
	}
	
	async login(values) {
		try {
			const loginRequest = await fetch("../php/login.php", {
			  headers: {
				  "Content-Type": "application/json",
				  "Accept":"text/plain;charset=UTF-8",
				},
			  method: "POST",
			  body: JSON.stringify({
				  name: values.username,
				  password: values.password,
			  })
			});
			const message = await loginRequest.text();
			if (message) {
				alert(message);
			} else {
				window.location.href = "../my-account";
			}
		} catch (err) {
			alert(err);
			return;
		}
	}
	
	togglePassword() {
		const passwordInputs = this.inputs.filter(x => x.name.includes('password'));
		const newType = ['text', 'password'][Number(passwordInputs[0].type === 'text')];
		passwordInputs.forEach(x => x.type = newType);
	}
}
