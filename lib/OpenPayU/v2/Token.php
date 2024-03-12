<?php
/**
 * OpenPayU Standard Library
 *
 * @copyright Copyright (c)
 * http://www.payu.com
 * http://developers.payu.com
 */

class OpenPayU_Token extends OpenPayU
{

    const TOKENS_SERVICE = 'tokens';

    /**
     * Deleting a payment token
     * @param string $token
     * @return OpenPayU_Result|null
     * @throws OpenPayU_Exception
     * @throws OpenPayU_Exception_Authorization
     * @throws OpenPayU_Exception_Configuration
     * @throws OpenPayU_Exception_Network
     * @throws OpenPayU_Exception_Request
     * @throws OpenPayU_Exception_ServerError
     * @throws OpenPayU_Exception_ServerMaintenance
     */
    public static function delete($token)
    {

        try {
            $authType = self::getAuth();
        } catch (OpenPayU_Exception $e) {
            throw new OpenPayU_Exception($e->getMessage(), $e->getCode());
        }

        if (!$authType instanceof AuthType_Oauth) {
            throw new OpenPayU_Exception_Configuration('Delete token works only with OAuth');
        }

        if (OpenPayU_Configuration::getOauthGrantType() !== OauthGrantType::TRUSTED_MERCHANT) {
            throw new OpenPayU_Exception_Configuration('Token delete request is available only for trusted_merchant');
        }

        $pathUrl = OpenPayU_Configuration::getServiceUrl() . self::TOKENS_SERVICE . '/' . $token;

        return self::verifyResponse(OpenPayU_Http::doDelete($pathUrl, $authType));
    }

    /**
     * @param array $response
     * @return OpenPayU_Result|void
     * @throws OpenPayU_Exception
     * @throws OpenPayU_Exception_Authorization
     * @throws OpenPayU_Exception_Network
     * @throws OpenPayU_Exception_Request
     * @throws OpenPayU_Exception_ServerError
     * @throws OpenPayU_Exception_ServerMaintenance
     */
    public static function verifyResponse($response)
    {
        $data = [];
        $httpStatus = $response['code'];

        $message = OpenPayU_Util::convertJsonToArray($response['response'], true);

        $data['status'] = isset($message['status']['statusCode']) ? $message['status']['statusCode'] : null;

        if (json_last_error() == JSON_ERROR_SYNTAX) {
            $data['response'] = $response['response'];
        } elseif (isset($message)) {
            $data['response'] = $message;
            unset($message['status']);
        }

        $result = self::build($data);

        if ($httpStatus == 204) {
            return $result;
        }

        OpenPayU_Http::throwHttpStatusException($httpStatus, $result);
    }
}
