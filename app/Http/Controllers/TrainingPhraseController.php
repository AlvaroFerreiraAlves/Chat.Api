<?php

namespace App\Http\Controllers;

use App\Models\TrainingPhrase;
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;

class TrainingPhraseController extends Controller
{
    const ALL = 3;

    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');

        $botman->listen();
    }

    public function index(TrainingPhrase $trainingPhrase)
    {
        $phrases = $trainingPhrase->orderBy('created_at', 'desc')->paginate(10);
        $status = self::ALL;
        return view('admin.training-phrases.table-list', compact('phrases','status'));
    }

    public function searchPhrases(Request $request, TrainingPhrase $trainingPhrase)
    {
        $dataForm = $request->except('_token');
        $phrases = $trainingPhrase->search($dataForm);
        $status = $request['status'];
        return view('admin.training-phrases.table-list', compact('phrases', 'dataForm','status'));
    }


    public function storeUnknownPhrases(BotMan $bot)
    {
        $trainingPhrase = new TrainingPhrase();
        $userReply = $bot->getMessage()->getText();
        if (!$trainingPhrase->verifyPhrasesExists($userReply)) {
            TrainingPhrase::create(['phrases' => $userReply]);
        }
        $bot->reply('nao entendi mano');
    }


    public function prepareFile($phrases)
    {
        $trainingPhrase = new TrainingPhrase();
        $sentences = $trainingPhrase->formatData($phrases);
        $trainingPhrase->writeDataToFile($sentences);
    }

    public function downlaodFilePhrases(TrainingPhrase $trainingPhrase, $status)
    {
        if($status == self::ALL){
            $phrases = $trainingPhrase->all();
            $this->prepareFile($phrases);
            $trainingPhrase->newQuery()->update(['status' => 1]);;

        }else{
            $phrases = $trainingPhrase->newQuery()->where('status','=', $status)->get();
            $this->prepareFile($phrases);
            $trainingPhrase->newQuery()->where('status','=', $status)->update(['status' => 1]);
        }

        return response()->download(public_path() . '/upload/txt/frases.txt');
    }

}
