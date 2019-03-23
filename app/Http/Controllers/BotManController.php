<?php

namespace App\Http\Controllers;

use App\Conversations\FLowQuestionsQuotasConversation;
use App\Conversations\FlowQuotasConversation;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class BotManController extends Controller
{

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
        if($apiIntent == "ps.rac.ppi"){
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
        if ($apiIntent == "ps.rac.ppi - renda" || $apiIntent == "ps.rac.ppi - renda"){
            $bot->startConversation(new FLowQuestionsQuotasConversation($apiReply));
        }
        else {
            $bot->reply($question);
        }
    }

}
