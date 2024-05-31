<html>
<head>


</head>
<body>

<?php
include "../config.php";
	echo"<div id='MainTab2' style='display:none;padding: 32px;'>";
	echo"
	<div id='ClassButtons'>
	<div style='width:100%;border:0px solid #000;'><center><table id='ButtonRow'><tr>";
	$i = 1; 
		while($i <= count($PWclass)) {
		echo "<td><a href='javascript:void(0);' class='myButtonGrey' onclick=\"showClassTab('".$i."');\">".$PWclass[$i]."</a></td>";
		$i++;
	} 
	echo"</tr></table></center></div><br>
	</div>";
		
	echo "
	<div id='ClassTab1'>
	<img src='./images/WR.png' valign='top' style='vertical-align:middle;float:left;'>
	<font size='4' color='#000044'><b>Class:</b></font> ".$PWclass[1]."<br>
	<font size='4' color='#000044'><b>Race:</b></font> Human <br><br>
	<font size='4' color='#000044'><b>Weapons</b></font> <br>
	*Warriors can use every weapon with the exception of Crossbows, Slingshots. <br><br>
	<b><img src='./images/weapons/D_Axe.png' style='vertical-align:middle;'> Axe and Hammers:</b> Slowest attack speed, most aoe skill, Lowest to Highest damage, high strength requiment <br>
	<b><img src='./images/weapons/Spear.png' style='vertical-align:middle;'> Spear and Long weapons:</b> Medium attack speed, ranged skill and longer weapon range, Low to High damage<br>
	<b><img src='./images/weapons/D_Sword.png' style='vertical-align:middle;'> Sword and Blades:</b> Medium attack speed, Medium to High damage <br>
	<b><img src='./images/weapons/Glove.png' style='vertical-align:middle;'> Glove and Claws:</b> Fast attack speed, Low damage, high agility requiment<br><br><br>
	<font size='4' color='#000044'><b>Armor</b></font><br>
	Warriors can use Light Armor, however, because most of their combat is up close, Heavy Armor is preferable. Slotting HP stones to boost HP is a must. <br><br>
	<font size='4' color='#000044'><b>At a glance</b></font><br>
	The Warrior is a melee damage class. Always at the front of the battle, Warriors can hold back a handful of players with their powerful AoE skills. They are the most versatile of all characters in the game. Not only because they have such a wide variety of skills, but also because of their wide variety in weapon choice and stats. Warriors are the open canvas of PW to form and build as you choose.  <br><br>
	<b><font color='#3333ff' size='4'>PvE</font></b></b><br>
	In solo PvE, Warriors are probably the strongest class in the game, as their AoE skills allow them to take on multiple enemy mobs at the same time. In a party, Warriors serve 3 functions: <br>
	&nbsp;&nbsp;&nbsp;&nbsp; 1. <i><b>Tank</b> - In parties with a Werebeast, they can serve as an off Tank in the event the Werebeast goes down during a boss fight. If a party is unable to find a Werebeast, the Warrior can take up the mantle with their high defense and HP. <br></i>
	&nbsp;&nbsp;&nbsp;&nbsp; 2. <i><b>Direct Damage</b> - A Warrior should use the strongest skills they have to attack the boss, while also being mindful to not take agro from the primary tank. Three standouts are: Axe, Spear, or Sword ulti are all great skills, especially Axe ulti skill, as it boosts the damage the boss takes by 55% at level 1, and by 100% at level 10.<br></i>
	&nbsp;&nbsp;&nbsp;&nbsp; 3. <i><b>Crowd Control</b> - Using AoE skills that stun such as Lion Roar, the Axe AoE attacks, and the Spear / Sword ranged skills make protecting the members of your party a much easier task<br><br></i>
	<b><font color='#ff3333' size='4'>PvP</font></b><br>
	Warriors differ a lot in PvP depending on which path you take. <br>
	&nbsp;&nbsp;&nbsp;&nbsp; <i><b>Axe Warrior</b> are most effective on stunning the enemy and reducing their defense. This path also contains some very powerful AoE's for crowd control. <br></i>
	&nbsp;&nbsp;&nbsp;&nbsp; <i><b>Longarm weapon</b> Warrior focuses on damage over time that stack with their normal attacks. Spear Warriors have the best range of the paths, this makes them ideal for attacking ranged classes. <br></i>
	&nbsp;&nbsp;&nbsp;&nbsp; <i><b>Sword Warriors</b> are the best path for fighting single targets, but suffer against groups of fighters. <br></i>
	&nbsp;&nbsp;&nbsp;&nbsp; <i><b>Claw Warrior</b> are most effective when target cannot move because of short range but high attack speed. <br><br></i>
	Once Warriors reach the dark or holy cultivation level these paths change. Warriors who plan to PvP frequently opt to go for the Dark path, in order to take advantage of the increased attack speed that the Dark Fury Burst gives. When they have attained this cultivation level, Warriors utilize Glove or Claw weapons for single target PvP, and Axes for group PvP. Most players also fight with two or more weapons to take advantage of the stronger skills in that weapons path. <br><br>
	For example, as a Claw Warrior you might consider using use an axe weapon to take advantage of the extra stun skill that the axe path has. When you have an enemy in retreat, use the axe for stunning, and when the enemy is stunned, you can switch back to fists for the faster damage. This is really easy since you can drag your weapon onto your speedbar for quick and easy switching. 		<br><br>
	<font size='4' color='#444400'><b>Territory War</b></font><br> 
	In Territory Wars, the Warrior serves as the Tank class due to their powerful AoE's and stuns. Because of this they are usually teamed up with a Elf Priest to better their chances for survival as more often than not, they are one of the primary targets by enemy squads. 
	</div>";

	echo"
	<div id='ClassTab2' style='display:none;'>
	<img src='./images/MG.png' valign='top' style='vertical-align:middle;float:left;'>
	<font size='4' color='#000044'><b>Class:</b></font> ".$PWclass[2]."<br>
	<font size='4' color='#000044'><b>Race:</b></font> Human <br><br>
	<font size='4' color='#000044'><b>Weapons</b></font> <br>
	<b><img src='./images/weapons/Staff.png' style='vertical-align:middle;'> Staff:</b> Low to High Damage <br>
	<b><img src='./images/weapons/Quoit.png' style='vertical-align:middle;'> Quoit:</b> Medium to High damage<br>
	<b><img src='./images/weapons/M_Sword.png' style='vertical-align:middle;'> Magic Sword:</b> Medium to High damage <br>
	<b><img src='./images/weapons/Wand.png' style='vertical-align:middle;'> Wand:</b> Medium Damage  <br><br>
	<font size='4' color='#000044'><b>Armor</b></font><br>
	Mages commonly use Magic Armor, but some people add more stats to Agility and Strength in order to use Light Armor, until they reach the high levels. Mages typically use either physical or HP stones depending on which armor they use. Mages go after armor stats that augment their channeling ability in armor to make their spells much more effective.  <br><br>
	<font size='4' color='#000044'><b>At a glance</b></font><br>
	The hardest hitting magical class, Magicians can harness one of three elements to kill you from a distance. Their slow beginnings may be a bit of a turn off, but once fledgling Mages make it to mid to late levels, their skills more than make up for the humble beginnings.  <br><br>
	<b><font color='#3333ff' size='4'>PvE</font></b></b><br>
	Mages tend to level quickly because of their high damage output, almost as quickly as the Werefox class. However, because of their low HP and low physical defense, they tend to die a lot early on because of this, which makes running in a party with someone who can tank a bit more advisable. However, once they get going, look out. These guys will hit har, and wipe out entire groups with their powerful AoE spells. <br><br>
	<b><font color='#ff3333' size='4'>PvP</font></b><br>
	In regular PvP, Magicians are at a disadvantage in the early levels. The best thing a Mage can do early on is team up with a strong melee class companion and team up on stronger foes. Once a Mage approaches the higher levels, they become a force to be reckoned with with spells that can sometimes one shot other classes combined with their elemental defense debuff! <br><br>
	<font size='4' color='#444400'><b>Territory War</b></font><br> 
	In Territory Wars, Mages are far more dangerous, as the large group of people fighting make them harder to find. Theirs is a task to kill the catapult carriers. With their high damage and their AoE's this is a relatively easy task. Especially since Werebeasts are not magical damage defensive in the first place. 
	</div>";

	echo"
	<div id='ClassTab3' style='display:none;'>
	<img src='./images/WB.png' valign='top' style='vertical-align:middle;float:left;'>
	<font size='4' color='#000044'><b>Class:</b></font> ".$PWclass[3]."<br>
	<font size='4' color='#000044'><b>Race:</b></font> Beastkind <br><br>
	<font size='4' color='#000044'><b>Weapons</b></font> <br>
	<b><img src='./images/weapons/P_Axe.png' style='vertical-align:middle;'> Poleaxe:</b> Fast attack speed, Low to High damage <br>
	<b><img src='./images/weapons/P_Ham.png' style='vertical-align:middle;'> Polehammer:</b> Fast attack speed, Low to High damage <br>
	<b><img src='./images/weapons/D_Axe.png' style='vertical-align:middle;'> Dual Axes:</b> Medium attack speed, Medium to High damage <br>
	<b><img src='./images/weapons/D_Ham.png' style='vertical-align:middle;'> Dual Hammers:</b> Medium attack speed, Medium to High damage<br><br>
	<font size='4' color='#000044'><b>Armor</b></font><br>
	Werebeast are a Tank class making heavy armor a must. To increase their damage intake they also can equip accessories that augment magical protection and physical attacks. Their armor is usually slotted with HP stones, for the increased HP. <br><br>
	<font size='4' color='#000044'><b>At a glance</b></font><br>
	Werebeast are essential as the Tank in any party. They have very high HP as well as very high physical defense. They can level the field of play with some of the most powerful AoE skills in the game making them the most sought after addition to any party. Werebeasts also have the added bonus of being able to transform into tigers, which increases their HP and movement speed. <br><br>
	<b><font color='#3333ff' size='4'>PvE</font></b></b><br>
	A werebeast can be very slow to level, at low levels due to his low accuracy, However, the level 29 a skill doubles the Werebeasts accuracy making the task of leveling much easier. The barbarian is also not a good fighter against magical attack mobs due to low magical defense. However in a party for dungeons, raids, or boss fights, the werebeast is invaluable. A werebeast's job in a party is simply to take damage and make sure that only he takes damage. The Werebeasts skills: Ripping Bite and Roar allow the Werebeasts to hold agro when the situation calls for it. <br><br>
	<b><font color='#ff3333' size='4'>PvP</font></b><br>
	PvP is where Werebeasts really shine. The more a barbarian levels, the stronger he gets. They can hold their own wherever they go, but at later levels, they become almost impossible to kill one vs. one. The only enemy werebeasts really need to fear then are magicians, but even they can have a hard time killing werebeasts without backup. <br><br>
	<font size='4' color='#444400'><b>Territory War</b></font><br> 
	In Territory Wars, werebeast's role is usually to act as catapult carriers. This is an important task, as most of the damage to the enemy comes from the catapults and are vital in order to win. As attacker the werebeasts are in a class on their own with hard hitting AoE skills Perdition and Sunder because of these skills, they usually find themselves the primary targets when the attack commences. 
	</div>
	";

	echo"
	<div id='ClassTab4' style='display:none;'>
	<img src='./images/WF.png' valign='top' style='vertical-align:middle;float:left;'>
	<font size='4' color='#000044'><b>Class:</b></font> ".$PWclass[4]."<br>
	<font size='4' color='#000044'><b>Race:</b></font> Beastkind <br><br>
	<font size='4' color='#000044'><b>Weapons</b></font> <br>
	<b><img src='./images/weapons/Staff.png' style='vertical-align:middle;'> Staff:</b> Low to High Damage <br>
	<b><img src='./images/weapons/Quoit.png' style='vertical-align:middle;'> Quoit:</b> Medium to High damage<br>
	<b><img src='./images/weapons/M_Sword.png' style='vertical-align:middle;'> Magic Sword:</b> Medium to High damage <br>
	<b><img src='./images/weapons/Wand.png' style='vertical-align:middle;'> Wand:</b> Medium Damage  <br><br>
	<font size='4' color='#000044'><b>Armor</b></font><br>
	Werefoxs can use Heavy Armor or Magic Armor depending on the playstyle due to their versatility. However, their generally low HP make slotting stones that augment you HP levels paramount.  <br><br>
	<font size='4' color='#000044'><b>At a glance</b></font><br>
	Known as the Pet Tamer class, Werefoxs are very unique in that they are the only class in the game that can wield Battle Pets. Because of this, many Werefoxs usually work alone. However, don't let that fool you into thinking that a Werefox isn't sought after in a Party. Their Debuffs more than hold their own and they have some of the highest damage ratings of any class.   <br><br>
	Werefoxs also have the ability to transform themselves into a fox. Upon doing so increases their physical defense and accuracy. <br><br>
	<b><font color='#3333ff' size='4'>PvE</font></b></b><br>
	Werefoxs usually find themselves questing in PvE alone. This is primarily due to the pets that they have at their disposal. Their pets are able to fill the role of Tank for the Werefox, so all incoming damage is rerouted to the pet making death a rarity.  <br><br>
	<b><font color='#ff3333' size='4'>PvP</font></b><br>
	Werefoxs are absolutely wicked in PvP; with their pet dealing physical damage and tanking support, and the Werefox assisting with debuffs and magical damage, fighting a Werefox is the equivalent of fighting 2 players at once. Skilled players encountering Werefoxs in PvP will concentrate their attacks on the Werefox rather than its pet. Players selecting a Werefox for the first time should make it a practice to develop good potion and remedy management skills.  <br><br>
	<font size='4' color='#444400'><b>Territory War</b></font><br> 
	In Territory Wars, Werefoxs really come in handy. Their skill removes all positive buffs from a target, which sounds kind of useless, but when you square off against a Werebeast, it's essential since they derive their power from their buffs. Couple this with their Amplify Damage skill which increases a targets received damage by 20% and Werebeasts go down easy. 
	</div>
	";

	echo"
	<div id='ClassTab6' style='display:none;'>
	<img src='./images/EP.png' valign='top' style='vertical-align:middle;float:left;'>
	<font size='4' color='#000044'><b>Class:</b></font> ".$PWclass[6]."<br>
	<font size='4' color='#000044'><b>Race:</b></font> Wing Elf <br><br>
	<font size='4' color='#000044'><b>Weapons</b></font> <br>
	<b><img src='./images/weapons/Staff.png' style='vertical-align:middle;'> Staff:</b> Low to High Damage <br>
	<b><img src='./images/weapons/Quoit.png' style='vertical-align:middle;'> Quoit:</b> Medium to High damage<br>
	<b><img src='./images/weapons/M_Sword.png' style='vertical-align:middle;'> Magic Sword:</b> Medium to High damage <br>
	<b><img src='./images/weapons/Wand.png' style='vertical-align:middle;'> Wand:</b> Medium Damage  <br><br>
	<font size='4' color='#000044'><b>Armor</b></font><br>
	Elf Priest are essential for any party and need the most magical power they can get their hands on, making Magic Armor the only choice for them. Soulstones that augment their physical defense, magical attack and increase their HP are paramount. <br><br>
	In addition, Elf Priests should also equip magic rings and accessories that augment their physical defense, as they can stand alone when it comes to magical defense, yet struggle in physical assaults. <br><br>
	<font size='4' color='#000044'><b>At a glance</b></font><br>
	Essential to any party, the Elf Priest is one of the most sought after classes in PW. They possess some of the strongest heals and stat buffs out of any character class, making them formidable not only in a group, but solo as well. They also have the added versatility of magical damage making them a fearsome opponent in any stage of the game. <br><br>
	<b><font color='#3333ff' size='4'>PvE</font></b></b><br>
	Because only Wing Elfs can be Elf Priests, they have the benefit of being able to fly right out of the gate, making world travel easier and less cumbersome. Couple this with their healing and buff skills, Elf Priests make some of the best PvE players out there, but in a party they truly show off their skill with the strongest party buffs in the game. <br><br>
	Players wishing to fine tune their Elf Priests as primarily heal and buff machines, should equip a wand for their weapon of choice, as wands augment their heal abilities. Players looking for that combative edge should use a magic sword or quoit.  <br><br>
	Mages tend to level quickly because of their high damage output, almost as quickly as the Werefox class. However, because of their low HP and low physical defense, they tend to die a lot early on because of this, which makes running in a party with someone who can tank a bit more advisable. However, once they get going, look out. These guys will hit har, and wipe out entire groups with their powerful AoE spells. <br><br>
	<b><font color='#ff3333' size='4'>PvP</font></b><br>
	Elf Priests that go on full attack are seen as a bit of an aberration as most Elf Priests are in the back healing all of the front line attackers. When they do enter the fray, look out! Lots of their attacks come with status debuffs and sleep effects, so they can paralyze entire groups and kill them all with ease. Because of this versatility, Elf Priests usually make high priority targets on the battlefield.  <br><br>
	<font size='4' color='#444400'><b>Territory War</b></font><br> 
	In Territory Wars, Elf Priests have one of the toughest jobs, making sure their party is properly buffed and alive while making sure they themselves don't die in the process. The enemy knows, a party with a Elf Priest is a dangerous party indeed, so Elf Priests are usually targeted first. 
	</div>";

	echo"
	<div id='ClassTab5' style='display:none;'>
	<img src='./images/EA.png' valign='top' style='vertical-align:middle;float:left;'>
	<font size='4' color='#000044'><b>Class:</b></font> ".$PWclass[5]."<br>
	<font size='4' color='#000044'><b>Race:</b></font> Wing Elf <br><br>
	<font size='4' color='#000044'><b>Weapons</b></font> <br>
	<b><img src='./images/weapons/Sling.png' style='vertical-align:middle;'> Slingshots:</b> Short Range, Medium Attack Speed, Medium Damage <br>
	<b><img src='./images/weapons/Bow.png' style='vertical-align:middle;'> Bows:</b> Medium Range, slow attack Speed, Low to High damage <br>
	<b><img src='./images/weapons/XBow.png' style='vertical-align:middle;'> Crossbows:</b> Long range, slow attack speed, Low to High damage<br><br><br>
	<font size='4' color='#000044'><b>Armor</b></font><br> 
	Archers are a agility based class, making Light Armor preferential. When slotting soulstones into their gear, use HP one as they will need Physical and Magical defense. <br><br>
	<font size='4' color='#000044'><b>At a glance</b></font><br>
	Archers are a ranged physical damage dealer class. The primary role of the archer is at the back of the party unleashing a flurry of arrows upon the enemy. Not only are archers able to deal physical damage but they also deal metal magical damage. This makes them very flexible damage dealers, add their high critical hit percentage, and you have one deadly sniper. <br><br>
	<b><font color='#3333ff' size='4'>PvE</font></b></b> <br>
	Because only Wing Elfs can be Archers, they have the ability to fly from right out of the gate making travel at early levels much easier. <br>
	When solo or grinding, archers can work uninterrupted with their variety of knockback, stun, and slow skills. They also have two shields at their disposal; however, it remains means to escape damage at high levels but usually in moments of last resort. </br></br>
	Archers are good damage dealers to have in a party for instances. Their Sharptooth Arrow skill, will reduce a target's maximum HP by 16%. The effect can be increased to 20% with the holy version. Making this skill crucial for quickly dispatching bosses. With their new skill, could reduce the target max hit points by 18% as well as increase damage taken by target by 25%. <br><br>
	<b><font color='#ff3333' size='4'>PvP</font></b> <br>
	At the low to mid levels, archers are the best overall damage dealer due to their range, and fast physical/magical damage. At higher levels, when classes become more balanced, archers are most effective at removing Priests, Magicians, and Werefoxs from the field of play. <br><br>
	<font size='4' color='#444400'><b>Territory War</b></font><br> 
	In Territory Wars, Archers are crucial as they help to kill the enemies fast and push them back. The skills HP reduction skill and AOE are two of the most important skills here. their HP reduce skill are used mostly on Werebeasts to lower their max HP so they can be removed from play quickly. Their AOE skill can kill a group of weaker players or be used to destroy catapults. 
	</div>";
	
	echo"</div>";

	echo"
	<div id='MainTab1' style='display:none; padding: 32px;'>
	<img src='./images/class/Class_Guide.jpg' width='800' alt='Guide'>
	<br><br>
	<font size='5'><b>Short Class description</b></font><br><br><br>
	<img src='./images/class/wr-0.png' valign='middle' style='vertical-align:middle;'><font color='#000055'> <b>Warrior [WR]</b><br></font>
	Master of Martial Arts and excelent melee weapon fighter, most flexible class.<br>
	<font size='2' color='#0000aa'><b>Pro:</b> most flexible class with most weapon type useage, good area damage skills and can stun lock, high defense.</font><br>
	<font size='2' color='#aa0000'><b>Contra:</b> short range class, need alot vigor for combos.</font>
	<br><br>
	<img src='./images/class/mg-0.png' valign='middle' style='vertical-align:middle;'><font color='#000055'> <b>Magician [MG]</b><br></font>
	Mage class what focused to Fire, Water, Earth element magic skills and distant fight.<br>
	<font size='2' color='#0000aa'><b>Pro:</b> highest damage per hit class, good debuff and aoe skills.</font><br>
	<font size='2' color='#aa0000'><b>Contra:</b> slow casting, lack of physical attack skills, need keep distance.</font>
	<br><br>
	<img src='./images/class/wb-0.png' valign='middle' style='vertical-align:middle;'><font color='#000055'> <b>Werebeast [WB]</b><br></font>
	Melee tank class with highest HP, can transform into tiger.<br>
	<font size='2' color='#0000aa'><b>Pro:</b> good tank class, good physical defence and movement speed in tiger form.</font><br>
	<font size='2' color='#aa0000'><b>Contra:</b> short range, low magical defense, higher repair cost.</font>
	<br><br>
	<img src='./images/class/wf-0.png' valign='middle' style='vertical-align:middle;'><font color='#000055'> <b>Werefox [WF]</b><br></font>
	Caster who use nature power, tame creatures, use her tricky skills and can transform to melee fox.<br>
	<font size='2' color='#0000aa'><b>Pro:</b> debuff, purge and tricky skills, can use pets, spammable skills.</font>
	<font size='2' color='#aa0000'><b>Contra:</b> low damage per hit, harder to mastery.</font>
	<br><br>
	<img src='./images/class/ep-0.png' valign='middle' style='vertical-align:middle;'><font color='#000055'> <b>Elf Priest [EP]</b><br></font>
	Wing Elf Priest is support mage class with buff and heal skill arsenal, often core member in parties.<br>
	<font size='2' color='#0000aa'><b>Pro:</b> spamable phyisical and metal element skills, good heal, buff skills, decent debuffs.</font><br>
	<font size='2' color='#aa0000'><b>Contra:</b> mana hungry class, often primary target in pvp.</font>
	<br><br>
	<img src='./images/class/ea-0.png' valign='middle' style='vertical-align:middle;'><font color='#000055'> <b>Elf Archer [EA]</b><br></font>
	Wing Elf Archer is master of ranged weapons like bows, crossbows and slingshots, don't forget the arrows from your pocket.<br>
	<font size='2' color='#0000aa'><b>Pro:</b> highest range, critical strike rate in game, good dodge and accurancy, can use metal damage skills.</font><br>
	<font size='2' color='#aa0000'><b>Contra:</b> low dph skills, depend on luck.</font>
	</div>";
	
	echo"
		<div id='MainTab3' style='display:none;padding: 32px;'>
			Welcome on our retro server, here we explain how can you start your journey...
			<br/><br/>
			<b>Choose your path/Why do you want to play?</b><br/>
			<br/> - <b>Option 1: Nostalgia</b> - you can do the regular quests and cultivations quests, grind with the original non-repetative quests/mission in 1x exp/sp rate like in the original. It is slower but you feel the original way of the playing.
			<br/> - <b>Option 2: PvP with grind</b> - you can buy high % exp gear from the webshop and grind, or buy scroll and oracles and do them (it has 5x exp, 10x sp, 2x coin rewards). It is fast leveling but you will lack of the spirit points (sp) or costly in gold.
			<br/> - <b>Option 3: PvP but no time</b> - you can buy exp pill from the in game item mall, however it became expensive, so either you wait and do daily vote or donate. It is fast leveling, but you need to wait, join to events, spend money.
			
			<br/><br><br/><br>
			<b>WebShop:</b> We have a webshop where you can buy starter gears, hieros, mats, oracles, scrolls, elf and many more with webpoints, each player start with a fixed amount of web points and can join to the discord events for additional points.
			<br><br>
			<br><br>
			<b>FAQ:</b>
			<br><b>How can i get webpoints/pw-gold?</b>: everybody start with an initial webpoint and pw-gold, for getting more you must join to the <b><span style='color: blue'>discord events, vote, stay online (hourly you get 5), or donate</span></b> but only if you want and liek the server.
			<br><b>Which gear:</b> we have 2 gear type in the webshop: <b><span style='color: blue'>exp gear</span></b> (gear with %exp on gear), <b><span style='color: red'>quester gear</span></b> (no exp but stronger attributes)
			<br><b>Which currency do we use?:</b> we use <b><span style='color: red'>PW-Gold</span></b> for the item mall inside the game, <b><span style='color: blue'>webshop point</span></b> at the website webshop, in-game <b><span style='color: yellow'>coin</span></b> for in-game and webshop
			<br><b>I must spend money for the webshop?:</b> No, few item even buyable with your in-game character inventory coins, rest need webshop point buy you get enough at the registration, then you can get more as discord events rewards etc.
			<br><br>
			<br><br> 
			<b>Tips & Tricks</b>
			<br><br><b>How can i get PW-Gold?</b>
			<br> - join to the discord events
			<br> - stay online (hourly rewards)
			<br> - donation
			<br><br><b>How to level up fast?</b>
			<br> - buy exp gear from webshop
			<br> - buy task dice items from webshop and from in-game NPC (Minny at West ADC banker) like:
			<br> &nbsp;&nbsp;&nbsp;&nbsp; - oracle [highest exp - kill mini bosses]
			<br> &nbsp;&nbsp;&nbsp;&nbsp; - scrolls [talk with npc, fastest but lowest reward]
			<br> &nbsp;&nbsp;&nbsp;&nbsp; - heroism [decent exp/sp rewards but also give reputation as well]
			<br> &nbsp;&nbsp;&nbsp;&nbsp; <b>Important:</b> you can buy the above 3 scroll type with your inventory coins and you can earn inventory coin as I wrote in the below section
			<br> - stone quests: starts from <b>Eunice</b> <i>[530 641]</i> and <b>Rosalind</b> <i>[532 642]</i> - it rewards 5x higher exp and 10x higher sp
			<br> - events: 
			<br> &nbsp;&nbsp;&nbsp;&nbsp; - <b>Wushu / Martial Arts</b> (PvP) event, very high exp for first 3 (the Lv90+ winner get 12 mil exp), every Thursday at <i>[529 663]</i> - <a href='https://www.youtube.com/watch?v=-SrNAbDM2UI' target='_blank'>video</a>
			<br> &nbsp;&nbsp;&nbsp;&nbsp; - <b>Path of Ashura</b> (PvP) event, you can exchange your collected items into exp, every day at 8PM <i>[108 509 53]</i> - <a href='https://youtu.be/IgqX23xSZbI?feature=shared&t=131' target='_blank'>video</a>
			<br><br>
			<br><br><b>What should i get from webshop with (inventory) coins?</b>
			<br> - scrolls, oracles, heroeism, mysterious chips, soul stones (Lv6-9), herbs, mirage stones, teleport stone, silver HP/MP hiero, Teleacoustic (for world chat)
			<br><br><b>How can I get (inventory) coins?</b>
			<br> - do 'Punish' quests which gives good coin rewards (500k - 1.2m) at <i>118 856 37</i>, <i>129 856 37</i>, <i>129 861 37</i>
			<br> - sell items to the npc
			<br> - do quests
			<br> - you can get <span style='color:red'><b>1 Million coin</b></span> for doing <b>Villa/World</b> quest (starts with <u>A Brooch For A Villa</u> at <b>ADC Elder</b> <i>[571 646]</i>) then it is daily at <b>Amerigo</b> <i>[531 641]</i>, the guide is <a href='https://pwi.fandom.com/wiki/World_Quest'><b>here</b></a>, check always the next target in the game chat (btw this is an npc talking quest)
			<br><br><b>How can I get rank 8?</b>
			<br> - buy heroism and make them for the rep reward
			<br> - exchange <b>Lucky Charm</b> (from the Anniversary/Coral packs) at <i>528 650</i> to the 25 rep badge
			<br> - slower but you can farm fb instances like 59 and TW
			<br> - if you do not have time and want invest money then the <b>Wedding Chocolate</b> in the <i>Item Mall</i> is an expensive but fast method to it
			<br><br><b>How to get Cube necklance?</b>
			<br> - Coral pack if you are lucky
			<br> - Buy Cube Stamp from the item mall and exchange it
			<br> - [Free/Grind] Finish cube and get 5 <b>Rotational Gear</b> from the room 60 with 500k EXP, then if you finished Cube 6x then then 30 Rot. Gear could be combined into 1 Grade 14 Cube Necklance, then 3 gr14 into gr15 and 3 gr15 into gr16
			<br><br><b>How to get lucky packs?</b>
			<br> - buy from Item Mall
			<br> - Lv90 Winner of the Wushu Events gets 50 - 150x <a href='https://www.pwdatabase.com/pwi/quest/21385' target='_blank'><b>Lucky Coral pack</b></a> - <a href='https://www.youtube.com/watch?v=-SrNAbDM2UI' target='_blank'>video</a>
			<br><br><b>What was change on Ashura event? <a href='https://youtu.be/IgqX23xSZbI?feature=shared&t=131' target='_blank'>video</a></b>
			<br> - when you enter then you will get 10x more collectible items (can be traded to mysterious chip, special necklance (+5% max hp, +8 con, 3% reduced phys harm), exp+coin etc)
			<br> - exchange rate into exp was adjusted, it will give 2x more exp, and give gold as well
			<br><br><b>Why to do card bosses?</b>
			<br> - Depends on the difficulty, they can drop <b>Dragon Orbs</b> (1start - 5star), gr12 stones, FB79 boss have the worst drop, CV/GV have the best drop rate
			<br> - Last boss drop several Warsoul Weapon materia which is the ultimate weapon in the game
			<br> - <b>Note: </b> maybe you can check the <a href='http://ecatomb.rf.gd/es.ecatomb.net/boss-card.html?i=1' target='_blank'>where they are</a> or <a href='https://www.youtube.com/watch?v=SioVYZZjvVw' target='_blank'> video tutorial</a>
			<br><br><b>Gear improve with refine/socketing?</b>
			<br> - farm socket stone and fortune stone from spiders at Nameless Isle <i>[696 689]</i>
			<br> - do World Bosses and Card Bosses for dragon orbs
			<br><br><b>World Bosses info</b>
			<br> - they can drop dragon orb, gr12 stones and +10con stone (if Chrono World boss then instead of con stone they drop +att/def lv stones)
			<br> - respawn time is 24h
			<br><br> - location is on World bosses:
			<br>&emsp;	- Aurumborn Kingsof - 19473 - 653 390 (24)
			<br>&emsp;	- Soul Hunter - 19472 - 658 434 (23)
			<br>&emsp;	- Alpha - 19468 - 162 427 (53)
			<br>&emsp;	- Obeaune - 19471 - 553 437 (22)
			<br>&emsp;	- ShadowShade - 19470 - 659 524 (28)
			<br>&emsp;	- Apro.Shield - 19469 - 151 340 (55)
			<br>&emsp;	- Awler - 19467 - 252 755 (26)
			<br>&emsp;	- Fetch Suppresser - 19466 - 159 976 (22)
			<br>&emsp;	- Ghost Rider - 19465 - 172 787 (22)
			<br>&emsp;	- Enguerrand - 19464 - 236 867 (23)
			<br>&emsp;	- Puzzle - 19463 - 314 955 (22)
			<br>&emsp;	- Deep - 19462 - 293 969 (22)
			<br>&emsp;&emsp;	- Pterygota Warrior - 19474 - 440 752 (22)
			<br>&emsp;	- Sword Demonic.Still - 19475 - 488 571 (22)
			<br>&emsp;	- Chilopod Terminator - 19476 - 639 868 (22)
			<br>&emsp;	- Falchion Demonic - 19477 - 438 471 (23)
			<br><br> - location is on Chrono World boss:
			<br>&emsp;	- Thunder Beast (chrono) - 19478 - 334 597 (23) - map2
			<br>&emsp;	- Skeleton King.Scorch (chrono) - 19479 - 365 611 (25) - map2
			<br>&emsp;	- Gaia Fighter (chrono)  - 19480 - 477 623 (24) - map3
			<br>&emsp;	- Skeleton King.Atropine (chrono) - 19481 - 422 570 (23) - map3
			<br>&emsp;	- Slaughtry (chrono) - 19482 - 367 472 (22) - map1
			<br>&emsp;	- Axemaster·Malphas (chrono) - 19483 - 345 459 (22) - map1
			<br>&emsp;	- Daricon (chrono) - 19484 - 463 520 (53) - map4
			<br>&emsp;	- Frost City Guard-0 (chrono) - 19485 - 477 476 (55) - map4
		</div>
	";

	echo"
	<div id='MainTab4' style='display:none;padding: 32px;'>
		<center>
			<iframe src='https://www.youtube.com/embed/IgqX23xSZbI' width='420' height='315' borde='0' style='float:left; margin: 0 20px;'></iframe>
			<h2>Base informations</h2>
			<table>
				<tr><td style='font-weight:bold;'> Note:</td><td>PvP server, gear and everything important buyable somehow!</td></tr>
				<tr><td style='font-weight:bold;'> Version:</td><td>1.4.1</td></tr>
				<tr><td style='font-weight:bold;'> Language:</td><td>PW MS & PW MY-EN</td></tr>
				<tr><td style='font-weight:bold;'> Race:</td><td>Human, Beastkind, Elf</td></tr>
				<tr><td style='font-weight:bold;'> Skills:</td><td>Normal + Lv79 & 100</td></tr>
				<tr><td style='font-weight:bold;'> Server rate:</td><td>Normal 1x, (+ culti and exp pill, exp starter gear)</td></tr>
				<tr><td style='font-weight:bold;'> Leveling:</td><td>Normal way or with pills</td></tr>
				<tr><td style='font-weight:bold;'> Get PW-Gold:</td><td>Vote to server for Gold</td></tr>
				<tr><td style='font-weight:bold;'> Elemental Elf: &nbsp;&nbsp;&nbsp;&nbsp;</td><td style='vertical-align:top;'>On, like original server.</td></tr>
				<tr><td style='font-weight:bold;'> How to Start:</td><td>Login and go to the WebShop and you can buy starter gears and items</td></tr>
				<tr><td style='font-weight:bold;'> Peak:</td><td><b>WebShop</b>, starter gear, vote system, discord events</td></tr>
				<tr><td style='font-weight:bold;'> F2P:</td><td>Multiple items (hiero, dye, tp stone) can buy with in-game gold without spending</td></tr>
				<tr><td style='font-weight:bold;'> Vote:</td><td> Please watch this: <a href='https://youtu.be/5PiYd6XS5pI' rel='noopener noreferrer' target='_blank'>https://youtu.be/5PiYd6XS5pI</a></td></tr>
			</table>

			<br/><br/>
			<div class='float: clear;' />
			<h2>Working instances</h2>
			<table>
				<tr><td style='font-weight:bold;'> FB instances:</td><td> 19 (elf, human, beast), 29, 39, 49, 59, 69, 79, 89 (both), 99 (both), 109 (both)</td></tr>
				<tr><td style='font-weight:bold;'> Special maps:</td><td> Arena (4), Chrono 1-4, TW, Horse Race (Snake Island), Ashura Path, Netherbeast, Dragon Temple</td></tr>
				<tr><td style='font-weight:bold;'> Gear instances:</td><td> Frost Walk City (FC), Crescent Valley (CV), Holy Hall (HH), Beehive/Cube, Godless Valley (GV), Warsong City (WS) - <span style='color:red'>the required player in team was reduced to 2</span></td></tr>
			</table>
			
			<br/><br/>
			<div class='float: clear;' />
			<h2>End game</h2>
			<table>
				<tr><td style='font-weight:bold;'> Jewelry:</td><td> Cube neck, Warsong belt, HH99 or Rank8, Gr16 rings, Gr16 bless box (PW School Teacher)</td></tr>
				<tr><td style='font-weight:bold;'> Armor:</td><td> Rank 8, HH99, Helmet and Cloack/Manteou from packs</td></tr>
				<tr><td style='font-weight:bold;'> Weapon:</td><td> Warsoul, R8, HH99, CV</td></tr>
				<tr><td style='font-weight:bold;'> SoulStones:</td><td> +1 DefLv, +1 DefLV, +1 AttackLv, +Vit (World Boss) stones, gr12 stones</td></tr>
			</table>
			
			<br/><br/>
			<span style='color: red;'>More information about the server customization, how to play, trick & tips can be found in the other section, at <b>Guide\How to Start?</b></span>
		</center>
	</div>
	";

	echo"
	<div id='MainTab5'>
		<div style='width:100%;border:0px solid #000;font-weight:bold;text-align:center;'>
			<br><br><br>
				You can fing all information, what you need, if you not found it then ask it on the discord
			<br><br><br><br><br>
			<div style='display: flex;gap: 32px;text-align:center; justify-content: center;'>
				<div>
					<a href='javascript:void(0);' style='text-decoration:none;' onclick='showMainTab(4);'><img src='./images/InfoIcon.png' width='100' alt='Server info'><br>Server information</a>
				</div>
				<div>
					<a href='javascript:void(0);' style='text-decoration:none;' onclick='showMainTab(3);'><img src='./images/HowToStart.png' width='100' alt='Details about characters'><br>How to start?</a>
				</div>
				
				<div>
					<a href='javascript:void(0);' style='text-decoration:none;' onclick='showMainTab(2);'><img src='./images/PW_char.png' width='100' alt='Details about characters'><br>Class: Descriptions</a>
				</div>
						
				<div>
					<a href='javascript:void(0);' style='text-decoration:none;' onclick='showMainTab(1);'><img src='./images/PW_all.png' width='100' alt='Details about characters'><br>Class: Pro vs Contra</a>
				</div>
			</div>
			<center>
			</center>
		</div>
	</div>
	";

echo"<br><br><br>";
?>
</body>
</html>
