<?php

/**
 * Handles database interations with games
 */
interface IGameRepository
{
  /**
   * Inserts a view for a specified game
   *
   * @param $gameId the game to track the view
   * @param $ipAddress the ip address assosciated with the user playing the game
   * @param $userId the logged in user who is playing the game, if applicable
   */
    public function trackView(int $gameId, string $ipAddress, int|null $userId): void;


  /**
   * Returns creator userid of a given game
   */
    public function getUserId(int $gameId): string;


  /**
   * Retrieves game data for playing a game
   */
    public function getGameData(int $gameId): GameData;
}

class GameData
{
    public readonly string $author;
    public readonly string $difficulty;
    public readonly float $avgScore;
    public function __construct(string $author, string $difficulty, float $avgScore)
    {
        $this->author = $author;
        $this->difficulty = $difficulty;
        $this->avgScore = $avgScore;
    }
}
