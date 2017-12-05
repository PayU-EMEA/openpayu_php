<?php

namespace PayU\OpenPayU\AuthType;

use PayU\OpenPayU\Exception\OpenPayUExceptionConfiguration;

class Basic implements AuthType
{

    /**
     * @var string
     */
    private $authBasicToken;

    public function __construct($posId, $signatureKey)
    {
        if (empty($posId)) {
            throw new OpenPayUExceptionConfiguration('PosId is empty');
        }

        if (empty($signatureKey)) {
            throw new OpenPayUExceptionConfiguration('SignatureKey is empty');
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