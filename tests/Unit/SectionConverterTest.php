<?php

namespace sankar\ST\Tests\Unit;

use toTwig\Converter\SectionConverter;
use PHPUnit\Framework\TestCase;

class SectionConverterTest extends TestCase
{

    /** @var SectionConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new SectionConverter();
    }

    /**
     * @covers       \toTwig\Converter\SectionConverter::convert
     * @dataProvider Provider
     */
    public function testConvert($smarty, $twig)
    {
        $this->assertSame($twig, $this->converter->convert($smarty));
    }

    public function Provider()
    {
        return [
            [
                '[{section name=picRow start=1 loop=10}]',
                '{% for picRow in 1..10 %}'
            ],
            [
                '[{ section name=picRow start=1 loop=10 }]',
                '{% for picRow in 1..10 %}'
            ],
            [
                '[{/section}]',
                '{% endfor %}'
            ],
            [
                '[{ /section }]',
                '{% endfor %}'
            ],
            [
                '[{section name=picRow start=1 loop=10}]
                    [{picRow}]
                [{/section}]',
                '{% for picRow in 1..10 %}
                    [{picRow}]
                {% endfor %}'
            ],
            [
                '[{ section name=picRow start=1 loop=10 }]
                    [{picRow}]
                [{ /section }]',
                '{% for picRow in 1..10 %}
                    [{picRow}]
                {% endfor %}'
            ],
            [
                '[{section name=picRow start=1 loop=$iPicCount+1 step=1}]
                    [{assign var="iIndex" value=$smarty.section.picRow.index}]
                [{/section}]',
                '{% for picRow in 1..$iPicCount+1 %}
                    [{assign var="iIndex" value=$smarty.section.picRow.index}]
                {% endfor %}'
            ],
            [
                '[{ section name=picRow start=1 loop=$iPicCount+1 step=1 }]
                    [{assign var="iIndex" value=$smarty.section.picRow.index}]
                [{ /section }]',
                '{% for picRow in 1..$iPicCount+1 %}
                    [{assign var="iIndex" value=$smarty.section.picRow.index}]
                {% endfor %}'
            ],
            [
                '[{section name=picRow loop=$iPicCount+1 step=1}]
                    [{assign var="iIndex" value=$smarty.section.picRow.index}]
                [{/section}]',
                '{% for picRow in 0..$iPicCount+1 %}
                    [{assign var="iIndex" value=$smarty.section.picRow.index}]
                {% endfor %}'
            ],
            [
                '[{ section name=picRow loop=$iPicCount+1 step=1 }]
                    [{assign var="iIndex" value=$smarty.section.picRow.index}]
                [{ /section }]',
                '{% for picRow in 0..$iPicCount+1 %}
                    [{assign var="iIndex" value=$smarty.section.picRow.index}]
                {% endfor %}'
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\SectionConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('section', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\SectionConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
