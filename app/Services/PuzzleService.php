<?php

namespace App\Services;

class PuzzleService implements PuzzleServiceInterface
{

    function validateWordInDictionary(string $word): bool
    {
        // TODO: Implement validateWordInDictionary() method.
        return true;
    }

    function createNewPuzzle(int $user_id): string
    {
        // TODO: Implement createNewPuzzle() method.
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
}
