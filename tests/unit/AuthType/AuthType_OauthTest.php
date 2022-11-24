<?php

/**
 * OpenPayU Standard Library
 *
 * @copyright Copyright (c) PayU
 * http://www.payu.com
 * http://developers.payu.com
 */

require_once realpath(dirname(__FILE__)) . '/../../TestHelper.php';

class AuthType_OauthTest extends PHPUnit_Framework_TestCase
{

    const CLIENT_ID = 'ClientId';

    /**
     * @test
     * @expectedException OpenPayU_Exception_Configuration
     * @expectedExceptionMessage ClientId is empty
     */
    public function shouldExceptionWhenEmptyClientId()
    {
        //when
        new AuthType_Oauth(null, null);
    }

    /**
     * @test
     * @expectedException OpenPayU_Exception_Configuration
     * @expectedExceptionMessage ClientSecret is empty
     */
    public function shouldExceptionWhenEmptyClientSecret()
    {
        //when
        new AuthType_Oauth(self::CLIENT_ID, null);
    }

}
