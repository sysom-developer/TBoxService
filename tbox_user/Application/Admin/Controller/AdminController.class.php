<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Util\String;
/**
 * 
 */
class AdminController extends BaseController {

  public function __construct(){
    parent::__construct();

  /*  $this->model =  M('Admin');*/
  }

  public function index(){
    
    $module=['name'=>'Air_conditioner','url'=>'Air_conditioner/index'];
    $this->assign($module);
    $this->display();
  }
  public function add(){
    if(IS_GET)
      {
    $this->display();
      }
  else{
    $this->model = D('Admin');
    $data['name'] =I('post.name');
    $salt = String::randString(10);
    $data['salt'] = $salt;
    $data['password'] = md5($_POST['password'].$salt);
    $data['created_at'] = time();
    $data['usergroup_id'] = I('post.usergroup');
    $data['repassword'] = md5($_POST['repassword'].$salt);
    $data['usergroup'] = $_POST['usergroup'];

    if($this->model->create($data,1)){
      $this->model->add($data);
     
      $this->success('操作成功！','index');
    }else{
      $this->error($this->model->getError());
    }
    }
  }


  public function update() {
    $id = I('get.id');
    if($id) {
      $data = $this->model->find($id);
      $this->assign('data',$data);
      $this->display();
      exit;
    }
    $post = I('post.');
    if( $post ) {

        $this->model = D('Admin');
            if( $this->model->where($post)->find())
            {              
              $this->success('修改成功','index');
            }
            else{
                if( $this->model->save($post) ) {

                  $this->success('修改成功','index');
                }
                else {
                  $this->error('修改失败!');
                }
              }

        
    }
  }

  public function delete(){
    $this->delById();
  }

  public function selectlist(){
    $this->model = D('usergroup');
    $_list = $this->model->field('id,name')->select();
    $this->ajaxReturn($_list,'json');
  }

}