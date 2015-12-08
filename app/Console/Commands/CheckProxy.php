<?php

namespace App\Console\Commands;

use App\Libs\PHPProxyChecker;
use App\Proxy;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Telegram\Bot\Laravel\Facades\Telegram;

class CheckProxy extends Command
{

	protected $name = "proxy:check";
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'proxy:check';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Check current proxies';

	/**
	 * The drip e-mail service.
	 *
	 * @var DripEmailer
	 */
	protected $drip;

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		define('HTTP_GATE','http://revolucaodosbytes.pt/gate.php'); // Gate for check HTTP,SOCKS proxy
		define('HTTPS_GATE','https://revolucaodosbytes.pt/gate.php'); // Gate for check HTTPS proxy
		define('CHECK_TIMEOUT',10); // Curl timeout request

		$proxies = Proxy::all();
		$this->info( "Checking existing proxies...\n" );
		foreach( $proxies as $proxy ) {
			$this->info( " * Checking " . $proxy->host . ":" . $proxy->port . "..." );
			$check = PHPProxyChecker::checkProxy( $proxy->host .":".$proxy->port );
			if( isset( $check['NOT_WORKING'] ) && $check['NOT_WORKING'] == 'Y' ) {
				$this->error( "Proxy not responding... Removing." );
				$proxy->delete();
				continue;
			}

			$this->table( [  "Type", "Type Name", "Query Time", "SSL" ], [
				[ $check['TYPE'], $check['TYPE_NAME'], $check['QUERY_TIME'], $check['SUPPORT_SSL'] ]
			] );

			// Save the old results
			$proxy->speed = $check['QUERY_TIME'];
			$proxy->working = true;
			$proxy->latest_report = json_encode( $check );

			$proxy->save();

			$this->info("Done. \n");
		}

	}
}