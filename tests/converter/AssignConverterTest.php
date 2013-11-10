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
use sankar\ST\Converter\AssignConverter;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class AssignConverterTest extends \PHPUnit_Framework_TestCase
{
    protected $converter;

    public function setUp()
    {
        $this->converter = new AssignConverter();
    }
    /**
     * @covers sankar\ST\Converter\AssignConverter::convert
     * @dataProvider Provider
     */
    public function testThatAssignIsConverted($smarty,$twig)
    {

        // Test the above cases
        $this->assertSame($twig,
            $this->converter->convert($this->getFileMock(), $smarty)
        );
       
    }

    public function Provider()
    {
        return array(
                array( 
                        '{assign var="name" value="Bob"}',
                        '{% set name = \'Bob\' %}'
                    ),
                array( 
                        '{assign var="name" value=$bob}',
                        '{% set name = bob %}'
                    ),                
                array(
                        '{assign "name" "Bob"}',
                        '{% set name = \'Bob\' %}'
                    ),
                array( 
                        '{assign var="foo" "bar" scope="global"}',
                        '{% set foo = \'bar\' %}'
                    )
            );
    }

    /**
     * @covers sankar\ST\Converter\AssignConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('assign', $this->converter->getName());
    }

    /**
     * @covers sankar\ST\Converter\AssignConverter::getDescription
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
