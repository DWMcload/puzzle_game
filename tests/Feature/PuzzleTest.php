<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;

class PuzzleTest extends TestCase
{
    private $game;

    protected function setUp(): void
    {
        $this->game = new WordPuzzleGame("dgeftoikbvxuaa");
    }

    public function testValidWordScoresCorrectly()
    {
        $score = $this->game->submitWord("fox");
        $this->assertEquals(3, $score);
    }

    public function testInvalidWordDoesNotScore()
    {
        $score = $this->game->submitWord("xyz"); // Assume "xyz" is not a valid word
        $this->assertEquals(0, $score);
    }

    public function testLettersAreRemovedAfterSubmission()
    {
        $this->game->submitWord("fox");
        $remainingLetters = $this->game->getRemainingLetters();
        $this->assertEquals("dgetikbvuaa", $remainingLetters);
    }

    public function testCannotReuseLettersBeyondAvailableCount()
    {
        $this->game->submitWord("fox");
        $score = $this->game->submitWord("fox"); // Should not allow reusing removed letters
        $this->assertEquals(0, $score);
    }

    public function testGameEndsAndShowsRemainingWords()
    {
        $this->game->submitWord("fox");
        $result = $this->game->endGame();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('score', $result);
        $this->assertArrayHasKey('remainingWords', $result);
    }

    public function testLeaderboardStoresTopScores()
    {
        $this->game->submitWord("fox");
        $this->game->submitWord("dog");

        $leaderboard = $this->game->getLeaderboard();
        $this->assertIsArray($leaderboard);
        $this->assertLessThanOrEqual(10, count($leaderboard));
    }

    public function testDuplicateWordsAreNotAllowedInLeaderboard()
    {
        $this->game->submitWord("fox");
        $this->game->submitWord("fox"); // Should not be added again

        $leaderboard = $this->game->getLeaderboard();
        $wordCounts = array_count_values(array_column($leaderboard, 'word'));

        $this->assertLessThanOrEqual(1, $wordCounts['fox'] ?? 0);
    }
}
