<?php

namespace Smm229\Aliyunoss\Http\Controllers;

use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class OssFormController extends Controller
{
	public function getOssParam()
	{
		//获取相关参数
		$config = config('filesystems.disks.oss');
		$id = $config['access_id'];
		$key = $config['access_key'];
		$host = $config['bucket'] . '.' .$config['endpoint'];
		$ssl = $config['ssl'] ? 'https' : 'http';
		$host = $ssl . '://' . $host;
		$cdn_url = $host;

		//用户上传时指定的前缀
		$dir = isset($config['oss_dir']) ? $config['oss_dir'] . date('Ymd'): 'public/images/' . date('Ymd');

		$now = time();
		//该pilicy超时时间
		$expire = 300;
		$end = $now + $expire;

		$expiration = $this->gmt_iso8601($end);


        //最大文件大小.用户可以自己设置
        $condition = array(0 =>'content-length-range', 1 => 0, 2 => 1048576000);
        $conditions[] = $condition;

        // 表示用户上传的数据，必须是以$dir开始，不然上传会失败，这一步不是必须项，只是为了安全起见，防止用户通过policy上传到别人的目录。
        $start = array(0 => 'starts-with', 1 => '$key', 2 => $dir);
        $conditions[] = $start;

        $arr = array('expiration' => $expiration, 'conditions' => $conditions);
        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $string_to_sign = $base64_policy;
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $key, true));

        $response = array();
        $response['accessid'] = $id;
        $response['host'] = $host;
        $response['cdn_url'] = $cdn_url;
        $response['policy'] = $base64_policy;
        $response['signature'] = $signature;
        $response['expire'] = $end;
        $response['dir'] = $dir;  // 这个参数是设置用户上传文件时指定的前缀。
        return $response;
	}


	private function gmt_iso8601($time)
	{
		$dtStr = date( "c", $time );
        $mydatetime = new \DateTime( $dtStr );
        $expiration = $mydatetime->format( \DateTime::ISO8601 );
        $pos = strpos( $expiration, '+' );
        $expiration = substr( $expiration, 0, $pos );
        return $expiration."Z";
	}
}
