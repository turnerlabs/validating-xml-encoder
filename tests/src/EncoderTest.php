<?php

namespace TurnerLabs\ValidatingXmlEncoder\Tests;

use TurnerLabs\ValidatingXmlEncoder\Exception\XsdValidationException;
use PHPUnit\Framework\TestCase;

class EncoderTest extends TestCase
{

    public function testUnreadableXsd()
    {

    }

    public function testEmptyXsd()
    {

    }

    public function testInvalidXsd()
    {

    }

    public function testValidXsd()
    {

    }

    public function testInvalidXml()
    {
        $this->expectException(XsdValidationException::class);
    }
}
