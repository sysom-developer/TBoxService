<?php

namespace comm;
use \GatewayWorker\Lib\Gateway;
use comm\Protocol\FID_ACK;
use comm\Protocol\Packet;
use comm\Protocol\Byte;
use comm\Cache\MyRedis;
class Handler_tbox {
    public static function con2tbx($client_id,$data, $data_file_name)
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
    static function message_handler($packet, $data_file_name)
    {
        global $error_code, $command_table;

        $result_set = [];
        $fun_code = $error_code['Message_type'][$packet->message_type];
        $fun = $command_table[$fun_code];
        $fun_result = $fun($packet, $data_file_name);
        return   "good";
/*        if(is_array($packet->_message_arr->arr)){
            array_walk($packet->_message_arr->arr, function($message) use ($packet, $error_code, $command_table, &$result_set, $data_file_name){
               
                if(!empty($fun_result)){
                    $result_set[] = $fun_result;
                }

            });
        }*/

        $state = $error_code['STATE']['STATE_RECV_OK'];
        $fid_ack = new FID_ACK($packet->_FID, $state);
        $ack = $fid_ack->get_bytes();
        $result_set[] = $ack;
        

        return $result_set;


    }
    protected function _makeServerToObdAck($fid, $status) {
        //  FID 10进制
        $fid = 0x40 + $fid;
        return pack('H*', dechex($fid) . $status . self::SSEP);
    }
    static function echo_packet_header($packet){
        $dev_id = Byte::Hex2String($packet->_DEV_ID);
        $fid = '0x'.$packet->_FID;
        $time = hexdec($packet->_TIME);
        $version = $packet->_PROTOCOL_VERSION;

        $echo_header = 'header'. "\n";
        $echo_header .= "    dev_id: " . $dev_id . "\n";
        $echo_header .= "    fid: " . $fid . "\n";
        $echo_header .= "    time:" . $time . "\n";
        $echo_header .= "    version:" . $version . "\n";
        $echo_header .= "\n";

        echo 'header'. "\n";
        echo "    dev_id: " . $dev_id . "\n";
        echo "    fid: " . $fid . "\n";
        echo "    time:" . $time . "\n";
        echo "    version:" . $version . "\n";
        echo "\n";

        return $echo_header;

    }

    static function echo_packet_messages($packet){
        $message_arr = $packet->_message_arr->arr;
        $i = 0;
        $echo_messages = '';
        if(is_array($message_arr)){
            array_walk($message_arr, function($message) use (&$i, &$echo_messages){
                $i++;

                $msg_id = '0x' .$message->_MSG_ID;
                $data_size = $message->_DATA_SIZE;
                $data = $message->_DATA;
                $checksum = $message->_CHECKSUM;
                $calculate_checksum = $message->_CALCULATE_CHECKSUM;
                echo 'message' . $i . ':'. "\n";
                echo '    MSG_ID:' . $msg_id. "\n";
                echo '    DATA_SIZE:' . $data_size. "\n";
                echo '    DATA:' . $data. "\n";
                echo '    CHECKSUM:' . $checksum. "\n";
                echo '    CALCULATE_CHECKSUM:' . $calculate_checksum. "\n";
                echo "\n";


                $echo_messages .= 'message' . $i . ':'. "\n";
                $echo_messages .= '    MSG_ID:' . $msg_id. "\n";
                $echo_messages .= '    DATA_SIZE:' . $data_size. "\n";
                $echo_messages .= '    DATA:' . $data. "\n";
                $echo_messages .= '    CHECKSUM:' . $checksum. "\n";
                $echo_messages .= '    CALCULATE_CHECKSUM:' . $calculate_checksum. "\n";
                $echo_messages .= "\n";
            });
            return $echo_messages;
        }

        return "not message".PHP_EOL;


    }
}