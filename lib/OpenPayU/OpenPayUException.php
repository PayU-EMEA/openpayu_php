<?php
/**
 * OpenPayU Exception
 *
 * @copyright  Copyright (c) 2013 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 *
 * http://www.payu.com
 * http://openpayu.com
 * http://twitter.com/openpayu
 *
 */

namespace OpenPayuSdk\OpenPayu;

class OpenPayU_Exception extends \Exception
{

}

class OpenPayU_Exception_Configuration extends  OpenPayU_Exception
{

}

class OpenPayU_Exception_Network extends  OpenPayU_Exception
{

}

class OpenPayU_Exception_ServerError extends  OpenPayU_Exception
{

}

class OpenPayU_Exception_ServerMaintenance extends  OpenPayU_Exception
{

}

class OpenPayU_Exception_Authorization extends  OpenPayU_Exception
{

}