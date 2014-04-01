[![Build Status](https://magnum.travis-ci.com/PayU/openpayu_php_sdk.png?token=JKaQyiwkWT1iqL9Lipsp&branch=master)](https://magnum.travis-ci.com/PayU/openpayu_php_sdk)
[![Code Climate](https://codeclimate.com/repos/524eb044f3ea00329815dff1/badges/885c2d52f25c02295344/gpa.png)](https://codeclimate.com/repos/524eb044f3ea00329815dff1/feed)

# OpenPayU PHP Library 2.x

The OpenPayU PHP library provides integration access to the PayU Gateway API ver. 2

## Dependencies

The following PHP extensions are required:

* cURL
* hash
* XMLWriter
* XMLReader

## Documentation

Full implementation guide [[Polish](http://developers.payu.com/)].

Quick guide [[English](http://www.payu.com/en/openpayu/QuickGuide.pdf)] [[Polish](http://www.payu.com/pl/openpayu/QuickGuide.pdf)].


## Installation

Add this line to your application's:

```php
    require_once 'lib/openpayu.php';
    require_once realpath(dirname(__FILE__)) . '/../../config.php';
```

##Configure
  To configure OpenPayU environment you must provide a set of mandatory data:

```php
    OpenPayU_Configuration::setEnvironment('secure'); // production
    OpenPayU_Configuration::setMerchantPosId('145227'); // POS ID
    OpenPayU_Configuration::setSignatureKey('13a980d4f851f3d9a1cfc792fb1f5e50'); //second MD5 key
```

##Usage

###Creating "Hosted Order"

   To create an order using HTML form you must provide an Array with order data:

   in your controller
```php
    $order['continueUrl'] = 'http://localhost/';
    $order['notifyUrl'] = 'http://localhost/';
    $order['customerIp'] = '127.0.0.1';
    $order['merchantPosId'] = '45654';
    $order['description'] = 'New order';
    $order['currencyCode'] = 'PLN';
    $order['totalAmount'] = 1000;
    $order['extOrderId'] = '1342';
    $order['validityTime'] = 48000;

    $order['products']['product'][0]['name'] = 'Product1';
    $order['products']['product'][0]['unitPrice'] = 1000;
    $order['products']['product'][0]['quantity'] = 1;

    $order['paymentMethods']['paymentMethod'][0]['type'] = 'PBL';

    $order['buyer']['email'] = 'dd@ddd.pl';
    $order['buyer']['phone'] = '123123123';
    $order['buyer']['firstName'] = 'Jan';
    $order['buyer']['lastName'] = 'Kowalski';
    $order['buyer']['language'] = 'pl_PL';

    $orderFormData = OpenPayU_Order::hostedOrderForm($order);
```
  in your view
```php
<html>
<?php echo $orderFormData; ?>
</html>
```
  or just
```php
echo $orderFormData
```

###Creating "Transparent Order" ( BETA version )

   To create an order using REST API in back-end you must provide an Array with order data:

   in your controller
```php
    $order['continueUrl'] = 'http://localhost/';
    $order['notifyUrl'] = 'http://localhost/';
    $order['customerIp'] = '127.0.0.1';
    $order['merchantPosId'] = '45654';
    $order['description'] = 'New order';
    $order['currencyCode'] = 'PLN';
    $order['totalAmount'] = 1000;
    $order['extOrderId'] = '1342';
    $order['validityTime'] = 48000;

    $order['products']['product'][0]['name'] = 'Product1';
    $order['products']['product'][0]['unitPrice'] = 1000;
    $order['products']['product'][0]['quantity'] = 1;

    $order['paymentMethods']['paymentMethod'][0]['type'] = 'PBL';

    $order['buyer']['email'] = 'dd@ddd.pl';
    $order['buyer']['phone'] = '123123123';
    $order['buyer']['firstName'] = 'Jan';
    $order['buyer']['lastName'] = 'Kowalski';
    $order['buyer']['language'] = 'pl_PL';

    $response = OpenPayU_Order::create($order);

    header('Location:'.$response->getResponse()->redirectUri); //You must redirect your client to PayU payment summary page.
```

###Retrieving order from OpenPayU
  You can retrieve order by its PayU order_id

```php
    $response = OpenPayU_Order::retrieve('Z963D5JQR2230925GUEST000P01');
```

###Cancelling order
  You can cancel order by its PayU order_id

```php
    $response = OpenPayU_Order::cancel('Z963D5JQR2230925GUEST000P01');
```

###Updating order status

  You can update order status to accept order when Autoreceive in POS is turned off

```php
    $status_update = array(
        "orderId" => 'Z963D5JQR2230925GUEST000P01',
        "orderStatus" => 'COMPLETED'
    );

    $response = OpenPayU_Order::status_update($status_update);
```

###Handling notifications from PayU

  PayU sends requests to your application when order status changes

```php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $body = file_get_contents('php://input');
        $data = stripslashes(trim($body));

        $reponse = OpenPayU_Order::consumeNotification($data)
        $response->Response->Status //NEW PENDING CANCELLED REJECTED COMPLETED WAITING_FOR_CONFIRMATION

        $rsp = OpenPayU::buildOrderNotifyResponse($reponse->Response->Order->OrderId);

        //you should response to PayU with special structure (OrderNotifyResponse)
        header("Content-Type: application/json");
        echo json_encode(OpenPayU_Util::parseXmlDocument(stripslashes($rsp)));
    }
```

###Refund money

  You can create refund to refund money on buyer account

```php
    $refund = OpenPayU_Refund::create(
        'Z963D5JQR2230925GUEST000P01', //Order Id - required
        'Money refund', //Description - required
        '100' //Amount - If not provided, returns whole transaction, optional
    );
```

## Contributing

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request
