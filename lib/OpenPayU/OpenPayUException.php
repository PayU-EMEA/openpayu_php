<?php
/**
 * OpenPayU Standard Library
 *
 * @copyright  Copyright (c) 2011-2016 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://www.payu.com
 * http://developers.payu.com
 */

class OpenPayU_Exception extends \Exception
{

}

class OpenPayU_Exception_Request extends OpenPayU_Exception
{
    /** @var stdClass|null */
    private $originalResponseMessage;

    public function __construct($originalResponseMessage, $message = "", $code = 0, $previous = null)
    {
        $this->originalResponseMessage = $originalResponseMessage;

        parent::__construct($message, $code, $previous);
    }

    /** @return null|stdClass */
    public function getOriginalResponse()
    {
        return $this->originalResponseMessage;
    }
}

class OpenPayU_Exception_Configuration extends OpenPayU_Exception
{

}

class OpenPayU_Exception_Network extends OpenPayU_Exception
{

}

class OpenPayU_Exception_ServerError extends OpenPayU_Exception
{

}

class OpenPayU_Exception_ServerMaintenance extends OpenPayU_Exception
{

}

class OpenPayU_Exception_Authorization extends OpenPayU_Exception
{

}