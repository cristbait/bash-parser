<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParseTest extends TestCase
{
    use DatabaseMigrations;

    public function testSeed()
    {
        App\Helpers\ParseHelper::seed();
    }

    public function testAdding()
    {
       App\Helpers\ParseHelper::addNew();
    }
}
