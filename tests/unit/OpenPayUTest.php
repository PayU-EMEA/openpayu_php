<?php
/**
 * OpenPayU Standard Library
 *
 * @copyright Copyright (c) PayU
 * http://www.payu.com
 * http://developers.payu.com
 */

use PHPUnit\Framework\TestCase;

require_once realpath(__DIR__) . '/../TestHelper.php';

class OpenPayUTest extends TestCase
{

    const INCOMING_SIGNATURE = 'sender=145227;algorithm=SHA-256;signature=846b2de129d2200443bd72abe691ce174fcec064f89dc529d6a5d98a046cbb4d';

    protected function setUp(): void
    {
        OpenPayU_Configuration::setEnvironment('secure');
        OpenPayU_Configuration::setMerchantPosId('145227');
        OpenPayU_Configuration::setSignatureKey('13a980d4f851f3d9a1cfc792fb1f5e50');
    }

    /**
     * @test
     */
    public function shouldNotVerifySignature()
    {
        $this->expectExceptionMessage(
            "Invalid signature - 846b2de129d2200443bd72abe691ce174fcec064f89dc529d6a5d98a046cbb4d"
        );
        $this->expectException(OpenPayU_Exception_Authorization::class);
        //when
        OpenPayU::verifyDocumentSignature('TEST_FAIL', self::INCOMING_SIGNATURE);

    }

    public function badSignatureDataProvider()
    {
        return array(
            array(null),
            array(''),
            array('sender=145227;algorithm=SHA-256'),
            array('sender=145227;signature=846b2de129d2200443bd72abe691ce174fcec064f89dc529d6a5d98a046cbb4d')
        );
    }

    /**
     * @test
     * @dataProvider badSignatureDataProvider
     * @param string $signature
     *
     * @throws OpenPayU_Exception_Authorization
     */
    public function shouldEmptySignature($signature)
    {
        $this->expectExceptionMessage("Signature not found");
        $this->expectException(OpenPayU_Exception_Authorization::class);

        //when
        OpenPayU::verifyDocumentSignature('ANY DATA', $signature);

    }

    /**
     * @test
     */
    public function shouldVerifySignature()
    {
        //then
        $this->expectNotToPerformAssertions();

        //when
        OpenPayU::verifyDocumentSignature('TEST_OK', self::INCOMING_SIGNATURE);
    }

}
