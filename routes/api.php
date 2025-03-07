<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/top10', [HighScoreController::class, 'list']);
Route::post('/new_puzzle', [PuzzleController::class, 'createPuzzle']);
Route::post('/submit_word', [PuzzleController::class, 'submitWord']);
Route::post('/finish', [PuzzleController::class, 'finishPuzzle']);
