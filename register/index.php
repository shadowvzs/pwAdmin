<?php include "../common/header.php"; ?>

		<div class="flex flex-wrap p-6 justify-center mx-auto rounded-xl gap-8 border-3 border-double border-slate-800" style="background-color: rgba(255,255,255,0.6); max-width: 768px; box-shadow: 5px 5px 5px 5px rgba(0,0,0,0.2)">
			<div class="flex gap-2 flex-col">
				<img src="../../images/spirit.png" alt="Dawn of Spirits" />
				<h2 class="font-bold text-center my-0"> RetroMS - 1.4.1</h2>
				<h3 class="font-bold text-center my-0"> Dawn of Spirits </h3>
			</div>
			<div>
				<h2 class="form-label text-center"> Registration </h2>
				<form id="regForm">
					<table border="0">
						<tr>
							<td class="form-label font-base" title="Please use only letters or numbers!"> <b>Username:</b></td>
							<td>
								<input type="text" id="RegUser" name="username" maxlength="20" placeholder="Allowed: a-z0-9" pattern="[a-z0-9]{3,32}" required title="Username must contains only lowercase letters (a-z) and numbers" />
							</td>
							<td>
								<span class="tooltip" title="Username must contains only lowercase letters (a-z) and numbers" onclick="alert('Username must contains only lowercase letters (a-z) and numbers')">i</span>
							</td>
						</tr>
						<tr>
							<td class="form-label font-base" title="Please use only letters, numbers, dot or @!"> <b>Email:</b></td>
							<td>
								<input type="text" id="RegMail" name="email" maxlength="50" placeholder="Use a valid email address!" required title="Email is for the emergency case when you forget your password!" />
							</td>
							<td>
								<span class="tooltip" title="Email is for the emergency case when you forget your password!" onclick="alert('Email is for the emergency case when you forget your password!')">i</span>
							</td>
						</tr>
						<tr>
							<td class="form-label font-base" title="Please use only letters, numbers and few symbol!"> <b>Password:</b></td>
							<td>
								<div class="flex items-center relative">
									<input type="password" id="RegPass1" name="password" maxlength="20" placeholder="Allowed: a-zA-Z0-9$!.,?" pattern="[a-z0-9A-Z\!\.\,\?\$\@]{6,32}" required title="The password must contains lower, uppercase letters, numbers, or following symbols:!.,?@" />
									<span class="toggle-password-btn absolute cursor-pointer text-xl" style="right: 3px;top: -3px;">👁</span>
								</div>
							</td>
							<td>
								<span class="tooltip" title="The password can contains lower, uppercase letters, numbers, or following symbols:!.,?@" onclick="alert('The password can contains lower, uppercase letters, numbers, or following symbols:!.,?@')">i</span>
							</td>
						</tr>
						<tr>
							<td class="form-label font-base" title="This field must be same as password!"> <b>Pass. again:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
							<td>
								<input type="password" id="RegPass2" name="password2" maxlength="20" placeholder="Allowed: a-zA-Z0-9$!.,?" pattern="[a-z0-9A-Z\!\.\,\?\$\@]{6,32}" required title="This field must be same as password!" />
							</td>
							<td>
								<span class="tooltip" title="This field must be same as password!" onclick="alert('This field must be same as password!')">i</span>
							</td>
						</tr>
						<tr>
							<td class='form-label font-base'> <b>Question:</b></td>
							<td style='font-size:16px;text-shadow: 2px 2px 5px #000, 0px 0px 1px #000;'>
								<span id='RegQuestion' style="display:inline-block;vertical-align:middle"></span>
								<span>
									<input type='text' id='RegAnswer' name='answer' maxlength='5' class='w-10' title="Write here the answer" required />
								</span>
							</td>
							<td></td>
						</tr>
					</table>
					<br>
					<div class='flex gap-2' style="align-items: normal" title="We not use those information for any ads or anything, also you can provide fake email address and we strongly recommend do not share any personal information in the game and respect the other player! If you want you can request the account deletion in case you not play anymore.">
						<input type="checkbox" id="RegTerm" name="term" required> <label for="RegTerm"> Agree with terms & conditions</label>
					</div>
					<br /><br />
					<footer class="flex justify-center gap-2">
						<div>
							<button type="submit" class="myButton cursor-pointer" id="RegConfirmBtn">Confirm</button>
						</div>
						<div>
							<button type="button" class="myButton cursor-pointer" id="RegAutofillBtn">Autofill</button>
						</div>
					</footer>
				</form>
			</div>
		</div>
			<script src="../js/register.js?t=<?php echo time(); ?>" type="text/javascript"></script>
	<script>	
		var regFormController = new RegFormController();
	</script>
<?php include "../common/footer.php"; ?>