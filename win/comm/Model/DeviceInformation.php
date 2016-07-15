<?php

namespace comm\Model;

use comm\Cache\MyRedis;
use \comm\Model\BaseModel as Model;
use comm\Protocol\Byte;


class DeviceInformation extends Model{

    protected $table = 'device_information';

    public static $data = [];
    private static $_instance;

    public static function getInstance($packet, $data){

        self::$_instance = new DeviceInformation($packet, $data);

        return self::$_instance;
    }

    function __construct($packet, $data){

        $this->init($packet, $data);
        parent::__construct();
    }


    public function init($packet, $data){
        $VIN_code = substr($data, 0, 17*2);

        $hardware_version_LSB = chr(hexdec(substr($data, 17*2, 1*2)));
        $hardware_version_MSB = chr(hexdec(substr($data, 18*2, 1*2)));

        $software_version_LSB = chr(hexdec(substr($data, 19*2, 1*2)));
        $software_version_MSB = chr(hexdec(substr($data, 20*2, 1*2)));

        $is_activated = substr($data, 21*2, 1*2);

        $wake_soure = substr($data, 22*2, 1*2);

        $obd_conf_version = substr($data, 23*2, 1*2);

        self::$data = [
            'VIN_code' => $VIN_code,
            'hardware_version' => $hardware_version_MSB . '.' . $hardware_version_LSB,
            'software_version' => $software_version_MSB . $software_version_LSB,
            'is_activated' => $is_activated,
            'wake_soure' => $wake_soure,

            'obd_conf_version' => $obd_conf_version,

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
//        $h_key = 'Device_Information:'.$data['create_time'];
//
//        $my_redis->sadd($s_key, $h_key);
//        $my_redis->hMset($h_key, $data);
    }


    public function echo_log($data_file_name, $_MSG_ID){
        $data = self::$data;

        $data_str = 'VIN_code:' .$data['VIN_code'] ."\n".
            'hardware_version:' .$data['hardware_version']."\n".
            'software_version:' .$data['software_version']."\n" .
            'is_activated:'.$data['is_activated']."\n".
            'wake_soure:'.$data['wake_soure']."\n".
            'obd_conf_version :'.$data['obd_conf_version']."\n" .
            'device_id:' .$data['device_id'] ."\n";

        file_put_contents($data_file_name .'MSG_' .$_MSG_ID, $data_str);

    }

}