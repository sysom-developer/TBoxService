<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 管理员用户
 */
class LoginController extends Controller {

  public function login(){
    $name = I('post.name');
    $password = I('post.password');
/*  $verify = I('post.verify');*/
    $User = M('User');
    if( $name && $password) {
      if(!$User->create($_POST,1)){

         exit($User->getError());
      }
        else{ 
/*              if(!$this->check_verify($verify))
              {
                 $this->ajaxReturn('验证码不正确','eval');
              }*/
          $arr=$User->field('id,name,password,salt,logintime')->where(array("name"=>$name))->find();
            
              if($User->where(array("name"=>$name))->select()==null){

              $this->ajaxReturn('用户名不存在','eval');
              }
              else if($arr['password']!=md5(md5($password).$arr['salt'])){

              $this->ajaxReturn('密码错误');
              }
           /*   else if($arr['is_on']==1)
              {
                 $this->ajaxReturn('用户已登录');
              }*/
              else{
                $data['logintime']=time();
               $User->where(array("name"=>$name))->save($data);
/*               $id=$arr["id"];
              
               $arr2=M("admin")->field("usergroup_modular.modular_id")->join("left join usergroup_modular on usergroup_modular.usergroup_id=admin.usergroup_id")->where("admin.id=$id")->select();
                $new=array();
               foreach($arr2 as $v){
                  $key = array_keys($v);
                foreach($key as $kv){
                $new[$kv][] = $v[$kv];
                }

                }
              
               $group=implode(",",$new['modular_id']);
                session('group',$group);
*/
              session(array('name'=>'session_id','expire'=>3600));
              session('id',$arr["id"]);
              
              $token=session_id();
              if(S($token)=="1")
                {
                  S($token,NULL);
                }
              S($token,'1',3600);
              cookie('token',$token);

             /* cookie('logintime',date('Y-m-d H:i:s',$arr["logintime"]));
              cookie('name',$arr["name"]); */ //设置cookie
              $this->ajaxReturn('1');
              }
            }

      }
    else
    $this->display();
  }

  
      public function check_verify($code, $id = '')
  {

    $verify = new \Think\Verify();  

    return $verify->check($code, $id);
  }
     //生成验证码
       public function verify()
    {
        $Verify =     new \Think\Verify();
        $Verify->fontSize = 30;
        $Verify->length   = 4;
        $Verify->useNoise = false;
        ob_end_clean();
        $Verify->entry();
    }

    public function layout(){
       /* $name= $_SESSION['name'];
       $data['is_on']=0;
        $admin->where(array("name"=>$name))->save($data); */
      $_SESSION=array();
        if(isset($_COOKIE[session_name()])){
          setcookie(session_name(),'',time()-1,'/');
        }
      session_destroy();
      $this->redirect('Login/login');
    }
}
?>