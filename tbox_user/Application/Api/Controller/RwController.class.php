<?php
namespace Api\Controller;
use Think\Model;
use Think\Controller;

 class RwController extends Controller {
 
 	public function dsa(){
     	
		$value="hello";
		S('a',$value);
     	
		$ss = S('a');
		echo "$ss";
		exit;
		
 	}
 	public function demo(){
 		$time="2";
 		tag('time',$time);
		$client = stream_socket_client("120.27.132.188 8282", $errno, $errmsg);
		if(!$client)
	{
    	exit("$errmsg\n");
}
// 设置成阻塞
stream_set_blocking($client, 1);
$data['apikey']='ugvM6Jexk4VC5npVBbDN';
$json=json_encode($data);

$json_len = strlen($json);
// 文件二进制数据

$PACKAGE_HEAD_LEN =4;
// 协议包
$package = pack('NC', $PACKAGE_HEAD_LEN  +$json_len+ strlen($file_data), $name_len) . $file_name . $file_data;
// 执行上传
fwrite($client, $package);
// 打印结果
echo fread($client, 8192),"\n";
 	}
 }
