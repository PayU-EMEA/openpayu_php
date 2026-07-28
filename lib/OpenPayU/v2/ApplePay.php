<?php
/**
 * OpenPayU Standard Library
 *
 * @copyright Copyright (c) PayU
 * http://www.payu.com
 * http://developers.payu.com
 */

class OpenPayU_ApplePay extends OpenPayU
{
    private const APPLE_PAY_SERVICE = 'applepay';

    /**
     * @throws OpenPayU_Exception
     * @throws OpenPayU_Exception_Configuration
     */
    public static function createSession(string $domain, string $displayName): OpenPayU_Result
    {
        try {
            $authType = self::getAuth();
        } catch (OpenPayU_Exception $e) {
            throw new OpenPayU_Exception($e->getMessage(), $e->getCode());
        }

        if (!$authType instanceof AuthType_Oauth) {
            throw new OpenPayU_Exception_Configuration('Apple Pay session works only with OAuth');
        }

        $url = OpenPayU_Configuration::getServiceUrl() . self::APPLE_PAY_SERVICE . '/session';
        $data = OpenPayU_Util::buildJsonFromArray(['domain' => $domain, 'displayName' => $displayName]);

        return self::verifyResponse(OpenPayU_Http::doPost($url, $data, $authType));
    }

    /**
     * @throws OpenPayU_Exception
     */
    public static function verifyResponse(array $response): OpenPayU_Result
    {

        $data = [];
        $httpStatus = $response['code'];
        $message = OpenPayU_Util::convertJsonToArray($response['response'], true);

        if (json_last_error() === JSON_ERROR_SYNTAX) {
            $data['response'] = $response['response'];
        } elseif (isset($message)) {
            $data['response'] = $message;
        }

        $result = self::build($data);

        if ($httpStatus === 200) {
            $result->setStatus('SUCCESS');
            return $result;
        }

        OpenPayU_Http::throwHttpStatusException($httpStatus, $result);
    }
}
