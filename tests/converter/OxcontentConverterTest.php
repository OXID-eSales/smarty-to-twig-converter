<?php

namespace sankar\ST\Tests\Converter;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\OxcontentConverter;

/**
 * Class OxcontentConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxcontentConverterTest extends TestCase
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
            [
                "[{oxcontent ident='me@example.com'}]",
                "{{ oxcontent({ ident: 'me@example.com' }) }}"
            ],
            [
                "[{oxcontent ident='me@example.com' text='send me some mail'}]",
                "{{ oxcontent({ ident: 'me@example.com', text: 'send me some mail' }) }}"
            ],
            [
                "[{oxcontent ident='me@example.com' assign=\$var}]",
                "{% set var = oxcontent({ ident: 'me@example.com' }) %}"
            ],
            [
                "[{oxcontent ident='me@example.com' subject='Subject of email' assign=\$var}]",
                "{% set var = oxcontent({ ident: 'me@example.com', subject: 'Subject of email' }) %}"
            ]
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

    /**
     * @return \SplFileInfo
     */
    private function getFileMock()
    {
        /** @var \SplFileInfo $mock */
        $mock = $this->getMockBuilder('\SplFileInfo')
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }
}
