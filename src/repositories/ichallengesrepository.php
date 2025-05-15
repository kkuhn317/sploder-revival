<?php

/**
 * Handles database interactions with challenges
 */
interface IChallengesRepository
{
    /**
     * Retrieves challenge data for a given game
     */
    public function getChallengeInfo(int $gameId): array;

    /**
     * Verify if 's' is correct
     */
    public function verifyIfSIsCorrect(int $gameId, int $userId): bool;
}
