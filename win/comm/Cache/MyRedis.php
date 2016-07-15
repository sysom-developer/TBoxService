<?php

namespace comm\Cache;


class MyRedis {

    private  static $redis;
    private static $my_redis;

    /**
     * 构造函数
     */
    private function __construct() {
        $redis_conf = require_once __DIR__ .'/../../Workerman/config/redis.php';
        self::$redis = new \Redis();
        self::$redis->connect($redis_conf['host'], $redis_conf['port']);
        self::$redis->auth($redis_conf['password']);
    }

    /**
     * @return MyRedis
     */
    public static function getInstance(){
        if(!(self::$my_redis instanceof self)){
            self::$my_redis = new MyRedis();
        }
        return self::$my_redis;
    }
    /**
     * 字符串
     * @param $key
     * @param $value
     * @param int $timeOut
     * @return bool
     */
    public function set($key, $value, $timeOut=0) {
        $retRes = self::$redis->set($key, $value);
        if ($timeOut > 0)
            self::$redis->expire('$key', $timeOut);
        return $retRes;
    }

    /**
     * 集合
     * @param $key
     * @param $value
     * @return int
     */
    public function sadd($key,$value){
        return self::$redis->sadd($key,$value);
    }


    /**
     * 取集合对应元素
     * @param $setName
     * @return array
     */
    public function smembers($setName){
        return self::$redis->smembers($setName);
    }

    /**
     * HASH类型
     * @param string $tableName  表名字key
     * @param $field
     * @param $value
     * @return int
     */
    public function hset($tableName,$field,$value){
        return self::$redis->hset($tableName,$field,$value);
    }

    /**
     * @param $tableName
     * @param $field
     * @return string
     */
    public function hget($tableName,$field){
        return self::$redis->hget($tableName,$field);
    }

    public function hMset($key, $hashKeys){
        return self::$redis->hMset($key, $hashKeys);
    }


    /**
     * @param $keyArray
     * @param $timeout
     * @return bool|string
     */
    public function sets($keyArray, $timeout) {
        if (is_array($keyArray)) {
            $retRes = self::$redis->mset($keyArray);
            if ($timeout > 0) {
                foreach ($keyArray as $key => $value) {
                    self::$redis->expire($key, $timeout);
                }
            }
            return $retRes;
        } else {
            return "Call  " . __FUNCTION__ . " method  parameter  Error !";
        }
    }

    /**
     * @param $key
     * @return bool|string
     */
    public function get($key) {
        $result = self::$redis->get($key);
        return $result;
    }

    /**
     * @param $keyArray
     * @return array|string
     */
    public function gets($keyArray) {
        if (is_array($keyArray)) {
            return self::$redis->mget($keyArray);
        } else {
            return "Call  " . __FUNCTION__ . " method  parameter  Error !";
        }
    }


    /**
     * 判断key是否存在
     * @param string $key KEY名称
     * @return bool
     */
    public function isExists($key){
        return self::$redis->exists($key);
    }


    /**
     * 获取KEY存储的值类型
     * none(key不存在) int(0)
     * string(字符串) int(1)
     * list(列表) int(3)
     * set(集合) int(2)
     * zset(有序集) int(4)
     * hash(哈希表) int(5)
     * @param string $key KEY名称
     * @return int
     */
    public function dataType($key){
        return self::$redis->type($key);
    }

    public function rPush($key, $value){
        return self::$redis->rPush($key, $value);
    }

    public function hIncrBy( $key, $hashKey, $value ) {
        return self::$redis->hIncrBy( $key, $hashKey, $value );
    }


    public function hMGet( $key, $hashKeys ) {
        return self::$redis->hMGet( $key, $hashKeys );
    }

    public function zAdd( $key, $score, $value){
        return self::$redis->zAdd( $key, $score, $value);
    }

    public function zRange( $key, $start, $end, $withscores = null ) {
        return self::$redis->zRange( $key, $start, $end, $withscores);
    }

    public function zRem( $key, $member ) {
        return self::$redis->zRem( $key, $member );
    }
}