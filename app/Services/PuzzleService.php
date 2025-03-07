<?php

namespace App\Services;

use App\Models\Puzzle;
use Illuminate\Support\Facades\Log;

class PuzzleService implements PuzzleServiceInterface
{

    public function __construct(private readonly DictionaryApiInterface $dictionaryApi)
    {
    }

    function validateWordInDictionary(string $word): bool
    {
        return $this->dictionaryApi->isValidWord($word);
    }

    function canFormWord(string $source, string $word): bool {
        $sourceLetters = array_count_values(str_split($source));
        $wordLetters = array_count_values(str_split($word));

        foreach ($wordLetters as $letter => $count) {
            if (($sourceLetters[$letter] ?? 0) < $count) {
                return false;
            }
        }
        return true;
    }

    function createNewPuzzle(int $user_id): Puzzle
    {
        $validWord = $this->dictionaryApi->getRandomWord();
        $randomString = $this->generateRandomString(20);
        $puzzle = new Puzzle();
        Log::info('Puzzle word generated: {puzzle}.', ['puzzle' => $validWord.'.'.$randomString]);
        $puzzle->initial = $puzzle->remaining = str_shuffle($validWord.$randomString);
        $puzzle->save();
        return $puzzle;
    }

    function submitWord(int $puzzle_id, string $word): array
    {
        $puzzle = Puzzle::findOrFail($puzzle_id);
        if(!$this->canFormWord($puzzle->remaining, $word))
        {
            return ["message" => "The submitted word cannot be made from the puzzle string.", "success" => false];
        }
        if(!$this->validateWordInDictionary($word))
        {
            return ["message" => "The submitted word is not a valid dictionary word.", "success" => false];
        }
        $puzzle->score += strlen($word);
        foreach (str_split($word) as $letter) {
            $pos = strpos($puzzle->remaining, $letter);
            if ($pos !== false) {
                $puzzle->remaining = substr_replace($puzzle->remaining, '', $pos, 1);
            }
        }
        $puzzle->solutions .= $word." ";
        $puzzle->save();
        return ["message" => "The submitted word have been accepted.", "puzzle" => $puzzle, "success" => true];
    }

    function finishPuzzle(int $puzzle_id): array
    {
        $puzzle = Puzzle::findOrFail($puzzle_id);
        $possibilities = $this->getPermutations($puzzle->remaining);
        //TODO: Check and add to High Scores if qualified
        return ["puzzle" => $puzzle, "remaining_words" => $possibilities];
    }

    private function getPermutations(string $letters): array {
        if (strlen($letters) <= 1) {
            return [$letters];
        }

        $permutations = [];
        $letterArray = str_split($letters);

        foreach ($letterArray as $index => $char) {
            $remaining = substr($letters, 0, $index) . substr($letters, $index + 1);
            foreach ($this->getPermutations($remaining) as $perm) {
                $permutations[] = $char . $perm;
            }
        }

        return array_unique($permutations);
    }

    private function generateRandomString(int $length): string {
        $letters = 'abcdefghijklmnopqrstuvwxyz';
        return implode('', array_map(fn() => $letters[random_int(0, 25)], range(1, $length)));
    }

}
