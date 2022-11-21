<?php

namespace App\Jobs;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class GetDolar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $payment;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $lastPayment = Payment::select('clp_usd','payment_date')->orderBy('payment_date', 'DESC')->first();

        if ($lastPayment) {
            $nowDay = Carbon::now();
            $lpDay = Carbon::parse($lastPayment->payment_date);
            $isSameDay = $lpDay->isSameDay($nowDay);
        }

        if ($isSameDay) 
        {
            $this->payment->clp_usd = number_format($lastPayment->clp_usd);
            $this->payment->status  = 'paid';
            $this->payment->save();

        }else{

            $response = Http::get('https://mindicador.cl/api/dolar')->json();
            $precioDolar = number_format($response['serie'][0]['valor'],0);

            $this->payment->clp_usd = $precioDolar;
            $this->payment->status  = 'paid';
            $this->payment->save();
        }

    }
}
