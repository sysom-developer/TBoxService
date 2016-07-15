<?php
namespace Api\Controller;
use Think\Model;
use Think\Controller\RestController;
  /*
      用户
    */
class UserController extends BaseController {
    public function __construct(){
    parent::__construct();
    $this->model =  M(CONTROLLER_NAME);
    
    }
    /*
        绑定设备
        param passcode 设备秘钥
        param device_id 设备编号
        param remarks 备注
        return     
    */ 
    public function bindings(){
        
        $where['passcode']=I('post.passcode');
        $where['device_id']=I('post.device_id');
        
        $model=M('device');
        $binding=M('binding');
        $binding->startTrans(); 
        $count=$model->field('device_id')->where($where)->find();
        if($count)
        {
            $data['user_id']=$this->user_id;
            $data['device_id']=$count['device_id'];
            tag('ifbind',$data);
                
            if($data['msg'])
            {
                $binding->rollback();
                $return['error']['msg']="Bound";
                $this->response($return,'json');
            }
            unset($data['msg']);
            $data['binding_at']=time();
            $data['remarks']= I('post.remarks');

            $arr=$binding->add($data);
            if(!$arr)
            {
                $binding->rollback();
                $return['error']['msg']="Bind failed";
                $this->response($return,'json');
            }        
        }
            else
            {
                $binding->rollback();
                $return['error']['msg']="Bind information error";
                $this->response($return,'json'); 
            }
            $binding->commit();
            $return['success']['msg']="Bind successfully";
            $this->response($return,'json'); 
  }
    /*
        解绑设备
        param passcode 设备秘钥
        param did 设备编号
        param remarks 备注
        return     
    */ 
  public function unbindings(){
        $where['device_id']=I('get.device_id');
        $model=M('device');
        $binding=M('binding');
        $return=array();
        $binding->startTrans();
        $count=$model->field('device_id')->where($where)->find();
        if($count)
            {
                $data['user_id']=$this->user_id;
                $data['device_id']=$count['device_id'];
                tag('ifbind',$data);
                
                if(!$data['msg'])
                {
                $binding->rollback();
               
                $return['error']['msg']="Device not bound";
                $this->response($return,'json');
            }
            $msg=$binding->where($data)->delete();
            if(!$msg)
            {
                $binding->rollback();
               
                $return['error']['msg']="Bind failure";
                $this->response($return,'json');
            }
        }
        else{
                $binding->rollback();
                
                $return['error']['msg']="Device does not exist";
                $this->response($return,'json'); 
        }
    
    $binding->commit();
    $return['success']['msg']="Tie success";
    $this->response($return,'json'); 
    
  }
 	 /*
        获取绑定设备列表
        return     
    */ 
  public function binded(){
    $data['binding.user_id']=$this->user_id;
    $page=I('get.page');
    $arr= M('device')->field("device.device_id,device.passcode,device.is_online,binding.remarks")->join("binding on binding.device_id=device.device_id")
     ->where($data)->page($page)->select();
    $this->response($arr,'json');
  }
  
   public function edit_pwd(){

        $data['id']=$this->user_id;
        $arr=$this->model->where($data)->find();
        $password=md5(md5(I('put.old_pwd')).$arr['salt']);
        if($arr['password']==$password)
        {
            $arr['password']=I('put.new_pwd');
            $arr2=$this->model->save($arr);
            if(!$arr2)
            {
                $return['error']['msg']="Modify password failed";
                $this->response($return,'json'); 
            }
            else
            {
                $return['success']['msg']="Modify password success";
                $this->response($return,'json'); 
            }
        }
        else
        {
            $return['error']['msg']="Password error";
            $this->response($return,'json'); 
        }

        
   }
     public function edit_email(){

        
        if(!is_email(I('put.email')))
        {
            $return['error']['msg']="Mailbox not correct";
            $this->response($return,'json');  
        }
        $data['email']=I('put.email');
        $arr=$this->model->where($data)->find();
        if($arr)
        {
            $return['error']['msg']="Mailbox already exists";
            $this->response($return,'json'); 
        }
        $data['id']=$this->user_id;
        $data['status']=0;
        $arr=$this->model->save($data);
        if($arr)
        {
            $title="OKIN激活邮件";
            $content="OKIN激活邮件内容";
            sendMail(I('put.email'), $title, $content);
            $return['success']['msg']="Modify mailbox success";
            $this->response($return,'json'); 
        }
        else
        {
            $return['error']['msg']="Modify mailbox failed";
            $this->response($return,'json'); 
        }  
        
   }
    public function edit_phone(){

        
        if(!is_phone(I('put.phone')))
        {
            $return['error']['msg']="手机格式不正确";
            $this->response($return,'json');  
        }
        $data['phone']=I('put.phone');
        $data['verify']=I('put.verify');
        $data['token']=I('put.token');
        $arr=M('verify')->where($data)->find();
        if(!$arr)
        {
            $return['error']['msg']="验证码不正确";
            $this->response($return,'json'); 
        }
        $data['id']=$this->user_id;
        $data['status']=0;
        $arr=$this->model->save($data);
        if($arr)
        {
            $title="OKIN激活邮件";
            $content="OKIN激活邮件内容";
            sendMail(I('put.email'), $title, $content);
            $return['success']['msg']="修改邮箱成功";
            $this->response($return,'json'); 
        }
        else
        {
            $return['error']['msg']="修改邮箱失败";
            $this->response($return,'json'); 
        }  
        
   }
 
   /*发送控制传感器命令
    param $sensor_id 传感器ID
    return 字段值  
  */
   public function control_sensor(){

        $where['user_id']=$this->user_id;
        $sensor_id=I('post.sensor_id');
        $where['device_id']=substr($sensor_id,0,-2);
        $device_id=$where['device_id'];
        $value=I('post.value');
        $binding=M('binding');
        $control=M('control');
        $sensor=M('sensor');

        tag('ifbind',$where);
        if(!$where['msg'])
        {
            $return['error']['msg']="Device not bound";
            $this->response($return,'json');
        }
        $control->startTrans();
        $data['timestamp']=time();
        $data['control_id']=$sensor_id;
        $data['value']=$value;
        $data2['status']=0;
        $data2['value']=$value;
        $arr=$control->add($data);
        $arr2=$sensor->where("sensor_id='$sensor_id'")->save($data2);
        if(!($arr&&$arr2))
        {
            $control->rollback();
            $return['error']['msg']="Control failure";
            $this->response($return,'json'); 
        }
        

            
        $control->commit();
        $return['success']['msg']="Control success";
        $this->response($return,'json'); 
   }
      /*发送控制设备命令
    param $device_id 设备ID
    return 字段值  
  */
   public function control_device(){
        $where['device_id']=I('post.device_id');
        $where['user_id']=$this->user_id;
        tag('ifbind',$where);
        $device_id=$data['device_id'];
        if(!$where['msg'])
        {
            $return['error']['msg']="Device not bound";
            $this->response($return,'json');
        }
        $data['timestamp']=time();
        $data['control_id']=$device_id;
        $data['value']=I('post.runsys');
        $data2['runsys']=I('post.runsys');
        $data2['status']=0;
        $arr=$control->add($data);
        $arr2=M('device')->where("device_id='$device_id'")->save($data2);
        if(!($arr&&$arr2))
        {
            $return['error']['msg']="Control failure";
            $this->response($return,'json');
        }
        else
        {
            $return['success']['msg']="Control success";
            $this->response($return,'json');
        }
   }

}