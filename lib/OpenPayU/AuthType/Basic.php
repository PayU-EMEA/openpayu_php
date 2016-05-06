<?php

class AuthType_Basic implements AuthType
{

    /**
     * @var string
     */
    private $authBasicToken;

    public function __construct($posId, $signatureKey)
    {
        if (empty($posId)) {
            throw new OpenPayU_Exception_Configuration('PosId is empty');
        }

        if (empty($signatureKey)) {
            throw new OpenPayU_Exception_Configuration('SignatureKey is empty');
        }

        $this->authBasicToken = base64_encode($posId . ':' . $signatureKey);
    }

    public function getHeaders()
    {
        return array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic ' . $this->authBasicToken
        );
    }

}