<?php
namespace Api\Model;
use Think\Model;
	/*
	传感器模型
	字段
	title 名称
	about 类型
	tags 标签
	unit_name 属性名称
	unit_symbol 属性单位
	*/
class SensorModel extends Model
	{  
	 protected $fields = array('_pk'=>'id','id','about','title','tags','unit_name','user_id','device_id','sensor_id','unit_symbol','upper_threshold','lower_threshold');
	protected $_validate = array(    
	  array('about','checknull',415,1,'callback'), // 自定义函数验证密码格式   
	   array('title','checknull',415,1,'callback'),
	   array('tags','checknull',415,1,'callback'),/*
	   array('unit_name','checknull',415,1,'callback'),
	   array('unit_symbol','checknull',415,1,'callback'),*/
	   array('user_id','checknull',415,1,'callback'));
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
