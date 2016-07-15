<?php
namespace Api\Controller;
use Think\Controller\RestController;

class BaseController extends RestController {

  protected $model;
  protected $user_id;
  protected $apikey;
  protected $lang;

  /*
    验证apikey
    param apikey
    return states 状态码  
  */
  public function __construct(){
    parent::__construct();
    $this->model =  M(CONTROLLER_NAME);
    $this->lang = include_once dirname(__FILE__)."/My_lang/zh_us.php";
    $header= get_all_headers();
    $data['apikey']=$header['apikey'];
    $this->apikey=$data['apikey'];
    $arr=M('apikey')->where($data)->find();
 
   if($arr)
   {
      if(time()-intval($arr['created_at'])>=86400*layout_time)
      {
        $save['id']=$arr['id'];
        $save['apikey']="";
        $del=M('apikey')->save($save);
        if($del)
        {
          //清除token成功
          $return['error']['msg']="apikey overdue";
          $this->response($return,'json');
        }
        elseif($del===0)
        {
          //没有token被清除
          $return['error']['msg']="apikey overdue";
          $this->response($return,'json');
        }
        else
        {
          //清除token失败
          $return['error']['msg']="apikey overdue";
          $this->response($return,'json');
        }
      }
      else{
        $this->user_id=$arr['user_id'];
      }
   }
   else
   {
      $return['error']['msg']="invalid apikey";
      $this->response($return,'json');
      
   }

  }

   /*
    基础查询方法
    param $data 查询条件
    param $field 查询字段
    return 字段值  
  */
    public function readbase($model,$data,$field){

    $return=$model->field($field)->where($data)->find();
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
      $this->response($return,'json');
    }
  }
   /*
    基础全部查询方法
    param $data 查询条件
    param $field 查询字段
    return 字段值  
  */
  public function readallbase($data,$field,$page){
    $return=$this->model->field($field)->where($data)->page(intval($page),C('PER_PAGE'))->select();
    if($return==null)
    {
      $this->sendHttpStatus(404);
    }
    elseif($return===false)
    {
      $this->sendHttpStatus(400);
    }
    else
    {
      $this->response($return,'json');
    }
   
  }
    /*
    基础编辑方法
    param $data 编辑数据
    return  状态码   
  */
    public function updatebase($where,$data){

      $return=$this->model->where($where)->save($data);

      if($return===false)
      {
        $return['error']['msg']='Modify failed';
        $this->response($return,'json');
      }
      else if($return===0)
      {
         $return['error']['msg']='No such data';
          $this->response($return,'json');
      }
    
  }
  /*
    基础删除方法
    param $data 查询条件
    return states 状态码   
  */
  public function deletebase($data){
    if(CONTROLLER_NAME!="User")
    {
      $return=$this->model->where($data)->delete();
      if($return===false)
      {
        $return['error']['msg']='Delete failed';
        $this->response($return,'json');
      }
      elseif($return===0)
      {
        $return['error']['msg']='No data';
        $this->response($return,'json');
      }
    }
  }
}