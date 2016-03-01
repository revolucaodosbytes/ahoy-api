<?php

namespace App\Console\Commands\Telegram;

use App\Site;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class AddNewSiteCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "adicionar";

	/**
	 * @var string Command Description
	 */
	protected $description = "Add a new site to Ahoy!";
	/**
	 * @inheritdoc
	 */
	public function handle($arguments)
	{

		if ( ! Cache::has( 'site-' . $arguments ) ) {
			$this->replyWithMessage("Não foi encontrado nenhum site com esse argumento.");
			return;
		}

		$site = Cache::get( 'site-' . $arguments );

		// Validate if the URL isn't on the database yet
		if( Site::where('url','=',$site)->first() != null ) {
			$this->replyWithMessage("O site $site já se encontra na base de dados.");
			return;
		}

		$site_obj = new Site();
		$site_obj->url = $site;
		$site_obj->save();

		$this->replyWithMessage( $site . " foi adicionado à base de dados.", true);

		// Notify the sitesbloqueados.pt about the new site
		$useragent = $_SERVER['HTTP_USER_AGENT'];

		$options = array(
			CURLOPT_RETURNTRANSFER => true,      // return web page
			CURLOPT_HEADER         => false,     // do not return headers
			CURLOPT_FOLLOWLOCATION => true,      // follow redirects
			CURLOPT_USERAGENT      => $useragent, // who am i
			CURLOPT_AUTOREFERER    => true,       // set referer on redirect
			CURLOPT_CONNECTTIMEOUT => 2,          // timeout on connect (in seconds)
			CURLOPT_TIMEOUT        => 2,          // timeout on response (in seconds)
			CURLOPT_MAXREDIRS      => 10,         // stop after 10 redirects
			CURLOPT_SSL_VERIFYPEER => false,     // SSL verification not required
			CURLOPT_SSL_VERIFYHOST => false,     // SSL verification not required
		);

		$ch = curl_init( 'https://sitesbloqueados.pt/wp-json/ahoy/refresh' );
		curl_setopt_array( $ch, $options );
		curl_exec( $ch );

		curl_close($ch);

		// Flush the PAC cache
		Cache::tags(['generate_pac'])->flush();

		// Remove the cache
		Cache::forget('site-' . $arguments );

	}
}