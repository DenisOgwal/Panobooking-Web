<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    /**
     * Runs before any test trait (e.g. RefreshDatabase, which wipes the database),
     * so a misconfigured environment can never touch a real database.
     */
    public function createApplication()
    {
        $app = parent::createApplication();

        $connection = $app['config']->get('database.default');
        $database = (string) $app['config']->get("database.connections.{$connection}.database");
        if (! str_ends_with($database, '_test')) {
            throw new RuntimeException("Refusing to run tests against database [{$database}]; expected a *_test database.");
        }

        return $app;
    }
}
