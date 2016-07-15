<?php
namespace Api\Model;
use Think\Model;
	/*
	用户模型
	字段
	username 用户名
	password 密码
	created_at 创建时间
	salt 盐值
	*/
class UserModel extends Model
	{  
	 protected $fields = array('_pk'=>'id','status','id','name','created_at','update_at','salt','password','type','phone','email');
	protected $_validate = array(    
	  array('name','','Username already exists',1,'unique',2),
	  array('email','','Mail already exists',1,'unique',2),
	  array('phone','','phone already exists',1,'unique',2), // 在新增的时候验证name字段是否唯一        
	  array('password','checkPwd','Password format error',1,'function'), // 自定义函数验证密码格式   
	   array('password','checknull','Password is not empty',1,'callback'));
	function checknull($data){
		if($data==null)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	}
