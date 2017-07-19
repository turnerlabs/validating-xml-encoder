<?php

namespace TurnerLabs\ValidatingXmlEncoder\Tests;

use PHPUnit\Framework\TestCase;
use TurnerLabs\ValidatingXmlEncoder\Exception\XsdValidationException;

class EncoderTest extends TestCase
{

    public function testUnreadableXsd()
    {
        $this->markTestIncomplete();
    }

    public function testEmptyXsd()
    {
        $this->markTestIncomplete();
    }

    public function testInvalidXsd()
    {
        $this->markTestIncomplete();
    }

    public function testValidXsd()
    {
        $this->markTestIncomplete();
    }

    public function testInvalidXml()
    {
        $this->markTestIncomplete();
        $this->expectException(XsdValidationException::class);
    }
}
