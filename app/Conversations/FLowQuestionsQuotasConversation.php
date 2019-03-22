<?php

namespace App\Conversations;


use App\Http\Controllers\BotManController;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Middleware\ApiAi;

class FLowQuestionsQuotasConversation extends Conversation
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


    private $question;

    public function __construct($question)
    {
        $this->question = $question;
    }


    public function askReason()
    {


        $question = Question::create($this->question)
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([]);


        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->bot->reply('oi');
            }
        });


    }

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askReason();
    }
}