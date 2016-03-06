<?php

class OpenPayU_Oauth
{

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @return OauthResultClientCredentials
     * @throws OpenPayU_Exception_ServerError
     */
    public static function getAccessToken($clientId = null, $clientSecret = null)
    {

        $authType = new AuthType_TokenRequest();

        $oauthUrl = OpenPayU_Configuration::getOauthEndpoint();
        $data = array(
            'grant_type' => OauthGrantType::CLIENT_CREDENTIAL,
            'client_id' => $clientId ? $clientId : OpenPayU_Configuration::getOauthClientId(),
            'client_secret' => $clientSecret ? $clientSecret : OpenPayU_Configuration::getOauthClientSecret()
        );

        $response = self::parseResponse(OpenPayU_Http::doPost($oauthUrl, http_build_query($data), $authType));

        return $response;
    }

    /**
     * Parse response from PayU
     *
     * @param array $response
     * @return OauthResultClientCredentials
     * @throws OpenPayU_Exception
     * @throws OpenPayU_Exception_Authorization
     * @throws OpenPayU_Exception_Network
     * @throws OpenPayU_Exception_ServerError
     * @throws OpenPayU_Exception_ServerMaintenance
     */
    private static function parseResponse($response)
    {
        $httpStatus = $response['code'];

        if ($httpStatus == 500) {
            $result = new ResultError();
            $result->setErrorDescription($response['response']);
            OpenPayU_Http::throwErrorHttpStatusException($httpStatus, $result);
        }

        $message = OpenPayU_Util::convertJsonToArray($response['response'], true);

        if (json_last_error() == JSON_ERROR_SYNTAX) {
            throw new OpenPayU_Exception_ServerError('Incorrect json response. Response: [' . $response['response'] . ']');
        }

        if ($httpStatus == 200) {
            $result = new OauthResultClientCredentials();
            $result->setAccessToken($message['access_token'])
                ->setTokenType($message['token_type'])
                ->setExpiresIn($message['expires_in'])
                ->setGrantType($message['grant_type']);

            return $result;
        }

        $result = new ResultError();
        $result->setError($message['error'])
            ->setErrorDescription($message['error_description']);

        OpenPayU_Http::throwErrorHttpStatusException($httpStatus, $result);

    }


}