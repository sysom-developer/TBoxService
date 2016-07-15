<?php
/**
 * 发送命令基类
 */
date_default_timezone_set( "PRC" );
abstract class ASend{
	
	protected $messageId;   //消息id
	protected $messageData; //消息data
	protected $encryptStr;  //加密字符串
	private   $error;       //错误信息
	private   $key;         //加密key
	

	
	public function __construct( $_key ) {
		$this -> setKey( $_key );
	}
	
	/**
	 * 设置消息数据
	 */
	abstract protected function setData( $_data = '' );
	
	/**
	 * 获得加密后的下发数据(16进制)
	 * @param string $_imsi 加密的
	 * @return string 加密数据（time+messageid+length+data+checksum）
	 */
	public function getSendCommond( $_data ='' ) {
		$this -> setData( $_data );
		$_encryptStr  = $this -> encrypt( );
		$_encryptStr .= $this -> getCheckSum( $_encryptStr );
		
		$bin = pack('H*', $_encryptStr);

		$cmd = str_replace( array(chr('0x1b'), chr('0x1c')), array(chr('0x1b').chr('0x1d'),chr('0x1b').chr('0x1e')), $bin) . chr(0x1c) ;
		
		$hex = bin2hex($cmd);
		return $hex;
	}
	
	/**
	 * 获得加密后的下发数据(2进制)
	 * @param string $_imsi 加密的
	 * @return string 加密数据（time+messageid+length+data+checksum）
	 */
	public function getSendCommondBin( $_data ='' ) {
		$_encryptStr = $this -> getSendCommond( $_data );
		return pack("H*", $_encryptStr );
	}
	
	/**
	 * 加密数据
	 * @param string $imsi 加密key
	 * @return string 加密后的数据
	 */
	protected function encrypt( ) {
		$messageData = $this->formatSendData();
		$key = array();
		for( $i=0;$i<strlen( $messageData ); $i+=2 ) {
			$key[] = substr( $messageData,$i,2);
		}
		
		$keys = array_chunk($key, count($key)/(count($key)/8));
		$_imsi = $this -> getKey();
		$obj = new des( $_imsi );
		$str = "";
		foreach($keys as $key => $value) {
			$data = $obj->encrypt($value);
			foreach ($data as $k => $v) {
				$str .= str_replace("0x", "", $v);
			}
		}
		return $str;
	}
	
	/**
	 * 格式化待发送数据
	 * @return string
	 */
	protected function formatSendData() {
		$time = dechex( time() - strtotime( "2000-01-01 00:00:00" ) );
		$this -> encryptStr  = str_pad( $time, 7*2, self::PADSTR, STR_PAD_RIGHT ); 
		$this -> encryptStr .= $this->messageId;
		// messageData 16 进制， 计算长度：当前长度/2
		
		$messageLength = dechex(strlen($this->messageData)/2);
		if( $this -> messageId == '29' ) {
			$messageLength = sprintf('%04s', $messageLength );
		} else {
			$messageLength = sprintf('%02s', $messageLength );
		}
// 		if( strlen($messageLength)%2 != 0 || strlen($messageLength) == 0 ) {
// 			$messageLength = "0".$messageLength;
// 		}
		$this -> encryptStr .= $messageLength;
		$this -> encryptStr .= $this->messageData;
		
// 		$messageWidth = (strlen($this -> encryptStr)/2) % 8;
// 		if( $messageWidth !== 0 ) {
// 			for( $i=0;$i<(8-$messageWidth);$i++ ){
// 				$this -> encryptStr .= self::PADSTR;
// 			}
// 		}
		$messageWidth = ceil( (strlen($this -> encryptStr)/2) / 8 ); // str/2 算出字节数 然后算出需要补齐到8的几倍字节数
		$this -> encryptStr = str_pad( $this -> encryptStr, 8*$messageWidth*2, self::PADSTR, STR_PAD_RIGHT ); //需要补齐的字节数 2字符算1字节 所以8x宽度x2 
		return $this -> encryptStr;
	}
	
	/**
	 * 计算checkSum
	 * @param string $_encryptStr 加密后的数据
	 * @return string checkSum
	 */
	protected function getCheckSum( $_encryptStr ) {
		$checkSum = 0;
		for( $i=0;$i<strlen( $_encryptStr ); $i+=2 ) {
			$checkSum += hexdec( substr( $_encryptStr,$i,2 ) );
		}
		$checkSum = dechex($checkSum);
		
		if( strlen($checkSum)%2 != 0 ) {
			$checkSum = "0".$checkSum;
		}
		return $checkSum;
	}
	
	/**
	 * 设置错误信息
	 */
	protected function setError( $_error ) {
		$this -> error = $_error;
	}
	
	/**
	 * 获得错误信息
	 */
	public function getError() {
		return $this -> error;
	}
	
	/**
	 * 设置加密key
	 */
	protected function setKey( $_key ) {
		$this -> key = $_key;
	}
	
	/**
	 * 获得加密key
	 */
	public function getKey() {
		return $this -> key;
	}
	
	/**
	 * 转换加密字符串
	 * @param string $_encryptStr 加密后的字符串
	 * @return 转换完的字符串
	 */
	protected function changeChar( $_encryptStr ) {
		return str_replace( array('1b','1c'), array('1b1d','1b1e'), $_encryptStr );
	}
}