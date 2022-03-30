<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Tests\Unit;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\CaptureConverter;

class CaptureConverterTest extends TestCase
{

    /** @var CaptureConverter */
    protected $converter;

    public function setUp(): void
    {
        $this->converter = new CaptureConverter();
    }

    /**
     * @covers       \toTwig\Converter\CaptureConverter::convert
     * @dataProvider provider
     *
     * @param $smarty
     * @param $twig
     */
    public function testThatIncludeIsConverted($smarty, $twig)
    {
        $this->assertSame($twig, $this->converter->convert($smarty));
    }

    public function provider()
    {
        return [
            [
                '[{capture name="foo"}] bar [{/capture}]',
                '{% capture name = "foo" %} bar {% endcapture %}'
            ],
            [
                '[{ capture name="foo"}] bar [{ /capture }]',
                '{% capture name = "foo" %} bar {% endcapture %}'
            ],
            [
                '[{capture assign="foo"}] bar [{/capture}]',
                '{% capture assign = "foo" %} bar {% endcapture %}'
            ],
            [
                '[{ capture assign="foo" }] bar [{ /capture }]',
                '{% capture assign = "foo" %} bar {% endcapture %}'
            ],
            [
                '[{capture append="foo" }] bar [{ /capture}]',
                '{% capture append = "foo" %} bar {% endcapture %}'
            ],
            [
                '[{ capture append="foo" }] bar [{ /capture }]',
                '{% capture append = "foo" %} bar {% endcapture %}'
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\CaptureConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('capture', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\CaptureConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
