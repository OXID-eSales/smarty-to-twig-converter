<?php

namespace sankar\ST\Tests\Converter;

use toTwig\Converter\OxscriptConverter;

/**
 * Class OxscriptConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxscriptConverterTest extends AbstractConverterTest
{
    /** @var OxscriptConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new OxscriptConverter();
    }

    /**
     * @covers \toTwig\Converter\OxscriptConverter::convert
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
                "[{oxscript include=\"js/pages/details.min.js\" priority=10}]",
                "{{ oxscript({ include: \"js/pages/details.min.js\", priority: 10 }) }}"
            ],
            [
                "[{oxscript add=\"oxVariantSelections  = [`\$_sSelectionHashCollection`];\"}]",
                "{{ oxscript({ add: \"oxVariantSelections  = [`\$_sSelectionHashCollection`];\" }) }}"
            ],
            [
                "[{oxscript add=\"\$( document ).ready( function() { Flow.initDetailsEvents(); });\"}]",
                "{{ oxscript({ add: \"$(document).ready(function() { Flow.initDetailsEvents(); });\" }) }}"
            ],
            [
                "[{oxscript widget=\$oView->getClassName()}]",
                "{{ oxscript({ widget: oView.getClassName() }) }}"
            ],
            [
                "[{oxscript}]",
                "{{ oxscript() }}"
            ],
            // With spaces
            [
                "[{ oxscript include=\"js/pages/details.min.js\" priority=10 }]",
                "{{ oxscript({ include: \"js/pages/details.min.js\", priority: 10 }) }}"
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\OxscriptConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('oxscript', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\OxscriptConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
