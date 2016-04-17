<?php

namespace App\Console\Commands\Telegram;

use App\Site;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class IgnoreSiteCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "ignorar";

	/**
	 * @var string Command Description
	 */
	protected $description = "Add a site to the ignore list";
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
		if( Cache::has('site-ignore-' . $site ) ) {
			$this->replyWithMessage("O site $site já está na lista de ignorados.");
			return;
		}

		Cache::forever('site-ignore-' . $site, 'true' );

		$this->replyWithMessage( $site . " será agora ignorado.", true);


		// Remove the cache
		Cache::forget('site-' . $arguments );

	}
}