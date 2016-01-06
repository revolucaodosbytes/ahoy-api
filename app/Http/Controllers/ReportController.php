<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 06/01/16
 * Time: 01:07
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

		// @todo use this for rate limit
		$user_ip = $request->ip();

		Telegram::sendMessage(env("TELEGRAM_CHANNEL"), "[$ip] Novo site encontrado! $site ");

		return [ 'success' => 'true' ];

	}


}