<?php
namespace Api\Behaviors;
class ccacheBehavior extends \Think\Behavior
	{ 
   //行为执行入口    
	public function run(&$param){
		$cachename;
		foreach ($param as $key => $value) {
			$cachename.=$value;
		}
        $cache=S($cachename);
        if($cache!==false)
        {
        	$param['data']=$cache;
        }
        else
        {
        	$param['data']=M('binding')->where($data)->select();
        }

	}
}