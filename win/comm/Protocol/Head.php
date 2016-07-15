<?php
namespace comm\Protocol;
class Head{
/*	public $Protocol_version;
	public $Equipment_ID;
	public $Equipment_ID_Type;
	public $Message_id;
	public $Service_type;
	public $message_type;
	public $hour_date;
	public $Message_protocol_version;
	public $data_length;*/
	private $sep;
	private $data;
	function __construct(){
		$sp=chr(0x7e);
		$this->sep=[chr(0x01),$sp.chr(0x02),$sp.chr(0x03)
		,$sp.chr(0x04),$sp.chr(0x05),$sp.chr(0x06)
		,$sp.chr(0x07),$sp.chr(0x08),$sp.chr(0x09)];
		$this->data=['Protocol_version'=>'0',
		'Equipment_ID'=>'0','Equipment_ID_Type'=>'0'
		,'Message_id'=>'0','Service_type'=>'0',
		'message_type'=>'0','hour_date'=>'0','Message_protocol_version'=>'0','data_length'=>'1111'];
	}
	public function setdata($data){
		$this->data=array_merge($this->data,$data);
	}

	public function getbyte(){
		$return="";
		for ($i=0; $i < sizeof($this->sep); $i++) { 
			$return.=$this->sep[$i].$this->data[$i];
		}
		return $return;
	}
}