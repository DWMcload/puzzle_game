<?php

namespace App\Services;

class DictionaryApi implements DictionaryApiInterface
{
    public function isValidWord(string $word): bool {
        $url = "https://api.dictionaryapi.dev/api/v2/entries/en/$word";
        $response = @file_get_contents($url);
        return $response !== false && strpos($response, 'word') !== false;
    }

    public function getRandomWord(): string {
        $url = "https://random-word-api.vercel.app/api?words=1";
        $response = json_decode(@file_get_contents($url));
        return $response[0];
    }
}
