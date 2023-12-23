<?php

namespace NotificationChannels\Novu;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Notifications\Notification;
use NotificationChannels\Novu\Exceptions\CouldNotTriggerEvent;

class NovuChannel
{
    /**
     * The Http Client.
     */
    protected Client $client;

    /**
     * Initialise a new Novu Channel instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     *
     * @throws CouldNotTriggerEvent
     * @throws GuzzleException
     */
    public function send($notifiable, Notification $notification): NovuChannel
    {
        if (! method_exists($notification, 'toNovuEvent')) {
            throw CouldNotTriggerEvent::undefinedMethod($notification);
        }

        if (! ($message = $notification->toNovuEvent($notifiable)) instanceof NovuMessage) {
            throw CouldNotTriggerEvent::invalidMessage($message);
        }

        if (! $endpoint = config('novu.api_url')) {
            throw CouldNotTriggerEvent::webhookUnavailable();
        }

        $dataArray = $message->toArray();
        if (! isset($dataArray['name'])) {
            throw CouldNotTriggerEvent::undefinedWorkflowIdentifier();
        }

        try {
            $this->client->request(
                'post',
                $endpoint.'/events/trigger',
                [
                    'json' => $dataArray,
                ]
            );
        } catch (ClientException $exception) {
            throw CouldNotTriggerEvent::clientError($exception);
        } catch (Exception $exception) {
            throw CouldNotTriggerEvent::unexpectedException($exception);
        }

        return $this;
    }
}
