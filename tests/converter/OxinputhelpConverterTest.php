<?php

namespace sankar\ST\Tests\Converter;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\OxinputhelpConverter;

/**
 * Class OxinputhelpConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxinputhelpConverterTest extends TestCase
{
    /** @var OxinputhelpConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new OxinputhelpConverter();
    }

    /**
     * @covers       \toTwig\Converter\CaptureConverter::convert
     * @dataProvider Provider
     *
     * @param $smarty
     * @param $twig
     */
    public function testThatIncludeIsConverted($smarty, $twig)
    {
        /** @var \SplFileInfo $fileMock */
        $fileMock = $this->getFileMock();
        $this->assertSame($twig, $this->converter->convert($fileMock, $smarty));
    }

    public function Provider()
    {
        return [
            [
                '[{oxinputhelp ident="foo"}]',
                '{% include "inputhelp.tpl" with {\'sHelpId\': getSHelpId(foo), \'sHelpText\': getSHelpText(foo)} %}'
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\CaptureConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('oxinputhelp', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\CaptureConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }

    private function getFileMock()
    {
        return $this->getMockBuilder('\SplFileInfo')->disableOriginalConstructor()->getMock();
    }
}