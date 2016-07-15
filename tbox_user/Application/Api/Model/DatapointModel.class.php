<?php
namespace Api\Model;
use Think\Model;
	/*
	数据点模型
	字段
	title 名称
	about 类型
	tags 标签
	unit_name 属性名称
	unit_symbol 属性单位
	*/
class DatapointModel extends Model
	{  
	 protected $fields = array('_pk'=>'id','id','value','timestamp','user_id','device_id','sensor_id');
	protected $_validate = array(    
	  array('value','checknull',415,1,'callback'), // 自定义函数验证密码格式   
	   array('timestamp','checknull',415,1,'callback'),
	   array('user_id','checknull',415,1,'callback'),
	   array('sensor_id','checknull',415,1,'callback'),
	   array('device_id','checknull',415,1,'callback'));
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
