<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Util\String;

/**
 * 用户
 */
class UserController extends BaseController {
  protected $model;
    public function __construct(){
    parent::__construct();

  }

  public function user_profile(){
     $map['apikey.user_id']=session('adminid');
    $field="apikey.apikey";
    $this->assign('type','index');
    $this->base_join_index(M("apikey"),$map,$join,$field);
  }

  public function change_password(){
    if(IS_GET)
      {
        $this->display();
        }
      else
      {
        $this->model= D('User');
        $data['id']=session("adminid");
        $arr=$this->model->where($data)->find();
        $data['password']=md5(md5(I('post.password')).$arr['salt']);
        
        if($arr['password']!==$data['password'])
        {
          $return="密码错误";
          $this->ajaxReturn($return);
        }

        $data['password']=I('post.password1');
        $password2=I('post.password2') ;
        if($data['password']!==$password2)
        {
          $return="两次密码不匹配";
          $this->ajaxReturn($return);
        }
        $data['password']=md5(md5(I('post.password1')).$arr['salt']);
        if($this->model->create($data)){
             if($this->model->save($data))
             {
              
                $return="1";
                $this->ajaxReturn($return);
              }
              else
              {
                $return="操作失败";
                $this->ajaxReturn($return);
              }
            }else{
             $return=$this->model->getError();
             $this->ajaxReturn($return);
            }   
        }
      }
     public function changekey(){
     $user_id=session('adminid');
    $data['apikey']=random(20); 
    $data['created_at']=time() ;
    M("apikey")->where("user_id='$user_id'")->save($data);

    echo $data['apikey'];
   }
  public function selectlist(){

    $_list=$this->model->field('id,truename')->select();
    $this->ajaxReturn($_list,'json');
    
  }

}