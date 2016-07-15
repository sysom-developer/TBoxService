<?php

use comm\Model\TruckInformation;

$func_backup_truck_information = function($packet, $message, $data_file_name) {
    $data = $message->_DATA;


    $truck_information_model = TruckInformation::getInstance($packet, $data);

    $truck_information_model->save();

    $truck_information_model->cached();

    $truck_information_model->echo_log($data_file_name, $message->_MSG_ID);


};
return $func_backup_truck_information;