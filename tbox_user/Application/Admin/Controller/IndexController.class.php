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
    $this->display();
    }
  public function selectlist(){

    $_list=$this->model->field('id,truename')->select();
    $this->ajaxReturn($_list,'json');
    
  }


}