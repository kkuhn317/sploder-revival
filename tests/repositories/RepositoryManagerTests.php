<?php

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . "/../../src/repositories/repositorymanager.php");

class RepositoryManagerTests extends TestCase
{
    private IDatabase $database;

    protected function setUp(): void
    {
        $this->database = $this->createMock(IDatabase::class);
    }

    public function testStaticInitialization(): void
    {
        $value = RepositoryManager::get();
        $this->assertNotNull($value);
    }
}
