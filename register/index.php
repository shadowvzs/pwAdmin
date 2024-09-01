<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="canonical” href="https://retroms.ddns.net">
		<meta name="description" content="Perfect World - Retro MS, the old version of this MMORPG game, from 2009, it is free to play oriented server, where we respect eachother." />
		<meta charset="utf-8">
		<title>Perfect World Retro PvP server</title>
		<link rel="stylesheet" type="text/css" href="./base.css">
		<link rel="stylesheet" type="text/css" href="./style.css">
		<link rel="icon" type="image/x-icon" href="../images/pwicon3.png">
	</head>
	<body>
		<table border="0" class="mx-auto" style="-webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; max-width: 1000px; width: 100%;">
			<tr>
				<td width="99" align="center" style="text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:white;">
					<b><span style="color: #ffff88">Perfect</span> <span style="color: #ffff99">World </span><span style="color: #ffffaa">RetroMS</span></b>
					<br><br><b><span style="color: #ffffcc">Wodan</span></b></td>
				<td align="center"><img src="../images/banner.jpg" alt="Perfect World RetroMS" border="0" /></td>
				<td width="99" align="center" style="text-shadow: 2px 2px 5px #000, 0px 0px 1px #000; color:white;">
					<b><span style="color:#ffff99">Three race</span></b><br>
					<b><span style="color:#ffffaa">Six class</span></b><br><br>
					<b><span style="color:#ffffcc">Lv 79 & 100</span> <span style="color:#ffffdd">skills + Elfs</span></b></td>
			</tr>
		</table>
		<br/><br/>

		<div class="flex flex-wrap p-6 justify-center mx-auto rounded-xl gap-8 border border-solid border-slate-800" style="background-color: rgba(0,0,0,0.2); max-width: 768px;">
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
								<span id='RegQuestion'></span>
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
							<button type="button" class="myButton" id="RegBackBtn"><a href="../">Back</a></button>
						</div>
						<div>
							<button type="submit" class="myButton" id="RegConfirmBtn">Confirm</button>
						</div>
						<div>
							<button type="button" class="myButton" id="RegAutofillBtn">Autofill</button>
						</div>
					</footer>
				</form>
			</div>
		</div>
	<script src="./register.js?t=<?php echo time(); ?>" type="text/javascript"></script>
	<script>	
		var regFormController = new RegFormController();
	</script>
	</body>
</html>