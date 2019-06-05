<?php

namespace sankar\ST\Tests\Unit;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\OxmultilangConverter;

/**
 * Class OxmultilangConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxmultilangConverterTest extends TestCase
{

    /** @var OxmultilangConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new OxmultilangConverter();
    }

    /**
     * @covers       \toTwig\Converter\OxmultilangConverter::convert
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
                "[{oxmultilang ident=\"ERROR_404\"}]",
                "{{ translate({ ident: \"ERROR_404\" }) }}"
            ],
            // Example from OXID
            [
                "[{oxmultilang noerror=true ident=\$menuitem->getAttribute('name')|default:\$menuitem->getAttribute('id')}]",
                "{{ translate({ noerror: true, ident: menuitem.getAttribute('name')|default(menuitem.getAttribute('id')) }) }}"
            ],
            // With spaces
            [
                "[{ oxmultilang ident=\"ERROR_404\" }]",
                "{{ translate({ ident: \"ERROR_404\" }) }}"
            ],
        ];
    }

    /**
     * @covers \toTwig\Converter\OxmultilangConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('oxmultilang', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\OxmultilangConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
