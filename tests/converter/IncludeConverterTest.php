<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) sankar suda <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace sankar\ST\Tests\Converter;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\IncludeConverter;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class IncludeConverterTest extends TestCase
{
    /** @var IncludeConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new IncludeConverter();
    }

    /**
     * @covers \toTwig\Converter\IncludeConverter::convert
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
        $this->assertSame($twig, $this->converter->convert($fileMock, $smarty));
    }

    public function Provider()
    {
        return [
            [
                "[{include file='page_header.tpl'}]",
                "{% include 'page_header.tpl' %}"
            ],
            [
                "[{ include file='page_header.tpl' }]",
                "{% include 'page_header.tpl' %}"
            ],
            [
                "[{include file=\"footer.tpl\" foo=\"bar\" links=\$links}]",
                "{% include \"footer.tpl\" with {foo: \"bar\", links: links} %}"
            ],
            [
                "[{ include file=\"footer.tpl\" foo=\"bar\" links=\$links }]",
                "{% include \"footer.tpl\" with {foo: \"bar\", links: links} %}"
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\IncludeConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('include', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\IncludeConverter::getDescription
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