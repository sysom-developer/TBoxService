<?php


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
          curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_NOBODY, true);
          curl_setopt($curl, CURLOPT_POST, true);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
       
          $return_str = curl_exec($curl);
          curl_close($curl);
          return $return_str;
    }
    function Url($url){
          $curl = curl_init();
          $method=$url['request_type'];
         
          $apikey=$url['apikey'];
          $header[0]="X-HTTP-Method-Override: $method";
          $header[1]="apikey:$apikey";
          $header[2]="Content-Type: application/x-www-form-urlencoded";
          curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //不验证证书
          curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); //不验证证书
          curl_setopt($curl, CURLOPT_URL, $url['url']);
          curl_setopt($curl,CURLOPT_HTTPHEADER,$header);//设置HTTP头信息
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_CUSTOMREQUEST,$method);
          if( $method=="POST")
          {
             
            curl_setopt($curl,CURLOPT_POST,1);
            curl_setopt($curl, CURLOPT_POSTFIELDS,htmlspecialchars_decode($url['post_data']));
        

          }
          elseif($method=="PUT")
          {
                curl_setopt($curl, CURLOPT_POSTFIELDS,htmlspecialchars_decode($url['post_data']));
          }
 
          $return_str = curl_exec($curl);
           

          if ($return_str === FALSE) {

          echo "cURL Error: " . curl_error($curl);
          exit;
          }
          else
          {
           
            $rinfo=curl_getinfo($curl);
            $rinfo['data'] =$return_str;
            curl_close($curl);
            return $rinfo;
          }
          
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
    function  design_formulas($form,$data){
      switch ($form) {
        case 'tp_fulijiankang':
          $re['ga']=$data['1'];
          break;
        
        default:
          # code...
          break;
      }
    }
