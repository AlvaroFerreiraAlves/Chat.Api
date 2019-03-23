<?php

namespace App\Conversations;


use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

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
    private $typeQuota;

    public function __construct($question)
    {
        $this->question = $question;
    }


    public function askReason()
    {

        $question = Question::create($this->question)
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Sim')->value('sim'),
                Button::create('não')->value('não'),
            ]);


        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                session_start();
                if ($answer->getValue() == "sim") {
                    $_SESSION['reply'][] = 1;
                } else {
                    if ($answer->getValue() == "não") {
                        $_SESSION['reply'][] = 0;
                    }
                }
                foreach ($this->questionsQuotas as $key => $value) {
                    if ($_SESSION['reply'] == $value) {
                        $this->typeQuota = $key;
                    }
                }
                $this->bot->reply('seu tipo de cota é ' . $this->typeQuota);

                session_destroy();
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
