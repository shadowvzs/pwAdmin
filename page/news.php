<html>
<head>
</head>
<body>
<?php
include "../config.php";
include "../basefunc.php";
?>
<center>
<font size='7' face='arial' color='white' style='text-shadow: 0 1px 0 #ccc,
				0 2px 0 #c9c9c9,
               0 3px 0 #bbb,
               0 4px 0 #b9b9b9,
               0 5px 0 #aaa,
               0 6px 1px rgba(0,0,0,.1),
               0 0 5px rgba(0,0,0,.1),
               0 1px 3px rgba(0,0,0,.3),
               0 3px 5px rgba(0,0,0,.2),
               0 5px 10px rgba(0,0,0,.25),
               0 10px 10px rgba(0,0,0,.2),
               0 20px 20px rgba(0,0,0,.15),    
			   1px 1px 2px black, 0 0 25px #000, 0 0 5px #000;
			   ;'>
			   Welcome on Perfect World Retro MS </font>
<br><br>
<div id="bigPFrameDiv">
	<div id="PhotoFrame" style='z-index:1; opacity: 1;'><img src='./images/photo1.jpg' height='200'></div>
	<div id="PhotoFrame" style='top: 20px; left:-250px; -ms-transform: rotate(-7deg); -webkit-transform: rotate(-7deg); transform: rotate(-7deg);'><img src='./images/photo2.jpg' height='200'></div>
	<div id="PhotoFrame" style='top: 20px; left: 250px; -ms-transform: rotate(7deg); -webkit-transform: rotate(7deg); transform: rotate(7deg);'><img src='./images/photo3.jpg' height='200'></div>
</div>
<br>

<br>
</center>

<div style="display: flex; justify-content: space-between;">
	<aside style="padding: 0 16px;">
		<b><h3>Important links:</h3></b>
	
		<b>Discord</b> <br>
		<a href="https://discord.gg/aapqeqP4" target="_blank" rel="noopener noreferrer"><strong>discord.gg/aapqeqP4</strong></a>
		<br><br>
		<b>Download</b><br>
		<a href="javascript:void(0);" onClick="ClrWin();ShowPage('./page/downloads.php');"> <strong>PW MS Client</strong> </a> </font>
	</aside>
	
	<main style="padding: 16px;">
		<div id="newsDiv">
			<div id="nHeaderDiv"> Last News </div>
			<div id="nContDiv">
				<table border='0'><tr><td><b>2024.02.03: Voting is back (12 hour cooldown)</b></td></tr>
					<tr><td> - Login then then click to vote</td></tr>
					<tr><td> - You will redirected to xtremetop100, vote there</td></tr>
					<tr><td> - after you vote then click again to our server on the xtremtop list</td></tr>
					<tr><td> - after 5 minutes, relog into the game and you will get 25 gold</td></tr>
				</table>
				<br><br>
				
				<table border='0'><tr><td><b>2024.02.01: Gold adjustment</b></td></tr>
					<tr><td> - Discord link  UPDATED, this one will not expire</td></tr>
					<tr><td> - Auto gold for new account was reduced to 50 from 250 gold</td></tr>
					<tr><td> - Working on the vote system for gold reward, till that write me on discord</td></tr>
					<tr><td> - Until the voting system works the online players each day will be rewarded randomly</td></tr>
				</table>
				<br><br>

				<table border='0'><tr><td><b>2024.01.23: v2 - Patch for the new players</b></td></tr>
					<tr><td> - Every new account get 250 gold automatically</td></tr>
					<tr><td> Server shop changes:</td></tr>
					<tr><td> - Added 1 mill exp pill for 250 Gold</td></tr>
					<tr><td> - Added HH90 heavy/light/mage armor set for 250 Gold</td></tr>
					<tr><td> - Added Nirvana cultivation pill for 250 Gold</td></tr>
					<tr><td> - Some mount/ride, box price adjusted</td></tr>
					<tr><td> - Test category is removed</td></tr>
				</table>
				<br><br>

				<table border='0'><tr><td><b>2024.01.15: Initial update</b></td></tr>
					<tr><td> - Server started - 1.4.1 Age of the Elemental Elfs</td></tr>
					<tr><td> - Exp, Sp, Gold drop: 1x</td></tr>
					<tr><td> - Regular items in the item mall [O]</td></tr>
					<tr><td> - Race: 3 - Beastking, Winged Elves, Human</td></tr>
					<tr><td> - You can request help on discord</td></tr>
				</table>
			</div>
		</div>
	</main>
	
	<aside style="padding: 16px;">
		<h3>Server Status:</h3>
		Wodan: 
		<?php
			$con = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
			if (ServerOnline($con)!==false){
				echo "<b>Online</b>";
			}else{
				echo "<i>Offline</i>";
			}
			mysqli_close($con);
		?>
	</aside>
</div>

<br><br>
</body>
</html>