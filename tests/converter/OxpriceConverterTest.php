<?php

namespace sankar\ST\Tests\Converter;

use toTwig\Converter\OxpriceConverter;

/**
 * Class OxpriceConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxpriceConverterTest extends AbstractConverterTest
{
    /** @var OxpriceConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new OxpriceConverter();
    }

    /**
     * @covers \toTwig\Converter\OxpriceConverter::convert
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
            // OXID examples
            [
                "[{oxprice price=\$basketitem->getUnitPrice() currency=\$currency}]",
                "{{ oxprice(basketitem.getUnitPrice(), { currency: currency }) }}"
            ],
            [
                "[{oxprice price=\$VATitem currency=\$currency}]",
                "{{ oxprice(VATitem, { currency: currency }) }}"
            ],
            // No currency
            [
                "[{oxprice price=\$basketitem->getUnitPrice()}]",
                "{{ oxprice(basketitem.getUnitPrice()) }}"
            ],
            // With spaces
            [
                "[{ oxprice price=\$basketitem->getUnitPrice() currency=\$currency }]",
                "{{ oxprice(basketitem.getUnitPrice(), { currency: currency }) }}"
            ],
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
