[![Code Climate](https://codeclimate.com/repos/524eb044f3ea00329815dff1/badges/885c2d52f25c02295344/gpa.png)](https://codeclimate.com/repos/524eb044f3ea00329815dff1/feed)

# Official OpenPayU PHP Library 2.1

The OpenPayU PHP library provides integration access to the PayU Gateway API ver. 2.1

## Dependencies

The following PHP extensions are required:

* cURL
* hash

## Documentation

Full implementation guide [[English](http://developers.payu.com/en/)][[Polish](http://developers.payu.com/)].

To process operations such as:
 - [order status update](examples/v2/order/OrderStatusUpdate.php)
 - [order retrieve](examples/v2/order/OrderRetrieve.php)
 - [order cancel](examples/v2/order/OrderCancel.php)

You will need to provide a parameter called <b>orderId</b>. The value of orderId is your order identifier that is set by PayU
Payment system, and it's used to invoke remote methods.

There are two ways to get orderId:

1. It is present inside the received notification message from PayU Payment System as a result of payment.
2. In the response from method OpenPayU_Order::create. 

In both cases you will find orderId using this statement: $response->getResponse()->orderId.

## Installation

### Composer
To install with Composer, simply add the requirement to your composer.json file:

```php
{
  "require" : {
    "openpayu/openpayu" : "2.1.*"
  }
}
```
Then install by running

```php
composer.phar install
```

### Manual installation
Obtain the latest version of openpayu_php SDK with:
```php
git clone https://github.com/PayU/openpayu_php.git
```

## Getting started

If you are using Composer use autoload functionality:

```php
include "vendor/autoload.php";
```

Or simply add this lines anywhere in your application:

```php
    require_once 'lib/openpayu.php';
    require_once realpath(dirname(__FILE__)) . '/../../config.php';
```

##Configure
  To configure OpenPayU environment you must provide a set of mandatory data in config.php file:

```php
    OpenPayU_Configuration::setEnvironment('secure');
    OpenPayU_Configuration::setMerchantPosId('145227'); // POS ID (Checkout)
    OpenPayU_Configuration::setSignatureKey('13a980d4f851f3d9a1cfc792fb1f5e50'); //Second MD5 key. You will find it in admin panel.
```

##Usage

Remember: All keys in "order array" must be in lowercase.

###Creating order using HTML form

   File with working example: [examples/v2/order/OrderForm.php](examples/v2/order/OrderForm.php)

   To create an order using HTML form you must provide an Array with order data:

   in your controller
```php
        $order['notifyUrl'] = 'http://localhost';
        $order['continueUrl'] = 'http://localhost';

        $order['customerIp'] = '127.0.0.1';
        $order['merchantPosId'] = OpenPayU_Configuration::getMerchantPosId();
        $order['description'] = 'New order';
        $order['currencyCode'] = 'PLN';
        $order['totalAmount'] = 3200;
        $order['extOrderId'] = rand(1000, 1000000);

        $order['products'][0]['name'] = 'Product1';
        $order['products'][0]['unitPrice'] = 1000;
        $order['products'][0]['quantity'] = 1;

        $order['products'][1]['name'] = 'Product2';
        $order['products'][1]['unitPrice'] = 2200;
        $order['products'][1]['quantity'] = 1;

        $order['buyer']['email'] = 'dd@ddd.pl';
        $order['buyer']['phone'] = '123123123';
        $order['buyer']['firstName'] = 'Jan';
        $order['buyer']['lastName'] = 'Kowalski';

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

###Creating order using REST API

   File with working example: [examples/v2/order/OrderCreate.php](examples/v2/order/OrderCreate.php)

   To create an order using REST API in back-end you must provide an Array with order data:

   in your controller
```php
    $order['continueUrl'] = 'http://localhost/'; //customer will be redirected to this page after successfull payment
    $order['notifyUrl'] = 'http://localhost/';
    $order['customerIp'] = $_SERVER['REMOTE_ADDR'];
    $order['merchantPosId'] = OpenPayU_Configuration::getMerchantPosId();
    $order['description'] = 'New order';
    $order['currencyCode'] = 'PLN';
    $order['totalAmount'] = 3200;
    $order['extOrderId'] = '1342'; //must be unique!

    $order['products'][0]['name'] = 'Product1';
    $order['products'][0]['unitPrice'] = 1000;
    $order['products'][0]['quantity'] = 1;

    $order['products'][1]['name'] = 'Product2';
    $order['products'][1]['unitPrice'] = 2200;
    $order['products'][1]['quantity'] = 1;

//optional section buyer
    $order['buyer']['email'] = 'dd@ddd.pl';
    $order['buyer']['phone'] = '123123123';
    $order['buyer']['firstName'] = 'Jan';
    $order['buyer']['lastName'] = 'Kowalski';

    $response = OpenPayU_Order::create($order);

    header('Location:'.$response->getResponse()->redirectUri); //You must redirect your client to PayU payment summary page.
```

###Retrieving order from OpenPayU

   File with working example: [examples/v2/order/OrderRetrieve.php](examples/v2/order/OrderRetrieve.php)

   You can retrieve order by its PayU order_id

```php
    $response = OpenPayU_Order::retrieve('Z963D5JQR2230925GUEST000P01'); //as parameter use orderId
```

###Cancelling order

   File with working example: [examples/v2/order/OrderCancel.php](examples/v2/order/OrderCancel.php)

   You can cancel order by its PayU order_id

```php
    $response = OpenPayU_Order::cancel('Z963D5JQR2230925GUEST000P01'); //as parameter use orderId
```

###Updating order status

   File with working example: [examples/v2/order/OrderStatusUpdate.php](examples/v2/order/OrderStatusUpdate.php)

   You can update order status to accept order.

```php
    $status_update = array(
        "orderId" => 'Z963D5JQR2230925GUEST000P01', //as value use ORDER_ID
        "orderStatus" => 'COMPLETED'
    );

    $response = OpenPayU_Order::statusUpdate($status_update);
```

###Handling notifications from PayU

   File with working example: [examples/v2/order/OrderNotify.php](examples/v2/order/OrderNotify.php)

   PayU sends requests to your application when order status changes

```php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $body = file_get_contents('php://input');
        $data = stripslashes(trim($body));

        $response = OpenPayU_Order::consumeNotification($data);
        $response->getResponse()->order->status; //NEW PENDING CANCELLED REJECTED COMPLETED WAITING_FOR_CONFIRMATION

        header("HTTP/1.1 200 OK");
    }
```

###Refund money

   File with working example: [examples/v2/refund/RefundCreate.php](examples/v2/refund/RefundCreate.php)

   You can create refund to refund money on buyer account

```php
    $refund = OpenPayU_Refund::create(
        'Z963D5JQR2230925GUEST000P01', //as a value use ORDER_ID
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
