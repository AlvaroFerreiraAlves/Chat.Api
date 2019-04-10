<?php

namespace App\Http\Controllers;

use App\Models\DialogFLow;
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;

class FaqController extends Controller
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
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */

    public function replyQuestionFaq(BotMan $bot)
    {
        $dialogFlow = new DialogFLow($bot);
        $replies = explode("|", $dialogFlow->getApiReply());

        foreach ($replies as $reply){
            $bot->reply($reply);
        }
    }
}
