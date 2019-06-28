<?php

namespace sankar\ST\Tests\Unit;

use toTwig\Converter\OxidIncludeDynamicConverter;

/**
 * Class OxidIncludeDynamicConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxidIncludeDynamicConverterTest extends FileConversionUnitTestCase
{

    /** @var OxidIncludeDynamicConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new OxidIncludeDynamicConverter();
        $this->templateNames = ['oxid-include-dynamic'];
        parent::setUp();
    }

    /**
     * @covers \toTwig\Converter\InsertTrackerConverter::convert
     */
    public function testConvert()
    {
        parent::testConvert();
    }

    /**
     * @covers       \toTwig\Converter\OxidIncludeDynamicConverter::convert
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
            // Basic usage
            [
                "[{oxid_include_dynamic file=\"form/formparams.tpl\"}]",
                "{% include_dynamic \"form/formparams.html.twig\" %}"
            ],
            // With spaces
            [
                "[{ oxid_include_dynamic file=\"form/formparams.tpl\" }]",
                "{% include_dynamic \"form/formparams.html.twig\" %}"
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\OxidIncludeDynamicConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('oxid_include_dynamic', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\OxidIncludeDynamicConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
