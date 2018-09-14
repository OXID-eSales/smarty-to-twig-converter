<?php
/**
 * Created by PhpStorm.
 * User: jskoczek
 * Date: 28/08/18
 * Time: 13:47
 */

namespace sankar\ST\Tests\Converter;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\InsertConverter;

class InsertConverterTest extends TestCase
{
    /** @var InsertConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new InsertConverter();
    }

    /**
     * @covers \toTwig\Converter\InsertConverter::convert
     * @dataProvider Provider
     *
     * @param $smarty
     * @param $twig
     */
    public function testThatIncludeIsConverted($smarty, $twig)
    {
        // Test the above cases
        /** @var \SplFileInfo  $fileMock */
        $fileMock = $this->getFileMock();
        $this->assertSame($twig, $this->converter->convert($fileMock, $smarty));
    }

    public function Provider()
    {
        return [
            [
                '[{insert name="oxid_tracker" title="PRODUCT_DETAILS"|oxmultilangassign product=$oDetailsProduct cpath=$oView->getCatTreePath()}]',
                '{% include "oxid_tracker" with {title: "PRODUCT_DETAILS"|oxmultilangassign, product: oDetailsProduct, cpath: oView.getCatTreePath()} %}'
            ],
            [
                '[{ insert name="oxid_tracker" title="PRODUCT_DETAILS"|oxmultilangassign product=$oDetailsProduct cpath=$oView->getCatTreePath() }]',
                '{% include "oxid_tracker" with {title: "PRODUCT_DETAILS"|oxmultilangassign, product: oDetailsProduct, cpath: oView.getCatTreePath()} %}'
            ],
            [
                '[{insert name="oxid_newbasketitem" tpl="widget/minibasket/newbasketitemmsg.tpl" type="message"}]',
                '{% include "oxid_newbasketitem" with {tpl: "widget/minibasket/newbasketitemmsg.tpl", type: "message"} %}'
            ],
            [
                '[{ insert name="oxid_newbasketitem" tpl="widget/minibasket/newbasketitemmsg.tpl" type="message" }]',
                '{% include "oxid_newbasketitem" with {tpl: "widget/minibasket/newbasketitemmsg.tpl", type: "message"} %}'
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\InsertConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('insert', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\InsertConverter::getDescription
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
