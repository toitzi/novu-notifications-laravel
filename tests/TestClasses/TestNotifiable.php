<?php

namespace NotificationChannels\Novu\Tests\TestClasses;

use Illuminate\Notifications\Notifiable;

class TestNotifiable
{
    use Notifiable;

    public function __construct()
    {
    }
}
