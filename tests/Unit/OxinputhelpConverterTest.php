<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Tests\Unit;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\OxinputhelpConverter;

/**
 * Class OxinputhelpConverterTest
 */
class OxinputhelpConverterTest extends TestCase
{

    /** @var OxinputhelpConverter */
    protected $converter;

    public function setUp(): void
    {
        $this->converter = new OxinputhelpConverter();
    }

    /**
     * @covers       \toTwig\Converter\CaptureConverter::convert
     * @dataProvider provider
     *
     * @param $smarty
     * @param $twig
     */
    public function testThatIncludeIsConverted($smarty, $twig)
    {
        $this->assertSame($twig, $this->converter->convert($smarty));
    }

    public function provider()
    {
        return [
            [
                "[{oxinputhelp ident='foo'}]",
                "{% include \"inputhelp.html.twig\" with {'sHelpId': help_id('foo'), 'sHelpText': help_text('foo')} %}"
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
