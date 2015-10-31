<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 30/10/15
 * Time: 12:41
 */

namespace App\Http\Controllers;

use App\Proxy;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use GuzzleHttp;

class ProxyController extends BaseController {

	// @todo grab from the database
	private $proxy_list = [
		"41.87.199.215:80",
		"165.139.149.169:3128",
		"212.83.157.246:3128",
		"176.31.99.80:2222",
		"176.31.254.105:3128",
		"46.51.175.89:3129",
		"31.210.10.34:8080",
		"36.234.88.45:8888",
		"36.234.95.114:8888",
		"166.78.165.36:3128",
		"220.255.3.191:8080",
		"178.141.152.245:8080",
		"220.255.3.171:8080",
		"178.33.129.16:9090",
		"183.111.169.206:3128",
		"41.74.76.123:3128",
		"117.239.192.68:8080",
		"151.80.88.44:3128",
		"181.143.156.46:3128",
		"103.225.66.138:3128",
	];

	protected function checkProxies() {
		$mc = curl_multi_init ();
		for ($thread_no = 0; $thread_no<count($proxies); $thread_no++)
		{
			$c [$thread_no] = curl_init ();
			curl_setopt ($c [$thread_no], CURLOPT_URL, "http://google.com");
			curl_setopt ($c [$thread_no], CURLOPT_HEADER, 0);
			curl_setopt ($c [$thread_no], CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($c [$thread_no], CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt ($c [$thread_no], CURLOPT_TIMEOUT, 10);
			curl_setopt ($c [$thread_no], CURLOPT_PROXY, trim ($proxies [$thread_no]));
			curl_setopt ($c [$thread_no], CURLOPT_PROXYTYPE, 0);
			curl_multi_add_handle ($mc, $c [$thread_no]);
		}

		do {
			while (($execrun = curl_multi_exec ($mc, $running)) == CURLM_CALL_MULTI_PERFORM);
			if ($execrun != CURLM_OK) break;
			while ($done = curl_multi_info_read ($mc))
			{
				$info = curl_getinfo ($done ['handle']);
				if ($info ['http_code'] == 301) {
					echo trim ($proxies [array_search ($done['handle'], $c)])."\r\n";
				}
				curl_multi_remove_handle ($mc, $done ['handle']);
			}
		} while ($running);
		curl_multi_close ($mc);
	}

	public function getProxyList( Request $req ) {
		return Proxy::all();
	}

	public function getProxy( Request $req ) {

		$proxy = Proxy::query()->orderBy('speed', 'asc')->take(10)->get()->shuffle()->first()g;

		return $proxy;
	}

}