<?php


namespace comm\Protocol;


class FID_ACK
{
    private $head;
    private $data;
    function  __construct($fid, $state){
        //把$fid转为十进制,运算
        $fid_arr = str_split($fid, 1);
        $fid = (int)$fid_arr[0] * 16 + (int)$fid_arr[1];
        $tmp = (string)((int)$fid | 0x80);
        //运算完转为ASCII值对应为字符
        $this->_FID_ACK = chr($tmp);
//var_dump($this->_FID_ACK);
        $this->_STATE = chr($state);
//var_dump($this->_STATE);
        $this->_SEP = chr(0x1c);
//var_dump($this->_SEP);
    }

    public function get_bytes(){
        return $this->_FID_ACK. $this->_STATE. $this->_SEP;
    }



}