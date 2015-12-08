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
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		define('HTTP_GATE','http://revolucaodosbytes.pt/gate.php'); // Gate for check HTTP,SOCKS proxy
		define('HTTPS_GATE','https://revolucaodosbytes.pt/gate.php'); // Gate for check HTTPS proxy
		define('CHECK_TIMEOUT',10); // Curl timeout request

		// Store the proxies on this array
		$proxy_array = [];

		// First proxy site
		$proxy_list_url = "http://www.google-proxy.net";
		$curl = curl_init($proxy_list_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		$output = curl_exec($curl);
		curl_close($curl);

		if( ! $output ) {
			$this->error( "Failed fetching $proxy_list_url");
			return;
		}

		$this->info( "\nFetched $proxy_list_url... Parsing HTML" );


		$dom = new \DOMDocument();
		libxml_use_internal_errors(true);
		$dom->loadHTML( $output );
		libxml_use_internal_errors(false);


		$xpath = new \DOMXPath( $dom );

		$entries = $xpath->query( '//*[@id="proxylisttable"]/tbody/tr' );

		foreach ($entries as $entry) {

			$host = $entry->childNodes->item(0)->textContent;
			$port = $entry->childNodes->item(1)->textContent;

			$this->info( " * FOUND " . $host . ":" . $port );
			$proxy_array[] = [ $host, $port ];

		}


		$this->info(" All done!! ");

		// Second proxy site
		$proxy_list_url = "http://proxy-list.org/english/index.php?p=";
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
				$this->info( " * FOUND " . $host . ":" . $port );
				$proxy_array[] = [ $host, $port ];

			}

		}

		/**
		 * NOW TEST THE PROXY AND ADD TO DATABASE
		 */

		$this->info("\n#############################################");
		$this->info("####       STARTED TESTING PROXIES       ####");
		$this->info("#############################################");
		$this->info("There are " . sizeof($proxy_array) . " proxy entries to test.\n");

		foreach( $proxy_array as $proxy ) {
			list( $host, $port ) = $proxy;

			if ( ! Proxy::whereRaw( 'host = ? and port = ?', array( $host, $port ) )->get()->isEmpty() ) {
				$this->info(" * Skipping " . $host . ":" . $port . "... Already on the database");
				continue;
			}


			$this->info( " * Testing " . $host . ":" . $port . "..." );

			$resultQuery = PHPProxyChecker::checkProxy( $host . ":" . $port );

			if( isset( $resultQuery['NOT_WORKING'] ) && $resultQuery['NOT_WORKING'] == 'Y' ) {
				$this->error( "\t * Proxy not responding... " );
				continue;
			}

			$this->info("\t * Success! ");
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
		}
		$this->info(" All done!! ");
	}
}