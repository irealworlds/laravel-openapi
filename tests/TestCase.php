<?php

namespace IrealWorlds\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use IrealWorlds\OpenApi\Providers\OpenApiServiceProvider;
use Mockery;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    use WithFaker;

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->app->setBasePath(__DIR__ . '/../');
        $this->setUpFaker();
    }

    /**
     * @inheritDoc
     */
    protected function getPackageProviders($app): array
    {
        return [OpenApiServiceProvider::class];
    }
}