# Official OpenPayU PHP Library 2.2

The OpenPayU PHP library provides integration access to the PayU Gateway API ver. 2.1

## Dependencies
PHP >= 5.3 with extensions [cURL][ext1] i [hash][ext2]

## Documentation

Full implementation guide: [English][ext3], [Polish][ext4].

To process operations such as:
 - [order status update](examples/v2/order/OrderStatusUpdate.php)
 - [order retrieve](examples/v2/order/OrderRetrieve.php)
 - [order cancel](examples/v2/order/OrderCancel.php)

You will need to provide a parameter called **orderId**. The value of orderId is your order identifier that is set by PayU
Payment system, and it's used to invoke remote methods.

There are two ways to get orderId:

1. It is present inside the received notification message from PayU Payment System as a result of payment.
2. In the response from method OpenPayU_Order::create. 

In both cases you will find orderId using this statement: `$response->getResponse()->orderId`.

## Installation

### Composer
To install with Composer, simply add the requirement to your composer.json file:

```php
{
  "require" : {
    "openpayu/openpayu" : "2.2.*"
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

## Configure
**Important:** SDK works only with 'REST API' (Checkout) points of sales (POS).
If you do not already have PayU merchant account, [**please register in Production**][ext5] or [**please register in Sandbox**][ext6]

Example "Configuration keys" from Merchant Panel

![pos_configuration][img0]

To configure OpenPayU environment you must provide a set of mandatory data in config.php file.

For production environment:
```php
    //set Production Environment
    OpenPayU_Configuration::setEnvironment('secure');

    //set POS ID and Second MD5 Key (from merchant admin panel)
    OpenPayU_Configuration::setMerchantPosId('145227');
    OpenPayU_Configuration::setSignatureKey('13a980d4f851f3d9a1cfc792fb1f5e50');
    
    //set Oauth Client Id and Oauth Client Secret (from merchant admin panel)
    OpenPayU_Configuration::setOauthClientId('145227');
    OpenPayU_Configuration::setOauthClientSecret('12f071174cb7eb79d4aac5bc2f07563f');    
```

For sandbox environment:
```php  
    //set Sandbox Environment
    OpenPayU_Configuration::setEnvironment('sandbox');

    //set POS ID and Second MD5 Key (from merchant admin panel)
    OpenPayU_Configuration::setMerchantPosId('300046');
    OpenPayU_Configuration::setSignatureKey('0c017495773278c50c7b35434017b2ca');
    
    //set Oauth Client Id and Oauth Client Secret (from merchant admin panel)
    OpenPayU_Configuration::setOauthClientId('300046');
    OpenPayU_Configuration::setOauthClientSecret('c8d4b7ac61758704f38ed5564d8c0ae0');
``` 
If you want to use sandbox environment, register at this link https://secure.snd.payu.com/cp/register?lang=en

## OAuth configuration
SDK supports two PayU OAuth grant types: `client_credentials` and `trusted_merchant`. Default is `client_credentials`. 

If you want to change grant type use:

```php
    OpenPayU_Configuration::setOauthGrantType('grant_type');
```
grant_type can be one of the following `OauthGrantType::TRUSTED_MERCHANT` or `OauthGrantType::TRUSTED_MERCHANT`


Parameters needed for `client_credentials`

```php
    //set Oauth Client Id and Oauth Client Secret (from merchant admin panel)
    OpenPayU_Configuration::setOauthClientId('300046');
    OpenPayU_Configuration::setOauthClientSecret('c8d4b7ac61758704f38ed5564d8c0ae0');
```

Parameters needed for `trusted_merchant`

```php
    //set Oauth Client Id and Oauth Client Secret (from merchant admin panel)
    OpenPayU_Configuration::setOauthClientId('clent_id');
    OpenPayU_Configuration::setOauthClientSecret('clent_secret');

    //set Oauth Email and Oauth Ext Customer Id
    OpenPayU_Configuration::setOauthEmail('email');
    OpenPayU_Configuration::setOauthExtCustomerId('ext_customer_id');
```

## Connection over Proxy

```php
    OpenPayU_Configuration::setProxyHost('address');
    OpenPayU_Configuration::setProxyPort(8080);
    OpenPayU_Configuration::setProxyUser('user');
    OpenPayU_Configuration::setProxyPassword('password');
