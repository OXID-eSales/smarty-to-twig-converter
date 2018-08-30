<?php
/**
 * Created by PhpStorm.
 * User: jskoczek
 * Date: 30/08/18
 * Time: 12:37
 */

namespace sankar\ST\Tests\Converter;

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
     * @covers \toTwig\Converter\CounterConverter::convert
     * @dataProvider Provider
     */
    public function testThatCounterIsConverted($smarty,$twig)
    {
        // Test the above cases
        $this->assertSame($twig,
            $this->converter->convert($this->getFileMock(), $smarty)
        );
    }

    public function Provider()
    {
        return [
            [
                '[{counter}]',
                '{% set default = ( default | default(0) ) + 1 %}'
            ],
            [
                '[{counter name="foo"}]',
                '{% set foo = ( foo | default(0) ) + 1 %}'
            ],
            [
                '[{counter start=2}]',
                '{% set default = ( default | default(1) ) + 1 %}'
            ],
            [
                '[{counter skip=2}]',
                '{% set default = ( default | default(0) ) + 2 %}'
            ],
            [
                '[{counter print=true}]',
                '{% set default = ( default | default(0) ) + 1 %} {{ default }}'
            ],
            [
                '[{counter print=false}]',
                '{% set default = ( default | default(0) ) + 1 %}'
            ],
            [
                '[{counter direction=up}]',
                '{% set default = ( default | default(0) ) + 1 %}'
            ],
            [
                '[{counter direction=down}]',
                '{% set default = ( default | default(0) ) - 1 %}'
            ],
            [
                '[{counter assign="foo"}]',
                '{% set default = ( default | default(0) ) + 1 %} {% set foo = default %}'
            ],
            [
                '[{counter name="mini_basket_countdown_nr" assign="countdown_nr"}]',
                '{% set mini_basket_countdown_nr = ( mini_basket_countdown_nr | default(0) ) + 1 %} {% set countdown_nr = mini_basket_countdown_nr %}'
            ],
            [
                '[{counter name="foo" start=3 skip=4 direction=down print=true assign="bar"}]',
                '{% set foo = ( foo | default(2) ) - 4 %} {{ foo }} {% set bar = foo %}'
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

    /**
     * @return \SplFileInfo
     */
    private function getFileMock()
    {
        /** @var \SplFileInfo $fileMock */
        $fileMock = $this->getMockBuilder('\SplFileInfo')
            ->disableOriginalConstructor()
            ->getMock();
        return $fileMock;
    }
}
