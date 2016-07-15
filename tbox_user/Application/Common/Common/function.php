<?php
	function push_message($deviceToken,$message,$url='ssl://gateway.sandbox.push.apple.com:2195')
	{
		
		//这里是密钥密码
		$passphrase = C('passphrase');
		//推送的消息
		////////////////////////////////////////////////////////////////////////////////
		$pem = dirname(__FILE__).'/'.'ck.pem';

		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $pem);//ck文件
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		foreach ($deviceToken as $key => $value) {
		// Open a connection to the APNS server
		$fp = stream_socket_client(
		    $url, $err,
		    $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		 
		if (!$fp)
		    return "无法连接: $err $errstr" . PHP_EOL;

		 
		// Create the payload body
		$body['aps'] = array(
		    'alert' => $message,
		    'sound' => 'default'
		    );
		
		// Encode the payload as JSON
		$payload = json_encode($body);
		
			# code...
		// Build the binary notification

		$msg = chr(0) . pack('n', 32) . pack('H*',$value['token']) . pack('n', strlen($payload)) . $payload;
		
		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));
		
		if (!$result) 
		    $result1[$key]= '信息没有发送' . PHP_EOL;
		else
		     $result1[$key]= '信息成功发送' . PHP_EOL;
		
		
	
		// Close the connection to the server
		fclose($fp);
		}
		
		return  $result1;
	}
