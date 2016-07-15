<?php
/**
 * Created by PhpStorm.
 * User: chenqiuyu
 * Date: 15/11/8
 * Time: 下午4:33
 */

namespace comm\Protocol;


class Message_Arr
{

    public $arr;

    function __construct($message_str){
//        echo "message:";
//        var_dump($message_str);
        echo PHP_EOL;
        for(;$message_str != false;){

            $message = new Message($message_str);
            if($message->_CHECKSUM_RESULT){
                $this->arr[] = $message;
            }
            $message_str = substr($message_str, $message->_TOTAL_LENGTH + 2);
        }

    }
}