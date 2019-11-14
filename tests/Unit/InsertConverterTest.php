<?php

namespace toTwig\Tests\Unit;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\InsertConverter;

class InsertConverterTest extends TestCase
{

    /** @var InsertConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new InsertConverter();
    }

    /**
     * @covers       \toTwig\Converter\InsertConverter::convert
     * @dataProvider provider
     *
     * @param $smarty
     * @param $twig
     */
    public function testThatIncludeIsConverted($smarty, $twig)
    {
        // Test the above cases
        $this->assertSame($twig, $this->converter->convert($smarty));
    }

    public function provider()
    {
        return [
            [
                '[{insert name="oxid_content" ident="foo"}]',
                '{% include "oxid_content" with {ident: "foo"} %}'
            ],
            [
                '[{ insert name="oxid_content" ident="foo" }]',
                '{% include "oxid_content" with {ident: "foo"} %}'
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\InsertConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('insert', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\InsertConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
