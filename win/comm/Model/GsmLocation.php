<?php

namespace comm\Model;

use comm\Cache\MyRedis;
use \comm\Model\BaseModel as Model;
use comm\Protocol\Byte;
use comm\Protocol\Time;


class GsmLocation extends Model{

    protected $table = 'gsm_location';

    private static $data = [];
    private static $_instance;

    public static function getInstance($packet, $data){

        self::$_instance = new GsmLocation($packet, $data);

        return self::$_instance;
    }


    function __construct($packet, $data){

        $this->init($packet, $data);
        parent::__construct();
    }


    public function init($packet, $data){
        $longitude = Byte::Parse_Latitude(substr($data, 0, 4*2));
        $ew_indicator = substr($data, 4*2, 1*2);

        $latitude = Byte::Parse_Latitude(substr($data, 5*2, 4*2));
        $ns_indicator = substr($data, 9*2, 1*2);

        $gps_data_status = substr($data, 10*2, 1*2);
        $unix_time = substr($data, 11*2, 4*2);

        self::$data = [
            'longitude' => $longitude,
            'ew_indicator' => $ew_indicator,

            'latitude' => $latitude,
            'ns_indicator' => $ns_indicator,

            'gps_data_status' => $gps_data_status,
            'unix_time' => Time::TimeConvert($unix_time),

            'device_id' => Byte::Hex2String($packet->_DEV_ID),
            'create_time' => time()
        ];

    }


    function save(){

        self::$_db->insert($this->table, self::$data);

    }

    function cached(){
//        $my_redis = MyRedis::getInstance();
//
//        $data = self::$data;
//        $s_key = 'DevId:'.$data['device_id'];
//        $h_key = 'Gsm_Location:'.$data['create_time'];
//
//        $my_redis->sadd($s_key, $h_key);
//        $my_redis->hMset($h_key, $data);
    }

    public function echo_log($data_file_name, $_MSG_ID){
        $data = self::$data;

        $data_str = 'longitude:' .$data['longitude'] ."\n".
            'ew_indicator:' .$data['ew_indicator']."\n".
            'latitude:' .$data['latitude']."\n" .
            'ns_indicator:'.$data['ns_indicator']."\n".
            'gps_data_status:'.$data['gps_data_status']."\n".
            'unix_time :'.$data['unix_time']."\n" .
            'device_id:' .$data['device_id'] ."\n";

        file_put_contents($data_file_name .'MSG_' .$_MSG_ID, $data_str);

    }

}