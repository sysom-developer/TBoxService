<?php

namespace comm\Model;

use comm\Cache\MyRedis;
use \comm\Model\BaseModel as Model;
use comm\Protocol\Byte;
use comm\Protocol\Time;



class TruckInformation extends Model{

    protected $table = 'truck_information';

    private static $redis_counter = 'counter:Truck_Information_List';

    public  $data = [];
    private static $_instance;

    public static function getInstance($packet, $data){

        self::$_instance = new TruckInformation($packet, $data);

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

        $gps_vehicle_speed = hexdec(substr($data, 10*2, 1*2));
      
        $engine_speed = hexdec(substr($data, 11*2, 2*2)) * 0.125;

        $unix_time = substr($data, 13*2, 4*2);
        $unix_time = Byte::ByteConvert($unix_time);

        $gps_data_status = substr($data, 17*2, 1*2);

        //发动机扭转百分比
        $percent_torque = substr($data, 18*2, 1*2);
        $percent_torque = (hexdec($percent_torque)-125)*0.01;

        //发动机负载百分比
        $engine_percent_load = substr($data, 19*2, 1*2);
        $engine_percent_load = hexdec($engine_percent_load)*0.01;

        //加速踏板位置百分比
        $accelerator = substr($data, 20*2, 1*2);
        $accelerator = hexdec($accelerator)*0.4*0.01;

        //刹车踏板位置百分比
        $brake_pedal_position = substr($data, 21*2, 1*2);
        $brake_pedal_position = hexdec($brake_pedal_position) * 0.4*0.01;

        //瞬时油耗
        $fuel_rate = substr($data, 22*2, 2*2);
        $fuel_rate = Byte::ByteConvert($fuel_rate);
        $fuel_rate = hexdec($fuel_rate)*0.05;

        //冷却液温度
        $engine_coolant_temperature = substr($data, 24*2, 1*2);
        $engine_coolant_temperature = hexdec($engine_coolant_temperature) - 40;

        //进气歧管压力
        $air_inlet_pressure = substr($data, 25*2, 1*2);
        $air_inlet_pressure = hexdec($air_inlet_pressure) * 2;

        //机油压力
        $engine_oil_pressure = substr($data, 26*2, 1*2);
        $engine_oil_pressure = hexdec($engine_oil_pressure) * 0.5;

        //基于转速传感器的车速
        $wheel_based_vehicle_speed = substr($data, 27*2, 2*2);
        $wheel_based_vehicle_speed = Byte::ByteConvert($wheel_based_vehicle_speed);
        $wheel_based_vehicle_speed = hexdec($wheel_based_vehicle_speed/256);

        //燃油温度
        $fuel_temperature = substr($data, 29*2, 1*2);
        $fuel_temperature = hexdec($fuel_temperature) - 40;

        //机油温度
        $engine_oil_temperature = substr($data, 30*2, 2*2);
        $engine_oil_temperature = Byte::ByteConvert($engine_oil_temperature);
        $engine_oil_temperature = hexdec($engine_oil_temperature) * 0.03125 - 273;

        //进气温度
        $inlet_air_temperature = substr($data, 32*2, 1*2);
        $inlet_air_temperature = hexdec($inlet_air_temperature) - 40;

        self::$data = [
            'longitude' => $longitude,
            'ew_indicator' => $ew_indicator,
            'latitude' => $latitude,
            'ns_indicator' => $ns_indicator,
            'gps_vehicle_speed' => $gps_vehicle_speed,
            'engine_speed' => $engine_speed,
            'unix_time' => Time::TimeConvert($unix_time),
            'gps_data_status' => $gps_data_status,
            'percent_torque' => $percent_torque,
            'engine_percent_load' => $engine_percent_load,
            'accelerator' => $accelerator,
            'brake_pedal_position' => $brake_pedal_position,
            'fuel_rate' => $fuel_rate,
            'engine_coolant_temperature' => $engine_coolant_temperature,
            'air_inlet_pressure' => $air_inlet_pressure,
            'engine_oil_pressure' => $engine_oil_pressure,
            'wheel_based_vehicle_speed' => $wheel_based_vehicle_speed,
            'fuel_temperature' => $fuel_temperature,
            'engine_oil_temperature' => $engine_oil_temperature,
            'inlet_air_temperature' => $inlet_air_temperature,

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
        $my_redis = MyRedis::getInstance();

        $count = $this->get_redis_list_count();

        $data = self::$data;
        $set_key = 'DevId:'.$data['device_id'];
        $list_key = $data['device_id'] . ':' . 'Truck_Information';

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

        $data_str = 'longitude:' .$data['longitude'] ."\n".
            'ew_indicator:' .$data['ew_indicator']."\n".
            'latitude:' .$data['latitude']."\n" .
            'ns_indicator:'.$data['ns_indicator']."\n".
            'gps_vehicle_speed:'.$data['gps_vehicle_speed']."\n".
            'engine_speed :'.$data['engine_speed']."\n" .

            'unix_time:' .$data['unix_time'] ."\n".
            'gps_data_status:' .$data['gps_data_status'] ."\n".
            'percent_torque:' .$data['percent_torque'] ."\n".
            'engine_percent_load:' .$data['engine_percent_load'] ."\n".
            'accelerator:' .$data['accelerator'] ."\n".
            'brake_pedal_position:' .$data['brake_pedal_position'] ."\n".
            'fuel_rate:' .$data['fuel_rate'] ."\n".
            'engine_coolant_temperature:' .$data['engine_coolant_temperature'] ."\n".
            'air_inlet_pressure:' .$data['air_inlet_pressure'] ."\n".
            'engine_oil_pressure:' .$data['engine_oil_pressure'] ."\n".
            'wheel_based_vehicle_speed:' .$data['wheel_based_vehicle_speed'] ."\n".
            'fuel_temperature:' .$data['fuel_temperature'] ."\n".
            'engine_oil_temperature:' .$data['engine_oil_temperature'] ."\n".
            'inlet_air_temperature:' .$data['inlet_air_temperature'] ."\n";

        echo $data_str . PHP_EOL;

        file_put_contents($data_file_name .'MSG_' .$_MSG_ID, $data_str,FILE_APPEND);

    }
}