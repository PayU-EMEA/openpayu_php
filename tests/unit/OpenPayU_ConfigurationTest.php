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

    const PHP_SDK_VERSION = 'PHP SDK 2.1.6';

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

    public function testSetValidHashAlgorithm()
    {
        OpenPayU_Configuration::setHashAlgorithm('SHA');
        $this->assertEquals('SHA', OpenPayU_Configuration::getHashAlgorithm());
    }

    /**
     * @expectedException OpenPayU_Exception_Configuration
     * @expectedExceptionMessage Hash algorithm "hash"" is not available
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
        $OpenPayU_ConfigurationMock->expects($this->any())->method('getComposerFilePath')
            ->will($this->returnValue('mock.json'));
        //when
        $sdkVersion = OpenPayU_Configuration::getSdkVersion();
        //then
        $this->assertEquals(self::PHP_SDK_VERSION,$sdkVersion);
    }

}
