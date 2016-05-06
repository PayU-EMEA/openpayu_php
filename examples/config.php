<?php

/**
 * OpenPayU Examples
 *
 * @copyright  Copyright (c) 2011-2016 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://www.payu.com
 * http://developers.payu.com
 */

//set Environment
OpenPayU_Configuration::setEnvironment('secure');

//set POS ID and Second MD5 Key (from merchant admin panel)
OpenPayU_Configuration::setMerchantPosId('145227'); // POS ID (pos_id) / OAuth protocol - client_id
OpenPayU_Configuration::setSignatureKey('13a980d4f851f3d9a1cfc792fb1f5e50'); // Second key (MD5)

//set Oauth Client Id and Oauth Client Secret (from merchant admin panel)
OpenPayU_Configuration::setOauthClientId('145227'); // OAuth protocol - client_id / POS ID (pos_id)
OpenPayU_Configuration::setOauthClientSecret('12f071174cb7eb79d4aac5bc2f07563f'); // Key (MD5) / OAuth protocol - client_secret 

/* path for example files*/
$dir = explode(basename(dirname(__FILE__)) . '/', $_SERVER['SCRIPT_NAME']);
$directory = $dir[0] . basename(dirname(__FILE__));
$url = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $dir[0];

define('HOME_DIR', $url);
define('EXAMPLES_DIR', HOME_DIR . 'examples/');