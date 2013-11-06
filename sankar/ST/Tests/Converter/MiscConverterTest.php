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

use sankar\ST\Converter;
use sankar\ST\ConverterAbstract;
use sankar\ST\Converter\MiscConverter;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class CommentconverterTest extends \PHPUnit_Framework_TestCase
{
    protected $converter;

    public function setUp()
    {
        $this->converter = new MiscConverter();
    }
    /**
     * @covers sankar\ST\Converter\MiscConverter::convert
     * @dataProvider Provider
     */
    public function testThatMiscIsConverted($smarty,$twig)
    {
        $this->assertSame($twig,
            $this->converter->convert($this->getFileMock(), $smarty)
        );
       
    }

    public function Provider()
    {
        return array(
                array( 
                    '{ldelim}',''
                    ),
                array(
                    '{rdelim}',''
                    ),
                array(
                    '{literal}','{# literal #}'
                    ),
                array(
                    '{/literal}','{# /literal #}'
                    )
            );
    }

    /**
     * @covers sankar\ST\Converter\Miscconverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('misc', $this->converter->getName());
    }

    /**
     * @covers sankar\ST\Converter\Miscconverter::getDescription
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
