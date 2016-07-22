<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Util\String;
/**
 * 用户
 */
class DemoController extends Controller {
/*  private $model;
    public function __construct(){
    parent::__construct();

    $this->model =  M('Brands');
  }*/

  public function demo_admin(){
    $tbox=1;
    session('CX',$CX);
    S($tbox,$tbox,3600);
    cookie($tbox,$tbox);
    $this->display();
     }
 
}