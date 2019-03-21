<?php

namespace sankar\ST\Tests\Unit;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\CycleConverter;

/**
 * Class CycleConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class CycleConverterTest extends TestCase
{

    /** @var CycleConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new CycleConverter();
    }

    /**
     * @covers       \toTwig\Converter\AssignConverter::convert
     *
     * @dataProvider Provider
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
    public function Provider()
    {
        return [
            [
                // Basic usage
                "[{cycle values=\"val1,val2,val3\"}]",
                "{{ smarty_cycle([\"val1\", \"val2\", \"val3\"]) }}"
            ],
            [
                // Using non-default delimiter
                "[{cycle values=\"val1:val2:val3\" delimiter=\":\"}]",
                "{{ smarty_cycle([\"val1\", \"val2\", \"val3\"]) }}"
            ],
            [
                // Assigning to variable
                "[{cycle values=\"val1,val2,val3\" assign=\"var\"}]",
                "{% set var = smarty_cycle([\"val1\", \"val2\", \"val3\"]) %}"
            ],
            [
                // Assigning to variable using non-default delimiter
                "[{cycle values=\"val1:val2:val3\" delimiter=\":\" assign=\"var\"}]",
                "{% set var = smarty_cycle([\"val1\", \"val2\", \"val3\"]) %}"
            ],
            [
                // Short-hand use
                "[{cycle}]",
                "{{ smarty_cycle() }}"
            ],
            [
                // Extra parameters
                "[{cycle values=\"val1:val2:val3\" delimiter=\":\" print=false advance=false reset=true}]",
                "{{ smarty_cycle([\"val1\", \"val2\", \"val3\"], { print: false, advance: false, reset: true }) }}"
            ],
            [
                // Extra parameters with assign
                "[{cycle values=\"val1:val2:val3\" delimiter=\":\" print=false advance=false reset=true assign=\"var\"}]",
                "{% set var = smarty_cycle([\"val1\", \"val2\", \"val3\"], { advance: false, reset: true }) %}"
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\AssignConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('cycle', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\AssignConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
