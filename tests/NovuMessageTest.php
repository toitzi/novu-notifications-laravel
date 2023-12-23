<?php

use NotificationChannels\Novu\NovuMessage;

it('adds variables', function () {
    $message = NovuMessage::create()->variables(['var1' => 'value']);

    expect($message->toArray())
        ->toHaveKey('payload')
        ->and($message->toArray()['payload'])
        ->toHaveKey('var1')
        ->and($message->toArray()['payload']['var1'])
        ->toBe('value');
});

it('adds a single var', function () {
    $message = NovuMessage::create()->addVariable('var1', 'value');

    expect($message->toArray())
        ->toHaveKey('payload')
        ->and($message->toArray()['payload'])
        ->toHaveKey('var1')
        ->and($message->toArray()['payload']['var1'])
        ->toBe('value');
});

it('sets the subscriber id', function () {
    $message = NovuMessage::create()->toSubscriber('1234');

    expect($message->toArray())
        ->toHaveKey('to')
        ->and($message->toArray()['to'])
        ->toHaveKey('subscriberId')
        ->and($message->toArray()['to']['subscriberId'])
        ->toBe('1234');
});

it('sets the workflow id', function () {
    $message = NovuMessage::create()->workflowID('1234');

    expect($message->toArray())
        ->toHaveKey('name')
        ->and($message->toArray()['name'])
        ->toBe('1234');
});

it('set recipient data', function () {
    $message = NovuMessage::create()->to(['subscriberId' => '1234', 'phone' => '1234567890']);

    expect($message->toArray())
        ->toHaveKey('to')
        ->and($message->toArray()['to'])
        ->toHaveKey('subscriberId')
        ->and($message->toArray()['to']['subscriberId'])
        ->toBe('1234')
        ->and($message->toArray()['to'])
        ->toHaveKey('phone')
        ->and($message->toArray()['to']['phone'])
        ->toBe('1234567890');
});
