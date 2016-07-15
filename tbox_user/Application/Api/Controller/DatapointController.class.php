<?php
namespace Api\Controller;
use Think\Model;
use Think\Controller\RestController;
// use Think\Controller;
class DatapointController extends BaseController {
    
  /*
    查询数据点
    param $sensor_id 传感器ID
    param $device_id 设备ID
    param $key 时间戳ID
    return 字段值  
  */
  public function read(){
    $data['device_id']=I('get.device_id');
    $data['sensor_id']=I('get.sensor_id');
    $data['timestamp']=I('get.key');
    $data['user_id']=$this->user_id;
    $field="value";
    $this->readbase($data,$field);
  }
    /*
    查询归属人某个设备某个传感器的所有数据点
     param $page 页数
     param $device_id 设备ID
     param $sensor_id 传感器ID
    return 字段值  
  */
    public function readall(){

    $page=I('get.page');
    $data['device_id']=I('get.device_id');
    $data['sensor_id']=I('get.sensor_id');
    $data['user_id']=$this->user_id;
    $field="timestamp,value";
    $this->readallbase($data,$field,$page);
  }
    /*
    查看历史数据
    param $device_id 设备ID
    param $sensor_id 传感器ID
    param $start 起始时间
	param $end 截止时间
    return 字段值
  */
    public function history(){
    $data['device_id']=I('get.device_id');
    $data['sensor_id']=I('get.sensor_id');
    $data['timestamp']=array(array('EGT',intval(I('get.start'))),array('ELT',intval(I('get.end'))), 'and');
    $page=I('get.page');
    $data['user_id']=$this->user_id;
    $field="timestamp,value";
    $return=$this->model->field($field)->where($data)->page($page,C('PER_PAGE'))->select ();

    $this->response($return,'json');
  }

      /*
    编辑设备
    param $device_id 设备ID
    param $sensor_id 数据点ID
    param $key 时间戳ID
    param $timestamp 数据点时间戳
	param $value 数据点值
    return 状态码  
  */
    public function update(){
    $data=I('put.');
    $where['user_id']=$this->user_id;
    $where['device_id']=I('get.device_id');
    $where['sensor_id']=I('get.sensor_id');
    $where['timestamp']=I('get.key');
    $this->updatebase($where,$data);
  }
  /*
    删除数据点
    param $device_id 设备ID
    param $sensor_id 数据点ID
    return 字段值  
  */
    public function delete(){
    $data['user_id']=$this->user_id;
    $data['device_id']=I('get.device_id');
    $data['sensor_id']=I('get.sensor_id');
    $data['timestamp']=I('get.key');
    $this->deletebase($data);
  }
}