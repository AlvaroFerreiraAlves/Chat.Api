<?php

use App\Http\Controllers\BotManController;
use BotMan\BotMan\Middleware\ApiAi;


$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});


$botman->hears('Start conversation', BotManController::class . '@startConversation');

$dialogflow = ApiAi::create('770c6958e53d4b2f81e4d8a4df7fb716')->listenForAction();
$botman->middleware->received($dialogflow);
$botman->hears('ps.reservaDeVagas', BotManController::class . '@flowQuotas')->middleware($dialogflow);
$botman->hears('ps(.*)|{reply}', BotManController::class . '@flowQuestionsQuotas')->middleware($dialogflow);

$botman->fallback(function($bot) {
    $bot->reply('Sorry, I did not understand these commands. Here is a list of commands I understand: ...');
});
