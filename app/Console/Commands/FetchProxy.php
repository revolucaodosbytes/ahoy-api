<?php

namespace App\Console\Commands;

use App\Libs\PHPProxyChecker;
use App\Proxy;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FetchProxy extends Command
{

	protected $name = "proxy:fetch";
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'proxy:fetch';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Fetch newer proxies';

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
		$proxy_list_url = "http://proxy-list.org/english/index.php?p=";

		define('HTTP_GATE','http://grupoaweso.me/gate.php'); // Gate for check HTTP,SOCKS proxy
		define('HTTPS_GATE','https://grupoaweso.me/gate.php'); // Gate for check HTTPS proxy
		define('CHECK_TIMEOUT',10); // Curl timeout request

		for( $page = 1; $page <= 10; $page++ ) {

			$curl = curl_init($proxy_list_url . $page );
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			$output = curl_exec($curl);
			curl_close($curl);

			if( ! $output ) {
				$this->error( "Failed fetching page $page");
				continue;
			}


			$this->info( "\nFetched page $page... Parsing HTML" );


			$dom = new \DOMDocument();
			libxml_use_internal_errors(true);
			$dom->loadHTML( $output );
			libxml_use_internal_errors(false);


			$xpath = new \DOMXPath( $dom );

			$entries = $xpath->query( '//*[@id="proxy-table"]//li[@class="proxy"]' );

			foreach ($entries as $entry) {

				if( strpos( $entry->textContent, ":" ) === false )
					continue;

				list( $host, $port ) = explode( ":", $entry->textContent );


				if ( ! Proxy::whereRaw( 'host = ? and port = ?', array( $host, $port ) )->get()->isEmpty() ) {
					$this->info(" Skipping " . $entry->textContent . "... Already on the database");
				}


				$this->info( "==== FOUND " . $entry->textContent . " ====\nStart testing..." );

				$resultQuery = PHPProxyChecker::checkProxy( $entry->textContent );

				if( isset( $resultQuery['NOT_WORKING'] ) && $resultQuery['NOT_WORKING'] == 'Y' ) {
					$this->error( "Proxy not responding... " );
					continue;
				}

				$this->info("Proxy Responding! ");
				$this->table( [  "Type", "Type Name", "Query Time", "SSL" ], [
					[ $resultQuery['TYPE'], $resultQuery['TYPE_NAME'], $resultQuery['QUERY_TIME'], $resultQuery['SUPPORT_SSL'] ]
				 ] );

				$proxy = new Proxy();
				$proxy->host = $host;
				$proxy->port = $port;
				$proxy->speed = $resultQuery['QUERY_TIME'];
				$proxy->working = true;
				$proxy->latest_report = json_encode( $resultQuery );

				$proxy->save();
				$this->info( "Done!\n" );

			}

			$this->info(" All done!! ");
		}
	}
}