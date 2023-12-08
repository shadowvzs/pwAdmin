<html>
<head>
<title>Perfect World RetroMS Server</title>
<style>
.myButton {
	-moz-box-shadow: 0px 10px 14px -7px #276873;
	-webkit-box-shadow: 0px 10px 14px -7px #276873;
	box-shadow: 0px 10px 14px -7px #276873;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #599bb3), color-stop(1, #408c99));
	background:-moz-linear-gradient(top, #599bb3 5%, #408c99 100%);
	background:-webkit-linear-gradient(top, #599bb3 5%, #408c99 100%);
	background:-o-linear-gradient(top, #599bb3 5%, #408c99 100%);
	background:-ms-linear-gradient(top, #599bb3 5%, #408c99 100%);
	background:linear-gradient(to bottom, #599bb3 5%, #408c99 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#599bb3', endColorstr='#408c99',GradientType=0);
	background-color:#599bb3;
	-moz-border-radius:4px;
	-webkit-border-radius:4px;
	border-radius:4px;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:Arial;
	font-size:16px;
	font-weight:bold;
	padding:7px 16px;
	text-decoration:none;
	text-shadow:0px 1px 0px #3d768a;
}
.myButton:hover {
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #408c99), color-stop(1, #599bb3));
	background:-moz-linear-gradient(top, #408c99 5%, #599bb3 100%);
	background:-webkit-linear-gradient(top, #408c99 5%, #599bb3 100%);
	background:-o-linear-gradient(top, #408c99 5%, #599bb3 100%);
	background:-ms-linear-gradient(top, #408c99 5%, #599bb3 100%);
	background:linear-gradient(to bottom, #408c99 5%, #599bb3 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#408c99', endColorstr='#599bb3',GradientType=0);
	background-color:#408c99;
}
.myButton:active {
	position:relative;
	top:1px;
}
body {
	position: relative;
	min-width: 1000px;
	font-family:Arial;
}
#loginDiv{
	position: absolute;
	border: 1px inset #777;
	padding: 10px 20px;
	background-color: #fff;
	-moz-box-shadow: 10px 10px 14px -7px #777;
	-webkit-box-shadow: 10px 10px 14px -7px #777;
	box-shadow: 10px 10px 14px -7px #777;
	left:-1000px;
	transition: 0.4s;
	transition-timing-function: ease-in;
	background: -webkit-linear-gradient(-90deg, #fff, #aaf);
	background: -o-linear-gradient(-90deg, #fff, #aaf);
	background: -moz-linear-gradient(-90deg, #fff, #aaf);
	background: linear-gradient(-90deg, #fff, #aaf);
	-moz-border-radius:4px;
	-webkit-border-radius:4px;
	border-radius:4px;
}
#RegDiv{
	position: absolute;
	border: 1px inset #777;
	padding: 10px 20px;
	background-color: #fff;
	-moz-box-shadow: 10px 10px 14px -7px #777;
	-webkit-box-shadow: 10px 10px 14px -7px #777;
	box-shadow: 10px 10px 14px -7px #777;
	left:-1000px;
	transition: 0.4s;
	transition-timing-function: ease-in;
	background: -webkit-linear-gradient(-90deg, #fff, #afa);
	background: -o-linear-gradient(-90deg, #fff, #afa);
	background: -moz-linear-gradient(-90deg, #fff, #afa);
	background: linear-gradient(-90deg, #fff, #afa);
	-moz-border-radius:4px;
	-webkit-border-radius:4px;
	border-radius:4px;
}
</style>
<script>
function ShowLoginDiv(){
	var ADiv = document.getElementById('RegDiv');
	var TCont = document.getElementById('ButtonRow');
	var LDiv = document.getElementById('loginDiv');
	var TdBut = document.getElementById('TdButLog');
	var but = document.getElementById('LoginButton');
	var butH = but.offsetHeight;
	var butW = but.offsetWidth;
	var butT = but.offsetTop;
	var butL = but.offsetLeft;
	var LDivW = LDiv.offsetWidth;
	var newT = TCont.offsetTop + butT + butH + 10;
	var newL = TdBut.offsetLeft + parseInt(TCont.offsetLeft + butL + (butW - LDivW)/2);
	LDiv.style.top = newT + "px";
	LDiv.style.left = newL + "px";
	ADiv.style.left = "-1000px";
}
function ShowRegDiv(){
	var ADiv = document.getElementById('loginDiv');
	var TCont = document.getElementById('ButtonRow');
	var LDiv = document.getElementById('RegDiv');
	var TdBut = document.getElementById('TdButReg');
	var but = document.getElementById('RegButton');
	var butH = but.offsetHeight;
	var butW = but.offsetWidth;
	var butT = but.offsetTop;
	var butL = but.offsetLeft;
	var LDivW = LDiv.offsetWidth;
	var newT = TCont.offsetTop + butT + butH + 10;
	var newL = TdBut.offsetLeft + parseInt(TCont.offsetLeft + butL + (butW - LDivW)/2);
	LDiv.style.top = newT + "px";
	LDiv.style.left = newL + "px";
	ADiv.style.left = "-1000px";
}
function ClrWin(){
	document.getElementById('loginDiv').style.left = "-1000px";
	document.getElementById('RegDiv').style.left = "-1000px";
}


</script>
</head>
<body>
coming soon
</body>
</html>
