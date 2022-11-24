<?php
/**
 * OpenPayU Standard Library
 *
 * @copyright Copyright (c) PayU
 * http://www.payu.com
 * http://developers.payu.com
 */

class OpenPayU_Shop extends OpenPayU
{
    const SHOPS_SERVICE = 'shops';

    /**
     * Retrieving shop data
     * @param string $publicShopId
     * @return PayuShop
     * @throws OpenPayU_Exception
     * @throws OpenPayU_Exception_Configuration
     */
    public static function get($publicShopId)
    {
        try {
            $authType = self::getAuth();
        } catch (OpenPayU_Exception $e) {
            throw new OpenPayU_Exception($e->getMessage(), $e->getCode());
        }

        if (!$authType instanceof AuthType_Oauth) {
            throw new OpenPayU_Exception_Configuration('Get shop works only with OAuth');
        }

        $pathUrl = OpenPayU_Configuration::getServiceUrl() . self::SHOPS_SERVICE . '/' . $publicShopId;

        return self::verifyResponse(OpenPayU_Http::doGet($pathUrl, $authType));
    }

    /**
     * @param array $response
     * @return PayuShop
     * @throws OpenPayU_Exception
     */
    public static function verifyResponse($response)
    {
        $httpStatus = $response['code'];

        if ($httpStatus == 500) {
            $result = (new ResultError())
                ->setErrorDescription($response['response']);
            OpenPayU_Http::throwErrorHttpStatusException($httpStatus, $result);
        }

        $message = json_decode($response['response'], true);

        if (json_last_error() === JSON_ERROR_SYNTAX) {
            throw new OpenPayU_Exception_ServerError('Incorrect json response. Response: [' . $response['response'] . ']');
        }

        if ($httpStatus == 200) {
            return (new PayuShop())
                ->setShopId($message['shopId'])
                ->setName($message['name'])
                ->setCurrencyCode($message['currencyCode'])
                ->setBalance(
                    (new PayuShopBalance())
                        ->setCurrencyCode($message['balance']['currencyCode'])
                        ->setTotal($message['balance']['total'])
                        ->setAvailable($message['balance']['available'])
                );
        }

        $result = (new ResultError())
            ->setError($message['error'])
            ->setErrorDescription($message['error_description']);

        OpenPayU_Http::throwErrorHttpStatusException($httpStatus, $result);
    }
}
