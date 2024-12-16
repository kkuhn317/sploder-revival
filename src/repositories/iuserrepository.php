<?php

/**
 * Handles database interations with users
 */
interface IUserRepository
{
  /**
   * Search for similar users by userName
   *
   * @param $userName
   * @param $limit
   * @return the array of search values
   */
    function search(string $userName, int $limit = 180);
}
