<?php
/**
 * 指令编码
 */

return [

    'SEP' => '1c',

    'FID' => [
        '01' => 'START_UP',//启动
        '02' => 'LOCATION',// 定位
        '03' => 'ACCELERATION',//加速度
        '04' => 'ANGLE',//角度
        '10' => 'DIAGNOSIS',// 诊断
        '20' => 'OTA',// 升级
    ],//上传的场景

    'MSG_ID' => [

        '02' => 'Device_Information',
        '20' => 'Truck_Acceleration_Information',
        '21' => 'Truck_Information',
        '22' => 'Event_Report',
        '23' => 'Backup_Truck_Information',
        '2a' => 'OTA_UPDATE_QUERY',//升级查询请求
        '40' => 'GSM_Location',
        '24' => 'Sleep_Voltage_Record',

    ],//消息处理

    'STATE' => [
        'STATE_RECV_OK' => 0x30,//返回给客户端接收成功
        'STATE_RECV_NG' => 0x31,
        'STATE_EXEC_OK' => 0x32,
        'STATE_EXEC_NG' => 0x33,

        '30' => 'STATE_RECV_OK',
        '31' => 'STATE_RECV_NG',
        '32' => 'STATE_EXEC_OK',
        '33' => 'STATE_EXEC_NG',
    ],
    'ONE_LEVEL'=>[
       'Protocol_version' =>'01' ,
        'Equipment_ID'=> '02',
        'Equipment_ID_Type'=> '03',
        'Message_id'=> '04',
        'Service_type'=> '05',
        'message_type'=> '06',
        'hour_date'=> '07',
        'Message_protocol_version'=>'08',
        'data_length'=> '09'
    ],
    'Message_type'=>[
    '01'=>'POSITION_DATA',
    '02'=>'VEHICLE_DATA',
    '03'=>'URL_encoding',
    '04'=>'PUSH_OBJECT_POI',
    '05'=>'PUSH_OBJECT_Guidance',
    '06'=>'',
    '07'=>'DETAILED_INFO',
    '08'=>'',
    '09'=>'',
    '10'=>'DETAILED_INFO',
    '11'=>'REMOTE_CONFIG_DATA',
    '12'=>'REMOTE_CONFIG_DATA',
    '13'=>'DTCs_LIST',
    '14'=>'',
    '15'=>'',
    '16'=>'DETAILED_INFO',
    '17'=>'STATUS_INFO',
    '18'=>'STATUS_INFO',
    '19'=>'',
    '20'=>'SVT_TRACE_INFO',
    '21'=>'',
    '22'=>'',
    '23'=>'',
    '24'=>'',
    '25'=>'',
    '26'=>'IVI_DETAILED_INFO',
    '27'=>'',
    '28'=>'',
    '29'=>'IVI_DETAILED_INFO',
    '30'=>'REMOTE_CONFIG_IVI',
    '31'=>'REMOTE_CONFIG_IVI',
    '32'=>'STATUS_INFO',
    '33'=>'STATUS_INFO',
    '34'=>'',
    '35'=>'',
    '36'=>'VEHICLE_MOVEMENT',
    '37'=>'STATUS_INFO',
    '38'=>'VHL_CTL_STATUS',
    '39'=>'VHL_CTL',
    '40'=>'VHL_CTL_RESULT',
    '41'=>'ACK_INFO',
    '42'=>'NEW_DATA_MINING_INFO',
    '43'=>'PUSH_MESSAGE',
    '44'=>'SELF_UPDATE_NOTIFICATION_INFO',
    '45'=>'MOBI_LOGIN_NOTIFICATION',
    '46'=>'VHL_CTL',
    '47'=>'VHL_CTL',
    '48'=>'VHL_INSTRUSION_ALERT',
    '49'=>'',
    '50'=>'',
    '51'=>'VHL_CTL',
    '52'=>'',
    '53'=>'',
    '54'=>'VHL_CTL_RESULT'],
    'vehicle_propulsion'=>[
    'Battery','Diesel and Battery','Petrol and Alcoho','Petrol and Battery','Diesel','Bi-fuel_Petrol/CNG','Bi-fuel Petrol/LPG','Petrol'],
    'language'=>['French','English','German','Spanish','Italian','Portuguese','Dutch','Greek','Brazilian Portuguese','Traditional Chinese','Simplified Chinese','Turkish','Japanese','Russian n ']
];
