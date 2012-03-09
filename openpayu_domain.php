<?php

/*
	ver. 0.1.3
	OpenPayU Standard Library, handle Order Domain
	
	@copyright  Copyright (c) 2011-2012 PayU
	@license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
	http://www.payu.com
	http://openpayu.com
	http://twitter.com/openpayu

	
	CHANGE_LOG:
	2012-01-24, ver. 0.1.3
		- builder deprecated
		- content of build function is moved to getDomain4Message function
	2012-01-12, ver. 0.1.2
		- qualifiers change
	2011-12-20, ver. 0.1.0
		- start file
		- change name from OrderUpdateRequest -> OrderStatusUpdateRequest.
		
*/

class OpenPayUDomain {

	private static $msg2domain = null;
	
	/**
	 * @deprecated
	 */
	private static function builder()
	{
		OpenPayUDomain::$msg2domain = array
		(
			'OrderCreateRequest' 				=> 'OrderDomainRequest',
			'OrderCreateResponse' 				=> 'OrderDomainResponse',
			'OrderStatusUpdateRequest' 			=> 'OrderDomainRequest',
			'OrderStatusUpdateResponse' 		=> 'OrderDomainResponse',
			'OrderCancelRequest' 				=> 'OrderDomainRequest',
			'OrderCancelResponse' 				=> 'OrderDomainResponse',											
			'OrderNotifyRequest' 				=> 'OrderDomainRequest',
			'OrderNotifyResponse' 				=> 'OrderDomainResponse',											
			'OrderRetrieveRequest' 				=> 'OrderDomainRequest',
			'OrderRetrieveResponse' 			=> 'OrderDomainResponse',
			'ShippingCostRetrieveRequest' 		=> 'OrderDomainRequest',
			'ShippingCostRetrieveResponse' 		=> 'OrderDomainResponse'
		);
	}

	/**
	 * Function returns Message domain
	 * @param string $msg
	 * @access public
	 * @return array
	 */
	public static function getDomain4Message($msg)
	{
		self::$msg2domain = array
		(
			'OrderCreateRequest' 				=> 'OrderDomainRequest',
			'OrderCreateResponse' 				=> 'OrderDomainResponse',
			'OrderStatusUpdateRequest' 			=> 'OrderDomainRequest',
			'OrderStatusUpdateResponse' 		=> 'OrderDomainResponse',
			'OrderCancelRequest' 				=> 'OrderDomainRequest',
			'OrderCancelResponse' 				=> 'OrderDomainResponse',											
			'OrderNotifyRequest' 				=> 'OrderDomainRequest',
			'OrderNotifyResponse' 				=> 'OrderDomainResponse',											
			'OrderRetrieveRequest' 				=> 'OrderDomainRequest',
			'OrderRetrieveResponse' 			=> 'OrderDomainResponse',
			'ShippingCostRetrieveRequest' 		=> 'OrderDomainRequest',
			'ShippingCostRetrieveResponse' 		=> 'OrderDomainResponse'
		);
		
		return self::$msg2domain[$msg];
	}
}

?>