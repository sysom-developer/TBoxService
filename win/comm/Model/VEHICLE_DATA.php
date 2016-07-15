<?php

namespace comm\Model;
use \comm\Model\BaseModel as Model;
use comm\Protocol\Byte;
use comm\Protocol\Time;
use comm\Cache\MyRedis;


class VEHICLE_DATA extends Model{

    protected $table = 'vehicle_data';
    public static $data = [];
    private static $redis_counter = 'counter:VEHICLE_DATA_LIST';
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

        $sql_data['TEAU']=hexdec(substr($message_all, 0, 1*2));
        $sql_data['T_HUILE']=hexdec(substr($message_all, 2, 1*2));
        $sql_data['NIV_HUILE']=hexdec(substr($message_all, 4, 1*2));
        $sql_data['NIV_CARB']=hexdec(substr($message_all, 6, 1*2));
        $sql_data['CONSO']=hexdec(substr($message_all, 8, 4*2));
        $sql_data['AUTONOMIE']=hexdec(substr($message_all, 16, 2*2));
        $sql_data['KM_TOTAL']=hexdec(substr($message_all, 20, 3*2));
        $sql_data['KM_MAINT']=hexdec(substr($message_all, 26, 2*2));
        $sql_data['NB_JOUR_MAINTENANCE']=hexdec(substr($message_all, 30, 2*2));
        $sql_data['SIGN_MAINT']=hexdec(substr($message_all, 34, 1*2));
        $sql_data['SIGN_ECHEANCE']=hexdec(substr($message_all, 36, 1*2));
        $sql_data['ETAT_PRINCIP_SEV']=hexdec(substr($message_all, 38, 1*2));
        $sql_data['INFO_CRASH']=hexdec(substr($message_all, 40, 1*2));
        $sql_data['ALERTES']=hexdec(substr($message_all, 42, 5*2));
        $packet->value=$sql_data;
        return  $packet;
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


    
}