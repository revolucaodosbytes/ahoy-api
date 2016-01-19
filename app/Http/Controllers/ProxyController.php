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
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
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

	/**
	 * Generates and outputs a new PAC based on a given IP.
	 *
	 * This is going to be used on devices that don't allow real time PAC generation.
	 *
	 * @param Request $req
	 *
	 * @return string the generated PAC
	 */
	public function generatePAC( Request $req ) {

		$proxy_addr = Input::get('proxy_addr', $this->getProxy($req) );

		if( is_array($proxy_addr) )
			$proxy_addr = $proxy_addr['host'] . ":" . $proxy_addr['port'];

		// check for cache
		if ( Cache::tags(['generate_pac'])->has($proxy_addr) ) {
			return new Response( Cache::tags(['generate_pac'])->get($proxy_addr), 200, [
					'Content-Type' => 'application/x-ns-proxy-autoconfig',
			]);
		}

		$pac = "function FindProxyForURL(url, host) {\n";

		foreach( SitesController::getAllSites() as $site ) {
			$pac .= "   if (host == \"*.$site\" || host == www.\"*.$site\") { \n";
			$pac .= "       return 'PROXY $proxy_addr';\n";
			$pac .= "   }\n";
		}
		$pac .= "   if (host == \"omeuip.com\" || host == \"www.omeuip.com\" ) { \n";
		$pac .= "       return 'PROXY $proxy_addr';\n";
		$pac .= "   }\n";
		$pac .= "   return 'DIRECT';\n";
		$pac .= "}";

		Cache::tags(['generate_pac'])->put($proxy_addr, $pac, 3600); // Store it for a day

		return new Response($pac, 200, [
				'Content-Type' => 'application/x-ns-proxy-autoconfig',
		]);

	}

}
