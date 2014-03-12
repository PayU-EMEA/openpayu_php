<?php 

/**
 *	OrderNotifyRequest message processing and retrieve order details.
 *
 *	@copyright  Copyright (c) 2011-2012, PayU
 *	@license    http://opensource.org/licenses/GPL-3.0  Open Software License (GPL 3.0)		
 */

require_once realpath(dirname(__FILE__)) . '/../../../lib/openpayu.php';
require_once realpath(dirname(__FILE__)) . '/../../config.php';

// helper function, just to save some output to file
function write_to_file($file, $data) {
	file_put_contents($file, $data, FILE_APPEND);
};

try {	
	// Processing notification received from payu service.
	// Variable $notification contains array with OrderNotificationRequest message.
	$result = OpenPayU_Order::consumeMessage($_POST['DOCUMENT']);
	if ($result->getMessage() == 'OrderNotifyRequest') {
		// Second step, request details of order data.
		// Variable $response contain array with OrderRetrieveResponse message.
		$result = OpenPayU_Order::retrieve($result->getSessionId());
		write_to_file("debug.txt", "order details: \n\n " . serialize($result->getResponse()) . "\n\n");		
	}		
} catch (Exception $e) {
	write_to_file("debug.txt", $e->getMessage());
	write_to_file("debug.txt", OpenPayU_Order::printOutputConsole());
}