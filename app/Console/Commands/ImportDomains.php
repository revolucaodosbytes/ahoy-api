<?php

namespace App\Console\Commands;

use App\Http\Controllers\SitesController;
use App\Libs\PHPProxyChecker;
use App\Proxy;
use App\Site;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Telegram\Bot\Laravel\Facades\Telegram;

class ImportDomains extends Command
{

	protected $name = "domains:import";
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'domains:import {csv} {--dry}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import domains from a CSV file';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{

		// Retrieve the path
		$csv_path = $this->argument('csv');
		$file = file( $csv_path );

		$dry_run = $this->option('dry');

		$domains = array_map('str_getcsv', $file);

		if( $dry_run )
			$this->warn('Running on dry-mode');


		$success = 0;
		foreach( $domains as $domain ) {
			// Set the time
			$date = strtotime( $domain[1] );
			if( $date === false ) {
				$date = time();
			}
			$date = date( 'Y-m-d H:i:s', $date );

			// Set the domain
			$domain = strtolower( $domain[0] );

			// Domain already exists, skip.
			if( Site::where('url','=',$domain)->first() != null ) {
				$this->error( " * " . $domain . " already exists. Skipping.");
				continue;
			}

			$new_site = new Site();
			$new_site->url = $domain;
			$new_site->setCreatedAt($date);

			if( $dry_run ) {
				$this->info( " * Should add " . $domain . " at " . $date . ".");
			} else {
				$this->info( " * Added add " . $domain . " at " . $date . ".");
				$new_site->save();
			}

			$success++;
		}

		$this->info("\nDone! Added $success new sites");

	}

}