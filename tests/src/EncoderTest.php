<?php

namespace TurnerLabs\ValidatingXmlEncoder\Tests;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use TurnerLabs\ValidatingXmlEncoder\Encoder;
use TurnerLabs\ValidatingXmlEncoder\Exception\XsdValidationException;

/**
 * @coversDefaultClass \TurnerLabs\ValidatingXmlEncoder\Encoder
 */
class EncoderTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testXsdDoesNotExist()
    {
        $root = vfsStream::setup('root');
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('vfs://root/does-not-exist.xsd could not be read.');
        new Encoder('root', $root->url() . '/does-not-exist.xsd');
    }

    /**
     * @covers ::__construct
     */
    public function testUnreadableXsd()
    {
        $structure = [
            'unreadable.xsd' => '',
        ];
        $root = vfsStream::setup('root', null, $structure);
        $root->getChild('unreadable.xsd')->chmod(0);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('vfs://root/unreadable.xsd could not be read.');
        new Encoder('root', $root->getChild('unreadable.xsd')->url());
    }

    /**
     * @covers ::__construct
     */
    public function testEmptyXsd()
    {
        $structure = [
            'invalid.xsd' => '',
        ];
        $root = vfsStream::setup('root', null, $structure);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('vfs://root/invalid.xsd could not be loaded.');
        new Encoder('root', $root->getChild('invalid.xsd')->url());
    }

    /**
     * Test that an invalid XSD throws an error.
     *
     * @covers ::encode
     */
    public function testInvalidXsd()
    {
        $structure = [
            'invalid.xsd' => '<xml>this is not an xsd</xml>',
        ];
        $root = vfsStream::setup('root', null, $structure);
        $encoder = new Encoder('root', $root->getChild('invalid.xsd')->url());
        $this->expectException(\PHPUnit_Framework_Error_Warning::class);
        $this->expectExceptionMessage('DOMDocument::schemaValidateSource(): Invalid Schema');
        $encoder->encode('<xml>test</xml>', 'xml');
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
