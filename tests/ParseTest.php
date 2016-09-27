<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParseTest extends TestCase
{
    use DatabaseMigrations;

    public function testSeed()
    {
        Artisan::call('migrate:refresh');
        $this->seed('DatabaseSeeder');
    }
}
