<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace sankar\ST\Tests\Unit;

use toTwig\Converter\NewBasketItemConverter;
use PHPUnit\Framework\TestCase;

class NewBasketItemConverterTest extends TestCase
{

    /** @var NewBasketItemConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new NewBasketItemConverter();
    }

    /**
     * @covers       \toTwig\Converter\NewBasketItemConverter::convert
     * @dataProvider Provider
     *
     * @param $smarty
     * @param $twig
     */
    public function testThatIncludeIsConverted($smarty, $twig)
    {
        // Test the above cases
        $this->assertSame($twig, $this->converter->convert($smarty));
    }

    public function Provider()
    {
        return [
            [
                '[{insert name="oxid_newbasketitem" tpl="widget/minibasket/newbasketitemmsg.tpl" type="message"}]',
                '{{ insert_new_basket_item({tpl: "widget/minibasket/newbasketitemmsg.html.twig", type: "message"}) }}'
            ],
            [
                '[{ insert name="oxid_newbasketitem" tpl="widget/minibasket/newbasketitemmsg.tpl" type="message" }]',
                '{{ insert_new_basket_item({tpl: "widget/minibasket/newbasketitemmsg.html.twig", type: "message"}) }}'
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\NewBasketItemConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('oxid_newbasketitem', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\NewBasketItemConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
