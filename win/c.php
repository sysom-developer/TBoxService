<?php
/** 客户端 **/
$hex ="123456789012345678901234";

$data=base_convert($hex[0] . $hex[1] , 16 , 2);
for ($i=0; $i < strlen($data) ; $i++) { 
	if($data[$i]=="1")
	{
		echo $data[$i];
	}
	else
	{
		echo $data[$i];
	}
}
exit;
$address = "127.0.0.1:8282";

/*$data ="123456789012345678901234";
echo substr($data,21);
exit;*/
// 建立socket连接
$client = stream_socket_client($address, $errno, $errmsg);
if(!$client)
{
    exit("$errmsg\n");
}
// 设置成阻塞
stream_set_blocking($client, 1);
// 数据
$a="1234567890";
$id="";
for($i=0;$i<strlen($a);$i++){
	$id=$id.ord($a[$i]);
}
/*echo $id;
exit;*/
$head = chr(0x01)."1".chr(0x7E).chr(0x02).$id.chr(0x7E).chr(0x03).chr(0x1b).chr(0x1d).chr(0x7E).chr(0x04)."4".chr(0x7E).chr(0x05)."5".chr(0x7E).chr(0x06).chr(0x01).chr(0x7E).chr(0x07);
$dead_reckoning_position=chr(0x32).chr(0x01).chr(0x32).chr(0x01).chr(0x32).chr(0x01).chr(0x32).chr(0x01);
$heading=chr(0x32).chr(0x01);
$delta_position_array="";
for ($i=0; $i < 9; $i++) { 
	$delta_position_array.=chr(0x32).chr(0x01).chr(0x32);
}
$visible_satellite_number=chr(0x34);
$altitude=chr(0x12).chr(0x01);
$raw_position=chr(0x12).chr(0x01).chr(0x54).chr(0x44).chr(0x32).chr(0x01).chr(0x32).chr(0x01);
$raw_position_uncertainty_estimate=chr(0x35);
$data=chr(0x7E).chr(0x10).$dead_reckoning_position.$heading.$delta_position_array.$visible_satellite_number.$altitude.$raw_position.$raw_position_uncertainty_estimate;

// 协议头长度 10字节包长+1字节文件名长度
/*for ($i=0; $i < 5; $i++) { 
	$data=$data.chr(0x7E)."dddssq";
	
}*/
/*echo $data;
echo "------";*/
$PACKAGE_HEAD_LEN = 4;
// 协议包
$package = $head.pack('N',$PACKAGE_HEAD_LEN+strlen($head.$data)).$data;
/*echo $package;
echo "------";*/
fwrite($client, $package);
	echo fread($client, 8192),"\n";
// 执行上传
/*fwrite($client, $package);*/
// 打印结果
/*echo fread($client, 8192),"\n";*/