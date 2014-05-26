<?php

/*
	OpenPayU Standard Library

	@copyright  Copyright (c) 2011-2014 PayU
	@license    http://opensource.org/licenses/LGPL-3.0  Open Software License (LGPL 3.0)
	http://www.payu.com
	http://developers.payu.com
	http://twitter.com/openpayu
*/

class OpenPayUBase extends OpenPayUNetwork
{

    /** @var string outputConsole message */
    protected static $outputConsole = '';

    /**
     * Show outputConsole message
     * @access public
     */
    public static function  printOutputConsole()
    {
        echo OpenPayU::$outputConsole;
    }

    /**
     * Add $outputConsole message
     * @access public
     * @param string $header
     * @param string $text
     */
    public static function addOutputConsole($header, $text = '')
    {
        OpenPayU::$outputConsole .= '<br/><strong>' . $header . ':</strong><br />' . $text . '<br/>';
    }

    /**
     * Function builds OpenPayU Request Document
     * @access public
     * @param string $data
     * @param string $startElement Name of Document Element
     * @param string $version Xml Version
     * @param string $xml_encoding Xml Encoding
     * @return string
     */
    public static function buildOpenPayURequestDocument($data, $startElement, $version = '1.0', $xml_encoding = 'UTF-8')
    {
        return OpenPayUBase::buildOpenPayUDocument($data, $startElement, 1, $version, $xml_encoding);
    }

    /**
     * Function builds OpenPayU Response Document
     * @access public
     * @param string $data
     * @param string $startElement Name of Document Element
     * @param string $version Xml Version
     * @param string $xml_encoding Xml Encoding
     * @return string
     */
    public static function buildOpenPayUResponseDocument($data, $startElement, $version = '1.0', $xml_encoding = 'UTF-8')
    {
        return OpenPayUBase::buildOpenPayUDocument($data, $startElement, 0, $version, $xml_encoding);
    }

    /**
     * Function converts array to XML document
     * @access public
     * @deprecated
     * @param XMLWriter $xml
     * @param array $data
     */
    public static function arr2xml(XMLWriter $xml, $data)
    {
        if (!empty($data) && is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    if (is_numeric($key)) {
                        self::arr2xml($xml, $value);
                    } else {
                        $xml->startElement($key);
                        self::arr2xml($xml, $value);
                        $xml->endElement();
                    }
                    continue;
                }
                $xml->writeElement($key, $value);
            }
        }
    }

    /**
     * Function converts array to Form
     * @access public
     * @param array $data
     * @param string $parent
     * @param integer $index
     * @return string $fragment
     */
    public static function arr2form($data, $parent, $index)
    {
        $fragment = '';

        if (!empty($data) && is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    if (is_numeric($key)) {
                        $fragment .= OpenPayUBase::arr2form($value, $parent, $key);
                    } else {
                        $p = $parent != '' ? $parent . '.' . $key : $key;
                        if (is_numeric($index)) {
                            $p .= '[' . $index . ']';
                        }
                        $fragment .= OpenPayUBase::arr2form($value, $p, $key);
                    }
                    continue;
                }

                $path = $parent != '' ? $parent . '.' . $key : $key;
                $fragment .= OpenPayUBase::buildFormFragmentInput($path, $value);
            }
        }

        return $fragment;
    }

    /**
     * Function converts xml to array
     * @access public
     * @deprecated
     * @param XMLReader $xml
     * @return array $tree
     */
    public static function read($xml)
    {
        $tree = null;
        if(isset($xml))
        {
            while ($xml->read()) {
                if ($xml->nodeType == XMLReader::END_ELEMENT) {
                    return $tree;
                } else if ($xml->nodeType == XMLReader::ELEMENT) {
                    if (!$xml->isEmptyElement) {
                        $tree[$xml->name] = OpenPayUBase::read($xml);
                    }
                } else if ($xml->nodeType == XMLReader::TEXT) {
                    $tree = $xml->value;
                }
            }
        }
        return $tree;
    }

    /**
     * Function builds OpenPayU Xml Document
     * @access public
     * @param string $data
     * @param string $startElement
     * @param integer $request
     * @param string $xml_version
     * @param string $xml_encoding
     * @return string $xml
     */
    public static function buildOpenPayUDocument($data, $startElement, $request = 1, $xml_version = '1.0', $xml_encoding = 'UTF-8')
    {
        if (!is_array($data))
            return false;

        $xml = new XmlWriter();
        $xml->openMemory();
        $xml->startDocument($xml_version, $xml_encoding);

        if(OpenPayU_Configuration::getApiVersion() < 2)
        {
            $xml->startElementNS(null, 'OpenPayU', 'http://www.openpayu.com/openpayu.xsd');
            $header = $request == 1 ? 'HeaderRequest' : 'HeaderResponse';

            $xml->startElement($header);

            $xml->writeElement('Algorithm', OpenPayU_Configuration::getHashAlgorithm());

            $xml->writeElement('SenderName', 'exampleSenderName');
            $xml->writeElement('Version', $xml_version);

            $xml->endElement();
        }
        else
            $xml->startElementNS(null, 'OpenPayU', 'http://www.openpayu.com/20/openpayu.xsd');

        // domain level - open
        if(OpenPayU_Configuration::getApiVersion() < 2)
            $xml->startElement(OpenPayUDomain::getDomain4Message($startElement));

        // message level - open
        $xml->startElement($startElement);

        OpenPayU_Util::convertArrayToXml($xml, $data);

        // message level - close
        $xml->endElement();
        // domain level - close
        $xml->endElement();

        // document level - close
        if(OpenPayU_Configuration::getApiVersion() < 2)
            $xml->endElement();

        return $xml->outputMemory(true);
    }

    /**
     * Function builds form input element
     * @access public
     * @param string $name
     * @param string $value
     * @param string $type
     * @return string
     */
    public static function buildFormFragmentInput($name, $value, $type = 'hidden')
    {
        return "<input type='" . $type . "' name='" . $name . "' value='" . $value . "'>\n";
    }

    /**
     * Function builds OpenPayU Form
     * @access public
     * @param string $data
     * @param string $msgName
     * @param string $version
     * @return string
     */
    public static function buildOpenPayuForm($data, $msgName, $version = '1.0')
    {
        if (!is_array($data))
            return false;

        $url = OpenPayUNetwork::getOpenPayuEndPoint();

        $form = "<form method='post' action='" . $url . "'>\n";
        $form .= OpenPayUBase::buildFormFragmentInput('HeaderRequest.Version', $version);
        $form .= OpenPayUBase::buildFormFragmentInput('HeaderRequest.Name', $msgName);
        $form .= OpenPayUBase::arr2form($data, '', '');
        $form .= "</form>";

        return $form;
    }

    /**
     * Function converts Xml string to array
     * @access public
     * @deprecated
     * @param string $xmldata
     * @return array $assoc
     */
    public static function parseOpenPayUDocument($xmldata)
    {
        $xml = new XMLReader();
        $xml->XML($xmldata);

        $assoc = self::read($xml);

        return $assoc;
    }
}