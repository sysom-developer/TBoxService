<?php
namespace Admin\Controller;
use Think\Controller;
use Org\Util\String;

/**
 * 
 */
class DebugController extends Controller {
	public function index(){

		$this->display();
	}
		public function demo(){
		
		echo "12";
	}
	public function recive(){
		$data=I('post.data');
			/*echo $data['post_data'];exit;*/
		$return=Url($data);
		$return2['http_code']=$return['http_code'];
		$return2['data']=$return['data'];
		$return2['content_type']=$return['content_type'];
		$return2['url']=$return['url'];
		
		$this->assign("Request",$return);
		$this->assign("Response",$return2);
		$this->display("index");
	}
}