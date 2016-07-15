<?php

use comm\Model\SleepVoltageRecord;

$func_sleep_voltage_record = function($packet, $message,$data_file_name) {
    $data = $message->_DATA;

    $sleepVoltageRecord_model = SleepVoltageRecord::getInstance($packet, $data);
    $sleepVoltageRecord_model->save();

//    $sleepVoltageRecord_model->cached();


};
return $func_sleep_voltage_record;