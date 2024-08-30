<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Unicodeveloper\Paystack\Facades\Paystack;

class PaymentController extends Controller
{
    public function fundwallet(){
        return view('walletfund');
    }

    public function wallet(Request $request){
       $request->validate(
        [
            'amount'=> 'required|numeric|min:1',
        ]
        );


    $user = auth::user();

        $amount = $request->amount * 100;

        $data = array(
            "amount" => $amount,
            "reference" => Paystack::genTranxRef(),
            "email" => $user->email,
            "currency" => "NGN",
            "callback_url" => route('payment.callback')
           
        );
        return Paystack::getAuthorizationUrl($data)->redirectNow();
    }
    public function handleGatewayCallback(){
    
        // Retrieve data from the callback
    $paymentDetails = Paystack::getPaymentData();
        // dd($paymentDetails);
    // Convert JSON object to an array for logging and processing
    $paymentDetailsArray = json_decode(json_encode($paymentDetails), true);

    // Log payment details for debugging
    // Log::info('Payment details: ', $paymentDetailsArray);

    // Access the status and other data from the JSON object
    $status = $paymentDetailsArray['data']['status'] ?? 'failure';

    if ($status === 'success') {
        $amount = $paymentDetailsArray['data']['amount'] / 100; // Convert to Naira
        $user = auth::user();

        // Update the wallet balance
        $wallet = $user->wallet;
        $wallet->balance += $amount;
        $wallet->save();

        // Log successful transaction


        // Log::info('Transaction successful: ', [
        //     'user_id' => $user->id,
        //     'amount' => $amount,
        //     'transaction_reference' => $paymentDetailsArray['data']['reference']
        // ]);

        return redirect()->route('dashboard')->with('success', 'Wallet funded successfully.');
    }

    // Log failed transaction
    // Log::warning('Transaction failed: ', [
    //     'status' => $status,
    //     'paymentDetails' => $paymentDetailsArray
    // ]);

    return redirect()->route('dashboard')->with('error', 'Payment failed. Please try again.');
}
    
}
