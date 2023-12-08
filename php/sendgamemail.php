<?php 
function SysSendMail($Receiver, $Title, $Message, $ItemID, $Count, $Count_Max, $Octets, $Proctype, $Expire_date, $Guid1, $Guid2, $Mask, $Money){
	$tID = "\x00\x00\x01\x58";
	$SysID = "\x00\x00\x00\x20";
	$SysType = "\x03";
	
	$Receiver = pack("N*", $Receiver);
	$Title = iconv("UTF-8", "UTF-16LE", $Title);
	$TitleLengh = strlen($Title);
	if($TitleLengh < 128){
		$TitleLengh = pack("C*", $TitleLengh);
	}else{
		$TitleLengh = pack("n*", $TitleLengh + 32768);
	}
	$Message = iconv("UTF-8", "UTF-16LE", $Message);
	$MessageLengh = strlen($Message);
	if($MessageLengh < 128)	{
		$MessageLengh = pack("C*", $MessageLengh);
	}else{
		$MessageLengh = pack("n*", $MessageLengh + 32768);
	}
	$ItemID = pack("N*", $ItemID);
	$Pos = "\x00\x00\x00\x00";
	$Count = pack("N*", $Count);
	$Count_Max = pack("N*", $Count_Max);	
	$Octets = pack("H*", $Octets);
	$OctetsLenght = pack("n*", strlen($Octets) + 32768);	
	$Proctype = pack("N*", $Proctype);
	$Expire_date = pack("N*", $Expire_date);
	$Guid1 = pack("N*", $Guid1);
	$Guid2 = pack("N*", $Guid2);
	$Mask = pack("N*", $Mask);
	$Money = pack("N*", $Money);
	$Err = 0;
	$Packet = $tID.$SysID.$SysType.$Receiver.$TitleLengh.$Title.$MessageLengh.$Message.$ItemID.$Pos.$Count.$Count_Max.$OctetsLenght.$Octets.$Proctype.$Expire_date.$Guid1.$Guid2.$Mask.$Money;
		$PacketLenght = strlen($Packet);
		if($PacketLenght < 128)
		{
			$PacketLenght = pack("C*", $PacketLenght);
		}else{
			$PacketLenght = pack("n*", $PacketLenght + 32768);
		}

	$Packet = "\x90\x76".$PacketLenght.$Packet;

	$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	if(!$sock){
			die(socket_strerror(socket_last_error()));
	}

	$GameServer = "localhost";
	if(socket_connect($sock, $GameServer, 29100)){
		socket_set_block($sock);
		if (false !== ($sbytes = socket_send($sock, $Packet, 8192, 0)))	{	
		}else{
			$Err = 1;
		}
		if (false !== ($rbytes = socket_recv($sock, $buf, 8192, 0))){
		}else{
			$Err = 2;
		}
        socket_set_nonblock($sock);
        socket_close($sock);
	}else{
		$Err = 3;
	}
	
	return $Err;
}
?>
