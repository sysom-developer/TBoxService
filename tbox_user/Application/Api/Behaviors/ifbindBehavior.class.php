<?php
namespace Api\Behaviors;
class ifbindBehavior extends \Think\Behavior
	{ 
   //行为执行入口    
	public function run(&$param){
		$where[0]=$param['binding'];
        $where[1]=$param['device_id'];
        $where[2]=$param['device_id'];
/*       $cache=S('binding'.$data['user_id'].$data['device_id']);
*/      tag('ccache',$where);
        if($where['data']!==false)
        {
        	$return=$cache;
        }
        else
        {
        	$return=M('binding')->where($data)->select();
        }
        

		if($return)
		{

			$param['msg']=true;
		}
		else
		{
			$param['msg']=false;
		}

	}
}