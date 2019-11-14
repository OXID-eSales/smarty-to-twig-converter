<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Tests\Unit;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\OxpriceConverter;

/**
 * Class OxpriceConverterTest
 */
class OxpriceConverterTest extends TestCase
{

    /** @var OxpriceConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new OxpriceConverter();
    }

    /**
     * @covers       \toTwig\Converter\OxpriceConverter::convert
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
            // OXID examples
            [
                "[{oxprice price=\$basketitem->getUnitPrice() currency=\$currency}]",
                "{{ format_price(basketitem.getUnitPrice(), { currency: currency }) }}"
            ],
            [
                "[{oxprice price=\$VATitem currency=\$currency}]",
                "{{ format_price(VATitem, { currency: currency }) }}"
            ],
            // No currency
            [
                "[{oxprice price=\$basketitem->getUnitPrice()}]",
                "{{ format_price(basketitem.getUnitPrice()) }}"
            ],
            // With spaces
            [
                "[{ oxprice price=\$basketitem->getUnitPrice() currency=\$currency }]",
                "{{ format_price(basketitem.getUnitPrice(), { currency: currency }) }}"
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\OxpriceConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('oxprice', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\OxpriceConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
