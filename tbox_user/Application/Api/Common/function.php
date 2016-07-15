<?php

function is_phone($phone) {  
    if (strlen ( $phone ) != 11 || ! preg_match ( '/^1[3|4|5|8][0-9]\d{4,8}$/', $phone )) {  
        return false;  
    } else {  
        return true;  
    }  
}  
/** 
 * 验证字符串是否为数字,字母,中文和下划线构成 
 * @param string $username 
 * @return bool 
 */  
function is_check_string($str){  
    if(preg_match('/^[\x{4e00}-\x{9fa5}\w_]+$/u',$str)){  
        return true;  
    }else{  
        return false;  
    }  
}  

/**
 * 获取自定义的header数据
 */
function get_all_headers(){

    // 忽略获取的header数据
    $ignore = array('host','accept','content-length','content-type');

    $headers = array();

    foreach($_SERVER as $key=>$value){
        if(substr($key, 0, 5)==='HTTP_'){
            $key = substr($key, 5);
            $key = str_replace('_', ' ', $key);
            $key = str_replace(' ', '-', $key);
            $key = strtolower($key);

            if(!in_array($key, $ignore)){
                $headers[$key] = $value;
            }
        }
    }

    return $headers;

}

     function uploadoneimg($file,$savePath =""){  
	   $upload = new \Think\Upload();// 实例化上传类  
      $upload->maxSize   =     3145728 ;
	   $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型  
	   $upload->rootPath = './Uploads/';
  	$upload->savePath  = $savePath   ; // 设置附件上传目录    // 

    $info   =   $upload->uploadOne($file);  

        if(!$info) 
        {// 上传错误提示错误信息     
            return $upload->getError();   
        }
        else
        {
              // 上传成功 获取上传文件信息         
           return $info['savepath'].$info['savename'];   
 		   }
}
  function explain_device_id($device_key){
    $device_id=md5(substr($device_key,1,15).'fo&dsm',true);
    $return=0;
    switch ($count) {
      case 'A':
        $return=1;
        break;
      case 'B':
        $return=2;
        break;
      case 'C':
        $return=3;
        break;
      case 'D':
        $return=4;
        break;     
      default:
        # code...
        break;
    }
    return $return;
  }
  function checkPwd($password){

		$len=strlen($password);
			if($len<6)
				return false;
			if($len>50)
				return false;
			return true;
	}
     function random($length = 6 , $numeric = 0) {
          PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
          if($numeric) {
               $hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
          } else {
              $hash = '';
              $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
              $max = strlen($chars) - 1;
              for($i = 0; $i < $length; $i++) {
                $hash .= $chars[mt_rand(0, $max)];
              }
          }
          return $hash;
    }
     function Post($curlPost,$url,$header){
          $curl = curl_init();
          curl_setopt($curl, CURLOPT_URL, $url);
          curl_setopt($curl, CURLOPT_HEADER, false);
        /*  curl_setopt($curl, CURLOPT_HTTPHEADER, $header);*/
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_NOBODY, true);
          curl_setopt($curl, CURLOPT_POST, true);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
       
          $return_str = curl_exec($curl);
          curl_close($curl);
          return $return_str;
    }

    function xml_to_array($xml){
      $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
      if(preg_match_all($reg, $xml, $matches)){
          $count = count($matches[0]);
          for($i = 0; $i < $count; $i++){
              $subxml= $matches[2][$i];
              $key = $matches[1][$i];
              if(preg_match( $reg, $subxml )){
                  $arr[$key] =xml_to_array( $subxml );
              }else{
                  $arr[$key] = $subxml;
              }
          }
      }
      return $arr;
    }

