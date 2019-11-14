<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace sankar\ST\Tests\Unit;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\OxscriptConverter;

/**
 * Class OxscriptConverterTest
 */
class OxscriptConverterTest extends FileConversionUnitTestCase
{

    /** @var OxscriptConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new OxscriptConverter();
        $this->templateNames = ['oxscript'];
        parent::setUp();
    }

    /**
     * @covers       \toTwig\Converter\OxscriptConverter::convert
     *
     * @dataProvider provider
     *
     * @param $smarty
     * @param $twig
     */
    public function testThatAssignIsConverted($smarty, $twig)
    {
        // Test the above cases
        $this->assertSame(
            $twig,
            $this->converter->convert($smarty)
        );
    }

    /**
     * @return array
     */
    public function provider()
    {
        return [
            // With spaces
            [
                "[{ oxscript include=\"js/pages/details.min.js\" priority=10 }]",
                "{{ script({ include: \"js/pages/details.min.js\", priority: 10, dynamic: __oxid_include_dynamic }) }}"
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\OxscriptConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('oxscript', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\OxscriptConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
