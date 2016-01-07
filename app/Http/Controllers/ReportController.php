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

		if ( empty( $site ) )
			return new Response( ['error' => 'no site provided'], Response::HTTP_BAD_REQUEST);

		// @todo use this for rate limit


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

		// @todo instead of sending to telegram, store in a database
		Telegram::sendMessage(env("TELEGRAM_CHANNEL"), "Foi detectado um novo site bloqueado.
				URL: $site
				Provider: {$ip_details->org}", true);

		return [ 'success' => 'true' ];

	}

	private function getIPDetails($ip) {
		$json = file_get_contents("http://ipinfo.io/{$ip}");
		$details = json_decode($json);
		return $details;
	}


}