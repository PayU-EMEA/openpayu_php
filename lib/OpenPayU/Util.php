<?php

/**
 * OpenPayU Standard Library
 *
 * @copyright Copyright (c) PayU
 * http://www.payu.com
 * http://developers.payu.com
 */
class OpenPayU_Util
{
    private const ALGORITHMS_TO_HASH = [
        'SHA' => 'SHA256',
        'SHA-1' => 'SHA1',
        'SHA-256' => 'SHA256',
        'SHA-384' => 'SHA384',
        'SHA-512' => 'SHA512',
    ];

    /**
     * Function generate sign data
     * @throws OpenPayU_Exception_Configuration
     * @throws OpenPayU_Exception
     */
    public static function generateSignData(array $data,
        string $algorithm = 'SHA-256',
        string $merchantPosId = '',
        string $signatureKey = ''): string
    {
        if (empty($signatureKey)) {
            throw new OpenPayU_Exception_Configuration('Merchant Signature Key should not be null or empty.');
        }

        if (empty($merchantPosId)) {
            throw new OpenPayU_Exception_Configuration('MerchantPosId should not be null or empty.');
        }

        $hashAlgorithm = strtoupper(self::toHashAlgorithm($algorithm));

        if ( !in_array($hashAlgorithm, ['SHA256', 'SHA384', 'SHA512'], true)) {
            throw new OpenPayU_Exception('Unknown algorithm.');
        }

        $contentForSign = '';
        ksort($data);

        foreach ($data as $key => $value) {
            $contentForSign .= $key . '=' . urlencode($value) . '&';
        }

        $signature = hash($hashAlgorithm, $contentForSign . $signatureKey);

        return 'sender=' . $merchantPosId . ';algorithm=' . $algorithm . ';signature=' . $signature;
    }

    /**
     * Function returns signature data object
     *
     * @param string $data
     *
     * @return null|array
     */
    public static function parseSignature($data)
    {
        if (empty($data)) {
            return null;
        }

        $signatureData = array();

        $list = explode(';', rtrim($data, ';'));
        if (empty($list)) {
            return null;
        }

        foreach ($list as $value) {
            $explode = explode('=', $value);
            if (count($explode) != 2) {
                return null;
            }
            $signatureData[$explode[0]] = $explode[1];
        }

        return $signatureData;
    }

    /**
     * Function returns signature validate
     *
     * @throws OpenPayU_Exception_Configuration
     * @throws OpenPayU_Exception
     */
    public static function verifySignature(
        string $message,
        string $signature,
        string $signatureKey,
        string $algorithm = 'MD5'): bool
    {
        if (empty($signatureKey)) {
            throw new OpenPayU_Exception_Configuration('Merchant Signature Key should not be null or empty.');
        }

        $hashAlgorithm = strtoupper(self::toHashAlgorithm($algorithm));
        if (!in_array($hashAlgorithm, [
            'MD5', 'SHA1', 'SHA256', 'SHA384', 'SHA512',
        ], true)) {
            throw new OpenPayU_Exception('Unknown algorithm.');
        }

        $hash = hash($hashAlgorithm, $message . $signatureKey);

        return hash_equals($hash, $signature);
    }

    /**
     * Function builds OpenPayU Json Document
     *
     * @param array $data
     * @param string $rootElement
     *
     * @return null|string
     */
    public static function buildJsonFromArray($data, $rootElement = '')
    {
        if (!is_array($data)) {
            return null;
        }

        if (!empty($rootElement)) {
            $data = array($rootElement => $data);
        }

        $data = self::setSenderProperty($data);

        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param string $data
     * @param bool $assoc
     * @return mixed|null
     */
    public static function convertJsonToArray($data, $assoc = false)
    {
        if (empty($data)) {
            return null;
        }

        return json_decode($data, $assoc);
    }

    /**
     * @param array $array
     * @return bool|stdClass
     */
    public static function parseArrayToObject($array)
    {
        if (!is_array($array)) {
            return $array;
        }

        if (self::isAssocArray($array)) {
            $object = new stdClass();
        } else {
            $object = array();
        }

        if (is_array($array) && count($array) > 0) {
            foreach ($array as $name => $value) {
                $name = trim($name);
                if (isset($name)) {
                    if (is_numeric($name)) {
                        $object[] = self::parseArrayToObject($value);
                    } else {
                        $object->$name = self::parseArrayToObject($value);
                    }
                }
            }
            return $object;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public static function getRequestHeaders()
    {
        if (!function_exists('apache_request_headers')) {
            $headers = array();
            foreach ($_SERVER as $key => $value) {
                if (substr($key, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))))] = $value;
                }
            }
            return $headers;
        } else {
            return apache_request_headers();
        }

    }

    /**
     * @param $array
     * @param string $namespace
     * @param array $outputFields
     * @return string
     */
    public static function convertArrayToHtmlForm($array, $namespace = '', &$outputFields = [])
    {
        $i = 0;
        $htmlOutput = "";
        $assoc = self::isAssocArray($array);

        foreach ($array as $key => $value) {

            if ($namespace && $assoc) {
                $key = $namespace . '.' . $key;
            } elseif ($namespace && !$assoc) {
                $key = $namespace . '[' . $i++ . ']';
            }

            if (is_array($value)) {
                $htmlOutput .= self::convertArrayToHtmlForm($value, $key, $outputFields);
            } else {
                $htmlOutput .= sprintf("<input type=\"hidden\" name=\"%s\" value=\"%s\" />\n", htmlspecialchars($key)
                , htmlspecialchars($value));
                $outputFields[$key] = $value;
            }
        }
        return $htmlOutput;
    }

    /**
     * @param $arr
     * @return bool
     */
    public static function isAssocArray($arr)
    {
        $arrKeys = array_keys($arr);
        sort($arrKeys, SORT_NUMERIC);
        return $arrKeys !== range(0, count($arr) - 1);
    }

    /**
     * @param array $data
     * @return array
     */
    private static function setSenderProperty($data)
    {
        $data['properties'][0] = array(
            'name' => 'sender',
            'value' => OpenPayU_Configuration::getFullSenderName()
        );
        return $data;
    }

    public static function statusDesc($response)
    {

        $msg = '';

        switch ($response) {
            case 'SUCCESS':
                $msg = 'Request has been processed correctly.';
                break;
            case 'DATA_NOT_FOUND':
                $msg = 'Data indicated in the request is not available in the PayU system.';
                break;
            case 'WARNING_CONTINUE_3DS':
                $msg = '3DS authorization required. Redirect the Buyer to PayU to continue the 3DS process.';
                break;
            case 'WARNING_CONTINUE_CVV':
                $msg = 'CVV/CVC authorization required.';
                break;
            case 'ERROR_SYNTAX':
                $msg = 'BIncorrect request syntax. Supported formats are JSON or XML.';
                break;
            case 'ERROR_VALUE_INVALID':
                $msg = 'One or more required values are incorrect.';
                break;
            case 'ERROR_VALUE_MISSING':
                $msg = 'One or more required values are missing.';
                break;
            case 'BUSINESS_ERROR':
            case 'ERROR_INTERNAL':
                $msg = 'PayU system is unavailable. Try again later.';
                break;
            case 'GENERAL_ERROR':
                $msg = 'Unexpected error. Try again later.';
                break;
        }

        return $msg;
    }

    private static function toHashAlgorithm(string $algorithm): string
    {
        return self::ALGORITHMS_TO_HASH[$algorithm] ?? $algorithm;
    }
}
