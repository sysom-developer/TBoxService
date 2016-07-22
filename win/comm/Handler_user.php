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
       
        $packet = self::message_handler($data, $data_file_name);
        $tbox_status=Gateway::getClientIdByUid($_SESSION('tbox'));
        if(!$tbox_status)
            return '设备不在线';
        else
        {
            Gateway::sendToUid($_SESSION('tbox'),$packet);
            return '请求已发送';
        }
        
        
    }
    public static function validate_user($token,$client_id)
    {
        $my_redis = MyRedis::getInstance();
        $value=$my_redis->get($token);
        if($value)
        {
            if(!Gateway::isUidOnline($token))
            {
                Gateway::bindUid($client_id, $token);
            }
            $_SESSION['token']=$token;
            $_SESSION['tbox']=$value;
            return true;
        }
        return false;
    }
}