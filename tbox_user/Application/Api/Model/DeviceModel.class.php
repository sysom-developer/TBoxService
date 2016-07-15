<?php
namespace Api\Model;
use Think\Model;
	/*
	设备模型
	字段
	title 名称
	about 类型
	tags 标签
	local 地点
	latitude 经度
	longitude 纬度
	*/
class DeviceModel extends Model
	{  
	 protected $fields = array('_pk'=>'id','id','device_id','about','title','tags','local','user_id','latitude','longitude','network','activity');
	protected $_validate = array(    
	  array('about','checknull',415,1,'callback'), // 自定义函数验证密码格式   
	   array('title','checknull',415,1,'callback'),
	   array('tags','checknull',415,1,'callback'),/*
	   array('local','checknull',415,1,'callback'),
	   array('latitude','checknull',415,1,'callback'),
	   array('longitude','checknull',415,1,'callback'),*/
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
