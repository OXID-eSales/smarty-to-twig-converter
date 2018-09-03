<?php

namespace sankar\ST\Tests\Converter;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\OxidIncludeDynamicConverter;

/**
 * Class OxidIncludeDynamicConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxidIncludeDynamicConverterTest extends TestCase
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
            [
                "[{oxid_include_dynamic file=\"form/formparams.tpl\"}]",
                "{{ oxid_include_dynamic(\"form/formparams.tpl\") }}"
            ],
            [
                "[{oxid_include_dynamic file=\"widget/product/compare_links.tpl\" testid=\"_`\$iIndex`\" type=\"compare\" aid=\$product->oxarticles__oxid->value anid=\$altproduct in_list=\$product->isOnComparisonList() page=\$oView->getActPage()}]",
                "{{ oxid_include_dynamic(\"widget/product/compare_links.tpl\", { testid: \"_`\$iIndex`\", type: \"compare\", aid: product.oxarticles__oxid.value, anid: altproduct, in_list: product.isOnComparisonList(), page: oView.getActPage() }) }}"
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