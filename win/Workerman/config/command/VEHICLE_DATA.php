<?php

use comm\Model\VEHICLE_DATA;

$func_VEHICLE_DATA_model = function($packet, $data_file_name) {
    $VEHICLE_DATA_model = VEHICLE_DATA::getInstance();
   	$data=$VEHICLE_DATA_model->init($packet);
    $VEHICLE_DATA_model->save($data);
/*    $NEW_DATA_MINING_INFO_model->echo_log($data_file_name, $message->_MSG_ID);*/


};
return $func_VEHICLE_DATA_model;