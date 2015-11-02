<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;
use GuzzleHttp;

class StatsController extends BaseController {


	public function hostname( Request $req, $hostname ) {
		if( ! $this->is_valid_domain_name( $hostname ) )
			return ['invalid hostname'];

		return DB::table('stats_hosts')->insertGetId(
			[
				'hostname' => $hostname,
				'created_at' => DB::raw('NOW()'),
				'updated_at' => DB::raw('NOW()'),
			]
		);
	}

	private function is_valid_domain_name($domain) {
		if(filter_var(gethostbyname($domain), FILTER_VALIDATE_IP)) {
			return true;
		}
	}
}