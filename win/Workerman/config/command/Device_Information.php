<?php
use comm\Model\DeviceInformation;
use comm\Protocol\Response_Message;



$func_device_information= function($packet, $message, $data_file_name) {
    $data = $message->_DATA;
    $device_information_model = DeviceInformation::getInstance($packet, $data);
//    $device_information_model->save();

//    $device_information_model->cached();

    $device_information_model->echo_log($data_file_name, $message->_MSG_ID);

    $is_activated = intval($device_information_model::$data['is_activated']);


//    $request = Requests::post('http://httpbin.org/post', array(), array('mydata' => 'something'));
//    var_dump($request);

    if(!empty($is_activated)){//已激活
//        $response_message = new Response_Message('01', '55');
//
//        $result = $response_message->getResponse();
//        file_put_contents($data_file_name. 'MSG_01', $result);
//        return $result;
    }else{//未激活，发送激活
        $response_message = new Response_Message('01', '00');
        $result = $response_message->getResponse();
        file_put_contents($data_file_name. 'MSG_01', $result);
        return $result;
    }

};

return $func_device_information;