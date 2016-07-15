<?php

use comm\Model\POSITION_DATA;

$func_POSITION_DATA_model = function($packet, $data_file_name) {
    $POSITION_DATA_model = POSITION_DATA::getInstance();
    $data=$POSITION_DATA_model->init($packet);
    $POSITION_DATA_model->save($$data);
/*    $NEW_DATA_MINING_INFO_model->echo_log($data_file_name, $message->_MSG_ID);*/


};
return $func_POSITION_DATA_model;