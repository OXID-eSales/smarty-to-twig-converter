<?php

namespace sankar\ST\Tests\Unit;

use toTwig\Converter\CounterConverter;
use PHPUnit\Framework\TestCase;

class CounterConverterTest extends TestCase
{

    /** @var CounterConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new CounterConverter();
    }

    /**
     * @covers       \toTwig\Converter\CounterConverter::convert
     * @dataProvider provider
     */
    public function testThatCounterIsConverted($smarty, $twig)
    {
        // Test the above cases
        $this->assertSame(
            $twig,
            $this->converter->convert($smarty)
        );
    }

    public function provider()
    {
        return [
            [
                '[{counter}]',
                '{% set defaultCounter = ( defaultCounter | default(0) ) + 1 %}'
            ],
            [
                '[{ counter }]',
                '{% set defaultCounter = ( defaultCounter | default(0) ) + 1 %}'
            ],
            [
                '[{counter name="foo"}]',
                '{% set foo = ( foo | default(0) ) + 1 %}'
            ],
            [
                '[{counter start=2}]',
                '{% set defaultCounter = ( defaultCounter | default(1) ) + 1 %}'
            ],
            [
                '[{counter skip=2}]',
                '{% set defaultCounter = ( defaultCounter | default(0) ) + 2 %}'
            ],
            [
                '[{counter print=true}]',
                '{% set defaultCounter = ( defaultCounter | default(0) ) + 1 %} {{ defaultCounter }}'
            ],
            [
                '[{counter print=false}]',
                '{% set defaultCounter = ( defaultCounter | default(0) ) + 1 %}'
            ],
            [
                '[{counter direction=up}]',
                '{% set defaultCounter = ( defaultCounter | default(0) ) + 1 %}'
            ],
            [
                '[{counter direction=down}]',
                '{% set defaultCounter = ( defaultCounter | default(0) ) - 1 %}'
            ],
            [
                '[{counter direction="up"}]',
                '{% set defaultCounter = ( defaultCounter | default(0) ) + 1 %}'
            ],
            [
                '[{counter direction="down"}]',
                '{% set defaultCounter = ( defaultCounter | default(0) ) - 1 %}'
            ],
            [
                '[{counter assign="foo"}]',
                '{% set defaultCounter = ( defaultCounter | default(0) ) + 1 %} {% set foo = defaultCounter %}'
            ],
            [
                '[{counter name="mini_basket_countdown_nr" assign="countdown_nr"}]',
                '{% set mini_basket_countdown_nr = ( mini_basket_countdown_nr | default(0) ) + 1 %} {% set countdown_nr = mini_basket_countdown_nr %}'
            ],
            [
                '[{counter name="foo" start=3 skip=4 direction=down print=true assign="bar"}]',
                '{% set foo = ( foo | default(7) ) - 4 %} {{ foo }} {% set bar = foo %}'
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\CounterConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('counter', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\CounterConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
