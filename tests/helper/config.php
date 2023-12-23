<?php

if (! function_exists('config')) {
    function config(string $key): ?string
    {
        if (\NotificationChannels\Novu\Tests\TestCase::$nextConfigInvalid) {
            \NotificationChannels\Novu\Tests\TestCase::$nextConfigInvalid = false;

            return null;
        }

        return 'fake_config';
    }
}
