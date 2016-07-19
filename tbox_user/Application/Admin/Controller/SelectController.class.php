<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Util\String;

/**
 * 
 */
class SelectController extends BaseController {
  public function __construct(){
    parent::__construct();

  }
	public function Car_brand(){
	$field="id,name";
	$arr=M('models')->field($field)->select();
    $this->ajaxReturn($arr);
	}
	public function tbox_id(){
	$field="id,tbox_id,name";
	$where['models_id']=I('post.models_id');
	$arr=M('tbox')->field($field)->where($where)->select();
    $this->ajaxReturn($arr);
  }

   
}
