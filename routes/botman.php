<?php

use App\Http\Controllers\ReservationOfVacanciesController;
use \App\Http\Controllers\TrainingPhraseController;
use \App\Http\Controllers\FaqController;
use BotMan\BotMan\Middleware\ApiAi;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});

$dialogflow = ApiAi::create('770c6958e53d4b2f81e4d8a4df7fb716')->listenForAction();
$botman->middleware->received($dialogflow);
$botman->hears('ps.reservaDeVagas', ReservationOfVacanciesController::class . '@flowQuotas')->middleware($dialogflow);
$botman->hears('faq(.*)', FaqController::class . '@replyQuestionFaq')->middleware($dialogflow);
$botman->hears('ps(.*)|{reply}', ReservationOfVacanciesController::class . '@flowQuestionsQuotas')->middleware($dialogflow);


$botman->fallback(TrainingPhraseController::class . '@storeUnknownPhrases');
