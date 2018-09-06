<?php

namespace sankar\ST\Tests\Converter;

use toTwig\Converter\OxcontentConverter;

/**
 * Class OxcontentConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxcontentConverterTest extends AbstractConverterTest
{
    /** @var OxcontentConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new OxcontentConverter();
    }

    /**
     * @covers \toTwig\Converter\OxcontentConverter::convert
     *
     * @dataProvider Provider
     *
     * @param $smarty
     * @param $twig
     */
    public function testThatAssignIsConverted($smarty, $twig)
    {
        // Test the above cases
        $this->assertSame($twig,
            $this->converter->convert($this->getFileMock(), $smarty)
        );
    }

    /**
     * @return array
     */
    public function Provider()
    {
        return [
            // Base usage
            [
                "[{oxcontent ident='me@example.com'}]",
                "{{ oxcontent({ ident: 'me@example.com' }) }}"
            ],
            // With additional parameters
            [
                "[{oxcontent ident='me@example.com' text='send me some mail'}]",
                "{{ oxcontent({ ident: 'me@example.com', text: 'send me some mail' }) }}"
            ],
            // Value converting and assign
            [
                "[{oxcontent ident='me@example.com' assign=\$var}]",
                "{% set var = oxcontent({ ident: 'me@example.com' }) %}"
            ],
            // As assignment
            [
                "[{oxcontent ident='me@example.com' subject='Subject of email' assign=\$var}]",
                "{% set var = oxcontent({ ident: 'me@example.com', subject: 'Subject of email' }) %}"
            ],
            // With spaces
            [
                "[{ oxcontent ident='me@example.com' }]",
                "{{ oxcontent({ ident: 'me@example.com' }) }}"
            ],
        ];
    }

    /**
     * @covers \toTwig\Converter\OxcontentConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('oxcontent', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\OxcontentConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
