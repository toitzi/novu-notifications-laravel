<?php

namespace NotificationChannels\Novu\Tests;

use NotificationChannels\Novu\NovuServiceProvider;
use PHPUnit\Framework\TestCase as BaseTestCase;

require_once __DIR__.'/helper/config.php';

abstract class TestCase extends BaseTestCase
{
    public static array $config = [];

    protected function setUp(): void
    {
        parent::setUp();
        static::$config = ([
            'novu.api_key' => 'key',
            'novu.api_url' => 'https://example.com',
        ]);
    }

    public function getPackageProviders($app): array
    {
        return [
            NovuServiceProvider::class,
        ];
    }
}
