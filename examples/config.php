<?php
/**
 * OpenPayU
 *
 * @copyright  Copyright (c) 2013 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 *
 * http://www.payu.com
 * http://openpayu.com
 * http://twitter.com/openpayu
 *
 */

OpenPayU_Configuration::setEnvironment('custom','http://10.205.49.116');
OpenPayU_Configuration::setMerchantPosId('45654');
OpenPayU_Configuration::setPosAuthKey('sdgxjX5');
OpenPayU_Configuration::setClientId('45654');
OpenPayU_Configuration::setClientSecret('65fe8d2f60e2bc37ddb9ad7ba2f681fa');
OpenPayU_Configuration::setSignatureKey('981852826b1f62fb24e1771e878fb42d');
//OpenPayU_Configuration::$serviceUrl = "http://10.205.49.116/api/v2/";
OpenPayU_Configuration::setApiVersion(2);
OpenPayU_Configuration::setDataFormat('xml');
OpenPayU_Configuration::setHashAlgorithm('MD5');



$country = 'api/';
$service = 'v2/';


/* path for example files*/
$dir = explode(basename(dirname(__FILE__)) . '/', $_SERVER['SCRIPT_NAME']);
$directory = $dir[0] . basename(dirname(__FILE__));
$url = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $dir[0];

define('HOME_DIR', $url);
define('EXAMPLES_DIR', HOME_DIR . 'examples/');