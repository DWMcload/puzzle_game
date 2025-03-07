<?php

namespace App\Services;

class PuzzleService implements PuzzleServiceInterface
{

    public function __construct(private readonly DictionaryApiInterface $dictionaryApi)
    {
    }

    function validateWordInDictionary(string $word): bool
    {
        // TODO: Implement validateWordInDictionary() method.
        return true;
    }

    function createNewPuzzle(int $user_id): string
    {
        // TODO: Implement createNewPuzzle() method.
        $puzzle = $this->generateRandomString(15);
        return '';
    }

    function submitWord(int $puzzle_id, string $word): bool
    {
        // TODO: Implement submitWord() method.
        return true;
    }

    function finishPuzzle(int $puzzle_id): void
    {
        // TODO: Implement finishPuzzle() method.
    }

    private function generateRandomString(int $length): string {
        $letters = 'abcdefghijklmnopqrstuvwxyz';
        return implode('', array_map(fn() => $letters[random_int(0, 25)], range(1, $length)));
    }

}
