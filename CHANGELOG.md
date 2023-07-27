## 2.3.6
* define CURL_SSLVERSION_TLSv1_2 missing for rare server configurations
* Retrieving order refunds #130 (Peter Knut)

## 2.3.5
* Licence changed

## 2.3.4
* Remove examples, tests, etc. from release package
* Sanitize display of POST data in examples
* Set min TLS version to 1.2

* ## 2.3.3
* Set Accept header to json
* Disable escaped json parameters

## 2.3.2
* Fix PAYU-26585

## 2.3.1
* Remove cast to int for refund amount
* Fix #116 - typo fix
* Fix #117 - Default for CURLOPT_SSL_VERIFYHOST (2) and CURLOPT_SSL_VERIFYPEER (true)
* Add PHP 7 to TravisCI checks (Vojta Svoboda)

## 2.3.0
* **Possibly breaking changes** - due possible conflict with popular class names "Shop" and "Balance" change to PayuShop and PayuShopBalance

## 2.2.13
* Fix #115

## 2.2.12
* Add Retrieving shop data

## 2.2.11
* Error when request don't have signature header
* Fix generate form
* OpenPayU_Refund::create add extCustomerId and extRefundId required for marketplace

## 2.2.10
* SHA256 as default algorithm

## 2.2.9
* Add a new exception type which holds the original server response (Răzvan Tache)
* Add method to retrieve order transaction (Mariusz Kalota)

## 2.2.8
* Set args separator

## 2.2.7
* Add delete token

## 2.2.6
* Remove cache for Trusted Merchant

## 2.2.5
* Added grant type to cache key

## 2.2.4
* Added connection over proxy support
* Added trusted_merchant Oauth Grant Type

## 2.2.3
* Trim oauthClientId and oauthClientSecret
* Add support for sandbox environment

## 2.2.2
* Added Order statuses constants
* Fix for HttpStatusException

## 2.2.1
* Added retrieving pay methods

## 2.2.0
* Added Oauth

## 2.1.6
* Remove downgrade http version in curl

## 2.1.5
* Update signature calculate for order form

## 2.1.4
* Remove Verify Auth Basic 
* Code cleanup 
* Fix messages for throwHttpStatusException

## 2.1.0
* Endpoint https://secure.payu.com/api/v2_1/orders
* Simplification of request structure by eliminating nesting
* Status code 200 as only response after receiving notification
* Parameters starting with lowercase letters
* CurrencyCode field removed from refund create request
* CompleteUrl replaced with continueUrl
* Value of optional field extOrderId must be unique within one point of sale (POS)

## 2.0.8
* More data in OrderCreate.php example: addition of invoice and delivery optional sections

## 2.0.7

* README.md update
* CHANGELOG.md update
* Cleaned and fixed links in OrderCreate.php example
* ContinueUrl.php deleted
* OpenPayU_Util::statusDesc($response) function update

## 2.0.6

* GeneratedOrderForm.php removal

## 2.0.5

* Fixed bugs in examples.

## 2.0.4

* Fixed bugs

## 2.0.3

* Added tracking of version

## 2.0.2

* Fixed some bugs
* Updated README.md

## 2.0.1

* Fixed some bugs
* Removed support for XML messages
* Removed unsupported examples
* Fixed problem with uppercase keys in order array

## 2.0

* Removed support for OpenPayU 1.0
* Added support for OpenPayuU REST API
* Fixed bugs
* Added unit tests

## 1.9.3

* Added order.cancel and order.statusUpdate functions
* Added hostedOrderForm function
* Unification according to ruby_sdk. Improve comments.

## 1.9.2

* Added protection against full path disclosure

## 0.1.9.1

* Added messages of results
* Changed method invokes

## 0.1.9
* Contains bug fixes 0.1.8 version
* Added PHPDoc and formatted code