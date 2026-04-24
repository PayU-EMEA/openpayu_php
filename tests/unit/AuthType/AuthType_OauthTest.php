<?php

/**
 * OpenPayU Standard Library
 *
 * @copyright Copyright (c) PayU
 * http://www.payu.com
 * http://developers.payu.com
 */

use PHPUnit\Framework\TestCase;

require_once realpath(__DIR__) . '/../../TestHelper.php';

class AuthType_OauthTest extends TestCase
{

    const CLIENT_ID = 'ClientId';

    /**
     * @test
     */
    public function shouldExceptionWhenEmptyClientId()
    {
        $this->expectExceptionMessage("ClientId is empty");
        $this->expectException(OpenPayU_Exception_Configuration::class);
        //when
        new AuthType_Oauth(null, null);
    }

    /**
     * @test
     */
    public function shouldExceptionWhenEmptyClientSecret()
    {
        $this->expectExceptionMessage("ClientSecret is empty");
        $this->expectException(OpenPayU_Exception_Configuration::class);
        //when
        new AuthType_Oauth(self::CLIENT_ID, null);
    }

}
