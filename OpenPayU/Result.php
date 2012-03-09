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

class OpenPayU_Result {
	private $status = '';
	private $error = '';
	private $success = 0;
	private $request = '';
	private $response = '';
	private $sessionId = '';
	private $message = '';
	private $countryCode = '';
	private $reqId = '';
	
	public function getStatus() {
		return $this->status;
	}

	public function setStatus($value) {
		$this->status = $value;
	}

	public function getError() {
		return $this->error;
	}

	public function setError($value) {
		$this->error = $value;
	}

	public function getSuccess() {
		return $this->success;
	}

	public function setSuccess($value) {
		$this->success = $value;
	}

	public function getRequest() {
		return $this->request;
	}

	public function setRequest($value) {
		$this->request = $value;
	}

	public function getResponse() {
		return $this->response;
	}

	public function setResponse($value) {
		$this->response = $value;
	}

	public function getSessionId() {
		return $this->sessionId;
	}

	public function setSessionId($value) {
		$this->sessionId = $value;
	}
	
	public function getMessage() {
		return $this->message;
	}

	public function setMessage($value) {
		$this->message = $value;
	}

	public function getCountryCode() {
		return $this->countryCode;
	}

	public function setCountryCode($value) {
		$this->countryCode = $value;
	}

	public function getReqId() {
		return $this->reqId;
	}

	public function setReqId($value) {
		$this->reqId = $value;
	}
	
}

?>