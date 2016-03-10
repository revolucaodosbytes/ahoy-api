<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 06/01/16
 * Time: 01:07
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Laravel\Lumen\Routing\Controller as BaseController;
use Telegram\Bot\Laravel\Facades\Telegram;


class ReportController extends BaseController{

	/**
	 * When a Ahoy! user gets into a blocked site that isn't on the whitelist,
	 *  the application will report back notifying us that a new site should be blocked.
	 *
	 * @TODO store this information in the database, and add some algorithm to data treatment
	 *
	 * @param Request $request
	 */
	public function autoReportBlockedSite( Request $request ) {

		$site = $request->input('site');
		$site = str_replace( 'www.', "", parse_url($site, PHP_URL_HOST) );

		if ( empty( $site ) )
			return new Response( ['error' => 'no site provided'], Response::HTTP_BAD_REQUEST);

		// Validate if site is already on the list
		foreach( SitesController::getAllSites() as $site_in_list ) {
			if( $site == $site_in_list )
				return new Response( ['error'=>'site already in the list'], Response::HTTP_ALREADY_REPORTED);
		}

		// @todo use this for rate limit
		// Test if site was reported in the last 5 minutes
		if( Cache::has('ip-reported-' . $site ) ) {
			$response = Response( ['error'=>'site reported in less than 5 minutes']);
			$response->setStatusCode('420', "Enhance Your Calm");

			return $response;
		}

		// Add a cache key for when a given host is reported. This key has 5 minutes duration
		$site_reported = Cache::remember( 'ip-reported-' . $site, 5, function() {
			return true;
		});

		// Get the IP details
		$user_ip = $request->ip();
		$ip_details = Cache::remember( 'ip-details-' . $user_ip, 3600, function() use($user_ip) {
			return $this->getIPDetails( $user_ip );
		});

		// Fallback
		if ( $ip_details == null ) {
			$ip_details = new \stdClass();
			$ip_details->org = "Unknown";
		}

		// Store the site host in cache, without www
		$site_id = $this->generateUniqueID();
		Cache::put( 'site-'.$site_id, $site, 3600 ); // Store it for a day

		// @todo instead of sending to telegram, store in a database
		Telegram::sendMessage(env("TELEGRAM_CHANNEL"), "Foi detectado um novo site bloqueado.
				URL: $site
				IP: $user_ip
				Provider: {$ip_details->org}", true);
		Telegram::sendMessage(env("TELEGRAM_CHANNEL"), "Para incluir este site, utiliza o comando /adicionar $site_id");

		return [ 'success' => 'true' ];

	}

	public function generateUniqueID() {
		$id = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);

		if( Cache::has( 'site-' . $id ) ) {
			return $this->generateUniqueID();
		}

		return $id;

	}

	private function getIPDetails($ip) {
		$json = file_get_contents("http://ipinfo.io/{$ip}");
		$details = json_decode($json);
		return $details;
	}


}