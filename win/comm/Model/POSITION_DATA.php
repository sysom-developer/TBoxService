<?php

namespace comm\Model;
use \comm\Model\BaseModel as Model;
use comm\Protocol\Byte;
use comm\Protocol\Time;
use comm\Cache\MyRedis;


class POSITION_DATA extends Model{

    protected $table = 'position_data';
    public static $data = [];
    private static $redis_counter = 'counter:POSITION_DATA_LIST';
    private static $_instance;

    public static function getInstance(){
    if (is_null(self::$_instance) || !isset(self::$_instance)) {
        self::$_instance = new self();
        }
     

        return self::$_instance;
    }

    function __construct(){

        parent::__construct();
    }


    public function init($packet){
        $message_all="";
        $sql_data=array();
        $this->check_add($packet->message,&$sql_data,&$message_all);
        $dead_reckoning_position['longitude']
        =Byte::Parse_Latitude(substr($message_all, 0, 4*2));
        $dead_reckoning_position['latitude']=Byte::Parse_Latitude(substr($message_all, 8, 4*2));
        /*$sql_data['dead_reckoning_position']=json_encode($dead_reckoning_position);*/
        $sql_data['dead_reckoning_position']=json_encode($dead_reckoning_position);
        $sql_data['heading']=array();
        $headings=substr($message_all, 18, 27*2);
        $headingarr=array();
        for ($i=0; $i < 9; $i++) {
            $heading=substr($headings,$i*6,6); 
            $heading_time=hexdec(substr($heading,0,2));
            $heading_Longitude=hexdec(substr($heading,2,4))*3;
            $heading_Latitude=hexdec(substr($heading,4,6))*3;
            $headingarr[$i]=$heading_time.'.'. $heading_Longitude.'.'.$heading_Latitude;
        }
        $sql_data['heading']=json_encode($headingarr);
        $sql_data['visible_satellite_number']=hexdec(substr($message_all, 72, 1*2));
        $sql_data['altitude']=hexdec(substr($message_all, 74, 2*2));
        $raw_position['longitude']=Byte::Parse_Latitude(substr($message_all, 78, 4*2));
        $raw_position['latitude']=Byte::Parse_Latitude(substr($message_all, 86, 4*2));
        $sql_data['raw_position']=json_encode($raw_position);
        $sql_data['raw_position_uncertainty_estimate']=hexdec(substr($message_all, 94, 1*2));

        $packet->value=$sql_data;
        return $packet;
        }
       
    


    function save($packet){
        
        $my_redis = MyRedis::getInstance();
        $count=$this->get_redis_list_count($packet->Equipment_ID);

        $list_key = $this->table. ':' . $packet->Equipment_ID ;
        echo $hash_key ;
        $hash_key = $list_key. ':' . $count;
        //列表值指向hash
        echo $hash_key ;
        $result = $my_redis->rPush($list_key, $hash_key);
        $result3 = $my_redis->hMset($hash_key, $packet->value);
    }


      function get_redis_list_count($Equipment_ID){
        $my_redis = MyRedis::getInstance();

        //获取计数器最高值
        $count =  $my_redis->hget(self::$redis_counter, $Equipment_ID);
        if($count === false){
            $my_redis->hset(self::$redis_counter,$Equipment_ID, 0);
            $count = 0;
        }
        $count++;
        $my_redis->hIncrBy(self::$redis_counter, $Equipment_ID, 1);
        return $count;

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