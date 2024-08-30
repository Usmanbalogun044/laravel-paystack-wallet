# Laravel Wallet with Paystack Integration

The Laravel Wallet system is designed to provide a seamless and efficient way to manage user funds within a web application. It acts as a virtual wallet that allows users to store, deposit, and withdraw money securely. This feature-rich wallet solution is ideal for applications that require internal fund management, such as e-commerce platforms, subscription-based services, or fintech solutions.

By integrating with Paystack, a popular and reliable payment gateway, the Laravel Wallet system offers users the flexibility to fund their wallet using various payment methods, including credit/debit cards, bank transfers, and USSD codes. The combination of Laravel's robust framework and Paystack's secure payment processing provides a seamless experience for users.

## Installation
[PHP](https://php.net) 10+ or  and [Composer](https://getcomposer.org) are required.

Laravel is accessible, powerful, and provides tools required for large, robust applications.

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
