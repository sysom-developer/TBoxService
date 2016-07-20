<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Util\String;

/**
 * 
 */
class ModuleController extends BaseController {
  public function __construct(){
    parent::__construct();

  }
	public function index(){
		$name=I('post.name');
	/*	$this->ajaxReturn($name);
		exit;*/
		$html=$this->fetch('Module:'.$name);
		$this->ajaxReturn($html);
	}

	
   
   
}
