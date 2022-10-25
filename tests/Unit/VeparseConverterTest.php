<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Tests\Unit;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\VeparseConverter;

/**
 * Class VeparseConverterTest
 */
class VeparseConverterTest extends TestCase
{
    /** @var VeparseConverter */
    protected $converter;

    public function setUp(): void
    {
        $this->converter = new VeparseConverter();
    }

    /**
     * @covers       \toTwig\Converter\VeparseConverter::convert
     * @dataProvider provider
     */
    public function testThatVeparseIsConverted($smarty, $twig)
    {
        $this->assertSame(
            $twig,
            $this->converter->convert($smarty)
        );
    }

    public function provider()
    {
        return [
            //Basic usage
            [
                "[{veparse name=\"hash\"}]content[{/veparse}]",
                "{%veparse name=\"hash\"%}content{%endveparse%}"
            ],
            [
                "[{veparse css=\".customcss{color:green}\"}]content[{/veparse}]",
                "{%veparse css=\".customcss{color:green}\"%}content{%endveparse%}"
            ],
            [
                "[{veparse cssclass=\"globalcss\"}]content[{/veparse}]",
                "{%veparse cssclass=\"globalcss\"%}content{%endveparse%}"
            ],
            //Additional variables
            [
                "[{veparse name=\"hash\" additional=\"true\" second_additional=\"true\"}]content[{/veparse}]",
                "{%veparse name=\"hash\" additional=\"true\" second_additional=\"true\"%}content{%endveparse%}"
            ],
        ];
    }

    /**
     * @covers \toTwig\Converter\VeparseConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('veparse', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\VeparseConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
