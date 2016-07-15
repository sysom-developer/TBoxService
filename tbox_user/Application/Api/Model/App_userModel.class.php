<?php
namespace Api\Model;
use Think\Model;
class App_userModel extends Model
	{  
	 protected $fields = array('_pk'=>'id','user_id','id','relative_url','img','sex','identity','city','province','company_id','other_company');
	protected $_validate = array(    
	   array('sex','array(0,1,2)','性别必须有',1,'in'),
	   array('city','checknull','城市不为空',1,'callback'),
	     array('identity','checknull','身份不为空',1,'callback'),
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
