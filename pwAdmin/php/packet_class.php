<?php
// Herzlich Willkommen. Das ist "Packet Class PW".
// Bei Desmond Hume
class ReadPacket
{
	public $data, $pos;
	
	function __construct($obj = null)
	{
		$this -> data = $obj -> response;
	}
	
	public function ReadBytes($length)
	{
		$value = substr($this -> data, $this -> pos, $length);
		$this -> pos += $length;
		
		return $value;
	}
	
	public function ReadUByte()
	{
		$value = unpack("C", substr($this -> data, $this -> pos, 1));
		$this -> pos++;
		
		return $value[1];
	}

	public function ReadFloat()
	{
		$value = unpack("f", strrev(substr($this -> data, $this -> pos, 4)));
		$this -> pos += 4;
		
		return $value[1];
	}
	
	public function ReadUInt32()
	{
		$value = unpack("N", substr($this -> data, $this -> pos, 4));
		$this -> pos += 4;
		
		return $value[1];
	}
	
	public function ReadUInt16()
	{
		$value = unpack("n", substr($this -> data, $this -> pos, 2));
		$this -> pos += 2;
		
		return $value[1];
	}
	
	
	public function ReadOctets()
	{
		$length = $this -> ReadCUInt32();
	
		$value = unpack("H*", substr($this -> data, $this -> pos, $length));
		$this -> pos += $length;
		
		return $value[1];
	}
	
	public function ReadUString()
	{
		$length = $this -> ReadCUInt32();
	
		$value = iconv("UTF-16", "UTF-8", substr($this -> data, $this -> pos, $length)); // LE?
		$this -> pos += $length;
		
		return $value;
	}
	
	public function ReadPacketInfo()
	{
		$packetinfo['Opcode'] = $this -> ReadCUInt32();
		$packetinfo['Length'] = $this -> ReadCUInt32();
		return $packetinfo;
	}
	
	public function Seek($value)
	{
		$this -> pos += $value;
	}
	
	public function ReadCUInt32()
	{
		$value = unpack("C", substr($this -> data, $this -> pos, 1));
		$value = $value[1];
		$this -> pos++;
		
		switch($value & 0xE0)
		{
			case 0xE0:
				$value = unpack("N", substr($this -> data, $this -> pos, 4));
				$value = $value[1];
				$this -> pos += 4;
				break;
			case 0xC0:
				$value = unpack("N", substr($this -> data, $this -> pos - 1, 4));
				$value = $value[1] & 0x1FFFFFFF;
				$this -> pos += 3;
				break;
			case 0x80:
			case 0xA0:
				$value = unpack("n", substr($this -> data, $this -> pos - 1, 2));
				$value = $value[1] & 0x3FFF;
				$this -> pos++;
				break;
		}
		
		return $value;
	}
}

class WritePacket
{
	public $request, $response;
	
	public function WriteBytes($value)
	{
		$this -> request .= $value;
	}
	
	public function WriteUByte($value)
	{
		$this -> request .= pack("C", $value);
	}
	
	public function WriteFloat($value)
	{
		$this -> request .= strrev(pack("f", $value));
	}
	
	public function WriteUInt32($value)
	{
		$this -> request .= pack("N", $value);
	}
	
	public function WriteUInt16($value)
	{
		$this -> request .= pack("n", $value);
	}
	
	public function WriteOctets($value)
	{
		if (ctype_xdigit($value))
			$value = pack("H*", $value);
			
		$this -> request .= $this -> CUInt(strlen($value));
		$this -> request .= $value;
	}
	
	public function WriteUString($value, $coding = "UTF-16LE")
	{
		$value = iconv("UTF-8", $coding, $value);
		$this -> request .= $this -> CUInt(strlen($value));
		$this -> request .= $value;
	}
	
	public function Pack($value)
	{
		$this -> request = $this -> CUInt($value) . $this -> CUInt(strlen($this -> request)) . $this -> request;
	}
	
	public function Unmarshal()
	{
		return $this -> CUInt(strlen($this -> request)) . $this -> request;
	}
	
