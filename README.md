<p align="center">
<a href="LICENSE"><img alt="Software License" src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square"></a>
<a href="composer.json"><img alt="Laravel Version Requirements" src="https://img.shields.io/badge/laravel-~10.0-gray?logo=laravel&style=flat-square&labelColor=F05340&logoColor=white"></a>
</p>

<h1>Novu - Laravel Notification Channel</h1>

This package makes it easy to send notifications using [Novu](https://novu.co)
````php
class InvoicePaidNotification extends Notification
{
    // Trigger a specific notification event
    public function toNovuEvent($notifiable)
    {
        return NovuMessage::create('workflow_1234')
            ->addVariable('invoice_id', $this->invoice->id)
            ->toSubscriberId('123456789');
    }
}
````
## Contents

- [Installation](#installation)
- [Usage](#usage)
- [API Overview](#novu-message)
    - [Novu Message](#novu-message)
- [Testing](#testing)
- [License](#license)


## Installation

The Novu notification channel can be installed easily via Composer:

````bash
$ composer require toitzi/novu-notifications-laravel
````

## Usage

In order to send a notification via the Novu channel, you'll need to specify the channel in the `via()` method of your notification:

````php
use NotificationChannels\Novu\NovuChannel;

public function via($notifiable)
{
    return [
        NovuChannel::class
    ]
}
````

## API Overview

### Novu Message

Namespace: `NotificationChannels\Novu\NovuMessage`

The `NovuMessage` class encompasses an entire message that will be sent to the Novu API.

- `static create(?string $workflowId)` Instantiates and returns a new `NovuMessage` instance, optionally pre-configuring it with the workflow id
- `workflowId(string $workflowId)` Set the `workflowId` of the message (Your novu workflow trigger id)
- `to(array $to)` Array of recipient information like `subscriberId`, `phone`, etc...
- `toSubscriber(string $subscriberId)` Set the `subscriberId` of the recipient
- `variables(array $variables)` Set the variables (`payload`) of the message. Those are your novu event variables
- `addVariable(string $key, $value)` Add a single variable to the message
- `toArray()` Returns the data that will be sent to the Novu API as an array

## Testing

Run the tests with:

``` bash
$ composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
