<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Util\String;
/**
 * 用户
 */
class UsergroupController extends Controller {
/*  private $model;
    public function __construct(){
    parent::__construct();

    $this->model =  M('Brands');
  }*/
public function adds(){
   var_dump(I('post.'));
}
  public function index(){
    $this->display();
     }
  public function wait(){
    $map['usergroup.is_screen']=1;
    $this->assign('type','wait');
  $this->base_join_index($this->model,$map,$join,$field,$order,'Usergroup:index');
  }
  public function update(){
   if(IS_GET)
      {
        $id=I('get.id');
       $data = $this->model->where("id=$id")->find();
        $this->assign('data',$data);
        $modular=$this->model->field('second_modular.id,second_modular.name')->join('usergroup_modular on usergroup_modular.usergroup_id=usergroup.id')->join('second_modular on usergroup_modular.modular_id=second_modular.id')->where("usergroup.id=$id")->select();
     
      $allmodular = M('first_modular')->field('id,name')->select();
    foreach ($allmodular as $key => $value) {
      $id=$value['id'];
      $arr=M('second_modular')->field('id,name')->where("first_modular_id=$id")->select();
    $allmodular[$key]['second_modular']=$arr;
    }
  
          foreach ($allmodular as $key1 => $value1) {
          
            foreach ($value1['second_modular'] as $key3 => $value3) {
            foreach ($modular as $key2 => $value2) {
      
                if($value3['id']==$value2['id'])
                {
                  $allmodular[$key1]['second_modular'][$key3]['checked']=1;
                  break;
                }
            }
          }
}
/*var_dump($allmodular);
exit;*/
         $this->assign('allmodular',$allmodular);
         $this->assign('data',$data);

        $this->display();
        }
      else
        {

          $id=I('post.id');
        $this->model= D('Usergroup');
            $data=I('post.');
            unset( $data['modular']);
        
            if($this->model->create($data)){
                $this->model->save($data);
                M('usergroup_modular')->where("usergroup_id=$id")->delete(); 
                  foreach (I('post.modular') as $key => $value) {
                    $data1['usergroup_id']=$id;
                     $data1['modular_id']=$value;
                    M('usergroup_modular')->data($data1)->add(); 
                  }
                  $this->success('操作成功！','index');
                  
             

            }else{
              $this->error($this->model->getError());
            }
          
        }
  }
  public function add(){
    if(IS_GET)
      {
    $allmodular = M('first_modular')->field('id,name')->select();
    foreach ($allmodular as $key => $value) {
      $id=$value['id'];
      $arr=M('second_modular')->field('id,name')->where("first_modular_id=$id")->select();
      $allmodular[$key]['second_modular']=$arr;
    }
   /* var_dump(json_encode($allmodular));
    exit;*/
     $this->assign('allmodular',$allmodular);
    $this->display();
  }
  else{
            $this->model= D('Usergroup');
        $data=I('post.');
        unset( $data['modular']);

  if($this->model->create($data)){
      $this->model->add($data);
       $id=$this->model->getLastInsID();
      foreach (I('post.modular') as $key => $value) {
            $data1['usergroup_id']=$id;
             $data1['modular_id']=$value;
            M('usergroup_modular')->data($data1)->add(); 
          }
      $this->success('操作成功！','');
    }else{
      $this->error($this->model->getError());
    }
  }
  }


   public function createmodular(){
    $this->model= D('modular');
    $data=I('post.');

  if($this->model->create($data)){
      $this->model->add($data);
      $this->success('操作成功！','');
    }else{
      $this->error($this->model->getError());
    }
  }
    public function off_screen(){
  $post=I('post.');

    if($this->model->save($post))
    {
      echo 1;
    }
    else
      echo "屏蔽失败";
   }
      public function on_screen(){
  $post=I('post.');

  if($this->model->save($post))
    {
      echo 1;
    }
    else
      echo "解除屏蔽失败";
   }
}