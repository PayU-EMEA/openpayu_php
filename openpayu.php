<?php

/*
	ver. 0.1.9.1
	OpenPayU Standard Library

	@copyright  Copyright (c) 2011-2012 PayU
	@license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
	http://www.payu.com
	http://openpayu.com
	http://twitter.com/openpayu
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