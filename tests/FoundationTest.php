<?php

namespace Zaazy\Support\Tests;

use Orchestra\Testbench\TestCase;
use Zaazy\Support\Facades\Support;
use Zaazy\Support\ServiceProvider;

class FoundationTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'support' => Support::class,
        ];
    }

    public function testExample()
    {
        $this->assertEquals(1, 1);
    }
}
