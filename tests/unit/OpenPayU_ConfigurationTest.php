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

require_once realpath(dirname(__FILE__)) . '/../TestHelper.php';

class OpenPayU_ConfigurationTest extends PHPUnit_Framework_TestCase
{

    public function testSetValidEnvironment()
    {
        OpenPayU_Configuration::setEnvironment('sandbox');
        $this->assertEquals('sandbox', OpenPayU_Configuration::getEnvironment());
    }

    /**
     * @expectedException OpenPayU_Exception_Configuration
     * @expectedExceptionMessage environment - is not valid environment
     */
    public function testSetInvalidEnvironment()
    {
        OpenPayU_Configuration::setEnvironment('environment');
    }

    public function testSetValidDataFormat()
    {
        OpenPayU_Configuration::setDataFormat('json');
        $this->assertEquals('json', OpenPayU_Configuration::getDataFormat());
    }

    /**
     * @expectedException OpenPayU_Exception_Configuration
     * @expectedExceptionMessage ".txt" - is not valid data format
     */
    public function testSetInvalidDataFormat()
    {
        OpenPayU_Configuration::setDataFormat('.txt');
    }

    public function testSetValidApiVersion()
    {
        OpenPayU_Configuration::setApiVersion(2);
        $this->assertEquals(2, OpenPayU_Configuration::getApiVersion());
    }

    /**
     * @expectedException OpenPayU_Exception_Configuration
     * @expectedExceptionMessage Invalid API version
     */
    public function testSetInvalidApiVersion()
    {
        OpenPayU_Configuration::setApiVersion(null);
    }

    public function testSetValidHashAlgorithm()
    {
        OpenPayU_Configuration::setHashAlgorithm('MD5');
        $this->assertEquals('MD5', OpenPayU_Configuration::getHashAlgorithm());
    }

    /**
     * @expectedException OpenPayU_Exception_Configuration
     * @expectedExceptionMessage hash - is not available
     */
    public function testSetInvalidHashAlgorithm()
    {
        OpenPayU_Configuration::setHashAlgorithm('hash');
    }

    public function testMerchantPosId()
    {
        OpenPayU_Configuration::setMerchantPosId('PosId');
        $this->assertEquals('PosId', OpenPayU_Configuration::getMerchantPosId());
    }

    public function testPosAuthKey()
    {
        OpenPayU_Configuration::setPosAuthKey('PosAuthKey');
        $this->assertEquals('PosAuthKey', OpenPayU_Configuration::getPosAuthKey());
    }

    public function testClientId()
    {
        OpenPayU_Configuration::setClientId('ClientId');
        $this->assertEquals('ClientId', OpenPayU_Configuration::getClientId());
    }

    public function testClientSecret()
    {
        OpenPayU_Configuration::setClientSecret('ClientSecret');
        $this->assertEquals('ClientSecret', OpenPayU_Configuration::getClientSecret());
    }

    public function testSignatureKey()
    {
        OpenPayU_Configuration::setSignatureKey('SignatureKey');
        $this->assertEquals('SignatureKey', OpenPayU_Configuration::getSignatureKey());
    }

    public function testServiceUrl()
    {
        OpenPayU_Configuration::setApiVersion(2);
        OpenPayU_Configuration::setEnvironment('secure');
        $this->assertEquals('https://secure.payu.com/api/v2/', OpenPayU_Configuration::getServiceUrl());
    }
}
