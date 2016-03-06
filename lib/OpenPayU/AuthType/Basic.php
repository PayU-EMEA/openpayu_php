<?php

class AuthType_Basic implements AuthType
{

    /**
     * @var string
     */
    private $posId;
    /**
     * @var string
     */
    private $signatureKey;

    public function __construct($posId, $signatureKey)
    {
        if (empty($posId)) {
            throw new OpenPayU_Exception_Configuration('PosId is empty');
        }

        if (empty($signatureKey)) {
            throw new OpenPayU_Exception_Configuration('SignatureKey is empty');
        }

        $this->posId = $posId;
        $this->signatureKey = $signatureKey;
    }

    public function getHeaders()
    {
        return array(
            'Content-Type: application/json',
            'Accept: application/json'
        );
    }

    public function isAuthBasic()
    {
        return true;
    }

    public function getAuthBasicUserAndPassword()
    {
        return $this->posId . ":" . $this->signatureKey;
    }

}