<?php
// Start the session
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- CHANGE HERE TO YOUR DOMAIN -->
	<link rel="stylesheet" type="text/css" href="../css/base.css?t=<?php echo time(); ?>">
	<link rel="stylesheet" type="text/css" href="../css/style.css?t=<?php echo time(); ?>">
	<link rel="canonical” href="https://retroms.ddns.net">
	<meta name="description" content="Perfect World - Retro MS, the old version of this MMORPG game, from 2009, it is free to play oriented server, where we respect eachother." />
	<meta charset="utf-8">
	<?php
	$cfgFile="../config.php";
	include $cfgFile;
	include "../basefunc.php";
	if (!(file_exists($cfgFile))) {
		header('Location: ../setup.php');
	}else{
		if (isset($_SESSION['UD1'])){
			if ($_SESSION['UD1'] != 1){
				if (isset($_SESSION['UD2'])){
					unset ($_SESSION['UD2']);
				}
			}else{
				$_SESSION['UD2'] = $AKey1;
			}
		}else{
			$_SESSION['UD1'] = 1;
			$_SESSION['UD2'] = $AKey1;
		}
	}
	?>
	<title>Perfect World RetroMS - Welcome on our free server</title>
	<link rel="stylesheet" type="text/css" href="../css/index.css">
	<link rel="icon" type="image/x-icon" href="../images/pwicon3.png">
	<script src="../js/guild.js?s=<?php echo uniqid(); ?>"></script>
	<script src="../js/index.js?s=<?php echo uniqid(); ?>"></script>
</head>
<body>
<div style="position:fixed;top:0;bottom:0;left:0;right: 0;z-index:-1;">
	<video id="bgvid" style="width:100%;height:100%;object-fit:cover;" autoplay muted>
		<source src="../videos/adc.mp4" type="video/mp4">
	</video>
</div>
<script>
    const video = document.getElementById('bgvid');
    video.addEventListener('ended', () => {
		video.currentTime = 0.05;
		video.play();
    });
</script>

<div class="flex justify-center">
	<div class="flex flex-wrap gap-2 justify-center">
		<aside class='flex flex-col justify-around hide-md'>
			<div class='font-bold'>
				<div style="color: #ffff88">Perfect</div>
				<div style="color: #ffff99">World </div>
				<div style="color: #ffffaa">RetroMS</div>
			</div>
			<div>
				<div class='font-bold' style="color: #ffffcc">Wodan</div>
				<div id="statusID">
					<?php
						$con = new mysqli($DB_Host, $DB_User, $DB_Password, $DB_Name);
						$isOnline = ServerOnline($con);
						if ($isOnline !== false){
							echo "<span style='color:lightgreen'><b>Online</b></span>";
						}else{
							echo "<span style='color:red'><b>Offline</b></span>";
						}
						mysqli_close($con);
						echo"<script>SrvrTmZone = parseInt('".date('Z')."',10);</script>";
					?>
				</div>
			</div>
			<div class='font-bold'>
				<div style='color:#ffffee'>Time:</div>
				<div id='STime_Count' style="color:#ffffff"><?php echo date("H:i:s"); ?></div>
			</div>
		</aside>
		
		<main class='relative'>
			<img class='max-w-full' src="../images/banner.jpg" alt="Perfect World RetroMS" border="0">
			<h1 class='absolute hide-sm' style='position:absolute; top: 10px; left: 50%; transform: translateX(-50%);font-family: arial;color: white;text-shadow: 0 1px 0 #ccc,
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
						   ;'
			>
						   Perfect World Retro MS
			</h1>
		</main>

		<aside class='flex flex-col justify-around hide-md'>
			<div class='font-bold'>
				<div style="color:#ffff99">Three race</div>
				<div style="color:#ffffaa">Six class</div>
			</div>
			<div>
				<div style="color:#ffffcc">Lv 79 & 100</div>
				<div style="color:#ffffdd">skills + Elfs</div>
			</div>
			<div class='font-bold'>
				<div style="color:#ffffee">Ver.: 1.4.1</div>
				<div style="color:#ffffff">PvP Ready</div>
			</div>
		</aside>
	</div>
</div>

<nav id="ButtonRow" class='flex flex-wrap gap-2 justify-center my-4'>
	<a href="../news" class="myButton">Home</a>
	<a href="../about-pwms" class="myButton">About PW-MS</a>
	<a href="../story" class="myButton">Story</a>
	<a href="../downloads" class="myButton">Download</a>
	<a href="../guide" class="myButton">Guide</a>
	<?php
		if ($Forum!==false){ 
			echo "<a href='../forum' class='myButton'>Forum</a>";
		}
		if ($Donation!==false){ 
			echo "<a href='../donation' class='myButton'>Donation</a>";
		} 
		?>
		<a href="../login" class="myButton" id="LoginButton">Sign In</a>
		<?php
		if ($RegisEnabled!==false){ 
			echo"<a href='../register' class='myButton' id='RegButton'>Registration</a>";
		}
	?>
</nav>
