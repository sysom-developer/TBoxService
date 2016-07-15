<?php

use comm\Model\EventReport;



$func_event_report = function($packet, $message,$data_file_name) {
    $data = $message->_DATA;
    /**
     * 存入mysql
     */
    $event_report_model = EventReport::getInstance($packet, $data);
    $event_report_model->save();

    /**
     * 推送到redis
     */
    $event_report_model->cached();

    $event_report_model->echo_log($data_file_name, $message->_MSG_ID);



};
return $func_event_report;