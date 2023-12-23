<?php

namespace NotificationChannels\Novu\Tests;

use NotificationChannels\Novu\NovuServiceProvider;
use PHPUnit\Framework\TestCase as BaseTestCase;

require_once __DIR__.'/helper/config.php';

abstract class TestCase extends BaseTestCase
{
    public static bool $nextConfigInvalid = false;

    public function getPackageProviders($app): array
    {
        return [
            NovuServiceProvider::class,
        ];
    }
}
