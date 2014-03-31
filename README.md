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

Full implementation guide [[English](http://www.payu.com/en/openpayu/guide.pdf)] [[Polish](http://www.payu.com/pl/openpayu/guide.pdf)].

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
    OpenPayU_Configuration::setMerchantPosId('145227'); // POS
    OpenPayU_Configuration::setClientSecret('12f071174cb7eb79d4aac5bc2f07563f'); //first MD5 key
    OpenPayU_Configuration::setSignatureKey('13a980d4f851f3d9a1cfc792fb1f5e50'); //second MD5 key
    OpenPayU_Configuration::setApiVersion(2);
    OpenPayU_Configuration::setDataFormat('json'); // json, xml
```

##Usage

###Creating Hosted order

   To create an order you must provide a Array with order data:

   in your controller
```php
    $order['ContinueUrl'] = 'http://localhost/';
    $order['NotifyUrl'] = 'http://localhost/';
    $order['CustomerIp'] = '127.0.0.1';
    $order['MerchantPosId'] = '45654';
    $order['Description'] = 'New order';
    $order['CurrencyCode'] = 'PLN';
    $order['TotalAmount'] = 1000;
    $order['ExtOrderId'] = '1342';
    $order['ValidityTime'] = 48000;

    $order['Products']['Product'][0]['Name'] = 'Product1';
    $order['Products']['Product'][0]['UnitPrice'] = 1000;
    $order['Products']['Product'][0]['Quantity'] = 1;

    $order['PaymentMethods']['PaymentMethod'][0]['Type'] = 'PBL';

    $order['Buyer']['Email'] = 'dd@ddd.pl';
    $order['Buyer']['Phone'] = '123123123';
    $order['Buyer']['FirstName'] = 'Jan';
    $order['Buyer']['LastName'] = 'Kowalski';
    $order['Buyer']['Language'] = 'pl_PL';
    $order['Buyer']['NIN'] = '123456';

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
        "OrderId" => 'Z963D5JQR2230925GUEST000P01',
        "OrderStatus" => 'COMPLETED'
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
