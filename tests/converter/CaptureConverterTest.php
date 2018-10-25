<?php

namespace sankar\ST\Tests\Converter;

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
     * @covers \toTwig\Converter\CaptureConverter::convert
     * @dataProvider Provider
     *
     * @param $smarty
     * @param $twig
     */
    public function testThatIncludeIsConverted($smarty, $twig)
    {
        // Test the above cases
        /** @var \SplFileInfo  $fileMock */
        $fileMock = $this->getFileMock();
        $this->assertSame($twig, $this->converter->convert($fileMock, $smarty));
    }

    public function Provider()
    {
        return [
            [
                '[{capture name="foo" append="var"}] bar [{/capture}]',
                '{% set foo %}{{ var }} bar {% endset %}'
            ],
            [
                '[{ capture name="foo" append="var" }] bar [{ /capture }]',
                '{% set foo %}{{ var }} bar {% endset %}'
            ],
            [
                '[{capture name="foo"}] bar [{/capture}]',
                '{% set foo %} bar {% endset %}'
            ],
            [
                '[{ capture name="foo" }] bar [{ /capture }]',
                '{% set foo %} bar {% endset %}'
            ],
            [
                '[{ capture append="foo" }] bar [{ /capture }]',
                '{% set foo %}{{ foo }} bar {% endset %}'
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\CaptureConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('CaptureConverter', $this->converter->getName());
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
