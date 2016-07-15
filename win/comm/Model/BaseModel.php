<?php

namespace comm\Model;
use comm\Cache\MyRedis;
use comm\Protocol\Byte;
class BaseModel{

   /* protected static $_db = null;*/
    function __construct(){
/*        if(!(self::$_db instanceof \medoo)) {
            self::$_db = new \medoo( require_once __DIR__ .'/../../config/database.php' );
        }*/

    }
    public function get_RCTL_VHL_PARAMS($data){
    	$sql_data=array();
    	$arr=explode('7c', $data);
    	foreach ($arr as $key => $value) {
    		$field=substr($value,2);
    		switch (substr($value,0,2)) {
    			case '11':
    				$sql_data['CTLSwitch_Status']=hexdec($field);
    				break;
    			case '12':
    				$sql_data['CTLMode']=hexdec($field);
    				break;
    			case '13':
    				$sql_data['CTLObjPosition']=hexdec($field);
    				break;
    			case '14':
    				$sql_data['CTLDirection']=hexdec($field);
    				break;
    			case '15':
    				$sql_data['CTLStrength']=hexdec($field);
    				break;
    			case '16':
    				$sql_data['GoalValue1']=hexdec($field);
    				break;
    			case '17':
    				$sql_data['UnitOfValue1']=hexdec($field);
    				break;
    			case '18':
    				$sql_data['GoalValue2']=hexdec($field);
    				break;
    			case '19':
    				$sql_data['UnitOfValue2']=hexdec($field);
    				break;
    			case '20':
    				$sql_data['ExtendValue1']=hexdec($field);
    				break;
    			case '21':
    				$sql_data['ExtendValue2']=hexdec($field);
    				break;
    			default:
    				# code...
    				break;
    		}
    	}
    	return $sql_data;
    }
    public function check_add($message,&$sql_data,&$message_all){
    	foreach ($message as $key => $value) {
           if(substr($value,0,2)=="10")
           {
            $message_all=substr($value,2);
           }
           if(substr($value,0,2)=="20")
            {
            	$sql_data['ADDITIONAL_DATA']=json_encode($this->get_adddata(substr($value,2)));
            }
        }
    }

    public function get_adddata($data){
    	/*global $error_code;
    	$e_vehicle_propulsion=$error_code['vehicle_propulsion'];*/
    	$vehicle_propulsion="";
    	$sql_data=array();
 /*   	for($i=0;i<=strlen($vehicle_propulsion_2bin);i++)
    	{
    		if($vehicle_propulsion_2bin[$i]==1)
    		{
    			$vehicle_propulsion.=$e_vehicle_propulsion[$i];
    		}
    	}*/
    	$sql_data['vehicle_propulsion']=byte::Hex2bin(substr($message_all, 0, 1*2));
        $sql_data['number_of_passenger']=hexdec(substr($message_all, 2, 1*2));
        $sql_data['Vehicle_speed']=hexdec(substr($message_all, 4, 1*2));
        $sql_data['language']=hexdec(substr($message_all, 6, 1*2));
        $sql_data['crash_information']=byte::Hex2bin(substr($message_all, 8, 1*2));
        $sql_data['back_up_battery_level']=hexdec(substr($message_all, 10, 1*2));
        $sql_data['extra_info']=byte::Hex2bin(substr($message_all, 12, 1*2));
        $sql_data['hardware_version']=byte::Parse_version(substr($message_all, 14, 1*2));
        $sql_data['software_version']=byte::Parse_version(substr($message_all, 16, 1*2));
    }
}