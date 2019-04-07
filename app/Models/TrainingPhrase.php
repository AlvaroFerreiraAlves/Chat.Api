<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class TrainingPhrase extends Model
{
    protected $table = "training_phrases";

    protected $fillable = ['phrases'];

    public function formatData($phrases): string
    {
        $data = [];

        foreach ($phrases as $phrase) {
            $data[] = $phrase->phrases;
        }
        $phrases = json_encode($data, JSON_UNESCAPED_UNICODE);
        $phrases = str_replace(',', PHP_EOL, $phrases);
        $phrases = str_replace(str_split('[]"'), "", $phrases);

        return $phrases;
    }

    public function writeDataToFile($phrases)
    {
        $file = 'frases.txt';
        $destinationPath = public_path() . "/upload/txt/";
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        File::put($destinationPath . $file, $phrases);
    }


    public function search(array $dataForm)
    {
        return $this->newQuery()->where('status', '=', $dataForm['status'])->paginate(10);
    }

    public function verifyPhrasesExists(string $phrase): bool
    {
        return $this->newQuery()->where('phrases', '=', $phrase)->exists();
    }
}
