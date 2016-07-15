<?php

namespace comm\Model;

use comm\Cache\MyRedis;
use \comm\Model\BaseSend as Send;
use comm\Protocol\Byte;

class STATUS_INFO extends Send{

    function __construct(){

        parent::__construct();
    }
    public function formatHead($arr){
    	$this->head->setdata($arr);
    
    }
    public function formatSendData($status){
    	$data=chr(0x7e).chr(0x10).$status;
    	$this->messageData=$data;
    }
 }