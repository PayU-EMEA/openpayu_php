<?php

/**
 * OpenPayU Standard Library
 *
 * @copyright  Copyright (c) 2011-2016 PayU
 * @license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
 * http://www.payu.com
 * http://developers.payu.com
 */
class OpenPayU_Util
{
    /**
     * Function generate sign data
     *
     * @param array $data
     * @param string $algorithm
     * @param string $merchantPosId
     * @param string $signatureKey
     *
     * @return string
     *
     * @throws OpenPayU_Exception_Configuration
     */
    public static function generateSignData(array $data, $algorithm = 'SHA-256', $merchantPosId = '', $signatureKey = '')
    {
        if (empty($signatureKey))
            throw new OpenPayU_Exception_Configuration('Merchant Signature Key should not be null or empty.');

        if (empty($merchantPosId))
            throw new OpenPayU_Exception_Configuration('MerchantPosId should not be null or empty.');

        $contentForSign = '';
        ksort($data);

        foreach ($data as $key => $value) {
            $contentForSign .= $key . '=' . urlencode($value) . '&';
        }

        if (in_array($algorithm, array('SHA-256', 'SHA'))) {
            $hashAlgorithm = 'sha256';
            $algorithm = 'SHA-256';
        } else if ($algorithm == 'SHA-384') {
            $hashAlgorithm = 'sha384';
            $algorithm = 'SHA-384';
        } else if ($algorithm == 'SHA-512') {
            $hashAlgorithm = 'sha512';
            $algorithm = 'SHA-512';
        }

        $signature = hash($hashAlgorithm, $contentForSign . $signatureKey);

        $signData = 'sender=' . $merchantPosId . ';algorithm=' . $algorithm . ';signature=' . $signature;

        return $signData;
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
     * @param string $message
     * @param string $signature
     * @param string $signatureKey
     * @param string $algorithm
     *
     * @return bool
     */
    public static function verifySignature($message, $signature, $signatureKey, $algorithm = 'MD5')
    {
        if (isset($signature)) {
            if ($algorithm === 'MD5') {
                $hash = md5($message . $signatureKey);
            } else if (in_array($algorithm, array('SHA', 'SHA1', 'SHA-1'))) {
                $hash = sha1($message . $signatureKey);
            } else {
                $hash = hash('sha256', $message . $signatureKey);
            }

            if (strcmp($signature, $hash) == 0) {
                return true;
            }
        }

        return false;
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

        return json_encode($data);
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
                $htmlOutput .= sprintf("<input type=\"hidden\" name=\"%s\" value=\"%s\" />\n", $key, htmlspecialchars($value));
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
            case 'WARNING_CONTINUE_3_DS':
                $msg = '3DS authorization required.Redirect the Buyer to PayU to continue the 3DS process by calling OpenPayU.authorize3DS().';
                break;
            case 'WARNING_CONTINUE_CVV':
                $msg = 'CVV/CVC authorization required. Call OpenPayU.authorizeCVV() method.';
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
                $msg = 'PayU system is unavailable. Try again later.';
                break;
            case 'ERROR_INTERNAL':
                $msg = 'PayU system is unavailable. Try again later.';
                break;
            case 'GENERAL_ERROR':
                $msg = 'Unexpected error. Try again later.';
                break;
        }

        return $msg;
    }

}
