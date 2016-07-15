<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Util\String;
/**
 * 贺卡模板
 */
class IndexController extends BaseController {

  public function __construct(){
    parent::__construct();
  }

  public function index(){
    $join="left join apikey on apikey.user_id=device.user_id";
    $map['device.user_id']=session('adminid');

    $field="device.id,device.device_id,device.title,apikey.apikey";
    $this->base_join_index(M("device"),$map,$join,$field);
    }
  public function selectlist(){

    $_list=$this->model->field('id,truename')->select();
    $this->ajaxReturn($_list,'json');
    
  }


}