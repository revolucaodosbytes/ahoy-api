<?php

namespace App\Http\Controllers;

use App\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Laravel\Lumen\Routing\Controller as BaseController;
use GuzzleHttp;

class SitesController extends BaseController {


	public static function getAllSites() {

		if( Cache::has('site-list') ) {
			return Cache::get('site-list');
		}

		$site_list = Site::all(['url']);
		$site_array = [];

		// Make sure we're only returning the hostname
		foreach( $site_list as $site ) {
			$site_array[] = $site->url;
		}

		Cache::put('site-list', $site_array, 15);

		return $site_array;

	}

	/**
	 * This function was deprecated in favor or getAllSites.
	 *
	 * @param Request $req
	 *
	 * @deprecated
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getSiteList( Request $req ) {

		return self::getAllSites();
	}

	public function getHostsList( Request $req ) {

		$hosts = [];

		foreach( self::getAllSites() as $site ) {

			$host = Cache::remember('host-' . $site, 6 * 60 + rand(1,30), function() use ($site) {
				return gethostbyname( $site );
			} );

			// If the gethostbyname fails
			if ( $host == $site || $host == '127.0.0.1' )
				continue;

			$hosts[] = $host;
		}

		return $hosts;

	}

	/**
	 * Tests if a given site is present in the site list
	 *
	 * @param $site the hostname to test
	 *
	 * @return bool true if the site is in the list
	 */
	public static function siteExists( $site ) {

		foreach( self::getAllSites() as $site_in_list ) {
			if ( strpos( $site, $site_in_list ) !== false ) {
				return true;
			}
		}

		return false;
	}

}
