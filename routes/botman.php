<?php

use App\Http\Controllers\ReservationOfVacanciesController;
use \App\Http\Controllers\TrainingPhraseController;
use BotMan\BotMan\Middleware\ApiAi;

$botman = resolve('botman');

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});

$dialogflow = ApiAi::create('6362d4faf2f9447b9c04ba78a1562083')->listenForAction();
$botman->middleware->received($dialogflow);
$botman->hears('ps.reservaDeVagas', ReservationOfVacanciesController::class . '@flowQuotas')->middleware($dialogflow);
$botman->hears('ps(.*)|{reply}', ReservationOfVacanciesController::class . '@flowQuestionsQuotas')->middleware($dialogflow);

$botman->fallback(TrainingPhraseController::class . '@storeUnknownPhrases');
