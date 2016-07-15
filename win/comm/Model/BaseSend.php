<?php

namespace comm\Send;
use comm\Cache\MyRedis;
use comm\Protocol\Byte;
use  comm\Protocol\Head;
use \GatewayWorker\Lib\Gateway;
class BaseSend{
    public $head;
    public $messageData;
   /* protected static $_db = null;*/
    function __construct(){
/*        if(!(self::$_db instanceof \medoo)) {
            self::$_db = new \medoo( require_once __DIR__ .'/../../config/database.php' );
        }*/
        $this->head=new Head();
    }
    public function send(){
        $data=$this->head->getbyte().$this->messageData;
        $data_length=strlen($data=$this->head->getbyte().$this->messageData);
        $arr=['data_length'=>$data_length];
        $this->head->setdata($arr);
        
        Gateway::sendToClient($data);
    }
    protected function formatSendData(){
        
    }
    protected function formatHead(){
        
    }
}