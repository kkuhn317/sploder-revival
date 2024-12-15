<?php

/**
 * Handles database interations with graphics
 */
interface IGraphicsRepository
{
  /**
   * Replaces all tags for a specified graphic
   *
   * @param $graphicId the graphic
   * @param $tags The array of tags (strings) to replace
   * @return void
   */
    function replaceTags(int $graphicId, array $tags): void;

  /**
   * Retrieves the UserId for the specified graphic
   *
   * @param $graphicId
   * @return the user id
   */
    function getUserId(int $graphicId): string;

  /**
   * Retrieves all of the tags for a specified graphic
   *
   * @param $graphicId
   * @return the array of tags
   */
    public function getTags($graphicId): array;
}
