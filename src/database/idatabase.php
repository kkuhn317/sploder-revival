<?php

/**
 * Handles abstractions over querying a database
 */
interface IDatabase
{
  /**
   * Executes a $query with $parameters and returns the results
   * @param $query
   * @param $parameters
   * @return array
   */
    public function query(string $query, array $parameters = []): array;

  /**
   * Executes a $query with $parameters and returns the first result
   * @param $query
   * @param $parameters
   * @return array
   */
    public function queryFirst(string $query, array $parameters = []): mixed;

  /**
   * Executes a $query with $parameters and returns the first $column result
   * @param $query
   * @param $parameters
   * @param $column
   * @return array
   */
    public function queryFirstColumn(string $query, int $column = 0, array $parameters = []): mixed;

  /**
   * Executes a $query with $parameters and returns if the query succeeded or not
   *
   * @param $query
   * @param $parameters
   * @return bool
   */
    public function execute(string $query, array $parameters = []): bool;
}
