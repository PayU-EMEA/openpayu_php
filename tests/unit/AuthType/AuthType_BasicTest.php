<?php
/**
 * OpenPayU Standard Library
 *
 * @copyright  Copyright (c) 2011-2016 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://www.payu.com
 * http://developers.payu.com
 */

require_once realpath(dirname(__FILE__)) . '/../../TestHelper.php';

class AuthType_BasicTest extends PHPUnit_Framework_TestCase
{
    const POS_ID = 'PosId';
    const SIGNATURE_KEY = 'SignatureKey';

    private $expectedHeaders;

    /**
     * @test
     * @expectedException OpenPayU_Exception_Configuration
     * @expectedExceptionMessage PosId is empty
     */
    public function shouldExceptionWhenEmptyPosId()
    {
        //when
        new AuthType_Basic(null, null);
    }

    /**
     * @test
     * @expectedException OpenPayU_Exception_Configuration
     * @expectedExceptionMessage SignatureKey is empty
     */
    public function shouldExceptionWhenEmptySignatureId()
    {
        //when
        new AuthType_Basic(self::POS_ID, null);
    }

    /**
     * @test
     */
    public function shouldGetCorrectHeaders()
    {
        //given
        $this->expectedHeaders = array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic UG9zSWQ6U2lnbmF0dXJlS2V5'
        );

        //when
        $authBasic = new AuthType_Basic(self::POS_ID, self::SIGNATURE_KEY);

        //then
        $this->assertEquals($this->expectedHeaders, $authBasic->getHeaders());
    }


}
