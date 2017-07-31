<?php

namespace TurnerLabs\ValidatingXmlEncoder\Exception;

use Symfony\Component\Serializer\Exception\UnexpectedValueException;

class XsdValidationException extends UnexpectedValueException
{
    /**
     * The error that generated this exception.
     *
     * @var \LibXMLError
     */
    protected $xmlError;

    /**
     * The invalid XML document.
     *
     * @var \DOMDocument
     */
    protected $dom;

    /**
     * Construct a new XsdValidationException.
     *
     * @param \LibXMLError $error  The error that triggered the exception.
     * @param \DOMDocument $dom               The invalid XML document.
     * @param \Exception|null $previous (optional) A previous exception, if it exists.
     */
    public function __construct(\LibXMLError $error, \DOMDocument $dom, \Exception $previous = null)
    {
        $this->xmlError = $error;
        $this->dom = $dom;

        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $level = 'warning';
                break;
            case LIBXML_ERR_ERROR:
                $level = 'error';
                break;
            case LIBXML_ERR_FATAL:
                $level = 'fatal';
                break;
            default:
                $level = 'unknown';
                break;
        }

        $message = sprintf('XSD validation %s code %s in %s line %s column %s: %s',
            $level, $error->code, $error->file, $error->line, $error->column, $error->message);
        parent::__construct($message, $error->code, $previous);
    }

    /**
     * @return \LibXMLError
     */
    public function getXmlError()
    {
        return $this->xmlError;
    }

    /**
     * The invalid XML document.
     *
     * @return \DOMDocument
     */
    public function getInvalidXmlDocument()
    {
        return $this->dom;
    }
}
