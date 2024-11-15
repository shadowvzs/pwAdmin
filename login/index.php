<?php include "../common/header.php"; ?>
	<div class="p-2 mx-auto rounded-xl gap-2 border-3 border-double border-slate-800" style="background-color: rgba(255,255,255,0.6); max-width: 320px; box-shadow: 5px 5px 5px 5px rgba(0,0,0,0.2); margin-top:48px;">
		<h2 class="form-label text-center"> Login </h2>
		<form id="loginForm">
			<table border="0" style="width: 100%;">
				<tr>
					<td class="form-label font-base" title="Please use only letters or numbers!"> <b>Username:</b></td>
					<td>
						<input type="text" id="LoginUser" name="username" maxlength="20" placeholder="Please enter your username" pattern="[a-z0-9]{3,32}" required title="Username must contains only lowercase letters (a-z) and numbers" style="width: 220px" />
					</td>
				</tr>
				<tr>
					<td class="form-label font-base" title="Please use only letters, numbers and few symbol!"> <b>Password:</b></td>
					<td>
						<div class="flex items-center relative">
							<input type="password" id="LoginPassword" name="password" maxlength="20" placeholder="Please enter your password" pattern="[a-z0-9A-Z\!\.\,\?\$\@]{6,32}" required title="The password must contains lower, uppercase letters, numbers, or following symbols:!.,?@" style="width: 220px" />
							<span class="toggle-password-btn absolute cursor-pointer text-xl" style="right: 9px;top: -3px;">👁</span>
						</div>
					</td>
				</tr>
			</table>
			<br>
			<footer class="flex justify-center gap-2">
				<div>
					<button type="submit" class="myButton cursor-pointer" id="LoginConfirmBtn">Login</button>
				</div>
			</footer>
		</form>
	
	</div>
	<script src="../js/login.js?t=<?php echo time(); ?>" type="text/javascript"></script>
	<script>	
		var loginFormController = new LoginFormController();
	</script>
<?php include "../common/footer.php"; ?>