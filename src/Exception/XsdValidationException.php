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

    public function __construct(\LibXMLError $error, \Exception $previous = null)
    {
        $this->xmlError = $error;
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
}
