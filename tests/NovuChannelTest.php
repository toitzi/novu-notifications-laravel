<?php

/** @noinspection PhpUnhandledExceptionInspection */

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Notifications\Notification;
use NotificationChannels\Novu\Exceptions\CouldNotTriggerEvent;
use NotificationChannels\Novu\NovuChannel;
use NotificationChannels\Novu\Tests\TestCase;
use NotificationChannels\Novu\Tests\TestClasses\TestNotifiable;
use NotificationChannels\Novu\Tests\TestClasses\TestNotification;

uses(TestCase::class);

it('rejects sending when to novu event method undefined', function () {
    $notification = $this->createMock(Notification::class);

    $this->expectException(CouldNotTriggerEvent::class);
    $this->expectExceptionMessage('Notification of class: '.get_class($notification).' must define a `toNovuEvent()` method in order to send via the Novu Channel');

    (new NovuChannel($this->createMock(Client::class)))->send('foo', $notification);
});

it('rejects sending when non novu message supplied', function () {
    $notification = $this->createMock(TestNotification::class);
    $notification->expects($this->once())
        ->method('toNovuEvent')
        ->with('notifiable')
        ->willReturn('This value is invalid, as it is not an instance of Novu Message');

    $this->expectException(CouldNotTriggerEvent::class);
    $this->expectExceptionMessage("Expected a message instance of type NotificationChannels\Novu\NovuMessage - Actually received string");

    (new NovuChannel($this->createMock(Client::class)))->send('notifiable', $notification);
});

it('rejects sending when no webhook is available', function () {
    TestCase::$config = [
        'novu.api_key' => '1234567890',
    ];

    $notifiable = new TestNotifiable();
    $notification = new TestNotification();
    $notification->setWorkflowId('1234567890');

    $this->expectException(CouldNotTriggerEvent::class);
    $this->expectExceptionMessage('No webhook URL was available when sending the Novu notification.');

    (new NovuChannel($this->createMock(Client::class)))->send($notifiable, $notification);
});

it('rejects sending when no api key is available', function () {
    TestCase::$config = [
        'novu.api_url' => 'https://example.com',
    ];

    $notifiable = new TestNotifiable();
    $notification = new TestNotification();
    $notification->setWorkflowId('1234567890');

    $this->expectException(CouldNotTriggerEvent::class);
    $this->expectExceptionMessage('No api key was available when sending the Novu notification.');

    (new NovuChannel($this->createMock(Client::class)))->send($notifiable, $notification);
});

it('handles missing workflow id', function () {
    $notifiable = new TestNotifiable();
    $notification = new TestNotification();

    $this->expectException(CouldNotTriggerEvent::class);
    $this->expectExceptionMessage('No workflow identifier was available when sending the Novu notification.');

    (new NovuChannel($this->createMock(Client::class)))->send($notifiable, $notification);
});

it('handles client exceptions', function () {
    $notifiable = new TestNotifiable();

    $notification = new TestNotification();
    $notification->setWorkflowId('1234567890');

    $exception = new ClientException(
        'Example 400 level HTTP exception',
        $this->createMock(Request::class),
        tap($this->createMock(Response::class), function ($mock) {
            $mock->method('getStatusCode')->willReturn(400);
        }),
    );

    $client = $this->createMock(Client::class);
    $client->expects($this->once())
        ->method('request')
        ->withAnyParameters()
        ->willThrowException($exception);

    $this->expectException(CouldNotTriggerEvent::class);
    $this->expectExceptionMessage('Failed to send Novu message, encountered client error: `400 - Example 400 level HTTP exception`');

    (new NovuChannel($client))->send($notifiable, $notification);
});

it('handles unexpected exceptions', function () {
    $notifiable = new TestNotifiable();

    $notification = new TestNotification();
    $notification->setWorkflowId('1234567890');

    $exception = new \Exception('Example unexpected exception');

    $client = $this->createMock(Client::class);
    $client->expects($this->once())
        ->method('request')
        ->withAnyParameters()
        ->willThrowException($exception);

    $this->expectException(CouldNotTriggerEvent::class);
    $this->expectExceptionMessage('Failed to send Novu message, unexpected exception encountered: `Example unexpected exception`');

    (new NovuChannel($client))->send($notifiable, $notification);
});

it('sets workflow id from creation', function () {
    $notifiable = new TestNotifiable();
    $notification = new TestNotification();
    $notification->setInitialWorkflowId('1234567890');

    $message = $notification->toNovuEvent($notifiable);
    expect($message->toArray())
        ->toHaveKey('name')
        ->and($message->toArray()['name'])
        ->toBe('1234567890');
});

it('returns the channel after send', function () {
    $notifiable = new TestNotifiable();
    $notification = new TestNotification();
    $notification->setInitialWorkflowId('1234567890');

    $channel = (new NovuChannel($this->createMock(Client::class)))->send($notifiable, $notification);
    expect($channel)->toBeInstanceOf(NovuChannel::class);
});
