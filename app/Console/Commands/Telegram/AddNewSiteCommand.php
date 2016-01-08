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
		$site = Site::where('url','=',$site)->first();

		if( $site != null ) {
			$this->replyWithMessage("O site $site já se encontra na base de dados.");
			return;
		}

		$site_obj = new Site();
		$site_obj->url = $site;
		$site->save();

		$this->replyWithMessage( $site . " foi adicionado à base de dados.", true);

		// Remove the cache
		Cache::forget('site-' . $arguments );

	}
}