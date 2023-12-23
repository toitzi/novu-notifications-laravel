<?php

if (! function_exists('config')) {
    function config(string $key): ?string
    {
        return \NotificationChannels\Novu\Tests\TestCase::$config[$key] ?? null;
    }
}
