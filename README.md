# Laravel Wallet with Paystack Integration
This project provides a complete solution for integrating a wallet system within a Laravel application using the Paystack payment gateway. Perfect for e-commerce platforms, fintech applications, and more.

## Installation
[PHP](https://php.net) 10+ or  and [Composer](https://getcomposer.org) are required.



To get the latest version of Laravel Paystack, simply require it

```bash
composer require unicodeveloper/laravel-paystack
```
Once Laravel Paystack is installed, you need to register the service provider.
## Configuration

You can publish the configuration file using this command:
```bash
php artisan vendor:publish --provider="Unicodeveloper\Paystack\PaystackServiceProvider"
```
A configuration-file named `paystack.php` with some sensible defaults will be placed in your `config` directory:
```php
<?php

return [

    /**
     * Public Key From Paystack Dashboard
     *
     */
    'publicKey' => getenv('PAYSTACK_PUBLIC_KEY'),

    /**
     * Secret Key From Paystack Dashboard
     *
     */
    'secretKey' => getenv('PAYSTACK_SECRET_KEY'),

    /**
     * Paystack Payment URL
     *
     */
    'paymentUrl' => getenv('PAYSTACK_PAYMENT_URL'),

    /**
     * Optional email address of the merchant
     *
     */
    'merchantEmail' => getenv('MERCHANT_EMAIL'),

];
```

## General payment flow

Though there are multiple ways to pay an order, most payment gateways expect you to follow the following flow in your checkout process:

### 1. The customer is redirected to the payment provider
After the customer has gone through the checkout process and is ready to pay, the customer must be redirected to the site of the payment provider.

The redirection is accomplished by submitting a form with some hidden fields. The form must send a POST request to the site of the payment provider. The hidden fields minimally specify the amount that must be paid, the order id and a hash.

The hash is calculated using the hidden form fields and a non-public secret. The hash used by the payment provider to verify if the request is valid.


### 2. The customer pays on the site of the payment provider
The customer arrives on the site of the payment provider and gets to choose a payment method. All steps necessary to pay the order are taken care of by the payment provider.

### 3. The customer gets redirected back to your site
After having paid the order the customer is redirected back. In the redirection request to the shop-site some values are returned. The values are usually the order id, a payment result and a hash.

The hash is calculated out of some of the fields returned and a secret non-public value. This hash is used to verify if the request is valid and comes from the payment provider. It is paramount that this hash is thoroughly checked.

## Usage

Open your .env file and add your public key, secret key, merchant email and payment url like so:

```php
PAYSTACK_PUBLIC_KEY=xxxxxxxxxxxxx
PAYSTACK_SECRET_KEY=xxxxxxxxxxxxx
PAYSTACK_PAYMENT_URL=https://api.paystack.co
MERCHANT_EMAIL=unicodeveloper@gmail.com
```


Note: Make sure you have `/payment/callback` registered in Paystack Dashboard [https://dashboard.paystack.co/#/settings/developer](https://dashboard.paystack.co/#/settings/developer) like so:

![payment-callback](https://cloud.githubusercontent.com/assets/2946769/12746754/9bd383fc-c9a0-11e5-94f1-64433fc6a965.png)


```php
//laravel 11
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Models\Wallet;


Route::get('/dashboard', function () {
    $user=Auth::user();
    $wallet = $user->wallet;

    if (!$wallet) {
        // Optionally create a wallet if it doesn't exist
        $wallet = Wallet::create([
            'user_id' => $user->id,
            'balance' => 0.00, // Default balance
        ]);
    }
    return view('dashboard', compact('wallet'));
});
    Route::get('/walletfundng',[PaymentController::class, 'fundwallet'])->name('fundwallet');
    Route::post('/fund-wallet', [PaymentController::class, 'Wallet'])->name('wallet');
    Route::get('/payment/callback', [PaymentController::class, 'handleGatewayCallback'])->name('payment.callback');
```
```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Container\Attributes\Log;
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
       
    // Convert JSON object to an array for logging and processing
    $paymentDetailsArray = json_decode(json_encode($paymentDetails), true);

    // Log payment details for debugging
    Log::info('Payment details: ', $paymentDetailsArray);

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


        Log::info('Transaction successful: ', [
            'user_id' => $user->id,
            'amount' => $amount,
            'transaction_reference' => $paymentDetailsArray['data']['reference']
        ]);

        return redirect()->route('dashboard')->with('success', 'Wallet funded successfully.');
    }

    // Log failed transaction
    Log::warning('Transaction failed: ', [
        'status' => $status,
        'paymentDetails' => $paymentDetailsArray
    ]);

    return redirect()->route('dashboard')->with('error', 'Payment failed. Please try again.');
}
    
}
```

