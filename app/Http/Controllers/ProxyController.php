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
		[
			'host' => 'proxy1.ahoy.pro',
			'port' => 3128,
			'id'   => 1,
		],
	//	[
	//		'host' => 'proxy2.ahoy.pro',
	//		'port' => 3128,
	//		'id'   => 2,
	//	],
	];

	public function getProxyList( Request $req ) {
		return $this->proxy_list;
	}

	public function getProxy( Request $req ) {

		return $this->proxy_list[ array_rand( $this->proxy_list ) ];

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
			$pac .= "   if (host == \"$site\" || host == \"www.$site\") { \n";
			$pac .= "       return 'PROXY $proxy_addr';\n";
			$pac .= "   }\n";
		}
		$pac .= "   return 'DIRECT';\n";
		$pac .= "}";

		Cache::tags(['generate_pac'])->put($proxy_addr, $pac, 3600); // Store it for a day

		return new Response($pac, 200, [
				'Content-Type' => 'application/x-ns-proxy-autoconfig',
		]);

	}

}
