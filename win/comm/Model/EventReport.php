<?php

namespace comm\Model;

use comm\Cache\MyRedis;
use \comm\Model\BaseModel as Model;
use comm\Protocol\Byte;
use comm\Protocol\Time;


class EventReport extends Model{

    protected $table = 'event_report';
    private static $redis_counter = 'counter:Event_Report_List';

    public static $data = [];
    private static $_instance;

    public static function getInstance($packet, $data){

        self::$_instance = new EventReport($packet, $data);

        return self::$_instance;
    }

    function __construct($packet, $data){

        $this->init($packet, $data);
        parent::__construct();
    }


    public function init($packet, $data){
        //引擎状态
        $engine_status = substr($data, 0, 1*2);
        //手刹状态
        $parking_brake_status = substr($data, 1*2, 1*2);
        //定速巡航状态
        $cruise_control_status = substr($data, 2*2, 1*2);
        //车辆电瓶电压值
        $car_battery_value = substr($data, 3*2, 2*2);
        $car_battery_value = hexdec(Byte::ByteConvert($car_battery_value))*0.05;

        //车辆电瓶电压状态
        $car_battery = substr($data, 5*2, 1*2);

        $longitude = Byte::Parse_Latitude(substr($data, 6*2, 4*2));
        $ew_indicator = substr($data, 10*2, 1*2);

        $latitude = Byte::Parse_Latitude(substr($data, 11*2, 4*2));
        $ns_indicator = substr($data, 15*2, 1*2);

        $gps_vehicle_speed = substr($data, 16*2, 1*2);

        $engine_speed = substr($data, 17*2, 2*2);

        $unix_time = substr($data, 19*2, 4*2);
        $unix_time = Byte::ByteConvert($unix_time);


        self::$data = [
            'engine_status' => $engine_status,
            'parking_brake_status' => $parking_brake_status,
            'cruise_control_status' => $cruise_control_status,
            'car_battery_value' => $car_battery_value,
            'car_battery' => $car_battery,

            'longitude' => $longitude,
            'ew_indicator' => $ew_indicator,
            'latitude' => $latitude,
            'ns_indicator' => $ns_indicator,

            'gps_vehicle_speed' => $gps_vehicle_speed,

            'engine_speed' => $engine_speed,

            'unix_time' => Time::TimeConvert($unix_time),

            'device_id' => Byte::Hex2String($packet->_DEV_ID),
            'create_time' => time()
        ];

    }


    function save(){

        self::$_db->insert($this->table, self::$data);

    }

    function get_redis_list_count(){
        $my_redis = MyRedis::getInstance();

        //获取计数器最高值
        $count =  $my_redis->hget(self::$redis_counter, self::$data['device_id']);
        if($count === false){
            $my_redis->hset(self::$redis_counter, self::$data['device_id'], 0);
            $count = 0;
        }
        $count++;
        $my_redis->hIncrBy(self::$redis_counter, self::$data['device_id'], 1);
        return $count;

    }

    function cached(){
        global $conf;

        $data = self::$data;
        $my_redis = MyRedis::getInstance();

        $count = $this->get_redis_list_count();

        $set_key = 'DevId:'.$data['device_id'];
        $list_key = $data['device_id'] . ':' . 'Event_Report';

        //设备id键集合添加元素
        $result1 = $my_redis->sadd($set_key, $list_key);


        $last_time = $my_redis->get('last_time');
        $current_time = $last_time + $conf['calculate_time'] * 60;
        $timing_key = $list_key .':'. $last_time .'-' .$current_time;

        $my_redis->zadd($list_key, $last_time, $timing_key);



        $hash_key = $list_key. ':' . $count;
        $hash_value = $data;


        //事件列表添加值
        $result = $my_redis->rPush($timing_key, $hash_key);

        //列表值指向hash
        $result3 = $my_redis->hMset($hash_key, $hash_value);

    }

    public function echo_log($data_file_name, $_MSG_ID){
        $data = self::$data;

        $data_str = 'engine_status:' .$data['engine_status'] ."\n".
            'parking_brake_status:' .$data['parking_brake_status']."\n".
            'cruise_control_status:' .$data['cruise_control_status']."\n" .
            'car_battery_value:'.$data['car_battery_value']."\n".
            'car_battery:'.$data['car_battery']."\n".
            'longitude :'.$data['longitude']."\n" .
            'ew_indicator:' .$data['ew_indicator'] ."\n".
            'latitude:'.$data['latitude'] ."\n".
            'ns_indicator:' .$data['ns_indicator'] ."\n" .
            'gps_vehicle_speed'. $data['gps_vehicle_speed'] ."\n".
            'engine_speed:' .$data['engine_speed'] ."\n" .
            'unix_time:' .$data['unix_time'] ."\n";

        file_put_contents($data_file_name .'MSG_' .$_MSG_ID, $data_str);

    }

}