<?php

/*
	ver. 0.1.7
	OpenPayU Standard Library
	
	@copyright  Copyright (c) 2011-2012 PayU
	@license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
	http://www.payu.com
	http://openpayu.com
	http://twitter.com/openpayu
	
	
	CHANGE_LOG:
	2012-02-14 ver. 0.1.7
		- file created
		
*/

class OpenPayU_Configuration {

	public static $env = 'sandbox';
	public static $merchantPosId = '';
	public static $posAuthKey = '';
	public static $clientId = '';
	public static $clientSecret = '';
	public static $signatureKey = '';

	public static $serviceUrl = '';
	public static $summaryUrl = '';
	public static $authUrl = '';
	public static $serviceDomain = '';

	public static function setEnvironment($value = 'sandbox', $domain = 'payu.pl', $country = 'pl') {
		$value = strtolower($value);
		$domain = strtolower($domain);
		$country = strtolower($country);		
		
		if ($value == 'sandbox' || $value == 'secure')
		{
			self::$env = $value;
			self::$serviceDomain = $domain;
			
			self::$serviceUrl = 'https://' . $value . '.' . $domain . '/' . $country . '/standard/';	
			self::$summaryUrl = self::$serviceUrl . 'co/summary';	
			self::$authUrl = self ::$serviceUrl . 'oauth/user/authorize';				
		}
		else if ($value == 'custom')
		{
			self::$env = $value;
			
			self::$serviceUrl = $domain . '/' . $country . '/standard/';	
			self::$summaryUrl = self::$serviceUrl . 'co/summary';	
			self::$authUrl = self::$serviceUrl . 'oauth/user/authorize';
		}
		else
		{
			throw new Exception('Invalid value:$value for environment. Proper values are: "sandbox" or "secure".');
		}
	}
	
	public static function getServiceUrl() {
		return self::$serviceUrl;
	}
	
	public static function getSummaryUrl() {
		return self::$summaryUrl;
	}
	
	public static function getAuthUrl() {
		return self::$authUrl;
	}
	
	public static function getEnvironment()
	{
		return self::$env;
	}

	public static function setMerchantPosId($value) {
		self::$merchantPosId = $value;
	}
	
	public static function getMerchantPosId()
	{
		return self::$merchantPosId;
	}

	public static function setPosAuthKey($value) {
		self::$posAuthKey = $value;
	}
	
	public static function getPosAuthKey() {
		return self::$posAuthKey;
	}

	public static function setClientId($value) {
		self::$clientId = $value;
	}
	
	public static function getClientId() {
		return self::$clientId;
	}

	public static function setClientSecret($value) {
		self::$clientSecret = $value;
	}
	
	public static function getClientSecret()	{
		return self::$clientSecret;
	}

	public static function setSignatureKey($value) {
		self::$signatureKey = $value;
	}
	
	public static function getSignatureKey() {
		return self::$signatureKey;
	}

}

?>