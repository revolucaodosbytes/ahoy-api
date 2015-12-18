<?php

namespace App\Console\Commands;

use App\Libs\Diff;
use App\Libs\PHPProxyChecker;
use App\Proxy;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Telegram\Bot\Laravel\Facades\Telegram;

class CheckProxySecurity extends Command
{

	protected $name = "proxy:security";
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'proxy:security';

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

		$proxies = Proxy::all();
		$this->info( "Checking existing proxies...\n" );
		Telegram::sendMessage(env("TELEGRAM_CHANNEL"),
				"A iniciar os teste de segurança para " . count($proxies) . " proxies.");

		foreach( $proxies as $proxy ) {
			$this->info( "\n # Checking security on " . $proxy->host . ":" . $proxy->port . "..." );

			//Check for proxy security
			$this->checkProxySecurity( $proxy );

		}

		Telegram::sendMessage(env("TELEGRAM_CHANNEL"),
				"Testes de segurança finalizados! De " . count($proxies) . " proxies, ficaram " . count( Proxy::all() ) . ".");

		$this->info("Done.");
	}

	public function checkProxySecurity( $proxy ) {

		$proxy_address = $proxy->host . ":" . $proxy->port;

		$timeout = 5;

		$urls = [
				"http://toppt.net/",
				"http://www.revolucaodosbytes.pt/",
			];

		foreach( $urls as $url ) {

			// initialize curls
			$curl_proxy   = curl_init();
			$curl_unproxy = curl_init();

			curl_setopt( $curl_proxy, CURLOPT_URL, $url );
			curl_setopt( $curl_proxy, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $curl_proxy, CURLOPT_CONNECTTIMEOUT, $timeout );
			curl_setopt( $curl_proxy, CURLOPT_TIMEOUT, $timeout );


			//proxy details
			curl_setopt( $curl_proxy, CURLOPT_PROXY, $proxy_address );
			curl_setopt( $curl_proxy, CURLOPT_PROXYTYPE, CURLPROXY_HTTP );

			curl_setopt( $curl_unproxy, CURLOPT_URL, $url );
			curl_setopt( $curl_unproxy, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $curl_unproxy, CURLOPT_CONNECTTIMEOUT, $timeout );

			if ( $curl_proxy === false || $curl_unproxy === false ) {
				die( 'Failed to create curl object' );
			}

			// get the data with proxy
			$this->info( "\t * Getting $url with proxy" );
			$data_proxy = curl_exec( $curl_proxy );
			$this->info( "\t * Getting $url unproxy" );
			$data_unproxy = curl_exec( $curl_unproxy );

			curl_close( $curl_proxy );
			curl_close( $curl_unproxy );

			$diff = Diff::compare( $data_unproxy, $data_proxy );

			if ( Diff::hasDifferences( $diff ) ) {
				$this->error( " # Proxy not secure. Below diff file for $url" );
				echo Diff::toString( $diff, "\n", true );
				// Remove the proxy
				$proxy->delete();
				return;
			}

		}

		$this->info(" # Proxy $proxy_address is secure and usable!");
	}
}