```

## Cache
OpenPayU library automatically stores OAuth authentication data in the Cache.

OpenPayU library has two classes implemented to manage the Cache:

* `OauthCacheFile` - data is stored in the file system.
   This is a default and automatic Cache method which stores the data in `lib/Cache` folder.
   **ATTENTION: for security reasons it is recommended to change the Cache folder, so it would not be accessible from the web browser.**
   
    Configuration:
    ```php
    OpenPayU_Configuration::setOauthTokenCache(new OauthCacheFile($directory));
    ```
   `$directory` - absolute path to the data folder; if the parameter is missing, the folder is `lib/Cache`   
   
* `OauthCacheMemcached` - data is stored in Memcached
   This method requires Memcached (https://memcached.org/) to be installed on the server along with Memcached PHP module (http://php.net/manual/en/book.memcached.php)

    Configuration:
    ```php
    OpenPayU_Configuration::setOauthTokenCache(new OauthCacheMemcached($host, $port, $weight));
    ```   
   `$host` - Memcached server address - `localhost` by default
   `$port` - Memcached server port - `11211` by default
   `$weight` - Memcached server priority - `0` by default

It is possible to implement another method to manage cache. In such a case it needs to implement `OauthCacheInterface` 

## Usage

Remember: All keys in "order array" must be in lowercase.

### Creating order using REST API

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

### Retrieving order from OpenPayU

   File with working example: [examples/v2/order/OrderRetrieve.php](examples/v2/order/OrderRetrieve.php)

   You can retrieve order by its PayU order_id

```php
    $response = OpenPayU_Order::retrieve('Z963D5JQR2230925GUEST000P01'); //as parameter use orderId
```
### Retrieving transactions for order from OpenPayU

   File with working example: [examples/v2/order/OrderTransactionRetrieve.php](examples/v2/order/OrderTransactionRetrieve.php)

   You can retrieve transactions for order by its PayU order_id

```php
    $response = OpenPayU_Order::retrieveTransaction('Z963D5JQR2230925GUEST000P01'); //as parameter use orderId
```

### Cancelling order

   File with working example: [examples/v2/order/OrderCancel.php](examples/v2/order/OrderCancel.php)

   You can cancel order by its PayU order_id

```php
    $response = OpenPayU_Order::cancel('Z963D5JQR2230925GUEST000P01'); //as parameter use orderId
```

### Updating order status

   File with working example: [examples/v2/order/OrderStatusUpdate.php](examples/v2/order/OrderStatusUpdate.php)

   You can update order status to accept order.

```php
    $status_update = array(
        "orderId" => 'Z963D5JQR2230925GUEST000P01', //as value use ORDER_ID
        "orderStatus" => 'COMPLETED'
    );

    $response = OpenPayU_Order::statusUpdate($status_update);
```

### Handling notifications from PayU

   File with working example: [examples/v2/order/OrderNotify.php](examples/v2/order/OrderNotify.php)

   PayU sends requests to your application when order status changes

```php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $body = file_get_contents('php://input');
        $data = trim($body);

        $response = OpenPayU_Order::consumeNotification($data);
        $response->getResponse()->order->status; //NEW PENDING CANCELED REJECTED COMPLETED WAITING_FOR_CONFIRMATION

        header("HTTP/1.1 200 OK");
    }
```

### Refund money

   File with working example: [examples/v2/refund/RefundCreate.php](examples/v2/refund/RefundCreate.php)

   You can create refund to refund money on buyer account

```php
    $refund = OpenPayU_Refund::create(
        'Z963D5JQR2230925GUEST000P01', //as a value use ORDER_ID
        'Money refund', //Description - required
        '100' //Amount - If not provided, returns whole transaction, optional
    );
```

### Retrieving pay methods from POS

   File with working example: [examples/v2/retrieve/RetrievePaymethods.php](examples/v2/retrieve/RetrievePaymethods.php)

   You can retrieve pay methods from POS

```php
    $response = OpenPayU_Retrieve::payMethods();
```

   You can add optional parameter `lang` to `payMethods()`  

```php
    $response = OpenPayU_Retrieve::payMethods('en');
```

### Delete card token

   File with working example: [examples/v2/token/TokenDelete.php](examples/v2/token/TokenDelete.php)

   You can delete user's card token.

   Token deletion is possible only for `trusted_merchant` grant type.

```php
    $refund = OpenPayU_Token::delete(
        'TOKC_EXAMPLE_TOKEN' // as a value use user card token 
    );
```

## Contributing

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request


<!--external links:-->
[ext1]: http://php.net/manual/en/book.curl.php
[ext2]: http://php.net/manual/en/book.hash.php
[ext3]: http://developers.payu.com/en/
[ext4]: http://developers.payu.com/pl/
[ext5]: https://secure.payu.com/boarding/#/form&pk_campaign=Plugin-Github&pk_kwd=SDK
[ext6]: https://secure.snd.payu.com/boarding/#/form&pk_campaign=Plugin-Github&pk_kwd=SDK

<!--images:-->
[img0]: readme_images/pos_configuration.png
