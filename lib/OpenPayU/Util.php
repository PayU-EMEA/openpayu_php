<?php
/**
 * OpenPayU
 *
 * @copyright  Copyright (c) 2012 PayU
 */

class OpenPayU_Util
{
    /**
     * Function generate sign data
     * @access public
     * @param string $data
     * @param string $algorithm
     * @param string $merchantPosId
     * @param string $signatureKey
     * @return string $signData
     * @throws OpenPayU_Exception_Configuration
     */
    public static function generateSignData($data, $algorithm = 'SHA', $merchantPosId = '', $signatureKey = '')
    {
        if (empty($signatureKey))
            throw new OpenPayU_Exception_Configuration('Merchant Signature Key should not be null or empty.');

        if (empty($merchantPosId))
            throw new OpenPayU_Exception_Configuration('MerchantPosId should not be null or empty.');

        $signature = '';

        $data = $data . $signatureKey;

        if ($algorithm == 'MD5') {
            $signature = md5($data);
        } else if (in_array($algorithm, array('SHA', 'SHA1', 'SHA-1'))) {
            $signature = sha1($data);
            $algorithm = 'SHA-1';
        } else if (in_array($algorithm, array('SHA-256', 'SHA256', 'SHA_256'))) {
            $signature = hash('sha256', $data);
            $algorithm = 'SHA-256';
        }

        $signData = 'sender=' . $merchantPosId . ';signature=' . $signature . ';algorithm=' . $algorithm . ';content=DOCUMENT';

        return $signData;
    }

    /**
     * Function returns signature data object
     * @param string $data
     * @return null|object
     */
    public static function parseSignature($data)
    {
        if (empty($data))
            return null;

        $signatureData = array();

        $list = explode(';', rtrim($data, ';'));
        if (empty($list))
            return null;

        foreach ($list as $value) {
            $explode = explode('=', $value);
            $signatureData[$explode[0]] = $explode[1];
        }

        return (object)$signatureData;
    }

    /**
     * Function returns signature validate
     * @param $message
     * @param $signature
     * @param $signatureKey
     * @param $algorithm
     * @return bool
     */
    public static function verifySignature($message, $signature, $signatureKey, $algorithm = 'MD5')
    {
        $hash = '';

        if (isset($signature)) {
            if ($algorithm == 'MD5') {
                $hash = md5($message . $signatureKey);
            } else if (in_array($algorithm, array('SHA', 'SHA1', 'SHA-1'))) {
                $hash = sha1($message . $signatureKey);
            } else if (in_array($algorithm, array('SHA-256', 'SHA256', 'SHA_256'))) {
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
     * @access public
     * @param string $data
     * @param string $rootElement
     * @return string $xml
     */
    public static function buildJsonFromArray($data, $rootElement = '')
    {
        if (!is_array($data))
            return null;

        if (!empty($rootElement))
            $data = array($rootElement => $data);

        return json_encode(array('OpenPayU' => $data));
    }

    /**
     * Function builds OpenPayU Xml Document
     * @access public
     * @param string $data
     * @param string $rootElement
     * @param string $version
     * @param string $encoding
     * @param string $rootElementXsi
     * @return string $xml
     */
    public static function buildXmlFromArray($data, $rootElement, $version = '1.0', $encoding = 'UTF-8', $rootElementXsi = null)
    {
        if (!is_array($data))
            return null;

        $xml = new XmlWriter();

        $xml->openMemory();

        $xml->setIndent(true);

        $xml->startDocument($version, $encoding);
        $xml->startElementNS(null, 'OpenPayU', 'http://www.openpayu.com/20/openpayu.xsd');

        $xml->startElement($rootElement);

        if (!empty($rootElementXsi)) {
            $xml->startAttributeNs('xsi', 'type', 'http://www.w3.org/2001/XMLSchema-instance');
            $xml->text($rootElementXsi);
            $xml->endAttribute();
        }

        self::convertArrayToXml($xml, $data);
        $xml->endElement();

        $xml->endElement();
        $xml->endDocument();

        return trim($xml->outputMemory(true));
    }

    /**
     * Function converts array to XML document
     * @access public
     * @param XMLWriter $xml
     * @param array $data
     */
    public static function convertArrayToXml(XMLWriter $xml, $data)
    {
        if (!empty($data) && is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    if (is_numeric($key)) {
                        self::convertArrayToXml($xml, $value);
                    } else {
                        $xml->startElement($key);
                        self::convertArrayToXml($xml, $value);
                        $xml->endElement();
                    }
                    continue;
                }
                $xml->writeElement($key, $value);
            }
        }
    }

    /**
     * @param $data
     * @return array
     */
    public static function parseXmlDocument($data)
    {
        if (empty($data))
            return null;

        $assoc = self::convertXmlToArray($data);

        return $assoc;
    }

    /**
     * Function converts xml to array
     * @access public
     * @param string $xml
     * @return array $tree
     */
    public static function convertXmlToArray($xml)
    {
        $xmlObject = simplexml_load_string($xml);
        $xmlArray = array( $xmlObject->getName() => (array)$xmlObject );
        return json_decode(json_encode($xmlArray),1);
    }

    /**
     * @param $data
     * @param bool $assoc
     * @return mixed|null
     */
    public static function convertJsonToArray($data, $assoc = false)
    {
        if (empty($data))
            return null;

        return json_decode($data, $assoc);
    }

    /**
     * @param $array
     * @return bool|stdClass
     */
    public static function parseArrayToObject($array)
    {
        if (!is_array($array)) {
            return $array;
        }

        $object = new stdClass();
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $name => $value) {
                $name = trim($name);
                if (!empty($name)) {
                    $object->$name = self::parseArrayToObject($value);
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
        return apache_request_headers();
    }

    /**
     * @param $array
     * @param string $namespace
     * @return string
     */
    public static function convertArrayToHtmlForm($array, $namespace = "", &$outputFields)
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
                $htmlOutput .= sprintf("<input type=\"hidden\" name=\"%s\" value=\"%s\" />\n", $key, $value);
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
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
