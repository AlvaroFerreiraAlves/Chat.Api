<?php

namespace App\Http\Controllers;

use App\Conversations\FlowQuotasConversation;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class BotManController extends Controller
{
    private $questionsQuotas = [
        'R1' => [1, 1, 1, 1],
        'R2' => [1, 0, 1, 1],
        'R3' => [0, 1, 1, 1],
        'R4' => [0, 0, 1, 1],
        'R5' => [1, 1, 1, 0],
        'R6' => [1, 0, 1, 0],
        'R7' => [0, 1, 1, 0],
        'R8' => [0, 0, 1, 0],
    ];


    private $typeQuota;
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');

        $botman->listen();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */

    public function flowQuotas(BotMan $bot)
    {
        $extras = $bot->getMessage()->getExtras();
        $apiReply = $extras['apiReply'];
        $apiAction = $extras['apiAction'];
        $apiIntent = $extras['apiIntent'];

        $bot->startConversation(new FlowQuotasConversation($apiReply));
    }


    public function flowQuestionsQuotas(BotMan $bot, $reply)
    {
        session_start();
        $extras = $bot->getMessage()->getExtras();
        $apiReply = $extras['apiReply'];
        $apiAction = $extras['apiAction'];
        $apiIntent = $extras['apiIntent'];
        if($apiIntent == "ps.rac.ppi" && isset($_SESSION['reply'])){
            session_destroy();
        }
        if ($apiReply != null || $apiIntent != "ps.rac.ppi - renda" || $apiIntent != "ps.rac.ppi - renda") {
            $question = Question::create($apiReply)
                ->fallback('Unable to ask question')
                ->callbackId('ask_reason')
                ->addButtons([
                    Button::create('Sim')->value('sim'),
                    Button::create('Não')->value('não'),
                ]);
        }

        if ($reply == "sim") {
            $_SESSION['reply'][] = 1;
        } else if ($reply == "não") {
                $_SESSION['reply'][] = 0;
        }
        if ($apiIntent == "ps.rac.ppi - renda - yes" || $apiIntent == "ps.rac.ppi - renda - no"){

            foreach ($this->questionsQuotas as $key => $value) {
                if ($_SESSION['reply'] == $value) {
                    $this->typeQuota = $key;
                }
            }
            $bot->reply('O tipo de reserva de vaga mais apropriado para o seu perfi é ' . $this->typeQuota);

        }
        else {
            $bot->reply($question);
        }
    }

}