	public function Send($address, $port)
	{
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		
		if (socket_connect($socket, $address, $port))
		{
			socket_set_block($socket);

			$send = socket_send($socket, $this -> request, 131072, 0);
			$recv = socket_recv($socket, $this -> response, 131072, 0); 
			socket_set_nonblock($socket);
			socket_close($socket);
			
			return true;
		}
		else
			return false;
	}
	
	public function WriteCUInt32($value)
	{
			$this -> request .= $this -> CUInt($value);
	}
	
	private function CUInt($value)
	{
		if ($value <= 0x7F)
			return pack("C", $value);
		else if ($value <= 0x3FFF)
			return pack("n", ($value | 0x8000));
		else if ($value <= 0x1FFFFFFF)
			return pack("N", ($value | 0xC0000000));
		else
			return pack("C", 0xE0) . pack("N", $value);
	}
}

function GetRoleData($id, $sVer){
		$GetRoleBase = new WritePacket();
		$GetRoleBase -> WriteUInt32(-1); // always
		$GetRoleBase -> WriteUInt32($id); // roleid
		$GetRoleBase -> Pack(0x1F43); // opcode  0xBC5   0x1F43

		if (!$GetRoleBase -> Send("localhost", 29400)) // send to gamedbd
		return;

		$GetRoleBase_Re = new ReadPacket($GetRoleBase); // reading packet from stream
		$packetinfo = $GetRoleBase_Re -> ReadPacketInfo(); // read opcode and length
		$GetRoleBase_Re -> ReadUInt32(); // always
		$GetRoleBase_Re -> ReadUInt32(); // retcode
	
		$GRoleData['base']['version'] = $GetRoleBase_Re -> ReadUByte();
		$GRoleData['base']['id'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['base']['name'] = $GetRoleBase_Re -> ReadUString();
		$GRoleData['base']['race'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['base']['cls'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['base']['gender'] = $GetRoleBase_Re -> ReadUByte();
		$GRoleData['base']['custom_data'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['base']['config_data'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['base']['custom_stamp'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['base']['status'] = $GetRoleBase_Re -> ReadUByte();
		$GRoleData['base']['delete_time'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['base']['create_time'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['base']['lastlogin_time'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['base']['forbidC'] = $GetRoleBase_Re -> ReadCUInt32();
		for ($i = 0; $i < $GRoleData['base']['forbidC']; $i++){
			$GRoleForbid['type'] = $GetRoleBase_Re -> ReadUByte();
			$GRoleForbid['time'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleForbid['createtime'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleForbid['reason'] = $GetRoleBase_Re -> ReadUString();
			$GRoleData['base']['forbid'][] = $GRoleForbid;
		}

		$GRoleData['base']['help_states'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['base']['spouse'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['base']['userid'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['base']['cross_data'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['base']['reserved2'] = $GetRoleBase_Re -> ReadUByte();
		$GRoleData['base']['reserved3'] = $GetRoleBase_Re -> ReadUByte();
		$GRoleData['base']['reserved4'] = $GetRoleBase_Re -> ReadUByte();
		$GRoleData['status']['version'] = $GetRoleBase_Re -> ReadCUInt32();
		$GRoleData['status']['level'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['status']['level2'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['status']['exp'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['status']['sp'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['status']['pp'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['status']['hp'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['status']['mp'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['status']['posx'] = $GetRoleBase_Re -> ReadFloat();
		$GRoleData['status']['posy'] = $GetRoleBase_Re -> ReadFloat();
		$GRoleData['status']['posz'] = $GetRoleBase_Re -> ReadFloat();
		$GRoleData['status']['worldtag'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['status']['invader_state'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['status']['invader_time'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['status']['pariah_time'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['status']['reputation'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['status']['custom_status'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['status']['filter_data'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['status']['charactermode'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['status']['instancekeylist'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['status']['dbltime_expire'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['status']['dbltime_mode'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['status']['dbltime_begin'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['status']['dbltime_used'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['status']['dbltime_max'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['status']['time_used'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['status']['dbltime_data'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['status']['storesize'] = $GetRoleBase_Re -> ReadUInt16();
		$GRoleData['status']['petcorral'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['status']['property'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['status']['var_data'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['status']['skills'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['status']['storehousepasswd'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['status']['waypointlist'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['status']['coolingtime'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['status']['npc_relation'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['status']['multi_exp_ctrl'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['status']['storage_task'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['status']['faction_contrib'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['status']['force_data'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['status']['online_award'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['status']['profit_time_data'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['status']['country_data'] = $GetRoleBase_Re -> ReadOctets();
		if ($sVer >= 80){
			//ignored line if v1.5.1
			$GRoleData['status']['king_data'] = $GetRoleBase_Re -> ReadOctets();	
			$GRoleData['status']['meridian_data'] = $GetRoleBase_Re -> ReadOctets();
			$GRoleData['status']['extraprop'] = $GetRoleBase_Re -> ReadOctets();
			$GRoleData['status']['title_data'] = $GetRoleBase_Re -> ReadOctets();
			$GRoleData['status']['reincarnation_data'] = $GetRoleBase_Re -> ReadOctets();
			$GRoleData['status']['realm_data'] = $GetRoleBase_Re -> ReadOctets();
		}
		$GRoleData['status']['reserved4'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['status']['reserved5'] = $GetRoleBase_Re -> ReadUInt32();
		
		$GRoleData['pocket']['capacity'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['pocket']['timestamp'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['pocket']['money'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['pocket']['itemsC'] = $GetRoleBase_Re -> ReadCUInt32();

		for ($i = 0; $i < $GRoleData['pocket']['itemsC']; $i++){
			$GRoleInventory['id'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['pos'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['count'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['maxcount'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['data'] = $GetRoleBase_Re -> ReadOctets();
			$GRoleInventory['proctype'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['expire_date'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['guid1'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['guid2'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['mask'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleData['pocket']['items'][] = $GRoleInventory;
		}

		$GRoleData['pocket']['reserved1'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['pocket']['reserved2'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['equipment']['invC'] = $GetRoleBase_Re -> ReadCUInt32();
		for ($i = 0; $i < $GRoleData['equipment']['invC']; $i++){
			$GRoleInventory['id'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['pos'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['count'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['maxcount'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['data'] = $GetRoleBase_Re -> ReadOctets();
			$GRoleInventory['proctype'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['expire_date'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['guid1'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['guid2'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['mask'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleData['equipment']['inv'][] = $GRoleInventory;
		}
		$GRoleData['storehouse']['capacity'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['storehouse']['money'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['storehouse']['itemsC'] = $GetRoleBase_Re -> ReadCUInt32();

		for ($i = 0; $i < $GRoleData['storehouse']['itemsC']; $i++){
			$GRoleInventory['id'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['pos'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['count'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['maxcount'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['data'] = $GetRoleBase_Re -> ReadOctets();
			$GRoleInventory['proctype'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['expire_date'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['guid1'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['guid2'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['mask'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleData['storehouse']['items'][] = $GRoleInventory;
		}
		$GRoleData['storehouse']['fasCap'] = $GetRoleBase_Re -> ReadCUInt32(); //material store size
		$GRoleData['storehouse']['matCap'] = $GetRoleBase_Re -> ReadCUInt32(); //fashion store size
		$GRoleData['storehouse']['dressC'] = $GetRoleBase_Re ->ReadCUInt32();
		for ($i = 0; $i < $GRoleData['storehouse']['dressC']; $i++){
			$GRoleInventory['id'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['pos'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['count'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['maxcount'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['data'] = $GetRoleBase_Re -> ReadOctets();
			$GRoleInventory['proctype'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['expire_date'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['guid1'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['guid2'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['mask'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleData['storehouse']['dress'][] = $GRoleInventory;
		}
		$GRoleData['storehouse']['materialC'] = $GetRoleBase_Re -> ReadCUInt32();
		for ($i = 0; $i < $GRoleData['storehouse']['materialC']; $i++){
			$GRoleInventory['id'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['pos'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['count'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['maxcount'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['data'] = $GetRoleBase_Re -> ReadOctets();
			$GRoleInventory['proctype'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['expire_date'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['guid1'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['guid2'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['mask'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleData['storehouse']['material'][] = $GRoleInventory;
		}
		if ($sVer >= 80){
			$GRoleData['storehouse']['cardCap'] = $GetRoleBase_Re -> ReadUByte();
			//these for v1.5.1
			$GRoleData['storehouse']['generalcardC'] = $GetRoleBase_Re -> ReadCUInt32();

			for ($i = 0; $i < $GRoleData['storehouse']['generalcardC']; $i++){
				$GRoleInventory['id'] = $GetRoleBase_Re -> ReadUInt32();
				$GRoleInventory['pos'] = $GetRoleBase_Re -> ReadUInt32();
				$GRoleInventory['count'] = $GetRoleBase_Re -> ReadUInt32();
				$GRoleInventory['maxcount'] = $GetRoleBase_Re -> ReadUInt32();
				$GRoleInventory['data'] = $GetRoleBase_Re -> ReadOctets();
				$GRoleInventory['proctype'] = $GetRoleBase_Re -> ReadUInt32();
				$GRoleInventory['expire_date'] = $GetRoleBase_Re -> ReadUInt32();
				$GRoleInventory['guid1'] = $GetRoleBase_Re -> ReadUInt32();
				$GRoleInventory['guid2'] = $GetRoleBase_Re -> ReadUInt32();
				$GRoleInventory['mask'] = $GetRoleBase_Re -> ReadUInt32();
				$GRoleData['storehouse']['generalcard'][] = $GRoleInventory;
			}
		}
		$GRoleData['task']['reserved'] = $GetRoleBase_Re -> ReadUInt32();
		$GRoleData['task']['task_data'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['task']['task_complete'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['task']['task_finishtime'] = $GetRoleBase_Re -> ReadOctets();
		$GRoleData['task']['task_inventoryC'] = $GetRoleBase_Re -> ReadCUInt32();
		for ($i = 0; $i < $GRoleData['task']['task_inventoryC']; $i++){
			$GRoleInventory['id'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['pos'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['count'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['maxcount'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['data'] = $GetRoleBase_Re -> ReadOctets();
			$GRoleInventory['proctype'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['expire_date'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['guid1'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['guid2'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleInventory['mask'] = $GetRoleBase_Re -> ReadUInt32();
			$GRoleData['task']['task_inventory'][] = $GRoleInventory;
		}
	return $GRoleData;
}

function PutRoleData($roleid, $GRoleData, $sVer){
	$PutRoleData = new WritePacket();
	$PutRoleData -> WriteUInt32(-1);
	$PutRoleData -> WriteUInt32($roleid);
	$PutRoleData -> WriteUByte(1); // overwrite
	$PutRoleData -> WriteUByte($GRoleData['base']['version']);
	$PutRoleData -> WriteUInt32($GRoleData['base']['id']);
	$PutRoleData -> WriteUString($GRoleData['base']['name']);
	$PutRoleData -> WriteUInt32($GRoleData['base']['race']);
	$PutRoleData -> WriteUInt32($GRoleData['base']['cls']);
	$PutRoleData -> WriteUByte($GRoleData['base']['gender']);
	$PutRoleData -> WriteOctets($GRoleData['base']['custom_data']);
	$PutRoleData -> WriteOctets($GRoleData['base']['config_data']);
	$PutRoleData -> WriteUInt32($GRoleData['base']['custom_stamp']);
	$PutRoleData -> WriteUByte($GRoleData['base']['status']);
	$PutRoleData -> WriteUInt32($GRoleData['base']['delete_time']);
	$PutRoleData -> WriteUInt32($GRoleData['base']['create_time']);
	$PutRoleData -> WriteUInt32($GRoleData['base']['lastlogin_time']);
	$PutRoleData -> WriteCUInt32($GRoleData['base']['forbidC']);
	if ($GRoleData['base']['forbidC'] > 0){
		foreach ($data['base']['forbid'] as $value){
			$PutRoleData -> WriteUByte($value['type']);
			$PutRoleData -> WriteUInt32($value['time']);
			$PutRoleData -> WriteUInt32($value['createtime']);
			$PutRoleData -> WriteUString($value['reason']);
		}
	}
	$PutRoleData -> WriteOctets($GRoleData['base']['help_states']);
	$PutRoleData -> WriteUInt32($GRoleData['base']['spouse']);
	$PutRoleData -> WriteUInt32($GRoleData['base']['userid']);
	$PutRoleData -> WriteOctets($GRoleData['base']['cross_data']);
	$PutRoleData -> WriteUByte($GRoleData['base']['reserved2']);
	$PutRoleData -> WriteUByte($GRoleData['base']['reserved3']);
	$PutRoleData -> WriteUByte($GRoleData['base']['reserved4']);
	$PutRoleData -> WriteCUInt32($GRoleData['status']['version']);
	$PutRoleData -> WriteUInt32($GRoleData['status']['level']);
	$PutRoleData -> WriteUInt32($GRoleData['status']['level2']);
	$PutRoleData -> WriteUInt32($GRoleData['status']['exp']);
	$PutRoleData -> WriteUInt32($GRoleData['status']['sp']);
	$PutRoleData -> WriteUInt32($GRoleData['status']['pp']);
	$PutRoleData -> WriteUInt32($GRoleData['status']['hp']);
	$PutRoleData -> WriteUInt32($GRoleData['status']['mp']);
	$PutRoleData -> WriteFloat($GRoleData['status']['posx']);
	$PutRoleData -> WriteFloat($GRoleData['status']['posy']);
	$PutRoleData -> WriteFloat($GRoleData['status']['posz']);
	$PutRoleData -> WriteUInt32($GRoleData['status']['worldtag']);
	$PutRoleData -> WriteUInt32($GRoleData['status']['invader_state']);
	$PutRoleData -> WriteUInt32($GRoleData['status']['invader_time']);
	$PutRoleData -> WriteUInt32($GRoleData['status']['pariah_time']);
	$PutRoleData -> WriteUInt32($GRoleData['status']['reputation']);
	$PutRoleData -> WriteOctets($GRoleData['status']['custom_status']);
	$PutRoleData -> WriteOctets($GRoleData['status']['filter_data']);
	$PutRoleData -> WriteOctets($GRoleData['status']['charactermode']);
	$PutRoleData -> WriteOctets($GRoleData['status']['instancekeylist']);
	$PutRoleData -> WriteUInt32($GRoleData['status']['dbltime_expire']);
	$PutRoleData -> WriteUInt32($GRoleData['status']['dbltime_mode']);
	$PutRoleData -> WriteUInt32($GRoleData['status']['dbltime_begin']);
	$PutRoleData -> WriteUInt32($GRoleData['status']['dbltime_used']);
	$PutRoleData -> WriteUInt32($GRoleData['status']['dbltime_max']);
	$PutRoleData -> WriteUInt32($GRoleData['status']['time_used']);
	$PutRoleData -> WriteOctets($GRoleData['status']['dbltime_data']);
	$PutRoleData -> WriteUInt16($GRoleData['status']['storesize']);
	$PutRoleData -> WriteOctets($GRoleData['status']['petcorral']);
	$PutRoleData -> WriteOctets($GRoleData['status']['property']);
	$PutRoleData -> WriteOctets($GRoleData['status']['var_data']);
	$PutRoleData -> WriteOctets($GRoleData['status']['skills']);
	$PutRoleData -> WriteOctets($GRoleData['status']['storehousepasswd']);
	$PutRoleData -> WriteOctets($GRoleData['status']['waypointlist']);
	$PutRoleData -> WriteOctets($GRoleData['status']['coolingtime']);
	$PutRoleData -> WriteOctets($GRoleData['status']['npc_relation']);
	$PutRoleData -> WriteOctets($GRoleData['status']['multi_exp_ctrl']);
	$PutRoleData -> WriteOctets($GRoleData['status']['storage_task']);
	$PutRoleData -> WriteOctets($GRoleData['status']['faction_contrib']);
	$PutRoleData -> WriteOctets($GRoleData['status']['force_data']);
	$PutRoleData -> WriteOctets($GRoleData['status']['online_award']);
	$PutRoleData -> WriteOctets($GRoleData['status']['profit_time_data']);
	$PutRoleData -> WriteOctets($GRoleData['status']['country_data']);
	if ($sVer >= 80){
		$PutRoleData -> WriteOctets($GRoleData['status']['king_data']);
		$PutRoleData -> WriteOctets($GRoleData['status']['meridian_data']);
		$PutRoleData -> WriteOctets($GRoleData['status']['extraprop']);
		$PutRoleData -> WriteOctets($GRoleData['status']['title_data']);
		$PutRoleData -> WriteOctets($GRoleData['status']['reincarnation_data']);
		$PutRoleData -> WriteOctets($GRoleData['status']['realm_data']);
	}
	$PutRoleData -> WriteUInt32($GRoleData['status']['reserved4']); 
	$PutRoleData -> WriteUInt32($GRoleData['status']['reserved5']); 
	$PutRoleData -> WriteUInt32($GRoleData['pocket']['capacity']);
	$PutRoleData -> WriteUInt32($GRoleData['pocket']['timestamp']);
	$PutRoleData -> WriteUInt32($GRoleData['pocket']['money']);
	$PutRoleData -> WriteCUInt32($GRoleData['pocket']['itemsC']);

	if ($GRoleData['pocket']['itemsC'] > 0){
		foreach ($GRoleData['pocket']['items'] as $value){
			$PutRoleData -> WriteUInt32($value['id']);
			$PutRoleData -> WriteUInt32($value['pos']);
			$PutRoleData -> WriteUInt32($value['count']);
			$PutRoleData -> WriteUInt32($value['maxcount']);
			$PutRoleData -> WriteOctets($value['data']);
			$PutRoleData -> WriteUInt32($value['proctype']);
			$PutRoleData -> WriteUInt32($value['expire_date']);
			$PutRoleData -> WriteUInt32($value['guid1']);
			$PutRoleData -> WriteUInt32($value['guid2']);
			$PutRoleData -> WriteUInt32($value['mask']);
		}
	}

	$PutRoleData -> WriteUInt32($GRoleData['pocket']['reserved1']);
	$PutRoleData -> WriteUInt32($GRoleData['pocket']['reserved2']);
	$PutRoleData -> WriteCUInt32($GRoleData['equipment']['invC']);
	if ($GRoleData['equipment']['invC'] > 0){
		foreach ($GRoleData['equipment']['inv'] as $value){
			$PutRoleData -> WriteUInt32($value['id']);
			$PutRoleData -> WriteUInt32($value['pos']);
			$PutRoleData -> WriteUInt32($value['count']);
			$PutRoleData -> WriteUInt32($value['maxcount']);
			$PutRoleData -> WriteOctets($value['data']);
			$PutRoleData -> WriteUInt32($value['proctype']);
			$PutRoleData -> WriteUInt32($value['expire_date']);
			$PutRoleData -> WriteUInt32($value['guid1']);
			$PutRoleData -> WriteUInt32($value['guid2']);
			$PutRoleData -> WriteUInt32($value['mask']);
		}
	}
	
	$PutRoleData -> WriteUInt32($GRoleData['storehouse']['capacity']);
	$PutRoleData -> WriteUInt32($GRoleData['storehouse']['money']);
	$PutRoleData -> WriteCUInt32($GRoleData['storehouse']['itemsC']);
	if ($GRoleData['storehouse']['itemsC'] > 0){
		foreach ($GRoleData['storehouse']['items'] as $value){
			$PutRoleData -> WriteUInt32($value['id']);
			$PutRoleData -> WriteUInt32($value['pos']);
			$PutRoleData -> WriteUInt32($value['count']);
			$PutRoleData -> WriteUInt32($value['maxcount']);
			$PutRoleData -> WriteOctets($value['data']);
			$PutRoleData -> WriteUInt32($value['proctype']);
			$PutRoleData -> WriteUInt32($value['expire_date']);
			$PutRoleData -> WriteUInt32($value['guid1']);
			$PutRoleData -> WriteUInt32($value['guid2']);
			$PutRoleData -> WriteUInt32($value['mask']);
		}
	}

	$PutRoleData -> WriteCUInt32($GRoleData['storehouse']['fasCap']);
	$PutRoleData -> WriteCUInt32($GRoleData['storehouse']['matCap']);
	$PutRoleData -> WriteCUInt32($GRoleData['storehouse']['dressC']);	
	if ($GRoleData['storehouse']['dressC'] > 0){
		foreach ($GRoleData['storehouse']['dress'] as $value){
			$PutRoleData -> WriteUInt32($value['id']);
			$PutRoleData -> WriteUInt32($value['pos']);
			$PutRoleData -> WriteUInt32($value['count']);
			$PutRoleData -> WriteUInt32($value['maxcount']);
			$PutRoleData -> WriteOctets($value['data']);
			$PutRoleData -> WriteUInt32($value['proctype']);
			$PutRoleData -> WriteUInt32($value['expire_date']);
			$PutRoleData -> WriteUInt32($value['guid1']);
			$PutRoleData -> WriteUInt32($value['guid2']);
			$PutRoleData -> WriteUInt32($value['mask']);
		}
	}
	$PutRoleData -> WriteCUInt32($GRoleData['storehouse']['materialC']);
	if ($GRoleData['storehouse']['materialC'] > 0){
		foreach ($GRoleData['storehouse']['material'] as $value){
			$PutRoleData -> WriteUInt32($value['id']);
			$PutRoleData -> WriteUInt32($value['pos']);
			$PutRoleData -> WriteUInt32($value['count']);
			$PutRoleData -> WriteUInt32($value['maxcount']);
			$PutRoleData -> WriteOctets($value['data']);
			$PutRoleData -> WriteUInt32($value['proctype']);
			$PutRoleData -> WriteUInt32($value['expire_date']);
			$PutRoleData -> WriteUInt32($value['guid1']);
			$PutRoleData -> WriteUInt32($value['guid2']);
			$PutRoleData -> WriteUInt32($value['mask']);
		}
	}
	if ($sVer >= 80){
		$PutRoleData -> WriteUByte($GRoleData['storehouse']['cardCap']);
		$PutRoleData -> WriteCUInt32($GRoleData['storehouse']['generalcardC']);
		if ($GRoleData['storehouse']['generalcardC'] > 0){
			foreach ($GRoleData['storehouse']['generalcard'] as $value){
				$PutRoleData -> WriteUInt32($value['id']);
				$PutRoleData -> WriteUInt32($value['pos']);
				$PutRoleData -> WriteUInt32($value['count']);
				$PutRoleData -> WriteUInt32($value['maxcount']);
				$PutRoleData -> WriteOctets($value['data']);
				$PutRoleData -> WriteUInt32($value['proctype']);
				$PutRoleData -> WriteUInt32($value['expire_date']);
				$PutRoleData -> WriteUInt32($value['guid1']);
				$PutRoleData -> WriteUInt32($value['guid2']);
				$PutRoleData -> WriteUInt32($value['mask']);
			}
		}
	}
	$PutRoleData -> WriteUInt32($GRoleData['task']['reserved']);
	$PutRoleData -> WriteOctets($GRoleData['task']['task_data']);
	$PutRoleData -> WriteOctets($GRoleData['task']['task_complete']);
	$PutRoleData -> WriteOctets($GRoleData['task']['task_finishtime']);
	$PutRoleData -> WriteCUInt32($GRoleData['task']['task_inventoryC']);
	if ($GRoleData['task']['task_inventoryC'] > 0){
		foreach ($GRoleData['task']['task_inventory'] as $value){
			$PutRoleData -> WriteUInt32($value['id']);
			$PutRoleData -> WriteUInt32($value['pos']);
			$PutRoleData -> WriteUInt32($value['count']);
			$PutRoleData -> WriteUInt32($value['maxcount']);
			$PutRoleData -> WriteOctets($value['data']);
			$PutRoleData -> WriteUInt32($value['proctype']);
			$PutRoleData -> WriteUInt32($value['expire_date']);
			$PutRoleData -> WriteUInt32($value['guid1']);
			$PutRoleData -> WriteUInt32($value['guid2']);
			$PutRoleData -> WriteUInt32($value['mask']);
		}
	}
	$PutRoleData -> Pack(0x1F42); //0x1F42
	return $PutRoleData -> Send("localhost", "29400");
}
?>