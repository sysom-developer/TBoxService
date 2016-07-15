<?php

namespace comm\Model;

use comm\Cache\MyRedis;
use \comm\Model\BaseModel as Model;
use comm\Protocol\Byte;
use comm\Protocol\Time;



class TruckAccelerationInformation extends Model{

    protected $table = 'trunck_acceleration_information';

    public static $data = [];
    private static $_instance;

    public static function getInstance($packet, $data){

        self::$_instance = new TruckAccelerationInformation($packet, $data);

        return self::$_instance;
    }

    function __construct($packet, $data){

        $this->init($packet, $data);
        parent::__construct();
    }


    public function init($packet, $data){
        $axis_arr = [];

        $num = substr($data, 0, 1*2);

        $axis_data = substr($data, 1*2,6*32*2);
        $axis_origin_data = str_split($axis_data, 6*2);
        $unix_time = substr($data, 193*2,4*2);
        $unix_time = Byte::ByteConvert($unix_time);
        array_walk($axis_origin_data, function($v) use (&$axis_arr, $unix_time, $packet){
            $tmp = [
                'x_axis' => Byte::ByteConvert(substr($v, 0, 2*2)),
                'y_axis' => Byte::ByteConvert(substr($v, 2*2, 2*2)),
                'z_axis' => Byte::ByteConvert(substr($v, 4*2, 2*2)),
                'unix_time' => Time::TimeConvert($unix_time),

                'device_id' => Byte::Hex2String($packet->_DEV_ID),
                'create_time' => time(),

            ];
            $axis_arr[] = $tmp;

        });

        self::$data = $axis_arr;

    }


    function save(){
        array_walk(self::$data, function($v){
            self::$_db->insert($this->table, $v);
        });
    }

    function cached(){
        $my_redis = MyRedis::getInstance();
        $data = self::$data;

        array_walk($data, function($v) use ($my_redis){
            $s_key = 'DevId:'.$v['device_id'];
            $h_key = 'Truck_Acceleration_Information:'.$v['create_time'];

            $my_redis->sadd($s_key, $h_key);
            $my_redis->hMset($h_key, $v);
        });
    }

    public function echo_log($data_file_name, $_MSG_ID){
        $data = self::$data;
        $data_str = '';

        array_walk($data, function($v) use (&$data_str){
            $data_str .= 'x_axis:' .$v['x_axis'] ."\n".
                'y_axis:' .$v['y_axis']."\n".
                'z_axis:' .$v['z_axis']."\n" .
                'unix_time:'.$v['unix_time']."\n".
                'device_id:'.$v['device_id']."\n".
                'create_time :'.$v['create_time']."\n";
        });

        file_put_contents($data_file_name .'MSG_' .$_MSG_ID, $data_str);

    }

}