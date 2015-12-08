<?php

namespace App\Console\Commands\Telegram;

use Telegram\Bot\Actions;
use Telegram\Bot\Commands\Command;

class FunCommand extends Command
{
	/**
	 * @var string Command Name
	 */
	protected $name = "piada";

	/**
	 * @var string Command Description
	 */
	protected $description = "Vamos lá ouvir uma piada";

	protected $piadas = [
			"Você sabe a diferença entre o pirata e o corinthians?\nÉ que o pirata tem só uma perna de pau, já o corinthians, tem onze!!!!",
			"Em tempos remotos havia um grande Capitão de um navio muito poderoso,o mais poderoso da sua época.. \nUm certa vez esse capitão teve de entrar numa grande batalha,e disse ao seu imediato: \n\n-Chiivar!!! \n-Sim capitão?! \n-Pegue-me minha camisa vermelha! \n-Camisa vermelha? \n-Sim Chiivar. \n-Capitão?Por que o senhor quer sua camisa vermelha? \n-Para que os outros não possam ver meu sangue.Caso venha a ser atingido. \n-Sim capitão.... \n\nE dali em diante toda batalha o Capitão dava essa ordem ao seu imediato. \n\nMas uma vez enquanto navegavam, o capitão avistou um navio muito maior e mais poderoso q o seu,um navio q insitava terror ao homem mais corajoso q existe.Vendo que não havia como escapar de um confronto direto,outra vez chamou seu imediato: \n\n-Chiivar!!! \n-Sim capitão.Pode deixar q já vou trazer sua camisa vermelha.... \n-Não meu caro Chiivar.Traga-me minha calça marrom. \n",
			"Um marinheiro e um pirata se encontraram em um bar e começaram a contar suas aventuras pelos sete mares. O marinheiro, notando que o pirata tinha perna de pau, um gancho e um tapa-olho, pergunta:\n\n- Por que você tem essa perna de pau?\n\nO pirata explica:\n\n- Estávamos em uma tormenta. Uma onda enorme veio por cima do navio e me atirou ao mar. Caí no meio de um monte de tubarões. Lutei contra eles e consegui me agarrar a escada do navio. Na subida, um tubarão conseguiu arrancar minha perna.\n\n- Uau! Que história! Mas e o gancho? Foi culpa do tubarão também?\n\n- Não, o gancho foi outra história. Estávamos abordando um barco inimigo e, enquanto lutávamos, fui cercado por quatro piratas inimigos. Consegui matar três, o quarto me cortou a mão.\n\n- Caramba! Incrível! E o tapa-olho?\n\n- Caiu um cocô de pomba no meu olho.\n\n- E você perdeu o olho só por causa do cocô de pomba?\n\n- Era o meu primeiro dia com o gancho...",
			"Para se atualizar ao mundo moderno, os piratas resolvem se comunicar via fones.\nO chefe dos piratas (General Jorjão), tinha um papagaio que guardava no ombro, o papagaio que passou de geraçôes em geraçôes de piratas.\n\nNuma certa noite de tempestades, o general Jorjão fala (via fone) com um de seus piratas:\n\n-Meidei... A tempestade está muito forte, não sei se vou aguentar... Meidei!!\n\nConforme o tempo passava, mais forte ficava a tempestade, e o general...:\n\n-Meidei... A tempestade irá acabar com nossas vidas, salve-se quem puder... Meidei, meidei\n\nE depois de várias tempestades e de vários \"Meideis\", o papagaio diz:\n\n-Porra, Jorjão! Já sei que você está peidou... não precisa repetir!!\n",
			"Porque é que o Pirata só assiste a metade do filme?\nPorque tem uma pala no olho!",
			"Um velho marujo e um velho pirata se encontraram em uma taverna e começaram a contar suas aventuras. \nEm um dado momento o marujo perguntou ao pirata o que havia acontecido para ele usar um tapa-olho. \nO pirata respondeu \n- coco de gaivota \n-Coco de gaivota??!?! \nLevantando a mão com um gancho o pirata concluiu: \n-Foi logo no dia seguinte após ter colocado isso aqui...",
	];

	/**
	 * @inheritdoc
	 */
	public function handle($arguments)
	{
		// This will send a message using `sendMessage` method behind the scenes to
		// the user/chat id who triggered this command.
		// `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
		// handled when you replace `send<Method>` with `replyWith` and use all their parameters except chat_id.
		$user = $this->getUpdate()->get('from')->getFirstName();
		$this->replyWithMessage('Ahoy, ' . $user);

	}
}