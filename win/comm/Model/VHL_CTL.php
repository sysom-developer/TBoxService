<?php

namespace comm\Model;
use \comm\Model\BaseModel as Model;
use comm\Protocol\Byte;
use comm\Protocol\Time;
use comm\Cache\MyRedis;
use \GatewayWorker\Lib\Gateway;

class VHL_CTL extends Model{
    
    protected $table = 'VHL_CTL';
    public static $data = [];
    private static $redis_counter = 'counter:VHL_CTL_LIST';
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
        $message_all=$packet->message;
        $sql_data=array();
        foreach ($message_all as $key => $value) {
            $field=substr($value,2);
            switch (substr($value,0,2)) {
                case '10': 
                $message_arr=explode('7d',$field);
                $RemoteAC=array();
                foreach ($message_arr as $key1 => $value1) {
                        $value2=substr($value1,2);
                        switch (substr($value1,0,2)) {
                            case '11':
                                $RemoteAC['RemoteAC_Mode']=hexdec($value2);
                                break;
                            case '12':
                                $RemoteAC['RemoteAC_Temperature_Target']=hexdec($value2);
                                break;
                            case '13':
                                $RemoteAC['RemoteAC_Working_Time']=hexdec($value2);
                            case '14':
                                $RemoteAC['RemoteAC_Temperature_Gears']=hexdec($value2);
                            case '15':
                                $RemoteAC['RemoteAC_Wind_Gears']=hexdec($value2);    
                                break;
                            case '16':
                                $RemoteAC['RemoteAC_Run_mode']=hexdec($value2); break;
                            case '17':
                                $RemoteAC['RemoteAC_Are_FlowMode']=hexdec($value2); break;
                            case '18':
                                $RemoteAC['RemoteAC_Are_CompressorStatus']=hexdec($value2); 
                                    break;           
                            default:
                                # code...
                                break;
                        }
                }
            $sql_data['RemoteAC']=json_encode($RemoteAC);
            break;
            case '11':
                $RemoteDoor['RemoteDoor_Command']=hexdec($field);
                $sql_data['RemoteDoor']=json_encode($RemoteDoor);
                break; 
            case 'f1':
                $RemoteControl_Extended_Object=array();
                $arr_two=explode('7d',$field);
                foreach ($arr_two as $key3 => $value3) {
                    $field=substr($value3,2);
                   switch (substr($value3,0,2)) {
                       case '20':
                           array_push($RemoteControl_Extended_Object,hexdec($field));
                           break;
                       case '21':
                           array_push($RemoteControl_Extended_Object,$this->get_RCTL_VHL_PARAMS($field));
                           break;
                       default:
                           break;
                   }
                }
                $sql_data['RemoteControl_Extended_Object']=json_encode($RemoteControl_Extended_Object);
                break;   
            }
            $packet->value=$sql_data;
             $this->save($packet);
        }
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
    function save($packet){
        $my_redis = MyRedis::getInstance();
        $count=$this->get_redis_list_count($packet->Equipment_ID);
        $list_key = $this->table. ':' . $packet->Equipment_ID ;
        $hash_key = $list_key. ':' . $count;
        //列表值指向hash
        $result = $my_redis->rPush($list_key, $hash_key);
        $result3 = $my_redis->hMset($hash_key, $packet->value);
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