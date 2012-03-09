<?php

/*
	ver. 0.1.8
	OpenPayU Standard Library
	
	@copyright  Copyright (c) 2011-2012 PayU
	@license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
	http://www.payu.com
	http://openpayu.com
	http://twitter.com/openpayu

	
	CHANGE_LOG:
	2012-02-23 ver. 0.1.8
		- exclude obsolete code to files, obsolete code will be removed in future.
	2012-02-14 ver. 0.1.7
		- all accesors from OpenPayU_Configuration, OpenPayU_OAuthResult, OpenPayU_Result classes are changed into set/get
		- classes created in 0.1.x versions was distributed to different files under directory OpenPayU
		- added OpenPayU::addOutputConsole() function
	2012-01-27
		- fixed call verifyResponse funciton, OpenPayU::verifyResponse
		- fixed orderDomainRequest.orderStatusUpdateRequest.timestamp in OpenPayU::updateStatus function, added Timestamp
	2012-01-20, ver. 0.1.6
		- added OpenPayU_Configuration::getServiceUrl() function
		- added OpenPayU_Configuration::getSummaryUrl() function
		- added OpenPayU_Configuration::getAuthUrl() function
		- added OpenPayU_Configuration::getEnvironment() function
		- added OpenPayU_Configuration::getMerchantPosId() function
		- added OpenPayU_Configuration::getPosAuthKey() function
		- added OpenPayU_Configuration::getClientId() function
		- added getClientSecret() function
		- added OpenPayU_Configuration::getClientSecret() function
		- added OpenPayU_Configuration::getSignatureKey() function
		- changed assignment variables in OpenPayU_Configuration
	2012-01-18, ver. 0.1.4
		- added $result->sessionId in OpenPayU_Order::consumeShippingCostRetrieveRequest function
	2012-01-12, ver. 0.1.3
		- change the display message 'OpenPayUNetwork::$openPayuEndPointUrl is empty' 
		- optimization function OpenPayUNetwork::isCurlInstalled, removed else condition
		- removed $countryCode unused parameter of the buildOpenPayuForm function
		- qualifiers change
		- removed unused $url in OpenPayuOAuth::getAccessTokenByCode function
		- removed unused $url in OpenPayuOAuth::getAccessToken function
		- removed unused $url in OpenPayuOAuth::getAccessTokenByClientCredentials function
		- removed unused $url in OpenPayuOAuth::getAccessTokenOnly function
		- changed comparing to the empty string to the empty() in getOpenPayuEndPoint(), sendOpenPayuDocument(), sendOpenPayuDocumentAuth()
	 2012-01-11, ver. 0.1.2
		- OpenPayU_Configuration::environment accept 'custom' url service now.
	 2012-01-03, ver. 0.1.1
		- arguments in function environment is converted to lower char
	 2011-12-20, ver. 0.1.0
		 - added classes OpenPayU_Configuration, OpenPayU_Order, OpenPayU_OAuth
		 - added method verifyResponse
	2011-11-07, ver. 0.0.18
		- bugfix for document parsing errors
	2011-11-06, ver. 0.0.17
		- transfer of algorithm computing authentication header to SKD
	2011-11-04, ver. 0.0.16
		- changes connected with changing OrderUpdateRequest with OrderNotifyRequest.
	2011-09-09, ver. 0.0.15
		- added http header authentication
*/

include_once('openpayu_domain.php');

/*
these files are obsolete and will be removed in future.
valid only for SDK 0.0.x 
*/
include_once('OpenPayU/OpenPayUNetwork.php');
include_once('OpenPayU/OpenPayUBase.php');
include_once('OpenPayU/OpenPayU.php');
include_once('OpenPayU/OpenPayUOAuth.php');

/* 
these files are 0.1.x compatible 
*/
include_once('OpenPayU/Result.php');
include_once('OpenPayU/ResultOAuth.php');
include_once('OpenPayU/Configuration.php');
include_once('OpenPayU/Order.php');
include_once('OpenPayU/OAuth.php');

?>