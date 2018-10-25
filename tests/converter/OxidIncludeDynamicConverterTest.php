<?php

namespace sankar\ST\Tests\Converter;

use toTwig\Converter\OxidIncludeDynamicConverter;

/**
 * Class OxidIncludeDynamicConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxidIncludeDynamicConverterTest extends AbstractConverterTest
{
    /** @var OxidIncludeDynamicConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new OxidIncludeDynamicConverter();
    }

    /**
     * @covers \toTwig\Converter\OxidIncludeDynamicConverter::convert
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
            // Basic usage
            [
                "[{oxid_include_dynamic file=\"form/formparams.tpl\"}]",
                "{{ oxid_include_dynamic(\"form/formparams.tpl\") }}"
            ],
            // Example from OXID
            [
                "[{oxid_include_dynamic file=\"widget/product/compare_links.tpl\" testid=\"_`\$iIndex`\" type=\"compare\" aid=\$product->oxarticles__oxid->value anid=\$altproduct in_list=\$product->isOnComparisonList() page=\$oView->getActPage()}]",
                "{{ oxid_include_dynamic(\"widget/product/compare_links.tpl\", { testid: \"_`\$iIndex`\", type: \"compare\", aid: product.oxarticles__oxid.value, anid: altproduct, in_list: product.isOnComparisonList(), page: oView.getActPage() }) }}"
            ],
            // With spaces
            [
                "[{ oxid_include_dynamic file=\"form/formparams.tpl\" }]",
                "{{ oxid_include_dynamic(\"form/formparams.tpl\") }}"
            ],
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