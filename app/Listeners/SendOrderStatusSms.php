<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Traits\Notification;

class SendOrderStatusSms
{
    use Notification;

    public function handle(OrderStatusChanged $event): void
    {
        $this->send_sms($event->order->id, $event->newStatus);
    }
}
