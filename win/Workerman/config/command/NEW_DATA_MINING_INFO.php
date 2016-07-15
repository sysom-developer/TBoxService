<?php

use comm\Model\NEW_DATA_MINING_INFO;

$func_NEW_DATA_MINING_INFO_model = function($packet, $data_file_name) {
    $NEW_DATA_MINING_INFO_model = NEW_DATA_MINING_INFO::getInstance();
    $data=$NEW_DATA_MINING_INFO_model->init($packet);
   $NEW_DATA_MINING_INFO_model->save($data);
/*    $NEW_DATA_MINING_INFO_model->echo_log($data_file_name, $message->_MSG_ID);*/


};
return $func_NEW_DATA_MINING_INFO_model;