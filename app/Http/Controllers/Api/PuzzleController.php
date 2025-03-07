<?php

use App\Http\Controllers\Controller;
use App\Services\PuzzleServiceInterface;

class PuzzleController extends Controller
{

    public function __construct(private readonly PuzzleServiceInterface $puzzleService)
    {
    }

    public function createPuzzle(CreatePuzzleRequest $request)
    {

    }

    public function submitWord(SubmitWordRequest $request)
    {

    }

    public function finishPuzzle(FinishPuzzleRequest $request)
    {

    }
}
