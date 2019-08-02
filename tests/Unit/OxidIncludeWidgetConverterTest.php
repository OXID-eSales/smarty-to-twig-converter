<?php

namespace sankar\ST\Tests\Unit;

use toTwig\Converter\OxidIncludeWidgetConverter;

/**
 * Class OxidIncludeWidgetConverterTest
 */
class OxidIncludeWidgetConverterTest extends FileConversionUnitTestCase
{

    /** @var OxidIncludeWidgetConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new OxidIncludeWidgetConverter();
        $this->templateNames = ['oxid-include-widget'];
        parent::setUp();
    }

    /**
     * @covers       \toTwig\Converter\OxidIncludeWidgetConverter::convert
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
                "[{ oxid_include_widget cl=\"oxwCategoryTree\" }]",
                "{{ include_widget({ cl: \"oxwCategoryTree\" }) }}"
            ],
        ];
    }

    /**
     * @covers \toTwig\Converter\OxidIncludeWidgetConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('oxid_include_widget', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\OxidIncludeWidgetConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
