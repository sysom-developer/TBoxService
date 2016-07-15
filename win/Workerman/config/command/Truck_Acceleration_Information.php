<?php
use comm\Model\TruckAccelerationInformation;

$func_truck_acceleration_information = function($packet, $message, $data_file_name) {
    $data = $message->_DATA;

    $truck_acceleration_information_model = TruckAccelerationInformation::getInstance($packet, $data);

    $truck_acceleration_information_model->save();

//    $truck_acceleration_information_model->cached();


    $truck_acceleration_information_model->echo_log($data_file_name, $message->_MSG_ID);


};
return $func_truck_acceleration_information;