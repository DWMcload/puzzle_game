<?php

namespace Tests\Feature;

use App\Models\Puzzle;
use App\Services\DictionaryApiInterface;
use App\Services\PuzzleService;
use App\Services\PuzzleServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class PuzzleTest extends TestCase
{
    use RefreshDatabase;

    private PuzzleServiceInterface $puzzleService;
    private DictionaryApiInterface $dictionaryApi;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockDictionaryApi = $this->createMock(DictionaryApiInterface::class);
        $this->puzzleService = new PuzzleService($this->mockDictionaryApi);
    }

    public function testValidWordScoresCorrectly()
    {
        $this->mockDictionaryApi->expects($this->once())
        ->method('getRandomWord')
            ->willReturn('fox');
        $this->puzzleService->createNewPuzzle(1);

        $this->mockDictionaryApi->expects($this->once())
        ->method('isValidWord')
            ->with('fox')
            ->willReturn(true);
        $response = $this->puzzleService->submitWord(1, "fox");
        $this->assertEquals(3, $response["puzzle"]->score);
    }

    public function testInvalidWordDoesNotScore()
    {
        $this->mockDictionaryApi->expects($this->once())
            ->method('getRandomWord')
            ->willReturn('fox');
        $puzzle = $this->puzzleService->createNewPuzzle(1);
        //this makes a word which is definitely cannot be made
        $word = '';
        while(strlen($word) == 0) {
            $word = implode("", array_values(array_diff(range('a', 'z'), array_unique(str_split(strtolower($puzzle->remaining))))));
        }
        $response = $this->puzzleService->submitWord(1, $word);
        $this->assertEquals(false, $response["success"]);
    }

    public function testLettersAreRemovedAfterSubmission()
    {
        $this->instance(
            PuzzleServiceInterface::class,
            Mockery::mock(PuzzleService::class, function (MockInterface $mock) {
                $puzzle = new Puzzle();
                $puzzle->initial = $puzzle->remaining = 'ranfdomloetterxs';
                $mock->shouldReceive('createNewPuzzle')->once();
                $mock->shouldReceive('submitWord')->once()->andReturn($puzzle);
            })
        );

        $this->postJson('/api/new_puzzle', ["user_id" =>1]);
        $response = $this->postJson('/api/submit_word', ["puzzle_id" => 1, "word" => "fox"]);
        //todo: fix this
        $response
            ->assertStatus(201)
            ->assertJsonPath('puzzle.remaining', 'randomletters');
    }

    public function testGameEndsAndShowsRemainingWords()
    {
        $this->markTestIncomplete();
        $this->puzzleService->submitWord(1, "fox");
        $result = $this->puzzleService->finishPuzzle(1);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('score', $result);
        $this->assertArrayHasKey('remainingWords', $result);
    }

    public function testLeaderboardStoresTopScores()
    {
        $this->markTestIncomplete();
        $this->puzzleService->submitWord(1, "fox");
        $this->puzzleService->submitWord(1, "dog");

        //$leaderboard = $this->puzzleService->getLeaderboard();
        $this->assertIsArray($leaderboard);
        $this->assertLessThanOrEqual(10, count($leaderboard));
    }

    public function testDuplicateWordsAreNotAllowedInLeaderboard()
    {
        $this->markTestIncomplete();
        $this->puzzleService->submitWord(1, "fox");
        $this->puzzleService->submitWord(1, "fox"); // Should not be added again

        //$leaderboard = $this->puzzleService->getLeaderboard();
        $wordCounts = array_count_values(array_column($leaderboard, 'word'));

        $this->assertLessThanOrEqual(1, $wordCounts['fox'] ?? 0);
    }
}
