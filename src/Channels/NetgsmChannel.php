<?php

namespace Noxarc\Netgsm\Channels;

use Illuminate\Notifications\Notification;
use Noxarc\Netgsm\Sms;

class NetgsmChannel
{
	public function send($notifiable, Notification $notification)
    {
        $message = $notification->toNetgsm($notifiable);

        $sms = new Sms;

        $sms->send([$notifiable->getPhone()], $message);
    }
}