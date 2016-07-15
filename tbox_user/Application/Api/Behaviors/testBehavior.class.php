<?php
namespace Api\Behaviors;
class testBehavior extends \Think\Behavior
	{ 
   //行为执行入口    
	public function run(&$param){
		if(Intval($param)<=6)
		{
			echo "间隔太短";
			exit;
		}

	}
}