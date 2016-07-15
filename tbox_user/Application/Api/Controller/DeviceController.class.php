<?php
namespace Api\Controller;
use Think\Model;
use Think\Controller\RestController;
// use Think\Controller;
class DeviceController extends BaseController {
  protected $lang;
  protected $model;
  public function __construct(){
        parent::__construct();
        $this->model = M(CONTROLLER_NAME);
        $this->lang = include_once dirname(__FILE__)."/My_lang/zh_us.php";
}
     /*
    查询设备
    param $device_id 设备ID
    return 字段值  
  */
  public function read(){
    $data['user_id']=$this->user_id;
    $data['device_id']=I('get.device_id');
    tag('ifbind',$data);
    if(!$data['msg'])
      {
        $return['error']['msg']="Device Not bound";
        $this->response($return,'json');
      }
    unset($data['msg']);
    unset($data['user_id']);
    $field="device_id,title,about,tags,is_online";
    $return=M('device')->field($field)->where($data)->find();
    if($return==null)
    {
      $return['error']['msg']='No data';
      $this->response($return,'json');
    }
    elseif($return===false)
    {
      $return['error']['msg']='Query error';
      $this->response($return,'json');
    }
    else
    {
     
    $field2="sensor_id,title,about,value";
    $return2=M('sensor')->field($field2)->where($data)->select(); 
    $return['sensor']=$return2;
    $this->response($return,'json');
    
    }
  }

      /*
     获取设备最近上传数据
      param $device_id 设备ID
      return 字段值  
    */
  public function latest(){
      $device_id=I('get.device_id');
      $user_id=$this->user_id;
      $arr['device_id']=$device_id;
      $arr['user_id']=$user_id;
      tag('ifbind',$arr);
      if(!$arr)
      {
          $return['error']['msg']="The device has no sensors";
          $this->response($return,'json');
      }
      $time=time();
      $timestamp_start=I('get.timestamp_start')?I('get.timestamp_start'):$time-10;
      $timestamp_end=I('get.timestamp_end')?I('get.timestamp_end'):$time;
      $page=I('get.page')?I('get.page'):1;
      $datapoint=M('datapoint');
      $device=M('device');
      $return=array();
      $arr=$device->field("sensor.sensor_id")->join("join sensor on sensor.device_id=device.device_id")->select();
      $where['timestamp']  = array('BETWEEN',array($timestamp_start,$timestamp_end));
      foreach ($arr as $key => $value) {
          $where['sensor_id'] = $value['sensor_id'];
        $return['sensor_id'][$value['sensor_id']]=$datapoint->field("timestamp,value")->where($where)->page($page)->select();
      }
        $this->response($return,'json');
  }


}