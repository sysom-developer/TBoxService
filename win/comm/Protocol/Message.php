<?php

namespace comm\Protocol;

class Message
{
    /**
     * 消息ID
     * @var
     */
    public $_MSG_ID;
    private $_MSG_ID_OFFSET;
    private $_MSG_ID_LENGTH;

    /**
     * 消息数据长度
     * @var
     */
    public $_DATA_SIZE;
    private $_DATA_SIZE_OFFSET;
    private $_DATA_SIZE_LENGTH;

    /**
     * 消息的数据
     * @var
     */
    public $_DATA;
    private $_DATA_OFFSET;
    private $_DATA_FACT_LENGTH;
    private $_DATA_LENGTH;

    /**
     * 校验码
     * @var
     */
    public $_CHECKSUM;
    private $_CHECKSUM_OFFSET;
    private $_CHECKSUM_LENGTH;

    /**
     * 校验结果
     * @var int
     */
    public $_CHECKSUM_RESULT = false;
    public $_CALCULATE_CHECKSUM;

    public $_TOTAL_LENGTH;


    function __construct($message_str){

        $this->_MSG_ID_LENGTH = 1 * 2;
        $this->_DATA_SIZE_LENGTH = 2 * 2;
        $this->_CHECKSUM_LENGTH = 2 * 2;




        //定义各个偏移量
        $this->_MSG_ID_OFFSET = 0;

        $this->_DATA_SIZE_OFFSET = $this->_MSG_ID_OFFSET + $this->_MSG_ID_LENGTH;

        $this->_DATA_OFFSET = $this->_DATA_SIZE_OFFSET + $this->_DATA_SIZE_LENGTH;




        // 设置消息id
        $this->_MSG_ID = substr($message_str, $this->_MSG_ID_OFFSET, $this->_MSG_ID_LENGTH);

        //设置消息长度
        $tmp = substr($message_str, $this->_DATA_SIZE_OFFSET, $this->_DATA_SIZE_LENGTH);
        /**
         * 分割成2个数组，每个数组2byte
         */
        $tmp_arr = str_split($tmp, 2);
        $tmp_arr = array_reverse($tmp_arr);
        $tmp = implode('', $tmp_arr);
        $this->_DATA_SIZE = $tmp;
        $data_size = hexdec($this->_DATA_SIZE);
        unset($tmp);
        unset($tmp_arr);
//        echo "data_size:";
//        var_dump($data_size);
//        echo PHP_EOL;
        $buma = 0;
        if($data_size % 8 > 0){
            $buma = 8 - $data_size % 8;
        }

        $this->_DATA_FACT_LENGTH = $data_size * 2;
        $this->_DATA_LENGTH = $this->_DATA_FACT_LENGTH + $buma * 2;
//echo 'data_size: ' .$data_size. PHP_EOL;
//        echo 'buma:' .$buma;
//        echo PHP_EOL;


        $this->_DATA = substr($message_str, $this->_DATA_OFFSET, $this->_DATA_FACT_LENGTH);

//        echo "data_end:";
//        var_dump($this->_DATA);
//        echo PHP_EOL;

        $this->_CHECKSUM_OFFSET = $this->_DATA_OFFSET + $this->_DATA_LENGTH;
        $tmp = substr($message_str, $this->_CHECKSUM_OFFSET, $this->_CHECKSUM_LENGTH);
        $tmp_arr = str_split($tmp, 2);
        $tmp_arr = array_reverse($tmp_arr);
        $tmp = implode('', $tmp_arr);
        $this->_CHECKSUM = $tmp;


        $this->_TOTAL_LENGTH = $this->_MSG_ID_LENGTH + $this->_DATA_SIZE_LENGTH + $this->_DATA_LENGTH + $this->_CHECKSUM_LENGTH;
//        echo "total_length:";
//        var_dump($this->_TOTAL_LENGTH);
//        echo PHP_EOL;
        $this->check_sum($message_str);
    }

    /**
     * 检查校验码
     */
    private  function check_sum($message_str){
        $data_checksum = substr($message_str, $this->_DATA_OFFSET, $this->_DATA_LENGTH);
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
        $this->_CALCULATE_CHECKSUM = $checksum;
        if($checksum == $this->_CHECKSUM){
            $this->_CHECKSUM_RESULT =  true;
        }
    }

}