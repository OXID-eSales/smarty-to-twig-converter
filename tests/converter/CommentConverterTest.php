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
use toTwig\Converter\CommentConverter;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class CommentConverterTest extends TestCase
{
    /** @var CommentConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new CommentConverter();
    }

    /**
     * @covers       \toTwig\Converter\CommentConverter::convert
     * @dataProvider Provider
     */
    public function testThatIfIsConverted($smarty, $twig)
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
                '{* foo *}',
                '{# foo #}'
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\CommentConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('comment', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\CommentConverter::getDescription
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
