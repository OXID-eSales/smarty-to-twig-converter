<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace sankar\ST\Tests\Unit;

use toTwig\Converter\InsertTrackerConverter;
use PHPUnit\Framework\TestCase;

class InsertTrackerConverterTest extends TestCase
{

    /** @var InsertTrackerConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new InsertTrackerConverter();
    }

    /**
     * @covers       \toTwig\Converter\InsertTrackerConverter::convert
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
                '[{insert name="oxid_tracker" title="PRODUCT_DETAILS"|oxmultilangassign product=$oDetailsProduct cpath=$oView->getCatTreePath()}]',
                '{{ insert_tracker({title: "PRODUCT_DETAILS"|translate, product: oDetailsProduct, cpath: oView.getCatTreePath()}) }}'
            ],
            [
                '[{ insert name="oxid_tracker" title="PRODUCT_DETAILS"|oxmultilangassign product=$oDetailsProduct cpath=$oView->getCatTreePath() }]',
                '{{ insert_tracker({title: "PRODUCT_DETAILS"|translate, product: oDetailsProduct, cpath: oView.getCatTreePath()}) }}'
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\InsertTrackerConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('oxid_tracker', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\InsertTrackerConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
