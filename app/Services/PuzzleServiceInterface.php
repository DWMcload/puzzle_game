<?php

namespace App\Services;

interface PuzzleServiceInterface
{
    function validateWordInDictionary(string $word): bool;

    function createNewPuzzle(int $user_id): string;

    function submitWord(int $puzzle_id, string $word): bool;

    function finishPuzzle(int $puzzle_id): void;
}
