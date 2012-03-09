<?php

/*
	ver. 0.1.8
	OpenPayU Standard Library
	
	This code is obsolete code. Will be removed in the future.	
	
	@copyright  Copyright (c) 2011-2012 PayU
	@license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
	http://www.payu.com
	http://openpayu.com
	http://twitter.com/openpayu

	
	CHANGE_LOG:
	2012-02-23 ver. 0.1.8
		- file created
*/

class OpenPayU extends OpenPayUBase {

	/**
	 * Function builds OrderCreateRequest Document 
	 * @access public
	 * @param string $data
	 * @return string
	 */
	public static function buildOrderCreateRequest($data)
	{
		$xml = OpenPayU::buildOpenPayURequestDocument($data, 'OrderCreateRequest');
		return $xml;
	}
	
	/**
	 * Function builds OrderRetrieveRequest Document 
	 * @access public
	 * @param array $data
	 * @return xml
	 */
	public static function buildOrderRetrieveRequest($data)
	{
		$xml = OpenPayU::buildOpenPayURequestDocument($data, 'OrderRetrieveRequest');
		return $xml;
	}

	/**
	 * Function builds ShippingCostRetrieveResponse Document 
	 * @access public
	 * @param array $data
	 * @return xml
	 */
	public static function buildShippingCostRetrieveResponse($data, $reqId) {

		$cost = array (
			'ResId' =>  $reqId,
			'Status' => array('StatusCode' => 'OPENPAYU_SUCCESS'),
			'AvailableShippingCost' => $data
		);

		$xml = OpenPayU::buildOpenPayUResponseDocument($cost, 'ShippingCostRetrieveResponse');
		return $xml;
	}

	/**
	 * Function builds buildOrderNotifyResponse Document 
	 * @access public
	 * @param string $reqId
	 * @return xml
	 */
	public static function buildOrderNotifyResponse($reqId) {

		$cost = array (
			'ResId' =>  $reqId, 
			'Status' => array('StatusCode' => 'OPENPAYU_SUCCESS')
		);

		$xml = OpenPayU::buildOpenPayUResponseDocument($cost, 'OrderNotifyResponse');
		return $xml;
	}

	/**
	 * Function builds verifyResponse Status 
	 * @access public
	 * @param string $data
	 * @param string $message
	 * @return string $xml
	 */
	public static function verifyResponse($data, $message) {
			
		$arr = OpenPayU::parseOpenPayUDocument(stripslashes($data));
		$status_code = $arr['OpenPayU']['OrderDomainResponse'][$message]['Status'];
		if($status_code == null){
			$status_code = $arr['OpenPayU']['HeaderResponse']['Status'];
		}
		return $status_code;
	}

	/**
	 * Function returns OrderCancelResponse Status Document 
	 * @access public
	 * @param string $data
	 * @return string $xml
	 */
	public static function verifyOrderCancelResponseStatus($data) {
		return OpenPayU::verifyResponse($data, 'OrderCancelResponse');
	}
	
	/**
	 * Function returns OrderStatusUpdateResponse Status Document 
	 * @access public
	 * @param string $data
	 * @return string $xml
	 */
	public static function verifyOrderStatusUpdateResponseStatus($data) {
		return OpenPayU::verifyResponse($data, 'OrderStatusUpdateResponse');
	}
	
	/**
	 * Function returns OrderCreateResponse Status 
	 * @access public
	 * @param string $data
	 * @return string $status_code
	 */
	public static function verifyOrderCreateResponse($data) {
			
		$arr = OpenPayU::parseOpenPayUDocument(stripslashes($data));
		$status_code = $arr['OpenPayU']['OrderDomainResponse']['OrderCreateResponse']['Status'];
		if($status_code == null){
			$status_code = $arr['OpenPayU']['HeaderResponse']['Status'];
		}
		return $status_code;
	}

	/**
	 * Function returns OrderRetrieveResponse Status 
	 * @access public
	 * @param string $data
	 * @return string $status_code
	 */
	public static function verifyOrderRetrieveResponseStatus($data) {
			
		$arr = OpenPayU::parseOpenPayUDocument(stripslashes($data));
		$status_code = $arr['OpenPayU']['OrderDomainResponse']['OrderRetrieveResponse']['Status'];
		if($status_code == null){
			$status_code = $arr['OpenPayU']['HeaderResponse']['Status'];
		}
		return $status_code;
	}

	/**
	 * Function returns OrderRetrieveResponse Data 
	 * @access public
	 * @param string $data
	 * @return string $order_retrieve
	 */
	public static function getOrderRetrieveResponse($data)
	{
		$arr = OpenPayU::parseOpenPayUDocument(stripslashes($data));
		$order_retrieve = $arr['OpenPayU']['OrderDomainResponse']['OrderRetrieveResponse'];

		return $order_retrieve;
	}

	/**
	 * Function builds OrderCancelRequest Document 
	 * @access public
	 * @param string $data
	 * @return string $xml
	 */
	public static function buildOrderCancelRequest($data) {
		$xml = OpenPayU::buildOpenPayURequestDocument($data, 'OrderCancelRequest');
		return $xml;
	}
	
	/**
	 * Function builds OrderStatusUpdateRequest Document 
	 * @access public
	 * @param string $data
	 * @return string $xml
	 */
	public static function buildOrderStatusUpdateRequest($data) {
		$xml = OpenPayU::buildOpenPayURequestDocument($data, 'OrderStatusUpdateRequest');
		return $xml;
	}
}

?>