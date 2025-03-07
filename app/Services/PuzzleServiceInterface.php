<?php

namespace App\Services;

use App\Models\Puzzle;

interface PuzzleServiceInterface
{
    function validateWordInDictionary(string $word): bool;

    function createNewPuzzle(int $user_id): Puzzle;

    function submitWord(int $puzzle_id, string $word): array;

    function finishPuzzle(int $puzzle_id): array;
}
