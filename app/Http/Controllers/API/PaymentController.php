<?php

namespace App\Http\Controllers\API;

use App\Events\RegisterPayment;
use App\Http\Controllers\Controller;
use App\Jobs\GetDolar;
use App\Mail\RegisterPayment as MailRegisterPayment;
use App\Models\Client;
use App\Models\Payment;
// use Aws\Api\Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Twilio\Security\RequestValidator;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // This is bad practice API

        if ($request->client) 
        {

            $id = $request->client;
            $client = Client::find($id);

            if ($client) 
            {    
                $payments = $client->payments->map(function ($payment) {
                    
                    return (object) [
                        'uuid' => $payment->uuid,
                        'payment_date' => $payment->payment_date,
                        'expires_at' => $payment->expires_at,
                        'status' => $payment->status,
                        'client_id' => $payment->client_id,
                        'clp_usd' => $payment->clp_usd,
                    ];
                });
                
                return response()->json($payments);
            }else{

                return response()->json("Client doesn't exist");
            }
        }

        return response()->json('Request Error');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validator($request->all())->validate();

        /**
         * I had a doubt about how to receive and create the payment, 
         * because I felt that it was more logical that there was a 'pending' payment 
         * in the database and in this 'Post' only the UUID will be sent to decree that it was paid.
         * 
         * */ 

        if ($request->uuid) 
        {
            $uuid = $request->uuid;
            $expires_at = $request->expires_at;
            $status = $request->status;
            $client_id = $request->client_id;
            
            // $payment = Payment::create([
            //     'uuid' => $uuid,
            //     'payment_date' => Carbon::now(),
            //     'expires_at' => $expires_at,
            //     'status' => $status,
            //     'client_id' => $client_id,
            //     'clp_usd' => null
            // ]);

            $payment = Payment::find($uuid);

            if ($payment) 
            {
                // Call the Dollar Job
                GetDolar::dispatch($payment);

                // Send Mail Event
                event(New RegisterPayment($payment));

                // Return object
                $payment = (object) [
                    'uuid' => $payment->uuid,
                    'payment_date' => $payment->payment_date,
                    'expires_at' => $payment->expires_at,
                    'status' => $payment->status,
                    'client_id' => $payment->client_id,
                    'clp_usd' => $payment->clp_usd,
                ];

                return response()->json($payment);
            } else {

                return response()->json("Payment doesn't exist");
            }
        }

        return response()->json('Request Error');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'uuid' => ['required', 'string'],
            // 'payment_date' => ['required', 'string'],
            'expires_at' => ['required', 'string'],
            'status' => ['required', 'string'],
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            // 'clp_usd' => ['required', 'string'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($client)
    {
        // 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
