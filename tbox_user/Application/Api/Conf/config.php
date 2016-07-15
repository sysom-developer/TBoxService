<?php
define('ip', 'http://121.40.133.69');
define('layout_time',3);

$api_config=array(    
		'URL_ROUTER_ON'   => true,
		'DATA_CACHE_TYPE'       => 'Redis',  // 数据缓存类型,
		'REDIS_HOST'=>'127.0.0.1', //redis服务器ip，多台用逗号隔开；读写分离开启时，第一台负责写，其它[随机]负责读；
		'REDIS_PORT'=>'6379',//端口号
		'REDIS_PERSISTENT'=>false,//是否长连接 false=短连接
		'DATA_CACHE_PREFIX'=> 'Redis_',
		'REDIS_RW_SEPARATE' => false, //Redis读写分离 true 开启
		'REDIS_TIMEOUT'=>'300',//超时时间
		'REDIS_PASSWORD'=>'',//Redis认证密码
		'DATA_CACHE_TIME'=> 10800,// 数据缓存有效期 0表示永久缓存
		'URL_ROUTE_RULES'=>array(
		array('device/:device_id/sensor/:sensor_id/json','datapoint/history','',array('method'=>'GET')),
		array('device/:device_id/sensor/:sensor_id/datapoint/:key','datapoint/read','',array('method'=>'GET')),
		array('device/:device_id/sensor/:sensor_id/datapoint/:key','datapoint/delete','',array('method'=>'DELETE')),
		array('device/:device_id/sensor/:sensor_id/datapoints/:page','datapoint/readall','',array('method'=>'GET')),
		array('device/:device_id/sensor/:sensor_id/datapoint/:key','datapoint/update','',array('method'=>'PUT')),
		array('device/:device_id/sensor/:sensor_id/datapoints','datapoint/create','',array('method'=>'POST')),
		array('sensor/:sensor_id$','sensor/read','',array('method'=>'GET')),
		array('sensor/latest/:sensor_id/:page/:timestamp_start/:timestamp_end$','sensor/latest','',array('method'=>'GET')),
		array('user/control_sensor$','user/control_sensor','',array('method'=>'POST')),
		array('user/control_device$','user/control_device','',array('method'=>'POST')),
		array('device/latest/:device_id/:page/:timestamp_start/:timestamp_end$','device/latest','',array('method'=>'GET')),
		array('user/verify','Nuser/verify','',array('method'=>'GET')),
		array('user/invoke_email','Nuser/invoke_email','',array('method'=>'POST')),
		array('user/edit_phone','user/edit_phone','',array('method'=>'PUT')),
		array('user/edit_email','user/edit_email','',array('method'=>'PUT')),
		array('user/edit_pwd$','user/edit_pwd','',array('method'=>'PUT')),
		array('device/:device_id$','device/read','',array('method'=>'GET')),
		array('user/bindings$','user/bindings','',array('method'=>'POST')),
		array('user/binded/:page$','user/binded','',array('method'=>'GET')),
		array('user/bindings/:device_id$','user/unbindings','',array('method'=>'DELETE')),
		array('user/apikey$','Nuser/apikey','',array('method'=>'POST')),
		array('user$','Nuser/create','',array('method'=>'POST'))
	 ));
return $api_config;


