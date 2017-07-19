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

    public function __construct(\LibXMLError $error, \Throwable $previous = null)
    {
        $this->xmlError = $error;
        $message = sprintf("XSD validation %s %s at %s %s %s: %s", $error->level, $error->code, $error->file, $error->line, $error->column, $error->message);
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
