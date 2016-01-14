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

	public function getProxyList( Request $req ) {
		return Proxy::all();
	}

	public function getProxy( Request $req ) {

		if( /*rand(0,100) <= 30*/ true ) {
			$proxy['host'] = 'proxy1.ahoy.pro';
			$proxy['port'] = 3128;
			$proxy['id'] = 'proxy1';

			return $proxy;
		}

		$proxy = Proxy::query()->orderBy('speed', 'asc')->take(40)->get()->shuffle()->first();

		return $proxy;
	}

}