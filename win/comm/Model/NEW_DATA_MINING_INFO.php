<?php

namespace comm\Model;
use \comm\Model\BaseModel as Model;
use comm\Protocol\Byte;
use comm\Protocol\Time;
use comm\Cache\MyRedis;


class NEW_DATA_MINING_INFO extends Model{

    protected $table = 'New_data_mining_info';
    public static $data = [];
    private static $redis_counter = 'counter:NEW_DATA_MINING_INFO_LIST';
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
        $message_all=$packet->message[0];
        $message_arr=explode('7d',$message_all);
        $message_time_arr=array();
        $message_data_arr=array();

        foreach ($message_arr as $key => $value) {
            if(substr($value,0,2)=="01")
            {
            array_push($message_time_arr,substr($value,2));
            }
            else if(substr($value,0,2)=="02")
            {
            array_push($message_data_arr,substr($value,2));
            }
        }
        $i=0;
        foreach ($message_data_arr as $key1 => $value1) {
            $arr=explode('7c',$value1);
            $sql_data=array();
            foreach ($arr as $key => $value) {
                $name=substr($value,0,2);
                $field=substr($value,2);
                switch ($name) {
                    case '05':
                    $Coordinates=array();
                        $Coordinates['longitude']=Byte::Parse_Latitude(substr($field, 0, 4*2));
                        $Coordinates['latitude']=Byte::Parse_Latitude(substr($field, 8, 4*2));
                        $sql_data['Coordinates']=$Coordinates;
                        break;
                    case '08':
                    $sql_data['Total_mileage']=hexdec($field);
                     break;
                     case '09':
                    $sql_data['Remain_Mileage']=hexdec($field);
                     break;
                    case '0a':
                    $sql_data['Mileage_before_maintenance']=hexdec($field)*20;
                     break;
                    case '0b':
                    $sql_data['Temperature_of_engine_water']=hexdec($field);
                     break;
                    case '0c':
                    $sql_data['Temperature_of_engine_oil']=hexdec($field);
                     break;
                     case '0d':
                    $sql_data['Level_of_engine_oil']=hexdec($field)*0.01;
                     break;
                     case '0c':
                    $sql_data['Temperature_of_engine_oil']=hexdec($field)*0.01;
                     break;
                     case '0d':
                    $sql_data['Level_of_engine_oil']=hexdec($field)*0.01;
                     break;
                     case '0e':
                    $sql_data['Level_of_fuel']=hexdec($field)*0.01;
                     break;
                     case '0f':
                    $sql_data['Total_volume_of_fuel_consumed']=hexdec($field)*80;
                     break;
                     case '10':
                    $sql_data['Mileage_before_maintenance']=Byte::alert($field);
                     break;
                     case '11':
                    $sql_data['Vehicle_State']=$field;
                     break;
                     case '12':
                     $Engine_State=array();
                    $Engine_State['Engine_State']=substr($field,0,2);
                    $Engine_State['Engine_State for-Hybird']=substr($field,2,4);
                    $sql_data['Engine_State']=$Engine_State;
                     break;
                     case '13':
                     $T_BOX_RSSI=array();
                    $T_BOX_RSSI['Network_Use']=substr($field,0,2);
                    $T_BOX_RSSI['RSSI']=substr($field,2,4);
                    $sql_data['T-BOX_RSSI']=$T_BOX_RSSI;
                     break;
                     case '1b':
                    $sql_data['Each_Mileage']=hexdec($field);
                     break;
                    case '1c':
                    $sql_data['Each_Volume_of_fuel_consumed']=hexdec($field);
                     break;
                     case '1d':
                    $sql_data['T-BOX_Satellite_Numbers']=hexdec($field);
                     break;
                     case '1e':
                    $sql_data['T-BOX_BACK-UP_BATTERY']=hexdec($field);
                     break;
                     case '1f':
                    $sql_data['Days_before_maintenance']=hexdec($field);
                     break;
                     case '20':
                     $speed=array();
                    $speed['usable_speed']=hexdec($field);
                    $speed['current_speed']=hexdec($field);
                    $sql_data['speed']=$speed;
                     break;
                     case '21':
                    $sql_data['Rapid_acceleration']=hexdec($field);
                     break;
                     case '22':
                    $sql_data['Rapid_deceleration']=hexdec($field);
                     break;
                    case '23':
                        $arr2=explode('7b',$field);
                        $Intense_driving=array();
                        $Intense_driving['coordinate']=array();
                        foreach ($arr2 as $key => $value) {
                            if(substr($value,0,2)=="01")
                                $Intense_driving['speed']=substr($value,2);
                            elseif(substr($value,0,2)=="02")
                            {
                                $coordinate['x']=hexdec(substr($value,4,6).substr($value,2,4));
                                $coordinate['y']=hexdec(substr($value,8,10).substr($value,6,8));
                                $coordinate['z']=hexdec(substr($value,12,14).substr($value,10,12));
                                array_push($Intense_driving['coordinate'],$coordinate);
                            }
                        }
                        $sql_data['Intense_driving']=$Intense_driving;
                     break;
                     case '24':
                     $Heading=array();
                    $Heading['usable_bearing']=hexdec(substr($field,0,2));
                    $Heading['bearing']=hexdec(substr($field,2));
                    $sql_data['Heading']=$Heading;
                     break;
                     case '25':
                    $sql_data['Ignition']=hexdec($field);
                     break;
                     case '26':
                    $sql_data['flameout']=hexdec($field);
                     break;
                      case '27':
                      $altitude=array();
                    $altitude['usable_altitude']=hexdec(substr($field,0,2));
                    $altitude['altitude']=hexdec(substr($field,2));
                    $sql_data['altitude']=$altitude;
                     break;
                      case '28':
                      $acceleration=array();
                    $acceleration['usable_acceleration']=hexdec(substr($field,0,2));
                    $acceleration['acceleration']=hexdec(substr($field,2));
                    $sql_data['acceleration']=$acceleration;
                     break;
                      case '29':
                      $gps_stat=array();
                    $gps_stat['gps_status']=hexdec(substr($field,0,2));
                    $gps_stat['gps_time']=Time::TimeConvert($field);
                    $sql_data['gps_stat']=$gps_stat;
                     break;
                      case '3a':
                    $sql_data['engine_rolling_speed']=hexdec($field);
                     break;
                      case '3b':
                    $sql_data['intakeMAP']=hexdec($field);
                     break;
                      case '3c':
                    $sql_data['intakeAir']=hexdec($field)*0.01;
                     break;
                      case '3d':
                    $sql_data['throttle']=hexdec($field);
                     break;
                    default:
                    break;
                }
            }
            $sql_data['time']=Time::TimeConvert($message_time_arr[$i]);
            $packet->value=$sql_data;
             $this->save($packet);
            $i++;
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