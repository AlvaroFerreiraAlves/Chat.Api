<?php

namespace App\Http\Controllers;

use App\Conversations\FlowQuotasConversation;
use App\Models\DialogFLow;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class ReservationOfVacanciesController extends Controller
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

    public function handle()
    {
        $botman = app('botman');

        $botman->listen();
    }

    public function flowQuotas(BotMan $bot)
    {
        $dialogFlow = new DialogFLow($bot);
        $bot->startConversation(new FlowQuotasConversation($dialogFlow->getApiReply()));
    }

    public function flowQuestionsQuotas(BotMan $bot, $reply)
    {
        session_start();
        $dialogFlow = new DialogFLow($bot);

        if ($dialogFlow->getApiIntent() == "ps.rac.ppi" && isset($_SESSION['reply'])) {
            session_destroy();
        }

        if ($dialogFlow->getApiReply() != null || $dialogFlow->getApiIntent() != "ps.rac.ppi - renda" || $dialogFlow->getApiIntent() != "ps.rac.ppi - renda") {
            $question = $this->questions($dialogFlow->getApiReply());
        }

        $this->storageResponseUser($reply);

        if ($dialogFlow->getApiIntent() == "ps.rac.ppi - renda - yes" || $dialogFlow->getApiIntent() == "ps.rac.ppi - renda - no") {

            $this->verifyUserAnswers($bot);

        } else {
            $bot->reply($question);
        }
    }

    public function storageResponseUser($reply)
    {
        if ($reply == "sim") {
            $_SESSION['reply'][] = 1;
        } else if ($reply == "não") {
                $_SESSION['reply'][] = 0;
        }
    }



    function questions($apiReply){
        $question = Question::create($apiReply)
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons([
                Button::create('Sim')->value('sim'),
                Button::create('Não')->value('não'),
            ]);

        return $question;
    }

    public function verifyUserAnswers($bot)
    {
        foreach ($this->questionsQuotas as $key => $value) {
            $_SESSION['reply'] == $value ? $this->typeQuota = $key : null;
        }

        $this->typeQuota != null ?
            $bot->reply(
                'O tipo de reserva de vaga mais apropriado para o seu perfi é ' . $this->typeQuota . '.'
            )
            :
            $bot->reply(
                "Seu perfil não se enquadra em nenhum tipo de reserva de vaga."
            );
    }
}
