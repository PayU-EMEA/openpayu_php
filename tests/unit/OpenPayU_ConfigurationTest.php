<?php
/**
 * OpenPayU
 *
 * @copyright  Copyright (c) 2014 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 *
 * http://www.payu.com
 * http://developers.payu.com
 * http://twitter.com/openpayu
 *
 */

require_once realpath(dirname(__FILE__)) . '/../TestHelper.php';
require realpath(dirname(__FILE__)).'\..\..\vendor/autoload.php';


class OpenPayU_ConfigurationTest extends PHPUnit_Framework_TestCase
{

    const PHP_SDK_VERSION = 'PHP SDK 2.1.0';

    public function testSetValidEnvironment()
    {
        OpenPayU_Configuration::setEnvironment('secure');
        $this->assertEquals('secure', OpenPayU_Configuration::getEnvironment());
    }

    /**
     * @expectedException OpenPayU_Exception_Configuration
     * @expectedExceptionMessage environment - is not valid environment
     */
    public function testSetInvalidEnvironment()
    {
        OpenPayU_Configuration::setEnvironment('environment');
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
        OpenPayU_Configuration::setEnvironment('secure');
        $this->assertEquals('https://secure.payu.com/api/v2_1/', OpenPayU_Configuration::getServiceUrl());
    }

    public function testFullSenderName(){
        $this->assertEquals('Generic@'.self::PHP_SDK_VERSION, OpenPayU_Configuration::getFullSenderName());
    }

    /**
     * @test
     */
    public function shouldReturnValidSenderFullNameWhenSenderIsGiven(){
        OpenPayU_Configuration::setSender("Test Data");
        $this->assertEquals('Test Data@' . self::PHP_SDK_VERSION, OpenPayU_Configuration::getFullSenderName());
    }

    /**
     * @test
     */
    public function shouldReturnValidSDKVersionWhenComposerFileIsGiven(){
        //when
        $sdkVersion = OpenPayU_Configuration::getSdkVersion();
        //then
        $this->assertEquals(self::PHP_SDK_VERSION,$sdkVersion);
    }

    /**
     * @test
     */
    public function shouldReturnDefgaultSDKVersionWhenComposerFileIsNotGiven(){
        //given
        $OpenPayU_ConfigurationMock = $this->getMock('OpenPayU_Configuration');
        $OpenPayU_ConfigurationMock->staticExpects($this->any())->method('getComposerFilePath')
            ->will($this->returnValue('mock.json'));
        //when
        $sdkVersion = OpenPayU_Configuration::getSdkVersion();
        //then
        $this->assertEquals(self::PHP_SDK_VERSION,$sdkVersion);
    }

}
