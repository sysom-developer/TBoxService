<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Util\String;

/**
 * 
 */
class Air_conditionerController extends BaseController {
  public function __construct(){
    parent::__construct();

    $this->model =  M('Sensor');
  }
	public function index(){
		
		$this->display();
	}

	public function add(){
	if(IS_GET)
	{
    	$this->display();
    }
    else{
    	   	$this->model= D('Classify');
   	$data=I('post.');
    if($this->model->create($data)){
    	if($this->model->add($data))
    		$this->success('操作成功！','index');
    	else
    		$this->error('操作失败！','');
      	
    }else{
      $this->error($this->model->getError());
    }
    }
  }

  	public function edit(){
  		if(IS_GET)
  		{
  			$data['device_id']=I('get.device_id');
  			$data['sensor_id']=I('get.sensor_id');
  			$data['user_id']=session("adminid");
  			$arr = $this->model->where($data)->find();
    		$this->assign('data',$arr);
    		$this->display();
    		}
    	else
	    	{
				$data=I('post.');
				    if($this->model->create($data)){
				     	if($this->model->save($data))
				      		$this->success('操作成功！','');
				  		else
				  			$this->error('操作失败！','');

				    }else{
				      $this->error($this->model->getError());
				    }
	    		
	    	}
  }
  public function delete(){
  		$data['device_id']=I('get.device_id');
  		$data['sensor_id']=I('get.sensor_id');
  		$data['user_id']=session("adminid");
		if($this->model->where($data)->delete())
	  		$this->success('操作成功！','');
		else
			$this->error('操作失败！','');
	  	}
  
	public function recycle_bin(){
	if(IS_GET)
	{
		$map['is_delete']=1;
	    $field="classify.id ,classify.name,classify.sort";
	    $this->assign('type','delete');
	    $this->base_join_index($this->model,$map,$join,$field,'','Classify:index');
	   }
	else
	{
		$data['id']=I('post.id');
		$data['is_delete']=1;
	  if($this->model->save($data))
	  {
	  	$return['status']=1;
	  	$this->ajaxReturn($return);
	  }
	  else
	  	echo "删除失败";
	}
	}
   public function reduction(){
		$data['id']=I('post.id');
		$data['is_delete']=0;
	  if($this->model->save($data))
	  {
	  	$return['status']=1;
	  	$this->ajaxReturn($return);
	  }
	  else
	  	echo "回收失败";
   }
     public function selectlist(){
		$data['is_delete']=0;
		$arr=$this->model->field('id,name,if_company')->where($data)->select();
	  if($arr)
	  {
	  
	  	$this->ajaxReturn($arr,'');
	  }
	  else
	  	echo 0;
   }
   
   
}
