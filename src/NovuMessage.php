<?php

namespace NotificationChannels\Novu;

use Illuminate\Contracts\Support\Arrayable;

class NovuMessage implements Arrayable
{
    /**
     * The configured message payload.
     */
    protected array $payload = [];

    public function to(array $to): self
    {
        $this->payload['to'] = $to;

        return $this;
    }

    public function toSubscriber(string $subscriberID): self
    {
        $this->payload['to']['subscriberId'] = $subscriberID;

        return $this;
    }

    public function variables(array $variables): self
    {
        $this->payload['payload'] = $variables;

        return $this;
    }

    public function addVariable(string $key, $value): self
    {
        $this->payload['payload'][$key] = $value;

        return $this;
    }

    public function workflowID(string $workflow_trigger_id): self
    {
        $this->payload['name'] = $workflow_trigger_id;

        return $this;
    }

    /**
     * Serialize the message to an array.
     */
    public function toArray(): array
    {
        return $this->castNestedArrayables($this->payload);
    }

    /**
     * Recursively attempt to cast arrayable values within an array.
     *
     * @param  mixed  $value
     * @return mixed
     */
    private function castNestedArrayables(array $value): array
    {
        foreach ($value as $key => $val) {
            if (is_array($val) || $val instanceof Arrayable) {
                $value[$key] = $this->castNestedArrayables((array) $val);
            } else {
                $value[$key] = $val;
            }
        }

        return $value;
    }

    /**
     * Return a new Novu Message instance. Optionally, provide the workflow trigger ID.
     */
    public static function create(?string $workflow_trigger_id = null): NovuMessage
    {
        $message = new static;

        if ($workflow_trigger_id) {
            $message->workflowID($workflow_trigger_id);
        }

        return $message;
    }
}
