<?php

namespace comm\Protocol;

class Packet
{
    /**
     * T-BOX ID
     * @var
     */
    public $Equipment_ID;
    /**
     * T-BOX类型 
     * @var
     */
    public $Equipment_ID_Type;

    /**
     * 消息ID
     * @var
     */
    public $Message_id;

    /**
     * 服务类型
     * @var
     */
    public $Service_type;

    /**
     * 协议版本
     * @var
     */
    public $Protocol_version;
    /**
     * 消息类型
     * @var
     */
    public $message_type;
     /**
     * 发送时间
     * @var
     */
    public $hour_date;
     /**
     * T-BOX消息协议版本号
     * @var
     */
    public $Message_protocol_version;
    /**
     * 消息
     * @var
     */
    public $message;
    public $value;
/*    private $_MESSAGE;
    private $_message_arr*/

    function __construct($data)
    {
        $this->init($data);
      
        $this->init_Equipment_ID();
        /*$this->init_message_arr();*/

    }
    public  function init($data){
        global $error_code;
       /* $data_arr1 = substr($data,0,42);
        $data_arr2=substr($data,42);*/
        $data_arr=explode('7e',Byte::String2Hex($data));
        $head=array_slice($data_arr,0,8);
       /* $hx_data1 = Byte::String2Hex($data_arr1);
        $hx_data2 = Byte::String2Hex($data_arr2);
        $head=explode('7e',$hx_data1);*/

        $this->message=array_slice($data_arr,9);
        /*        if($data == $error_code['SEP']){
            return false;
        }
        if(strlen($data) == 6){
            return false;
        }*/
        $arr=(array)$error_code['ONE_LEVEL'];
        foreach ($arr as $key1 => $value1) {
            $field=$value1;
            foreach ($head as $key2 => $value2) {
                $type=substr($value2,0,2);
                if($type==$field)
                {
                    $value=byte::Escape(substr($value2,2));
                    $this->$key1=$value;
                    unset($head[$key2]);
                }
            }
            
/*            foreach ($error_code['Message_type'] as $key => $value) {
               if(hexdec($this->message_type)==$key)
               {

               }
            }*/
            
        }
    }
/*    private function init_Protocol_version(){
        $this->Protocol_version=;
    }*/

    private function init_Equipment_ID()
    {
        $this->Equipment_ID=Byte::ASCii2String(Byte::Hex2String($this->Equipment_ID));
    }

    public function getbytes()
    {
        $return="";

        $this->_message_arr = new Message_Arr($message_arr);
    }
}