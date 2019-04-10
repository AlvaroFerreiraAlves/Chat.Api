<?php

namespace App\Models;

use BotMan\BotMan\BotMan;

class DialogFLow
{

    /**
     * @var string
     */
    private $apiReply;
    /**
     * @var string
     */
    private $apiAction;
    /**
     * @var string
     */
    private $apiIntent;


    public function __construct(BotMan $bot)
    {
        $extras = $bot->getMessage()->getExtras();
        $this->setApiReply($extras['apiReply']);
        $this->setApiAction($extras['apiAction']);
        $this->setApiIntent($extras['apiIntent']);
    }

    /**
     * @return string
     */
    public function getApiReply()
    {
        return $this->apiReply;
    }

    /**
     * @param string $apiReply
     */
    public function setApiReply(string $apiReply): void
    {
        $this->apiReply = $apiReply;
    }

    /**
     * @return string
     */
    public function getApiAction()
    {
        return $this->apiAction;
    }

    /**
     * @param string $apiAction
     */
    public function setApiAction(string $apiAction): void
    {
        $this->apiAction = $apiAction;
    }

    /**
     * @return string
     */
    public function getApiIntent()
    {
        return $this->apiIntent;
    }

    /**
     * @param string $apiIntent
     */
    public function setApiIntent(string $apiIntent): void
    {
        $this->apiIntent = $apiIntent;
    }
}
