<?php

namespace App\Services;

interface DictionaryApiInterface
{
    function isValidWord(string $word): bool;
    public function getRandomWord(): string;

}
