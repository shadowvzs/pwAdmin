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

class RegFormController extends BaseFormController {
	constructor() {
		super('regForm');
		this.init();
	}
	
	init() {
		super.init();
		this.autoFillBtn = this.buttons.find(btn => btn.id === 'RegAutofillBtn');
		this.autoFillBtn.onclick = this.autoFill.bind(this);
		this.onSubmit = this.onSubmit.bind(this);
		this.form.onsubmit = this.onSubmit;
		this.generateQuestion();				
		this.passwordToggleButton = this.form.querySelector('.toggle-password-btn');
		this.togglePassword = this.togglePassword.bind(this);
		this.passwordToggleButton.onclick = this.togglePassword;
	}
	
	autoFill() {
		const username = 'user'+String(Math.random()).slice(6);
		const password = this.generatePassword();
		this.inputMap['username'].value = username;
		this.inputMap['email'].value = username + '@gmail.com';
		this.inputMap['password'].value = password;
		this.inputMap['password2'].value = password;
	}
	
	generatePassword() {
		const characters = [
			{ pick: 3, text: '1234567890' },
			{ pick: 11, text: 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXZ' },
			{ pick: 2, text: '!$@?.,' }
		];
		
		const maxLength = characters.reduce((total, charOption) => total + charOption.pick, 0);
		
		let i = 0;
		let res = "";
		let index;
		while(i < maxLength) {
			index = Math.floor(characters.length * Math.random());
			const charOption = characters[index];
			res += charOption.text[Math.floor(charOption.text.length * Math.random())];
			charOption.pick--;
			if (charOption.pick === 0) {
				characters.splice(index, 1);
			}
			i++;
		}
		
		return res;
	}
	
	generateQuestion() {
		const rnd1 = Math.floor(Math.random() * 10);
		const rnd2 = Math.floor(Math.random() * 10);
		const opSign = ['+', '*'][Math.floor(Math.random() * 2)]
		
		if (opSign === '+'){
			this.answer = rnd1 + rnd2;
		} else {
			this.answer = rnd1 * rnd2;
		}
		
		const question = document.getElementById('RegQuestion');
		question.textContent = `${rnd1} ${opSign} ${rnd2} = `;
		
		this.inputMap['answer'].value = '';	
	}
	

	validation(values) {
		const alphaNumRGX=/^[a-z\d]+$/;
		const passwdRGX=/^[a-z\d\.\,\@\!\$\?]+$/i;
		const emailRGX = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		const errors = [];
		
		if (!alphaNumRGX.test(values.username) || values.username.length < 6 || values.username.length > 32) {
			errors.push("Username must be between 6-32 character, lowercase letters or numbers!");
		}
		
		if (!passwdRGX.test(values.password) || values.password.length < 6 || values.password.length > 32) {
			errors.push("Password must be between 6-32 character, lowercase, uppercase letters, number and symbols (,.!?@)!");
		}
		
		if (!emailRGX.test(values.email)) {
			errors.push("Not valid email address!");
		}
		
		if (this.answer !== Number(values.answer)) {
			errors.push("The answer is not correct!");
		}

		if (!this.inputMap['term'].checked) {
			errors.push("You must click to the 'Term' checkbox!");
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
		
		this.register(values);
		return false;
	}
	
	async register(values) {
		try {
			const registerRequest = await fetch("../php/reg.php", {
			  headers: {
				  "Content-Type": "application/json",
				  "Accept":"text/plain;charset=UTF-8",
				},
			  method: "POST",
			  body: JSON.stringify({
				  name: values.username,
				  password1: values.password,
				  password2: values.password2,
				  email: values.email,
				  answer1: Number(values.answer),
				  answer2: this.answer,
				  term: this.inputMap['term'].checked
			  })
			});
			window.location.href = "../page/myacc.php";
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
