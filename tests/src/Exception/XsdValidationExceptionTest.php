<?php

namespace TurnerLabs\ValidatingXmlEncoder\Tests\Exception;

use PHPUnit\Framework\TestCase;
use TurnerLabs\ValidatingXmlEncoder\Exception\XsdValidationException;

/**
 * @coversDefaultClass \TurnerLabs\ValidatingXmlEncoder\Exception\XsdValidationException
 */
class XsdValidationExceptionTest extends TestCase
{
    /**
     * Test returning the original libxml error.
     *
     * @covers ::__construct
     * @covers ::getXmlError
     */
    public function testGetXmlError()
    {
        $error = $this->getLibXmlError();
        $e = new XsdValidationException($error);
        $this->assertEquals($error, $e->getXmlError());
    }

    /**
     * Test the error message we generate.
     *
     * @param string $level  The error level to test against.
     * @param string $string The error string to test against.
     * @covers ::__construct
     * @dataProvider getXsdErrorLevels
     */
    public function testGetMessage($level, $string)
    {
        $error = $this->getLibXmlError();
        $error->level = $level;
        $e = new XsdValidationException($error);
        $this->assertEquals("XSD validation $string code 123 in /tmp/file.xml line 789 column 456: This is a test", $e->getMessage());
    }

    /**
     * Test that use use the XML error code as the exception code.
     *
     * @covers ::__construct
     */
    public function testGetCode()
    {
        $error = $this->getLibXmlError();
        $e = new XsdValidationException($error);
        $this->assertEquals($error->code, $e->getCode());
    }

    /**
     * Test that we properly set the previous exception.
     *
     * @covers ::__construct
     */
    public function testGetPrevious()
    {
        $error = $this->getLibXmlError();
        $previous = new \Exception();
        $e = new XsdValidationException($error, $previous);
        $this->assertEquals($previous, $e->getPrevious());
    }

    /**
     * Return libxml error levels and the human-readable version.
     *
     * @return array
     *               The array of error levels and error strings.
     */
    public function getXsdErrorLevels()
    {
        return [
            [LIBXML_ERR_WARNING, 'warning'],
            [LIBXML_ERR_ERROR, 'error'],
            [LIBXML_ERR_FATAL, 'fatal'],
            [-1, 'unknown'],
        ];
    }

    /**
     * Return a mock XML error.
     *
     * The class doens't have constructor parameters, and instead relies on
     * public properties.
     *
     * @return \LibXMLError
     */
    protected function getLibXmlError()
    {
        $error = new \LibXMLError();
        $error->file = '/tmp/file.xml';
        $error->code = 123;
        $error->column = 456;
        $error->line = 789;
        $error->level = LIBXML_ERR_FATAL;
        $error->message = 'This is a test';

        return $error;
    }
}
