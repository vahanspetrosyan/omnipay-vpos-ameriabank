# Omnipay: Ameriabank

**Ameriabank driver for the Omnipay Laravel payment processing library**

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.5+. This package implements iDram support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, you can simply run:
 ```` bash
composer require vahanspetrosyan/omnipay-vpos-ameriabank
````

## Basic Usage

1. Use Omnipay gateway class:

```php
use Omnipay\Omnipay;
```

2. Initialize Ameriabank gateway:

```php
$gateway = Omnipay::create('Ameriabank');
$gateway->setClientId('Client_ID'); // Shoud be your Ameriabank Client ID (e.g. 7e7ef8ff-6300-4a78-bb31-3ad1a8c67d5f)
$gateway->setUsername('Username');  // Should be your Ameria Username 
$gateway->setPassword('Password');  // Should be your Ameria password
$gateway->setOpaque('Opaque');      // Is not mandatory field and used as additional information during information exchange 
$gateway->setOrderId('Order_ID');   // Is randomly generated ID. Integer which is generated by using system local time e.g. time()
$gateway->setCurrency('AMD');       // Uses national currency e.g. AMD, USD, EUR 
$gateway->setTestMode(false);       // Boolean and can be only true or false
$payment = $gateway->purchase([             
     'amount' => '100',         
     'returnUrl' => 'http://example.com/xxx',  // The URL to which you will be redirected after completing the purchase. Please also refer to poin 4 below
     'description' => 'Description ...'
    ]
)->createPaymentRequest();
```

3. Processing payment <br>
After payment request approval, call receive positive or negative response 

```php

if (empty($payment->GetPaymentIDResult->PaymentID) || $payment->GetPaymentIDResult->Respmessage != 'OK') {

    return $payment->GetPaymentIDResult; // in case if response was negative (rejected).

} else {
    $gateway->setPaymentId($payment->GetPaymentIDResult->PaymentID);            //if positive, call receive payment ID 
    $gateway->setClientUrl("http://example.com/ameriarequestframe.aspx");       // Setting /ameriarequestframe.aspx inside your site
    $gateway->setLanguage('en');
    $response = $gateway->purchase()->send();                                   // generate unique URL 
    return [
        'redirectUrl' => $response->getRedirectUrl()                            // redirection to previously generated unique URL 
    ];
}


```

4. Completeng Payment <br>
You will be redirected to AmeriaBank VPOS form page. 
After filling and submitting credit card data AmeriaBank page will webhook http://example.com/xxx (refer to also to point 2)

```php

$gateway = Omnipay::create('Ameriabank');
$gateway->setClientId('Client_ID'); // Shoud be your Ameriabank Client ID (e.g. 7e7ef8ff-6300-4a78-bb31-3ad1a8c67d5f)
$gateway->setUsername('Username');  // Should be your Ameria Username 
$gateway->setPassword('Password');  // Should be your Ameria password
$gateway->setOrderId('Order_ID');   // Is randomly generated ID. Integer which is generated by using system local time e.g. time()
$gateway->setTestMode(false);       // Boolean and can be only true or false
$$webService = $gateway->completePurchase([             
     'amount' => '100'
    ]
)->getPaymentFields();

if ($paymentFields = $webService->GetPaymentFieldsResult) {
   // Your logic
    return $paymentFields;
}

return $webService; // Return error text in case of connection errors

```
For testing puposes you should use only AMD currency and charge not more than 10 AMD 

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Support
