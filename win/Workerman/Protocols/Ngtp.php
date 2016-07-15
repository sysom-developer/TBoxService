<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Workerman\Protocols;

use Workerman\Connection\TcpConnection;

/**
 * my Protocol.
 */
class Ngtp
{
    /**
     * Check the integrity of the package.
     *
     * @param string        $buffer
     * @param TcpConnection $connection
     * @return int
     */
    public static function input($recv_buffer, TcpConnection $connection)
    {
        if(strlen($recv_buffer)<43)
        {
            return 0;
        }
        $unpack_data = unpack('Ntotal_length',substr($recv_buffer,39));
        return $unpack_data['total_length'];
    }

    /**
     * Encode.
     *
     * @param string $buffer
     * @return string
     */
    public static function encode($xml_string)
    {
        // 包体+包头的长度
        $total_length = strlen($xml_string)+4;
        // 长度部分凑足10字节，位数不够补0
        $total_length_str = str_pad($total_length,4, '0', STR_PAD_LEFT);
        // 返回数据
        return $total_length_str . $xml_string;
    }

    /**
     * Decode.
     *
     * @param string $buffer
     * @return string
     */
    public static function decode($buffer)
    {
        /*$body_json_str = substr($buffer, 4);*/
        // json解码
        return $buffer;
    }
}
