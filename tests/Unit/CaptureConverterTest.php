<?php

namespace sankar\ST\Tests\Unit;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\CaptureConverter;

class CaptureConverterTest extends TestCase
{

    /** @var CaptureConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new CaptureConverter();
    }

    /**
     * @covers       \toTwig\Converter\CaptureConverter::convert
     * @dataProvider Provider
     *
     * @param $smarty
     * @param $twig
     */
    public function testThatIncludeIsConverted($smarty, $twig)
    {
        // Test the above cases
        /** @var \SplFileInfo $fileMock */
        $fileMock = $this->getFileMock();
        $this->assertSame($twig, $this->converter->convert($smarty));
    }

    public function Provider()
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

    private function getFileMock()
    {
        return $this->getMockBuilder('\SplFileInfo')->disableOriginalConstructor()->getMock();
    }
}
