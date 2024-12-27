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
   *
   * @param $gameId
   * @return game data associated with the game
   */
    public function getGameData(int $gameId): GameData;

    /**
     * Retrieves tags for a given game
     *
     * @param $gameId
     * @return tags associated with the game
     */
    public function getGameTags(int $perPage, int $offset): GameTags;

    /**
     * Retrieves random games from the database
     * @return random games
     */
    public function getRandomGames(): array;

    /**
     * Retrieves the contest winners from the database
     * @return contest winners
     */
    public function getContestWinners(int $contestId): array;
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

class GameTags
{
    public readonly array $tags;
    public readonly float $total;

    public function __construct(array $tags, int $total)
    {
        $this->tags = $tags;
        $this->total = $total;
    }
}
