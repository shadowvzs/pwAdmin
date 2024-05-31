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
		<a href="https://discord.gg/r8MtRK37wX" target="_blank" rel="noopener noreferrer"><strong>discord.gg/r8MtRK37wX</strong></a>
		<br><br>
		<b>Download</b><br>
		<a href="javascript:void(0);" onClick="ClrWin();ShowPage('./page/downloads.php');"> <strong>PW MS Client</strong> </a> </font>
	</aside>

	<main style="padding: 16px;">
		<div id="newsDiv">
			<div id="nHeaderDiv"> Last News </div>
			<div id="nContDiv">
				<table border='0'><tr><td><b>2024.04.29: Coin quest & World boss update</b></td></tr>
					<tr><td> - Punish quests now gives good coin rewards (500k - 1.2m) at 118 856 37, 129 856 37, 129 861 37 </td></tr>
					<tr><td> - Added many world boss to the Chrono 4 (also Nameless boss) - regular 1 day respawn time</td></tr>
				</table>
				<br><br>
				<table border='0'><tr><td><b>2024.04.16: Gear update/b></td></tr>
					<tr><td> - Added new Gr16 WarSoul weapon (weapon list is on discord) and armors at 553 653 27</td></tr>
					<tr><td> - Added item reroll furnice for few existing items, to get the max stat with reroll at 572 661 21</td></tr>
				</table>
				<br><br>
				<table border='0'><tr><td><b>2024.04.05: Hourly Online Reward Changes</b></td></tr>
					<tr><td> - Increased drastically the rewards for online hours, but each player should get only for 1 account</td></tr>
					<tr><td> - On the discord you must send me the list with your main username and the usernames of your alts</td></tr>
					<tr><td> - The script will go over on all online account and on all their roles, and select the role with the highest score and add the reward to you main account!</td></tr>
					<tr><td> New Formula:</td></tr>
					<tr><td>
							- <b>10 PW gold + 10 WebShop point</b> - without culti<br/>
							- <b>15 PW gold + 15 WebShop point</b> - Lv 9 culti + Lv9+<br/>
							- <b>20 PW gold + 20 WebShop point</b> - Lv19 culti + Lv19+<br/>
							- <b>25 PW gold + 25 WebShop point</b> - Lv29 culti + Lv29+<br/>
							- <b>30 PW gold + 30 WebShop point</b> - Lv39 culti + Lv39+<br/>
							- <b>35 PW gold + 35 WebShop point</b> - Lv49 culti + Lv49+<br/>
							- <b>40 PW gold + 40 WebShop point</b> - Lv59 culti + Lv59+<br/>
							- <b>45 PW gold + 45 WebShop point</b> - Lv69 culti + Lv69+<br/>
							- <b>50 PW gold + 50 WebShop point</b> - Lv79 culti + Lv79+<br/>
							- <b>60 PW gold + 60 WebShop point</b> - 1st Holy/Dark + Lv89+<br/>
							- <b>65 PW gold + 65 WebShop point</b> - 2nd Holy/Dark + Lv99+<br/>
							- <b>70 PW gold + 70 WebShop point</b> - 3rd Holy/Dark + Lv99+<br/>
					</td></tr>
				</table>
				<br><br>
				<table border='0'><tr><td><b>2024.03.31: Update</b></td></tr>
					<tr><td> - Summon card item now contain the instance and coordinates to the Summon Robot</td></tr>
					<tr><td> - Summon Boss quest give now 7 reward cards instead of 1</td></tr>
					<tr><td> - Final Card boss (nameless) now can drop high level dragon orb too (8-12)</td></tr>
					<tr><td> - Revive/Resurrection scroll cooldown was drastically decreased</td></tr>
					<tr><td> - Dragon Orbs can get via Cinny npc (visa-versa Retro Coin - Dragon Orb) </td></tr>
				</table>
				<br><br>
				<table border='0'><tr><td><b>2024.03.22: Update</b></td></tr>
					<tr><td> - Cinny has now iton blood table rewards (Fb19-79)!</td></tr>
					<tr><td> - Added Blessing Box Lv1 - Lv12, middle ADC PW School Teacher at 553 651</td></tr>
				</table>
				<br><br>
				<table border='0'><tr><td><b>2024.03.10: Update</b></td></tr>
					<tr><td> - Minny nexchange more thing with retroCoin also you can trade back the gr12 stones at him</td></tr>
					<tr><td> - Spiders at 697 667 now drop only Fortune Stones (avg 2 per mob)</td></tr>
					<tr><td> - WB boss drop was a bit increased</td></tr>
					<tr><td> - Wanted (can get from Minny) give Socket Stone, ex. for lv90+ it gives 1</td></tr>
					<tr><td> - All FB give RetroCoin for the Iron Blood Table quests</td></tr>
				</table>
				<br><br>
				<table border='0'><tr><td><b>2024.03.09: Update</b></td></tr>
					<tr><td> - Minny (NPC near the West ADC Banker) now sell oracle/heroism/craft mats/hiero etc for coin!</td></tr>
					<tr><td> - Added socket stone and refine transfer drop for spiders at 697 667</td></tr>
				</table>
				<br><br>
				<table border='0'><tr><td><b>2024.03.03: Update</b></td></tr>
					<tr><td> - HH boss drop rate drastically increased, removed the useless items from drop</td></tr>
					<tr><td> - World Bosses drops lucky packs, dragon orbs, Gr12 and Vit soulstones</td></tr>
					<tr><td> - Card final boss drops def/att lv stone too, not only Warsoul mats</td></tr>
					<tr><td> - At West ADC outskirt added stake</td></tr>
					<tr><td> - Item mall: Nirvana pill removed, refine item/hiero price adjustment</td></tr>
					<tr><td> - Netherbeast City [weekly - Wednesday] event rewards exchange rate increased by 10x</td></tr>
					<tr><td> - Asura path reward exchange was adjusted and WarSong/Cube neck craftable there with m. chip+event items</td></tr>
					<tr><td> - Daily Attendance/PW School Teacher exchange was changed and now you can get the gr16 ring for long time playing</td></tr>
					<tr><td> - Chrono Bosses drop lucky packs, dragon orbs, gr12 stones and attack/def level stone</td></tr>
					<tr><td> - Chrono Elder (card boss exchanger npc) was added near to the West ADC banker</td></tr>
					<tr><td> - Token exchange was a bit changed (m.chip, rep craft time faster, a lil more expensive), gr7 stones became cheaper</td></tr>
				</table>
				<br><br>
				<table border='0'><tr><td><b>2024.02.25: Client update</b></td></tr>
					<tr><td> - few heroism and oracle was added into the item mall</td></tr>
					<tr><td> - Horse Race (racing event, each day 2x) exp reward was increased, now give coin rewards too and chance to gr8 stones</td></tr>
					<tr><td> - increased the Chrono/OHT 3 map monsters exp by 2x (you can do zhen party there)</td></tr>
					<tr><td> - card bosses have chance to drop gr12 stones</td></tr>
					<tr><td> - Every hour a script is running and rewards the online players with 7 PW-Gold and 5 WebShop point</td></tr>
				</table>
				<br><br>
				
				<table border='0'><tr><td><b>2024.02.23: Client update</b></td></tr>
					<tr><td> - webshop oracle, scroll, authenticate/heroism price and text was adjusted</td></tr>
					<tr><td> - oracle, scroll, authenticate/heroeism reward was increased by 5x, heroism/authenticate give rep as well</td></tr>
					<tr><td> - bh reward was increased</td></tr>
					<tr><td> - daily stone quest rewards was increased</td></tr>
					<tr><td> - daily PW History traveller quest gives alot coin if you finish it (adc alder, the amerigo)</td></tr>
					<tr><td> - daily ashura initial  items was increased by 10x and exp exchange rate was increased too</td></tr>
					<tr><td> - weekly wushu/martial arts event give now bigger exp (ex. 12 mil exp for the 1st place at 90+ AND 50-150 Lucky Coral pack)</td></tr>
					<tr><td> - card bosses can drop Dragon Orbs (1-5 star, depends which instance), the last boss drop gr16 Warsoul Weapon materia </td></tr>
					<tr><td> - cube have increase reward also now you will get 5 Rot. Gear instead of 1 (you need 30 for grade 14 neck)</td></tr>
				</table>
				<br><br>
				<table border='0'><tr><td><b>2024.02.11: Client update</b></td></tr>
					<tr><td> - Fix: UI for the player shop/stall, wine sales in West ADC</td></tr>
					<tr><td> - Added heaven fragment into TSC skill crafting NPC sale list</td></tr>
					<tr><td> - Adjusted the boxes price in the Item Mall </td></tr>
					<tr><td> - Item mall: added coral pack, 1m spirit pack, 10 and 100m exp pack </td></tr>
				</table>
				<br><br>

				<table border='0'><tr><td><b>2024.02.09: Changes & Discord Events</b></td></tr>
					<tr><td> - Starter gold increased from 50 -> 100 Gold</td></tr>
					<tr><td> - Vote reward increased from 25 -> 50 Gold</td></tr>
					<tr><td> - <b>Vote guide video: <a href="https://youtu.be/5PiYd6XS5pI" rel="noopener noreferrer" target="_blank">https://youtu.be/5PiYd6XS5pI</a> </b></td></tr>
					<tr><td> - WebShop: Added a ton of item, mats etc </td></tr>
					<tr><td> - Discord Event: Share us on FB, Twitter for Gold </td></tr>
					<tr><td> - Discord Event: Reach lv101 for Gold + Webshop points </td></tr>
				</table>
				<br><br>
				
				<table border='0'><tr><td><b>2024.02.05: Discord Events</b></td></tr>
					<tr><td> - Weekly screenshot event - for gold prize</td></tr>
					<tr><td> - Referral event - for 7 days gear (you choose)</td></tr>
				</table>
				<br><br>
				
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
			$isOnline = ServerOnline($con);
			if ($isOnline!==false){
				echo "<b>Online</b>";
				if (is_numeric($isOnline)) {
					echo "<br/><center> ($isOnline player) </center>";
				}
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