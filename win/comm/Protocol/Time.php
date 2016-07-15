<?php

namespace comm\Protocol;

class Time{

    /**
     * 转换时间
     * @param $unix_time
     * @return string
     */
    public static function TimeConvert($unix_time){

        $result = hexdec($unix_time) + strtotime('2000-01-01 00:00:00');

        return $result;
    }
}