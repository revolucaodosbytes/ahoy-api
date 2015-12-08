<?php

namespace App\Console\Commands\Telegram;

use Illuminate\Support\Facades\DB;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class TopCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "top";

	/**
	 * @var string Command Description
	 */
	protected $description = "The list of the most visited sites";

	/**
	 * @inheritdoc
	 */
	public function handle($arguments)
	{
		// This will send a message using `sendMessage` method behind the scenes to
		// the user/chat id who triggered this command.
		// `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
		// handled when you replace `send<Method>` with `replyWith` and use all their parameters except chat_id.
		$this->replyWithMessage('Os sites bloqueados mais visitados são os seguintes: ');

		// This will update the chat status to typing...
		$this->replyWithChatAction(Actions::TYPING);

		$top = DB::select('select hostname,count(hostname) as hits from stats_hosts group by hostname order by count(hostname) DESC LIMIT 0,10');
		// Build the list
		$response = '';
		$posicao = 1;
		foreach ($top as $site) {
			$response .= $posicao++ . "º Lugar - " . $site->hostname . " com " . $site->hits . " acessos.". PHP_EOL;
		}

		// Reply with the commands list
		$this->replyWithMessage($response, true );

		// Trigger another command dynamically from within this command
		// When you want to chain multiple commands within one or process the request further.
		// The method supports second parameter arguments which you can optionally pass, By default
		// it'll pass the same arguments that are received for this command originally.
		//$this->triggerCommand('subscribe');
	}
}