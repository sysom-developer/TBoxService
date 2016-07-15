<?php

namespace comm\Protocol;

class Response_Message
{
    /**
     * 消息ID
     * @var
     */
    private   $_MSG_ID;


    /**
     * 消息数据长度
     * @var
     */
    private $_LENGTH;

    /**
     * 消息的数据
     * @var
     */
    private $_DATA;

    /**
     * 校验码
     * @var
     */
    private $_CHECKSUM;

    private $_SEP;



    function __construct($meg_id, $data){
        $this->_MSG_ID = $meg_id;
        $this->_LENGTH = $this->get_data_len($data);

        $this->_DATA = $this->get_data($data);

        $this->_CHECKSUM = $this->get_check_sum();

        $this->_SEP = '1c';
    }

    public function get_data_len($data){
        $size = strlen($data)/2;

        $size_hex = (string)dechex($size);
        $diff = 4 - strlen($size_hex);
        for($i = $diff; $i >0 ;$i--){
            $size_hex = '0'.$size_hex;
        }
        $size_hex = Byte::ByteConvert($size_hex);
        return $size_hex;
    }

    public function get_data($data){

        $buma = 0;
        $data_size = strlen($data)/2;
        if($data_size % 8 > 0){
            $buma = 8 - $data_size % 8;
        }
        if($buma > 0){
            for($i = 0; $i < $buma; $i++){
                $data .= '55';
            }
        }
        return $data;
    }

    public function get_check_sum(){

        $data_checksum = $this->_DATA;
        $tmp = 0;
        $data_str_arr = str_split($data_checksum, 2);
        array_walk($data_str_arr, function($v) use(&$tmp){
            $tmp += hexdec($v);
        });

        $tmp = dechex($tmp);
        $checksum = (string)$tmp;
        $diff = 4 - strlen($checksum);
        for($i = $diff; $i >0 ;$i--){
            $checksum = '0'.$checksum;
        }
        $checksum = Byte::ByteConvert($checksum);
        return $checksum;
    }


    public  function  getResponse(){
        $all_data = $this->_MSG_ID . $this->_LENGTH .$this->_DATA . $this->_CHECKSUM . $this->_SEP;
        $result = pack('H*', $all_data);
        return $result;
    }



}