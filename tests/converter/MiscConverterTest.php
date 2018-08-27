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
use toTwig\Converter\MiscConverter;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class MiscConverterTest extends TestCase
{
    /** @var MiscConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new MiscConverter();
    }

    /**
     * @covers       \toTwig\Converter\MiscConverter::convert
     * @dataProvider Provider
     */
    public function testThatMiscIsConverted($smarty, $twig)
    {
        $this->assertSame($twig,
            $this->converter->convert($this->getFileMock(), $smarty)
        );
    }

    public function Provider()
    {
        return [
            [
                '[{ldelim}]', ''
            ],
            [
                '[{rdelim}]', ''
            ],
            [
                '[{literal}]', '{# literal #}'
            ],
            [
                '[{/literal}]', '{# /literal #}'
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\MiscConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('misc', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\MiscConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }

    private function getFileMock()
    {
        return $this->getMockBuilder('\SplFileInfo')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
