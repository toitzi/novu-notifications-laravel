<?php

namespace NotificationChannels\Novu\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;
use NotificationChannels\Novu\NovuMessage;

class CouldNotTriggerEvent extends Exception
{
    /**
     * Thrown if a notification instance does not implement a toNovuEvent() method, but is
     * attempting to be delivered via the Novu notification channel.
     */
    public static function undefinedMethod(object $notification): CouldNotTriggerEvent
    {
        return new CouldNotTriggerEvent(
            sprintf(
                'Notification of class: %s must define a `toNovuEvent()` method in order to send via the Novu Channel',
                get_class($notification)
            )
        );
    }

    /**
     * Thrown if a notification message does not contain a workflow identifier.
     */
    public static function undefinedWorkflowIdentifier(): CouldNotTriggerEvent
    {
        return new CouldNotTriggerEvent(
            'No workflow identifier was available when sending the Novu notification.'
        );
    }

    /**
     * Thrown if a notification instance's toNovuEvent() method returns a value other than
     * an instance of \NotificationChannels\Novu\NovuMessage.
     *
     * @param  mixed  $actual
     */
    public static function invalidMessage($actual): CouldNotTriggerEvent
    {
        return new CouldNotTriggerEvent(
            sprintf(
                'Expected a message instance of type %s - Actually received %s',
                NovuMessage::class,
                is_object($actual) ? 'instance of: '.get_class($actual) : gettype($actual)
            )
        );
    }

    /**
     * Thrown if a notification is about to be sent, however no webhook could be found. This
     * exception means that:
     *      - No endpoint was configured in `novu.php` config file
     */
    public static function webhookUnavailable(): CouldNotTriggerEvent
    {
        return new CouldNotTriggerEvent(
            'No webhook URL was available when sending the Novu notification.'
        );
    }

    /**
     * Thrown if a notification is about to be sent, however no api key could be found. This
     * exception means that:
     *      - No key was configured in `novu.php` config file
     */
    public static function apiKeyUnavailable(): CouldNotTriggerEvent
    {
        return new CouldNotTriggerEvent(
            'No api key was available when sending the Novu notification.'
        );
    }

    /**
     * Thrown if a 400-level Http error was encountered whilst attempting to deliver the
     * notification.
     */
    public static function clientError(ClientException $exception): CouldNotTriggerEvent
    {
        $statusCode = $exception->getResponse()->getStatusCode();
        $description = $exception->getMessage();

        return new CouldNotTriggerEvent(
            sprintf('Failed to send Novu message, encountered client error: `%s - %s`', $statusCode, $description)
        );
    }

    /**
     * Thrown if an unexpected exception was encountered whilst attempting to deliver the
     * notification.
     */
    public static function unexpectedException(Exception $exception): CouldNotTriggerEvent
    {
        return new CouldNotTriggerEvent(
            sprintf('Failed to send Novu message, unexpected exception encountered: `%s`', $exception->getMessage()),
            0,
            $exception
        );
    }
}
