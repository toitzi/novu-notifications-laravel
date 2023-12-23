<?php

namespace NotificationChannels\Novu\Tests\TestClasses;

use Illuminate\Notifications\Notification;
use NotificationChannels\Novu\NovuChannel;
use NotificationChannels\Novu\NovuMessage;

class TestNotification extends Notification
{
    private ?string $workflowId = null;

    private ?string $initialWorkflowId = null;

    public function setWorkflowId(string $workflowId): self
    {
        $this->workflowId = $workflowId;

        return $this;
    }

    public function setInitialWorkflowId(string $initialWorkflowId): self
    {
        $this->initialWorkflowId = $initialWorkflowId;

        return $this;
    }

    public function via($notifiable): array
    {
        return [NovuChannel::class];
    }

    public function toNovuEvent($notifiable)
    {
        $message = NovuMessage::create($this->initialWorkflowId);
        if ($this->workflowId) {
            $message->workflowID($this->workflowId);
        }

        return $message;
    }
}
