<?php

namespace TurnerLabs\ValidatingXmlEncoder\Tests;

use TurnerLabs\ValidatingXmlEncoder\Exception\XsdValidationException;
use PHPUnit\Framework\TestCase;

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
