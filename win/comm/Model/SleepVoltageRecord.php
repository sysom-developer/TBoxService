<?php

namespace comm\Model;

use comm\Cache\MyRedis;
use \comm\Model\BaseModel as Model;
use comm\Protocol\Byte;


class SleepVoltageRecord extends Model{

    protected $table = 'sleep_voltage_record';

    private static $data = [];
    private static $_instance;

    public static function getInstance($packet, $data){

        self::$_instance = new SleepVoltageRecord($packet, $data);

        return self::$_instance;
    }


    function __construct($packet, $data){

        $this->init($packet, $data);
        parent::__construct();
    }


    public function init($packet, $data){
        $valid_flag = substr($data, 0, 6*2);


        $voltage_saved_data = substr($data, 6*2, 36*2);

        $voltage_saved_data_arr = str_split($voltage_saved_data, 6*2);

        array_walk($voltage_saved_data_arr, function($v) use($valid_flag, $packet){
            $timestamp = substr($v, 0, 4*2);
            $voltage_saved = substr($v, 4*2, 2*2);
            $voltage_saved = Byte::ByteConvert($voltage_saved);

            $tmp = [
                'valid_flag' => $valid_flag,
                'timestamp' => $timestamp,
                'voltage_saved' => $voltage_saved,

                'device_id' => Byte::Hex2String($packet->_DEV_ID),
                'create_time' => time()
            ];
            self::$data[] = $tmp;

        });

    }


    function save(){
        array_walk(self::$data, function($v){
            self::$_db->insert($this->table, $v);
        });
    }

    function cached(){
//        $my_redis = MyRedis::getInstance();
//        $data = self::$data;
//
//        array_walk($data, function($v) use ($my_redis){
//            $s_key = 'DevId:'.$v['device_id'];
//            $h_key = 'Sleep_Voltage_Record:'.$v['create_time'];
//
//            $my_redis->sadd($s_key, $h_key);
//            $my_redis->hMset($h_key, $v);
//        });

    }
//    function echo_log($data_file_name){
//        $data_str = 'valid_flag:' .$valid_flag ."\n".
//            'timestamp_1:' .$timestamp_1."\n".
//            'voltage_saved_1:' .$voltage_saved_1."\n" .
//
//            'timestamp_2:'.$timestamp_2."\n".
//            'voltage_saved_2:'.$voltage_saved_2."\n".
//
//            'timestamp_3:'.$timestamp_3."\n".
//            'voltage_saved_3:'.$voltage_saved_3."\n".
//
//            'timestamp_4:'.$timestamp_4."\n".
//            'voltage_saved_4:'.$voltage_saved_4."\n".
//
//            'timestamp_5:'.$timestamp_5."\n".
//            'voltage_saved_5:'.$voltage_saved_5."\n".
//
//            'timestamp_6:'.$timestamp_6."\n".
//            'voltage_saved_6:'.$voltage_saved_6."\n";
//
//
//        file_put_contents($data_file_name .'MSG_' .$message->_MSG_ID, $data_str);
//    }

}