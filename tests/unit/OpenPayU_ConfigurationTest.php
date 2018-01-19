<?php
/**
 * OpenPayU Standard Library
 *
 * @copyright  Copyright (c) 2011-2016 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://www.payu.com
 * http://developers.payu.com
 */

require_once realpath(dirname(__FILE__)) . '/../TestHelper.php';
require realpath(dirname(__FILE__)) . '\..\..\vendor/autoload.php';


class OpenPayU_ConfigurationTest extends PHPUnit_Framework_TestCase
{

    const PHP_SDK_VERSION = 'PHP SDK 2.2.9';
    const API_VERSION = '2.1';
    const POS_ID = 'PosId';
    const SIGNATURE_KEY = 'SignatureKey';
    const OAUTH_CLIENT_ID = 'OauthClientId';
    const OAUTH_CLIENT_SECRET = 'OauthClientSecret';


    public function testValidApiVersion()
    {
        //then
        $this->assertEquals(self::API_VERSION, OpenPayU_Configuration::getApiVersion());
    }

    public function getCorrectEnvironments()
    {
        return array(
            array('secure'),
            array('sandbox'),
            array('custom')
        );
    }

    /**
     * @dataProvider getCorrectEnvironments
     */
    public function testSetValidEnvironment($environment)
    {
        //when
        OpenPayU_Configuration::setEnvironment($environment);

        //then
        $this->assertEquals($environment, OpenPayU_Configuration::getEnvironment());
    }

    /**
     * @expectedException OpenPayU_Exception_Configuration
     * @expectedExceptionMessage environment - is not valid environment
     */
    public function testSetInvalidEnvironment()
    {
        //when
        OpenPayU_Configuration::setEnvironment('environment');
    }

    public function testSecureServiceUrl()
    {
        //when
        OpenPayU_Configuration::setEnvironment('secure');

        //then
        $this->assertEquals('https://secure.payu.com/api/v2_1/', OpenPayU_Configuration::getServiceUrl());
    }

    public function testSandboxServiceUrl()
    {
        //when
        OpenPayU_Configuration::setEnvironment('sandbox');

        //then
        $this->assertEquals('https://secure.snd.payu.com/api/v2_1/', OpenPayU_Configuration::getServiceUrl());
    }

    public function testCustomServiceUrl()
    {
        //when
        OpenPayU_Configuration::setEnvironment('custom', 'http://testdomain.com', 'testapi/', 'vTest_1/');

        //then
        $this->assertEquals('http://testdomain.com/testapi/vTest_1/', OpenPayU_Configuration::getServiceUrl());
    }

    public function testSecureOauthEndpoint()
    {
        //when
        OpenPayU_Configuration::setEnvironment('secure');

        //then
        $this->assertEquals('https://secure.payu.com/pl/standard/user/oauth/authorize', OpenPayU_Configuration::getOauthEndpoint());
    }

    public function testSandboxOauthEndpoint()
    {
        //when
        OpenPayU_Configuration::setEnvironment('sandbox');

        //then
        $this->assertEquals('https://secure.snd.payu.com/pl/standard/user/oauth/authorize', OpenPayU_Configuration::getOauthEndpoint());
    }

    public function testCustomOauthEndpointUrl()
    {
        //when
        OpenPayU_Configuration::setEnvironment('custom', 'http://testdomain.com', 'testapi/', 'vTest_1/');

        //then
        $this->assertEquals('http://testdomain.com/pl/standard/user/oauth/authorize', OpenPayU_Configuration::getOauthEndpoint());
    }


    public function testSetValidHashAlgorithm()
    {
        //when
        OpenPayU_Configuration::setHashAlgorithm('SHA');

        //then
        $this->assertEquals('SHA', OpenPayU_Configuration::getHashAlgorithm());
    }

    /**
     * @expectedException OpenPayU_Exception_Configuration
     * @expectedExceptionMessage Hash algorithm "MD5"" is not available
     */
    public function testSetInvalidHashAlgorithm()
    {
        //when
        OpenPayU_Configuration::setHashAlgorithm('MD5');
    }

    public function testMerchantPosId()
    {
        //when
        OpenPayU_Configuration::setMerchantPosId(self::POS_ID);

        //then
        $this->assertEquals(self::POS_ID, OpenPayU_Configuration::getMerchantPosId());
    }

    public function testSignatureKey()
    {
        //when
        OpenPayU_Configuration::setSignatureKey(self::SIGNATURE_KEY);

        //then
        $this->assertEquals(self::SIGNATURE_KEY, OpenPayU_Configuration::getSignatureKey());
    }

    public function testOauthClientId()
    {
        //when
        OpenPayU_Configuration::setOauthClientId(self::OAUTH_CLIENT_ID);

        //then
        $this->assertEquals(self::OAUTH_CLIENT_ID, OpenPayU_Configuration::getOauthClientId());
    }

    public function testOauthClientSecret()
    {
        //when
        OpenPayU_Configuration::setOauthClientSecret(self::OAUTH_CLIENT_SECRET);

        //then
        $this->assertEquals(self::OAUTH_CLIENT_SECRET, OpenPayU_Configuration::getOauthClientSecret());
    }

    /**
     * @expectedException OpenPayU_Exception_Configuration
     * @expectedExceptionMessage Oauth token cache class is not instance of OauthCacheInterface
     */
    public function testNotOautchCacheInterfaceOfOauthTokenCache()
    {
        //when
        OpenPayU_Configuration::setOauthTokenCache(new stdClass());
    }

    public function testOautchCacheInterfaceOfOauthTokenCache()
    {
        //given
        $cacheFile = new OauthCacheFile('./');

        //when
        OpenPayU_Configuration::setOauthTokenCache($cacheFile);

        //then
        $this->assertInstanceOf('OauthCacheFile', OpenPayU_Configuration::getOauthTokenCache());
    }

    public function testFullSenderName()
    {
        //then
        $this->assertEquals('Generic@' . self::PHP_SDK_VERSION, OpenPayU_Configuration::getFullSenderName());
    }


    /**
     * @test
     */
    public function shouldReturnValidSenderFullNameWhenSenderIsGiven()
    {
        //given
        OpenPayU_Configuration::setSender("Test Data");

        //then
        $this->assertEquals('Test Data@' . self::PHP_SDK_VERSION, OpenPayU_Configuration::getFullSenderName());
    }

    /**
     * @test
     */
    public function shouldReturnValidSDKVersionWhenComposerFileIsGiven()
    {
        //then
        $this->assertEquals(self::PHP_SDK_VERSION, OpenPayU_Configuration::getSdkVersion());
    }

    /**
     * @test
     */
    public function shouldDefaultSDKVersionAndFromJsonIsTheSame()
    {
        //then
        $this->assertEquals(OpenPayU_Configuration::DEFAULT_SDK_VERSION, OpenPayU_Configuration::getSdkVersion());
    }


}
