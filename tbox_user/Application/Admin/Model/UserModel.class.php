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
	 protected $fields = array('_pk'=>'id','id','name','created_at','salt','password','last_at');
	protected $_validate = array(    
	  array('name','',"用户名已存在",0,'unique',2), // 在新增的时候验证name字段是否唯一        
	   array('password','checknull',"密码不为空",0,'callback'),
	   array('name','checknull',"用户名不为空",0,'callback',2));
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
