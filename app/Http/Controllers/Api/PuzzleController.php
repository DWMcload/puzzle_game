<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePuzzleRequest;
use App\Http\Requests\FinishPuzzleRequest;
use App\Http\Requests\SubmitWordRequest;
use App\Models\Puzzle;
use App\Services\PuzzleServiceInterface;

class PuzzleController extends Controller
{

    public function __construct(private readonly PuzzleServiceInterface $puzzleService)
    {
    }

    public function createPuzzle(CreatePuzzleRequest $request)
    {
        $puzzle = $this->puzzleService->createNewPuzzle($request->user_id);
        return response()->json(["id" => $puzzle->id, "puzzle" => $puzzle->initial]);
    }

    public function submitWord(SubmitWordRequest $request)
    {
        $response = $this->puzzleService->submitWord($request->puzzle_id, $request->word);

        if($response["success"]) {
            if($response["puzzle"]->remaining === '') {
                $this->puzzleService->finishPuzzle($request->puzzle_id);
                return response()->json(
                    [
                        "message" => "The submitted word have been accepted and no letters remain in the puzzle.",
                        "final_score" => $response["puzzle"]->score
                    ]);
            }
            return response()->json(
                [
                    "message" => $response["message"],
                    "puzzle" => $response["puzzle"]->remaining,
                    "current_score" => $response["puzzle"]->score
                ]);
        }
        else {
            return response()->json(["message" => $response["message"]], 422);
        }

    }

    public function finishPuzzle(FinishPuzzleRequest $request)
    {
        $final = $this->puzzleService->finishPuzzle($request->puzzle_id);
        return response()->json(
            [
                "message" => "The puzzle ended by the request of the user.",
                "remaining_words" => $final["remaining_words"],
                "final_score" => $final["puzzle"]->score
            ]);
    }
}
