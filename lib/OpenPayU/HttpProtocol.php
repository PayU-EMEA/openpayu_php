<?php
/**
 * OpenPayU
 *
 * @copyright  Copyright (c) 2012 PayU
 */
namespace OpenPayuSdk\OpenPayu;

interface OpenPayU_HttpProtocol
{
    public static function doRequest($requestType, $pathUrl, $fieldsList, $posId, $signatureKey);
}
