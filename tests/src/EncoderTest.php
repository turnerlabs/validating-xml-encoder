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
        $encoder->encode([], 'xml');
    }

    /**
     * Test validating and encoding.
     *
     * @covers ::encode
     * @covers ::createDomDocument
     */
    public function testValidXsd()
    {
        $xsd =<<<XSD
<xsd:schema xmlns:xsd="http://www.w3.org/2001/XMLSchema">
    <xsd:element name="root" type="RootType"/>
    <xsd:complexType name="RootType">
        <xsd:sequence>
            <xsd:element name="name" type="xsd:string"/>
        </xsd:sequence>
    </xsd:complexType>
</xsd:schema>
XSD;

        $structure = [
            'valid.xsd' => $xsd,
        ];
        $root = vfsStream::setup('root', null, $structure);
        $encoder = new Encoder('root', $root->getChild('valid.xsd')->url());
        $data = [
            'name' => 'john',
        ];
        $result = $encoder->encode($data, 'xml');
        $expected = <<<'XML'
<?xml version="1.0"?>
<root><name>john</name></root>

XML;
        $this->assertEquals($expected, $result);
    }

    /**
     * @covers ::encode
     */
    public function testInvalidXml()
    {
        $xsd =<<<XSD
<xsd:schema xmlns:xsd="http://www.w3.org/2001/XMLSchema">
    <xsd:element name="root" type="RootType"/>
    <xsd:complexType name="RootType">
        <xsd:sequence>
            <xsd:element name="name" type="xsd:string"/>
        </xsd:sequence>
    </xsd:complexType>
</xsd:schema>
XSD;

        $structure = [
            'valid.xsd' => $xsd,
        ];
        $root = vfsStream::setup('root', null, $structure);
        $encoder = new Encoder('root', $root->getChild('valid.xsd')->url());
        $data = [
            'not-name' => 'john',
        ];
        $this->expectException(XsdValidationException::class);

        // When using the VFS, the error message contains the path to the
        // project itself on disk, so we wildcard that out of the assertion.
        $this->expectExceptionMessageRegExp('!XSD validation error code 1871.*line 2 column 0: Element \'not-name\': This element is not expected. Expected is \( name \).!');
        $encoder->encode($data, 'xml');
    }
}
