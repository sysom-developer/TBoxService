<?php

namespace comm\Protocol;

class Byte{

    /**
     * 把字符串转为16进制
     * ord, 返回首个字符的ASCII值
     * dechex,把10进制转为16进制
     */
    public static function String2Hex($string)
    {
        $hex_string = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $hex = dechex(ord($string[$i]));
            if (strlen($hex) == 1) {
                $hex = '0'.$hex;
            }
            
            $hex_string = $hex_string . $hex;
        }
        return $hex_string;
    }

    /**
     * 把16进制转为二进制

     */
    public static function Hex2bin($hex)
    {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= base_convert($hex[$i] . $hex[$i + 1] , 16 , 2);
        }
        return $string;
    }
    /**
     * 把16进制转为字符串
     * chr，把ASCII值转换为字符
     * hexdec，把16进制转为10进制
     */
    public static function Hex2String($hex)
    {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $string;
    }

    /**
     * 转换字节序
     * @param $str
     * @return string
     */
    public static function ByteConvert($str){
        $str_arr = str_split($str, 2);
        $str_arr = array_reverse($str_arr);
        $new_str = implode('', $str_arr);
        return $new_str;
    }

    /**
     * 解析转义
     * @param $str
     * @return string
     */
    public static function Escape($str){
        $escape_arr = [
            '1b' => '1b1d',
            '7a' => '1b6a',
            '7b' => '1b6b',
            '7c' => '1b6c',
            '7d' => '1b6d',
            '7e' => '1b6e'
        ];
        array_walk($escape_arr, function($v, $k) use (&$str){
            $str = str_replace($v, $k, $str);
        });
        $str = strtolower($str);
        return $str;
    }

    public static function Parse_Latitude($str){
        $int = hexdec(substr($str, 0, 2));
        $decimal = hexdec(substr($str, 2));

        $result = $int .'.'. $decimal;

        return $result;
    }
        public static function Parse_version($str){
        $int = hexdec(substr($str, 0, 1));
        $decimal = hexdec(substr($str, 1));

        $result = $int .'.'. $decimal;

        return $result;
    }
      /**
     * 把ascii转为字符
     */
     public static function ASCii2String($origin_version){

       $origin_version_arr = str_split($origin_version, 2);
       $version = "";
        array_walk($origin_version_arr, function (&$v) use (&$version){
            $version =$version. chr($v);
        });
        return $version;
    }
     /**
     * 报警代码
     */
     public static function alert($alert){
        return hexdec($value);
    }
    
}