<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Util\String;
/**
 * 
 */
class MenuController extends BaseController {
	private $cx='cx_';
	protected $model;
  public function __construct(){
    parent::__construct();
 	$this->model = M('module');
  }

  public function getmenu(){
  	$CX=session('CX');
  	$string=S($cx.$CX);
/*  	S($CX,null);
  	$this->ajaxReturn(1);*/
  	if($string==null)
  	{
  		$where['module.type']=2;
  		$field="module.id,module.name,module.url,module.type,module.P_node";
  		$arr=$this->model->field($field)->join("models_module on models_module.module_id=module.id")->join("models on models.id=models_module.models_id")->where($where)->select();
  		/*foreach ($arr as $key => &$value) {
  			$value['url']=U($value['url'].'/index');
  		}*/
  		S($cx.$CX,json_encode($arr));
  	}
  	else
  	{
  		
  		$arr=$string;
  	}
  	$this->ajaxReturn($arr);
   
  }
   
}