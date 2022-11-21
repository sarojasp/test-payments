<?php

namespace App\Listeners;

use App\Mail\RegisterPaymentMail;
use App\Event\RegisterPayment;
use App\Events\RegisterPayment as EventsRegisterPayment;
use App\Models\Client;
use App\Models\Payment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendMail
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(EventsRegisterPayment $event)
    {
        Mail::to($event->payment->client->email)->send(new RegisterPaymentMail());
    }
}
