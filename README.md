# Laravel Wallet with Paystack Integration

The Laravel Wallet system is designed to provide a seamless and efficient way to manage user funds within a web application. It acts as a virtual wallet that allows users to store, deposit, and withdraw money securely. This feature-rich wallet solution is ideal for applications that require internal fund management, such as e-commerce platforms, subscription-based services, or fintech solutions.

By integrating with Paystack, a popular and reliable payment gateway, the Laravel Wallet system offers users the flexibility to fund their wallet using various payment methods, including credit/debit cards, bank transfers, and USSD codes. The combination of Laravel's robust framework and Paystack's secure payment processing provides a seamless experience for users.

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

