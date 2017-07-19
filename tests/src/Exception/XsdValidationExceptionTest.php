<?php

namespace TurnerLabs\ValidatingXmlEncoder\Tests\Exception;

use PHPUnit\Framework\TestCase;
use TurnerLabs\ValidatingXmlEncoder\Exception\XsdValidationException;

/**
 * @coversDefaultClass TurnerLabs\ValidatingXmlEncoder\Exception\XsdValidationException
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
     * @covers ::__construct
     */
    public function testGetMessage()
    {
        $error = $this->getLibXmlError();
        $e = new XsdValidationException($error);
        $this->assertEquals('XSD validation fatal code 123 in /tmp/file.xml line 789 column 456: This is a test', $e->getMessage());
    }

    public function testGetCode()
    {
        $this->markTestIncomplete();
    }

    public function testGetPrevious()
    {
        $this->markTestIncomplete();
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
