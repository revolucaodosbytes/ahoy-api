<?php

namespace App\Console\Commands\Telegram;

use App\Http\Controllers\SitesController;
use App\Proxy;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class StatsCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "stats";

	/**
	 * @var string Command Description
	 */
	protected $description = "Some stats about Ahoy";

	/**
	 * @inheritdoc
	 */
	public function handle($arguments)
	{
		// This will send a message using `sendMessage` method behind the scenes to
		// the user/chat id who triggered this command.
		// `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
		// handled when you replace `send<Method>` with `replyWith` and use all their parameters except chat_id.
		$this->replyWithMessage('O Ahoy! está a funcionar sem problemas, meu capitão!');

		$load = sys_getloadavg();
		$this->replyWithMessage("Os sistemas estão operacionais, com um load imediato de " . $load[0] .".");

		if( $load[0] > 1 ) {
			$this->replyWithMessage("Precisamos de ter algum cuidado com a sobrecarga, capitão!");
		}

		$uptime = shell_exec("cut -d. -f1 /proc/uptime");
		$days = floor($uptime/60/60/24);
		$hours = $uptime/60/60%24;
		$mins = $uptime/60%60;
		$secs = $uptime%60;

		$uptime_msg = "Estamos a navegar sem parar à ";

		if( $days > 0) {
			$uptime_msg .= $days . " dias e ";
		}

		$uptime_msg .= $hours . " horas.";

		$this->replyWithMessage($uptime_msg);

		$num_proxies = count(Proxy::all());
		$num_sites = count(SitesController::$sites_list);

		$this->replyWithMessage("Existem " . $num_proxies . " proxies e " . $num_sites . " sites bloqueados.");

		$num_ultima_hora = DB::table('stats_hosts')->where('created_at', '>', \Carbon\Carbon::now()->subHours(1));
		$num_ultimo_minuto = DB::table('stats_hosts')->where('created_at', '>', \Carbon\Carbon::now()->subMinutes(1));

		//$this->replyWithMessage($num_ultima_hora . " pessoas utilizaram o Ahoy! na última hora, e $num_ultimo_minuto pessoas utilizaram no último minuto.");

		// Trigger another command dynamically from within this command
		// When you want to chain multiple commands within one or process the request further.
		// The method supports second parameter arguments which you can optionally pass, By default
		// it'll pass the same arguments that are received for this command originally.
		//$this->triggerCommand('subscribe');
	}
}
