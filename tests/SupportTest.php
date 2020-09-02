<?php

namespace Zaaz\Support\Tests;

use Zaaz\Support\Facades\Support;
use Zaaz\Support\ServiceProvider;
use Orchestra\Testbench\TestCase;

class SupportTest extends TestCase
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
