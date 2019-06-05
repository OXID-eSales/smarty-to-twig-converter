<?php

namespace sankar\ST\Tests\Unit;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\OxidIncludeWidgetConverter;

/**
 * Class OxidIncludeWidgetConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxidIncludeWidgetConverterTest extends TestCase
{

    /** @var OxidIncludeWidgetConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new OxidIncludeWidgetConverter();
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
            // Example from OXID
            [
                "[{oxid_include_widget cl=\"oxwCategoryTree\" cnid=\$oView->getCategoryId() deepLevel=0 noscript=1 nocookie=1}]",
                "{{ include_widget({ cl: \"oxwCategoryTree\", cnid: oView.getCategoryId(), deepLevel: 0, noscript: 1, nocookie: 1 }) }}"
            ],
            // Example from OXID
            [
                "[{oxid_include_widget cl=\"oxwArticleBox\" _parent=\$oView->getClassName() nocookie=1 _navurlparams=\$oViewConf->getNavUrlParams() iLinkType=\$_product->getLinkType() _object=\$_product anid=\$_product->getId() sWidgetType=product sListType=listitem_\$type iIndex=\$testid blDisableToCart=\$blDisableToCart isVatIncluded=\$oView->isVatIncluded() showMainLink=\$showMainLink recommid=\$recommid owishid=\$owishid toBasketFunction=\$toBasketFunction removeFunction=\$removeFunction altproduct=\$altproduct inlist=\$_product->isInList() skipESIforUser=1 testid=\$testid}]",
                "{{ include_widget({ cl: \"oxwArticleBox\", _parent: oView.getClassName(), nocookie: 1, _navurlparams: oViewConf.getNavUrlParams(), iLinkType: _product.getLinkType(), _object: _product, anid: _product.getId(), sWidgetType: \"product\", sListType: listitem_\$type, iIndex: testid, blDisableToCart: blDisableToCart, isVatIncluded: oView.isVatIncluded(), showMainLink: showMainLink, recommid: recommid, owishid: owishid, toBasketFunction: toBasketFunction, removeFunction: removeFunction, altproduct: altproduct, inlist: _product.isInList(), skipESIforUser: 1, testid: testid }) }}"
            ],
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
