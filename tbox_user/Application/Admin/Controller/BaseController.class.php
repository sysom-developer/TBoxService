<?php
namespace Admin\Controller;
use Think\Controller;

class BaseController extends Controller {

 protected $model;
/*  public function __construct(){
    parent::__construct();
   $this->model =  M(CONTROLLER_NAME);
    $url1=CONTROLLER_NAME;
   $url2=ACTION_NAME;

   if(  ACTION_NAME=="selectlist")
   {
    
   }
   else{
   if(M('second_modular')->join('usergroup_modular on usergroup_modular.modular_id=second_modular.id')
    ->join('usergroup on usergroup.id=usergroup_modular.usergroup_id')
    ->join('admin on admin.usergroup_id=usergroup.id')
    ->join("first_modular on second_modular.first_modular_id=first_modular.id")
    ->where("admin.id='".session('adminid')."' and first_modular.url='$url1' and second_modular.url='$url2'")->find()==null)
    M('second_modular')session('group')
   { 
    if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){ 
      echo "你没有权限";
      exit;
     }else{ 
      $this->error('你没有权限');
    }
 }
   }
    // echo M('second_modular')->getLastSql();
    // exit;
    
   
  }*/
   Public function _initialize(){
        if (!isset($_SESSION['id'])){
          $this->error('你没有权限,请重新登录', U('Admin/Login/login')); 
        }
        else
        {
          $url1=CONTROLLER_NAME;
          $url2=ACTION_NAME;
          $this->assign('controller_name',CONTROLLER_NAME);
          $this->assign('action_name',ACTION_NAME);
          $this->assign('username',cookie('name'));
        }
       
   }
  /**
   * [通用的数据]
   * @param  [type] $app_model [数据模型对象]
   * @param  [type] $map       [查询条件]
   * @return [type]            [description]
   */
  public function base_index($app_model, $map){
    $parameter['keyword'] = $map['keyword'];
    unset($map['keyword']);
    $count = empty($map) ? $app_model->count() : $app_model->where($map)->count();

    $page = new \Think\Page($count, C('PER_PAGE'),$parameter);
    if (empty($map)) {
      $records = $app_model->limit($page->firstRow.','.$page->listRows)->select();
    } else {
      $records =  $app_model->where($map)->limit($page->firstRow.','.$page->listRows)->select();
    }

    $this->assign('keyword',$parameter['keyword']);
    $this->assign('records', $records);
    $this->assign('page', $page->show());
    
    $this->display();
  }
    public function base_join_index($app_model, $map, $join,$field,$order,$model=''){

    $parameter['id'] = $map['id'];
    unset($map['id']);
    $count = empty($map) ? $app_model->count() : $app_model->where($map)->count();

    $page = new \Think\Page($count, C('PER_PAGE'),$parameter);
    if (empty($map)) {
      $records = $app_model->field($field)->join($join)->order($order)->limit($page->firstRow.','.$page->listRows)->select();
   
    } 
    else if(empty($order)){
      $records =  $app_model->field($field)->join($join)->where($map)->order("id desc")->limit($page->firstRow.','.$page->listRows)->select();
  
    }
    else
    {
      $records =  $app_model->field($field)->join($join)->where($map)->order($order)->limit($page->firstRow.','.$page->listRows)->select();

    }
   /*  echo $app_model->getlastsql();exit;*/
    $this->assign('keyword',$parameter['keyword']);
   
    $this->assign('records', $records);
    $this->assign('page', $page->show());

    $this->display();
  }
    public function updateById() {
    $id = I('get.id');
    if($id) {
      $data = $this->model->find($id);
      $this->assign('data',$data);

      $this->display();
      exit;
    }

    $post = I('post.');
    
    if( $post ) {
      if( $this->model->save($post) ) {
        $this->success('修改成功',U(CONTROLLER_NAME.'/index',I('get.')));

      } else {
        $this->error('修改失败!');
      }
    }
  }
  public function createById(){
    $data = I('post.');
    /*var_dump($data);
    exit;*/

    if ($this->model->create()) {
      $this->model->add($data);

      $this->success('操作成功！',U(CONTROLLER_NAME.'/index',I('get.')));
    }else{
      $this->error('添加失败！');
    }
  }
  public function delById() {
    $id = I('get.id');
    $data1 = $this->model->where("id=$id")->find();
    if($data1['relative_url'])
    {
       unlink($_SERVER['DOCUMENT_ROOT'].$data1['relative_url']);
    }
     
    if( $this->model->delete($id) ) {
      $this->success('操作成功！');
    } else {
      $this->error('删除失败！');
    }
  }

//批量删除
  public function delById_all() {

    $obj = I('get.ids');
    // print_r($obj);
    // exit;
        if(is_array($obj))
    {
    foreach ($obj as $key => $id) {
       $data = $this->model->where("id=$id")->find();

    if($data['thumb_relative_url']&&$data['relative_url'])
    {
       unlink($_SERVER['DOCUMENT_ROOT'].$data['relative_url']);
       unlink($_SERVER['DOCUMENT_ROOT'].$data['thumb_relative_url']);
    }
     
    if( $this->model->delete($id) ) {
      
    } else {
      $this->error('删除失败！');
    }
    }
    $this->success('操作成功！');
  }
  else
    {
      $ob=$obj;
       $data = $this->model->where("id=$ob")->find();
   
      if($data['thumb_relative_url']&&$data['relative_url'])
      {
       unlink($_SERVER['DOCUMENT_ROOT'].$data['relative_url']);
       unlink($_SERVER['DOCUMENT_ROOT'].$data['thumb_relative_url']);
      } 
     if( $this->model->delete($ob) ) {
      $this->success('操作成功！');
    } else {
      $this->error('删除失败！');
    }
    }
    //echo $ob;


    $this->display();
  }
//批量放入回收站
  public function Recycle_on_all(){
    $obj = I('get.ids');
    if(is_array($obj))
    {
      foreach ($obj as $key => $data) {
        if($ob){
          $ob .=",".$data;        
        }else{
          $ob=$data;
        }    
      }
    }
    else
    {
      $ob=$obj;
    }
   
    if($this->model->where('id in('.$ob.')')->setField('is_delete','1')) {
      $this->success('操作成功！');
    } else {
      $this->error('删除失败！');
    }
    $this->display();
  }

//批量还原回收站
  public function Recycle_off_all(){
    $obj = I('get.ids');
    //var_dump($obj);
    if(is_array($obj))
    {
    foreach ($obj as $key => $data) {
      if($ob){
        $ob .=",".$data;        
      }else{
        $ob=$data;
      }    
    }
  }
  else{
      $ob=$obj;
  }
    //echo $ob;

    if($this->model->where('id in('.$ob.')')->setField('is_delete','0')) {
      $this->success('操作成功！');
    } else {
      $this->error('删除失败！');
    }
    $this->display();
  }
 

//批量上线
  public function Online_is_on_all(){
    $obj = I('get.ids');
    //var_dump($obj);
if(is_array($obj))
    {
    foreach ($obj as $key => $data) {
      if($ob){
        $ob .=",".$data;        
      }else{
        $ob=$data;
      }    
    }
  }
  else{
     $ob=$obj;
  }
    //echo $ob;
    $data2['is_on']=1;
    $data2['online_at'] = time();

    if($this->model->where('id in('.$ob.')')->setField($data2)) {
      echo "1";
    } else {
      echo '上线失败！';
    }
   
  }

//批量下线
  public function Online_is_off_all(){
    $obj = I('get.ids');
    //var_dump($obj);
if(is_array($obj))
    {
    foreach ($obj as $key => $data) {
      if($ob){
        $ob .=",".$data;        
      }else{
        $ob=$data;
      }    
    }
  }
  else{
     $ob=$obj;
  }
    //echo $ob;
    
    if($this->model->where('id in('.$ob.')')->setField('is_on','0')) {
      echo "1";
    } else {
     echo '下线失败';
    }
    
  }






}
