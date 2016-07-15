<?php
namespace Api\Controller;
use Think\Model;
use Think\Controller\RestController;
// use Think\Controller;
class SensorController extends BaseController {
    
  /*
    查询传感器当前信息
    param $sensor_id 传感器ID
    param $device_id 设备ID
    return 字段值  
  */
  public function read(){
    $data['sensor_id']=I('get.sensor_id');
    $arr=M('sensor')->field("device_id")->where($data)->find();
    $arr['user_id']=$this->user_id;
    tag('ifbind',$arr);
    if(!$arr['msg'])
      {
        $return['error']['msg']="Device not bound";
        $this->response($return,'json');
      }
    else{
      $field="title,about,tags,type,unit_name,unit_symbol,value";
      $this->readbase($this->model,$data,$field);
    }
}
  /*
     获取设备最近上传数据
      param $device_id 设备ID
      return 字段值  
    */
  public function latest(){
      $sensor_id=I('get.sensor_id');
      $arr=M('sensor')->field("device_id")->where($data)->find();
      $arr['user_id']=$user_id;
      tag('ifbind',$arr);
      if(!$arr['msg'])
      {
        $return['error']['msg']="Device not bound";
        $this->response($return,'json');
      }
      else{
        $time=time();
        $timestamp_start=I('get.timestamp_start')?I('get.timestamp_start'):$time-10;
        $timestamp_end=I('get.timestamp_end')?I('get.timestamp_end'):$time;
        $page=I('get.page')?I('get.page'):1;
        $datapoint=M('datapoint');
        $return=array();
        $where['timestamp'] = array('BETWEEN',array($timestamp_start,$timestamp_end));
        $where['sensor_id'] = $sensor_id;
        $return['sensor']=$datapoint->field("timestamp,value")->where($where)->page($page)->select();
        $this->response($return,'json');
      } 
  }

  /*
    创建传感器
    param $title 传感器名称
    param $tags 传感器标签
    param $about 传感器简介
    param $unit_name 属性名称
    param $unit_symbol 属性单位
    return states 状态码 
  */
    public function create(){
    $user_id['user_id']=$this->user_id;
    $user_id['device_id']=I('get.device_id');
    $user_id['sensor_id']=I('post.sensor_id');
    $count= $this->model->where($user_id)->count(1);
    if($count!=0)
    {
      $this->sendHttpStatus(412);
      exit;
    }
    $data2=I('post.');
    $data2['user_id']= $user_id['user_id'];
    $data2['device_id']= $user_id['device_id'];
    $data2['sensor_id']=$user_id['sensor_id'];
    
    
    $this->model=D('Sensor');
    if (!$this->model->create($data2))
    { 
        // 如果创建失败 表示验证没有通过 输出错误提示信息 
      $this->sendHttpStatus($this->model->getError());
      
    }
    else
    {
      if(!$this->model->add($data2))
      {
        $this->sendHttpStatus(400);
      }
      else
      {
        $return['id']=$user_id['sensor_id'];
        $this->response($return,'json');
      }
    }
  }
      /*
    编辑设备
    param $device_id 设备ID
    param $sensor_id 传感器ID
    param $tags 传感器标签
    param $localtion 传感器信息
    param $about 传感器简介
    param $unit_name 属性名称
    param $unit_symbol 属性单位
    return 状态码  
  */
    public function update(){
    $data=I('put.');
    $where['user_id']=$this->user_id;
    $where['device_id']=I('get.device_id');
    $where['sensor_id']=I('get.sensor_id');
    $this->updatebase($where,$data);
  }
  /*
    删除传感器
    param $device_id 设备ID
    param $sensor_id 传感器ID
    return 字段值  
  */
    public function delete(){
    $data['user_id']=$this->user_id;
    $data['device_id']=I('get.device_id');
    $data['sensor_id']=I('get.sensor_id');
    $this->deletebase($data);
  }
  
}