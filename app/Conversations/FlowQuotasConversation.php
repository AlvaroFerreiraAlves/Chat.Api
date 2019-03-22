<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class FlowQuotasConversation extends Conversation
{
    private $typeQuotas = [
        'R1' => 'R1 candidatos autodeclarados pretos, pardos e indígenas - PPI -, com deficiência, que
                    concluíram o ensino médio integralmente em escola pública, com renda igual ou inferior a 1,5 salário
                    mínimo per capita, em proporção igual ao percentual dessa população no último censo do IBGE
                    (73,59%);',
        'R2' => 'R2 candidatos autodeclarados pretos, pardos e indígenas - PPI - que concluíram o ensino
                    médio integralmente em escola pública, com renda igual ou inferior a 1,5 salário mínimo per capita,
                    em proporção igual ao percentual dessa população no último censo do IBGE (73,59%);',
        'R3' => 'R3 candidatos que concluíram o ensino médio integralmente em escola pública, com
                    deficiência, com renda igual ou inferior a 1,5 salário mínimo per capita, em proporção igual ao
                    percentual dessa população no último censo do IBGE (26,41%);',
        'R4' => 'R4 candidatos que concluíram o ensino médio integralmente em escola pública, com renda
                    igual ou inferior a 1,5 salário mínimo per capita, em proporção igual ao percentual dessa população
                    no último censo do IBGE (26,41%);',
        'R5' => 'R5 candidatos autodeclarados pretos, pardos e indígenas - PPI -, com deficiência, que
                    concluíram o ensino médio integralmente em escola pública, com renda superior a 1,5 salário mínimo
                    per capita, em proporção igual ao percentual dessa população no último censo do IBGE (73,59%);',
        'R6' => 'R6 candidatos autodeclarados pretos, pardos e indígenas - PPI - que concluíram o ensino
                    médio integralmente em escola pública, com renda superior a 1,5 salário mínimo per capita, em
                    proporção igual ao percentual dessa população no último censo do IBGE (73,59%);',
        'R7' => 'R7 candidatos que concluíram o ensino médio integralmente em escola pública, com
                    deficiência, com renda superior a 1,5 salário mínimo per capita, em proporção igual ao percentual
                    dessa população no último censo do IBGE (26,41%);',
        'R8' => 'R8 candidatos que concluíram o ensino médio integralmente em escola pública, com renda
                    superior a 1,5 salário mínimo per capita, em proporção igual ao percentual dessa população no
                    último censo do IBGE (26,41%).',

    ];

    private $buttons = [];
    private $question;

    public function __construct($question)
    {

        $this->question = $question;
    }

    public function askReason()
    {
        foreach ($this->typeQuotas as $key => $value) {
            $this->buttons[] = Button::create($key)->value($key);
        }

        $question = Question::create($this->question)
            ->fallback('Unable to ask question')
            ->callbackId('ask_reason')
            ->addButtons($this->buttons);


        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {

                foreach ($this->typeQuotas as $key => $value) {
                    if ($answer->getValue() === $key) {
                        $this->bot->reply($value);
                    }
                }
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