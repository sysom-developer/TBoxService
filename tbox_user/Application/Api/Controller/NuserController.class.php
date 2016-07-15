<?php
namespace Api\Controller;
use Think\Model;
use Think\Controller\RestController;
  /*
      未登录用户
    */
class NuserController extends RestController {
  protected $model;
  protected $lang;
  public function __construct(){
        parent::__construct();
        $this->model = M('user');
        $this->lang = include_once dirname(__FILE__)."/My_lang/zh_us.php";

}
   
  public function apikey(){
    $name=I('post.name');
    if(is_phone($name))
    {
        $data['phone']=$name;
    }
    elseif(is_email($name))
    {
        $data['email']=$name;
    }
    else
    {
        $data['name']=$name;
    }
    $arr= $this->model->where($data)->find();

    if(!$arr)
    {
        $return['error']['msg']='Wrong account';
        
        $this->response($return,'json');
    }
    $password=I('post.password');
    if($arr['status']===0)
    {
        $return['error']['msg']='Account is not activated';
        
        $this->response($return,'json');
    }
    if($arr['password']==md5(md5($password).$arr['salt']))
    {
    	
    	$data2['user_id']=$arr['id'];
        $data2['token']=I('post.token');
    	$arr2=M('apikey')->where($data2)->find();
      
    	if($arr2)
    	{
    		$data2['id']= $arr2['id'];
    		$data2['apikey']= random(20); 
    		$data2['created_at']= time();
            $created_at=86400*layout_time+$data2['created_at'];
    		if(M('apikey')->save($data2))
    		{
                $return['success']['created_at']=$created_at;
	    		$return['success']['apikey']=$data2['apikey'];
	    		$this->response($return,'json');
    		}
    		else
    		{
    			$return['error']['msg']='Data update failed';
                $this->response($return,'json');
    		}
    	}
    	else
    	{
    		$data2['apikey']= random(20); 
    		$data2['created_at']= time();
            $created_at=86400*layout_time+$data2['created_at'];

    		if(M('apikey')->add($data2))
    		{
                $return['success']['created_at']=$created_at;

	    		$return['success']['apikey']=$data2['apikey'];
	    		$this->response($return,'json');
    		}
	    	else
	    	{
	    		$return['error']['msg']='Data insertion failed';
                $this->response($return,'json');
	    	}
    	}
		

    }
    else
    {
    	$return['error']['msg']='Password error';
        $this->response($return,'json');
    }
   
   
  }
  /*
		注册用户
		param name 用户名
		param password 密码
		return  states 状态码  
	*/
    public function create(){

    $name=I('post.name');
    $email=I('post.email');
    $phone=I('post.phone');
    if($phone)
    {
        $data['phone']=$phone;
        $data2['phone']=$phone;
        $data['status']=1;
        $data2['token']=I('post.token');
        $data2['verify']=I('post.verify');
        $arr=M('verify')->where($data2)->find();
        if($arr==null)
        {
            $return['error']['msg']='Verification code is not correct';
            $this->response($return,'json');
 
        }
        else
        {
            if((time()-$arr['send_at'])>120)
            {
                $return['error']['msg']='Verification code has expired';
                $this->response($return,'json');
            }
        }  
    }
    elseif($email)
    {
        $data['email']=$email;
        $data['status']=0;
    }
    elseif($name)
    {
        $data['name']=$name;
        $data['status']=1;
    }
    else
    {
        $return['error']['msg']='No user name';
        $this->response($return,'json');
    }
    $data['salt']=random(4);
    $data['password']=md5(md5(I('post.password')).$data['salt']);
    $data['created_at']=time();
    $data['type']=I('post.type');
    $this->model = D('user');

    if (!$this->model->create($data,2))
    { 
        // 如果创建失败 表示验证没有通过 输出错误提示信息 
     	$return['error']['msg']=$this->model->getError();
        $this->response($return,'json');
     	
    }
    else
    {
        if(!$this->model->add($data))
    	{
     		$return['error']['msg']='Registration failure';
            $this->response($return,'json');
    	}// 验证通过 可以进行其他数据操作
        else
        {
            $return['success']['msg']='Registration success';
            $this->response($return,'json');
        }
    }
 	
  }
  public function invoke_email(){

        $data['email']=I('post.email');
        $data['token']=I('post.token');

        $arr=M('email')->field("id,user_id,send_at")->where($data)->find();
        if($arr)
        {
            if((time()-$arr['send_at'])>3600)
            {
                $return['error']['msg']='Activation link failure';
                $this->response($return,'json');
            }
            $data2['status']=1;
            $data2['id']=$arr['user_id'];
            $arr2=M('user')->save($data2);
            if($arr2)
            {
                $return['success']['msg']='Activate success';
                $this->response($return,'json');
            }
            else
            {
                $return['error']['msg']='Activation failed';
                $this->response($return,'json');
            }
        }
        else
        {
            $return['error']['msg']='Invalid activation';
            $this->response($return,'json');
        }
  }
 public function verify(){
    echo "待开发";
    $token=I('post.token');
    $phone=I('post.phone');
    exit;
 }
}