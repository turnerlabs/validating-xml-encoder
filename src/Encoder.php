<?php

namespace TurnerLabs\ValidatingXmlEncoder;

use TurnerLabs\ValidatingXmlEncoder\Exception\XsdValidationException;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

/**
 * Encodes XML data, validating the result against an XSD.
 */
class Encoder extends XmlEncoder
{
    /**
     * The XSD to validate against.
     *
     * @var string
     */
    protected $xsd;

    /**
     * Construct a new Encoder.
     *
     * @param string $rootNodeName The name of the root XML node.
     * @param string $xsd_path The path to the XSD to validate against.
     *
     * @throws \InvalidArgumentException Thrown when $xsd_path is inaccessible.
     * @throws \RuntimeException         Thrown when the XSD is accessible but could not be loaded.
     */
    public function __construct($rootNodeName, $xsd_path)
    {
        parent::__construct($rootNodeName);

        if (!file_exists($xsd_path) && !is_readable($xsd_path)) {
            throw new \InvalidArgumentException('The XSD could not be read');
        }

        // We load the XSD into memory so we avoid having to enable external
        // XML entities and the security implications of that.
        if (!$this->xsd = file_get_contents($xsd_path)) {
            throw new \RuntimeException('The XSD could not be loaded.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function encode($data, $format, array $context = [])
    {
        // Since the parent class only allows us to return a string, we
        // minimize our code duplication and re-parse the string. If this turns
        // out to be a significant performance hit, we should override more of
        // the parent class or remove the inheritance entirely.
        $xml = parent::encode($data, $format, $context);

        $internalErrors = libxml_use_internal_errors(true);
        $disableEntities = libxml_disable_entity_loader(true);
        libxml_clear_errors();

        $dom = $this->createDomDocument($context);
        $dom->loadXML($xml, LIBXML_NOENT | LIBXML_NOBLANKS);
        $valid = $dom->schemaValidateSource($this->xsd);

        $error = libxml_get_last_error();
        libxml_use_internal_errors($internalErrors);
        libxml_disable_entity_loader($disableEntities);

        if (!$valid) {
            // @todo how do we format xml responses?
            throw new XsdValidationException($error);
        }

        return $dom->saveXML();
    }

    /**
     * Create a DOM document, taking serializer options into account.
     *
     * @param array $context options that the encoder has access to
     *
     * @see \Symfony\Component\Serializer\Encoder\XmlEncoder::createDomDocument
     *
     * @return \DOMDocument
     */
    private function createDomDocument(array $context)
    {
        $document = new \DOMDocument();

        // Set an attribute on the DOM document specifying, as part of the XML declaration,
        $xmlOptions = array(
            // nicely formats output with indentation and extra space
            'xml_format_output' => 'formatOutput',
            // the version number of the document
            'xml_version' => 'xmlVersion',
            // the encoding of the document
            'xml_encoding' => 'encoding',
            // whether the document is standalone
            'xml_standalone' => 'xmlStandalone',
        );
        foreach ($xmlOptions as $xmlOption => $documentProperty) {
            if (isset($context[$xmlOption])) {
                $document->$documentProperty = $context[$xmlOption];
            }
        }

        return $document;
    }
}
