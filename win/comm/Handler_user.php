<?php

namespace comm;
use \GatewayWorker\Lib\Gateway;
use comm\Protocol\FID_ACK;
use comm\Protocol\Packet;
use comm\Protocol\Byte;
use comm\Cache\MyRedis;
class Handler_user {

    public static function con2user($client_id,$data, $data_file_name)
    {
        global $error_code;
        $packet = new Packet($data);
        if(!Gateway::isUidOnline($packet->Equipment_ID))
        {
            Gateway::bindUid($client_id, $packet->Equipment_ID);
        }
        
        $result = self::message_handler($packet, $data_file_name);
       /* Gateway::sendToClient($client_id,$result_set);*/
        return $result;
    }
    public static function validate_user($token)
    {
        $my_redis = MyRedis::getInstance();
        $value=$my_redis->get($token);
        if($value=='1')
        {
            
            return true;
        }
        return false;
    }
}