<?php
/**
 * OpenPayU Standard Library
 *
 * @copyright Copyright (c) PayU
 * http://www.payu.com
 * http://developers.payu.com
 */

class OpenPayU_Result
{
    private $status = '';
    private $error = '';
    private $success = 0;
    private $request = '';
    /** @var object */
    private $response;
    private $sessionId = '';
    private $message = '';
    private $countryCode = '';
    private $reqId = '';

    /**
     * @access public
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @access public
     * @param $value
     */
    public function setStatus($value)
    {
        $this->status = $value;
    }

    /**
     * @access public
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @access public
     * @param $value
     */
    public function setError($value)
    {
        $this->error = $value;
    }

    /**
     * @access public
     * @return int
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @access public
     * @param $value
     */
    public function setSuccess($value)
    {
        $this->success = $value;
    }

    /**
     * @access public
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @access public
     * @param $value
     */
    public function setRequest($value)
    {
        $this->request = $value;
    }

    /**
     * @access public
     * @return object
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @access public
     * @param $value
     */
    public function setResponse($value)
    {
        $this->response = $value;
    }

    /**
     * @access public
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @access public
     * @param $value
     */
    public function setSessionId($value)
    {
        $this->sessionId = $value;
    }

    /**
     * @access public
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @access public
     * @param $value
     */
    public function setMessage($value)
    {
        $this->message = $value;
    }

    /**
     * @access public
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @access public
     * @param $value
     */
    public function setCountryCode($value)
    {
        $this->countryCode = $value;
    }

    /**
     * @access public
     * @return string
     */
    public function getReqId()
    {
        return $this->reqId;
    }

    /**
     * @access public
     * @param $value
     */
    public function setReqId($value)
    {
        $this->reqId = $value;
    }

    public function init($attributes)
    {
        $attributes = OpenPayU_Util::parseArrayToObject($attributes);

        if (!empty($attributes)) {
            foreach ($attributes as $name => $value) {
                $this->set($name, $value);
            }
        }
    }

    public function set($name, $value)
    {
        $this->{$name} = $value;
    }

    public function __get($name)
    {
        if (isset($this->{$name}))
            return $this->name;

        return null;
    }

    public function __call($methodName, $args) {
        if (preg_match('~^(set|get)([A-Z])(.*)$~', $methodName, $matches)) {
            $property = strtolower($matches[2]) . $matches[3];
            if (!property_exists($this, $property)) {
                throw new Exception('Property ' . $property . ' not exists');
            }
            switch($matches[1]) {
                case 'get':
                    $this->checkArguments($args, 0, 0, $methodName);
                    return $this->get($property);
                case 'default':
                    throw new Exception('Method ' . $methodName . ' not exists');
            }
        }
    }
}