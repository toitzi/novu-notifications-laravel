<?php

namespace NotificationChannels\Novu\Tests;

use NotificationChannels\Novu\Tests\TestClasses\TestEndToEndNotification;

uses(TestCase::class);

beforeEach(function () {
    $this->notification = new TestEndToEndNotification;
});

it('generates complete correct array structure', function () {

    expect($this->notification->toNovuEvent('')->toArray())
        ->toBeArray()
        ->toEqual([
            'name' => 'workflow_trigger_1',
            'to' => [
                'subscriberId' => '1234567890',
                'phone' => '+1234567890',
            ],
            'payload' => [
                'foo' => 'bar',
                'hello' => 'world',
            ],
        ]);
});
