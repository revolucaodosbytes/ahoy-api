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

		$this->replyWithMessage( $site . " foi adicionado à base de dados.", true);


	}
}