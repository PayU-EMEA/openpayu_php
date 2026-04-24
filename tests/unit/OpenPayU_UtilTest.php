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

class OpenPayU_UtilTest extends TestCase
{

    private const MERCHANT_POS_ID = 'MerchantPosId';
    private const SIGNATURE_KEY = 'SignatureKey';

    public function verifyGenerateSignData(): array
    {
        return [
            [
                'SHA-256',
                'sender=MerchantPosId;algorithm=SHA-256;signature=e4c8315c9ab2e097ef1221f3fbd1e761405d961760e538fb2e0055d3d90b5e35',
            ],
            [
                'SHA-384',
                'sender=MerchantPosId;algorithm=SHA-384;signature=ef1f0258e004b4ade604115289a374a0cb54899ff48fc65ca6a23d5dfc6332dfd5a3a63de88b65743cff5ecf8db4fda2',
            ],
            [
                'SHA-512',
                'sender=MerchantPosId;algorithm=SHA-512;signature=3ebb7cd2b9f60dacda25835f323fb15adda3eb729da71fd61c9bfd41b97c6eeea47949cee0aaf5fa1a4d8fa9c065369aaeffd34afc4f376bd11dd8bfdd3c564d',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider verifyGenerateSignData
     */
    public function shouldGenerateSignData(string $algorithm, string $expected): void
    {
        //when
        $signature = OpenPayU_Util::generateSignData(['test' => 'OpenPayUData'],
            $algorithm,
            self::MERCHANT_POS_ID,
            self::SIGNATURE_KEY);

        //then
        $this->assertEquals($expected, $signature);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenSignatureKeyIsEmptyInGenerateSignData(): void
    {
        //then
        $this->expectException(OpenPayU_Exception_Configuration::class);
        $this->expectExceptionMessage('Merchant Signature Key should not be null or empty.');

        //when
        OpenPayU_Util::generateSignData(['test' => 'OpenPayUData'], 'SHA-256', self::MERCHANT_POS_ID, '');
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenMerchantPosIdIsEmptyInGenerateSignData(): void
    {
        //then
        $this->expectException(OpenPayU_Exception_Configuration::class);
        $this->expectExceptionMessage('MerchantPosId should not be null or empty.');

        //when
        OpenPayU_Util::generateSignData(['test' => 'OpenPayUData'], 'SHA-256', '', self::SIGNATURE_KEY);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionForUnknownAlgorithmInGenerateSignData(): void
    {
        //then
        $this->expectException(OpenPayU_Exception::class);
        $this->expectExceptionMessage('Unknown algorithm.');

        //when
        OpenPayU_Util::generateSignData(['test' => 'OpenPayUData'],
            'UNKNOWN-ALG',
            self::MERCHANT_POS_ID,
            self::SIGNATURE_KEY);
    }

    public function verifySignatureDataProvider(): array
    {
        return [
            ['MD5', '8375034eb737d520c829fad4026a38aa', true],
            ['SHA-1', '52bb16149d1a5ccc8ac05f8e435c30d82efd5364', true],
            ['SHA256', '8b2fd55b48f150347df56ce18d787335f32ced1d67f214016476f7c0a8f09981', true],
            [
                'SHA384',
                '219a7029f09ae02ee406a623becca9691557cbf503fe58485cb5a08bf1a2a72f745fbc3e8fb1fa80f824b4f890ad3b35',
                true,
            ],
            [
                'SHA512',
                '4f658844a6bc4ed3c4320ecae0dc5e414c4fa2ce07563914cd5069f277ddead37be5ca6dc35099ff9d8c184bb561bb740f626fdb5162d8f7160dba8ec4ad1d5d',
                true,
            ],
            ['SHA256', 'incorrectSignature', false],
        ];
    }

    /**
     * @test
     * @dataProvider verifySignatureDataProvider
     */
    public function shouldVerifySignature(string $algorithm, string $signature, bool $result): void
    {
        //when
        $valid = OpenPayU_Util::verifySignature('OpenPayUData', $signature, self::SIGNATURE_KEY, $algorithm);

        //then
        $this->assertEquals($valid, $result);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenSignatureKeyIsEmptyInVerifySignature(): void
    {
        //then
        $this->expectException(OpenPayU_Exception_Configuration::class);
        $this->expectExceptionMessage('Merchant Signature Key should not be null or empty.');

        //when
        OpenPayU_Util::verifySignature('OpenPayUData', 'someSignature', '');
    }

    /**
     * @test
     */
    public function shouldThrowExceptionForUnknownAlgorithmInVerifySignature(): void
    {
        //then
        $this->expectException(OpenPayU_Exception::class);
        $this->expectExceptionMessage('Unknown algorithm.');

        //when
        OpenPayU_Util::verifySignature('OpenPayUData', 'someSignature', self::SIGNATURE_KEY, 'UNKNOWN-ALG');
    }

    public function verifyParseSignatureDataProvider(): array
    {
        return [
            ['', null],
            [null, null],
            ['TEST', null],
            [
                'sender=MerchantPosId;algorithm=SHA-256;signature=e4c8315c9ab2e097ef1221f3fbd1e761405d961760e538fb2e0055d3d90b5e35',
                [
                    'sender' => 'MerchantPosId',
                    'algorithm' => 'SHA-256',
                    'signature' => 'e4c8315c9ab2e097ef1221f3fbd1e761405d961760e538fb2e0055d3d90b5e35',
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider verifyParseSignatureDataProvider
     */
    public function shouldParseSignature(?string $signature, ?array $result): void
    {
        //when
        $parsedSignature = OpenPayU_Util::parseSignature($signature);

        //then
        $this->assertEquals($parsedSignature, $result);
    }


    /**
     * @test
     */

    public function shouldSetSenderProperty(): void
    {
        //when
        $result = json_decode(OpenPayU_Util::buildJsonFromArray([]));

        //then
        $this->assertEquals($result->properties[0]->name, 'sender');
        $this->assertEquals($result->properties[0]->value, OpenPayU_Configuration::getFullSenderName());
    }

}
