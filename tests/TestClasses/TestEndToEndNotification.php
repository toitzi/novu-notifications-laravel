<?php

namespace NotificationChannels\Novu\Tests\TestClasses;

use Illuminate\Notifications\Notification;
use NotificationChannels\Novu\NovuChannel;
use NotificationChannels\Novu\NovuMessage;

class TestEndToEndNotification extends Notification
{
    public function via($notifiable): array
    {
        return [NovuChannel::class];
    }

    public function toNovuEvent($notifiable)
    {
        return NovuMessage::create()
            ->to([
                'subscriberId' => '0',
                'phone' => '+1234567890',
            ])
            ->workflowID('workflow_trigger_1')
            ->toSubscriber('1234567890')
            ->variables([
                'foo' => 'bar',
            ])
            ->addVariable('hello', 'world');
    }
}
