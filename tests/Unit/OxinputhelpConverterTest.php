<?php

namespace sankar\ST\Tests\Unit;

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
        $this->assertSame($twig, $this->converter->convert($smarty));
    }

    public function Provider()
    {
        return [
            [
                '[{oxinputhelp ident="foo"}]',
                '{% include "inputhelp.html.twig" with {\'sHelpId\': help_id("foo"), \'sHelpText\': help_text("foo")} %}'
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
}