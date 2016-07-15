<?php
namespace Api\Controller;
use Think\Model;
use Think\Controller\RestController;
// use Think\Controller;
class AutoController extends RestController {
	/*
    自动创建设备
    param $passcode 设备秘钥
    return states 状态码 
  */
    public function device_auto(){
	    $data['passcode']=I('post.passcode');
	    $data['devicekey']=I('post.devicekey');
	    $count=explain_device_id($data['devicekey']);
	    $Device=M('Device');
	    $Device->add($data);
	    $Sensor=M('Sensor');
	    $Sensor->addAll($dataList);
	}
		/*
    自动上传数据点
    param $passcode 设备秘钥
    return states 状态码 
  */
    public function datapoint_auto(){
	    $data['passcode']=I('post.passcode');
	    $data['did']=random(20);
	    $model=M('Device');
	    $model->add($data);

	}
}