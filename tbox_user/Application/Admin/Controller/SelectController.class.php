<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Util\String;

/**
 * 
 */
class SelectController extends BaseController {
  public function __construct(){
    parent::__construct();

  }
	public function Car_brand(){
	$arr=['GeelyNL-3','Geely_EF-3/4'];
    $this->ajaxReturn($arr);
	}
	public function tbox_id(){
	$arr=['1','2'];
    $this->ajaxReturn($arr);
  }
	public function devices(){
		$data['user_id']=session("adminid");
		$arr= $this->model->where($data)->select();
		foreach ($arr as $key => $value) {
			$data2['device_id']=$value['device_id'];
			$data2['user_id']=$data['user_id'];
			$arr[$key]['sensor']=M('sensor')->where($data2)->select();
		}
		$reconds= M('apikey')->where($data)->find();

		$this->assign("arr",$arr);
		$content = $this->fetch('device');
		$this->assign("content",$content);
		$this->assign("reconds",$reconds);
		$this->display();
	}
	public function del_dev_img(){
		$data['device_id']=I('post.dev_img_id');
		$data['user_id']=session("adminid");
		$data1 = $this->model->where($data)->find();
		if(!empty($data1['relative_url']))
		unlink($_SERVER['DOCUMENT_ROOT'].$data1['relative_url']);
		$data2['relative_url']="";
		$data2['img']="";
		if($this->model->where($data)->save($data2))
	  		{
	  			$return['status']='success';
	  		}
			else
				 {
				 	
				 	$return['message']='上传失败';
				 }
				 $this->ajaxReturn($return,'json');
	}
	public function upload_dev_img(){
		
		$data['device_id']=I('post.device_id');
		$data['user_id']=session("adminid");
		$data1 = $this->model->where($data)->find();
		if(!empty($data1['relative_url']))
		unlink($_SERVER['DOCUMENT_ROOT'].$data1['relative_url']);
		$url='/Uploads'.uploadoneimg($_FILES['uimg'],'/Device/');
		$data2['relative_url']=imgurl.$url;
		$data2['img']=ip.$data2['relative_url'];
	 	if($this->model->where($data)->save($data2))
	  		{
	  			$return['status']='success';
	  			$return['file_dir']=$data2['img'];
	  			$return['tn_dir']=$data2['img'];
	  			$return['id']=$data['device_id'];
	  		}
			else
				 {
				 	
				 	$return['message']='上传失败';
				 }


		$this->ajaxReturn($return,'json');
	}
	public function delete(){
		$data['device_id']=I('get.id');
  		$data['user_id']=session("adminid");
		if($this->model->where($data)->delete())
	  		$this->success('操作成功！','');
		else
			$this->error('操作失败！','');
	  	}
  	public function edit(){
  		if(IS_GET)
  		{
  			$data['device_id']=I('get.id');
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
	public function recycle_bin(){
		if(IS_GET)
		{
		$map['is_delete']=1;
	    $this->assign('type','delete');
	    $this->base_join_index($this->model,$map,$join,$field,'','Advert:index');
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
	public function off_line(){
	$post=I('post.');
	  if($this->model->save($post))
	  {
	  	echo 1;
	  }
	  else
	  	echo "下线失败";
   }
   		public function on_line(){
	$post=I('post.');
	$post['online_at']=time();

	if($this->model->save($post))
	  {
	  	echo 1;
	  }
	  else
	  	echo "上线失败";
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
   
}
