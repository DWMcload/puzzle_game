<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePuzzleRequest;
use App\Http\Requests\FinishPuzzleRequest;
use App\Http\Requests\SubmitWordRequest;
use App\Services\PuzzleServiceInterface;

class PuzzleController extends Controller
{

    public function __construct(private readonly PuzzleServiceInterface $puzzleService)
    {
    }

    public function createPuzzle(CreatePuzzleRequest $request)
    {
        $word = $this->puzzleService->createNewPuzzle($request->user_id);
    }

    public function submitWord(SubmitWordRequest $request)
    {

    }

    public function finishPuzzle(FinishPuzzleRequest $request)
    {

    }
}
