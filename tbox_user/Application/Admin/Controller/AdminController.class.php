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
    $TOB_ID=I('post.TOB_ID');
    $CX=I('post.CX');
    session('TOB_ID',$TOB_ID);

    session('CX',$CX);
    $token=session_id();
  if(S($token)==$TOB_ID)
    {
      S($token,NULL);
      S($TOB_ID,NULL);
    }
    S($token,$TOB_ID,3600);
    S($TOB_ID,$token,3600);
    cookie('token',$token);

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