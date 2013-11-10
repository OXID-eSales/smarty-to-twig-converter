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
use sankar\ST\Converter\VariableConverter;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class CommentconverterTest extends \PHPUnit_Framework_TestCase
{
    protected $converter;

    public function setUp()
    {
        $this->converter = new VariableConverter();
    }
    /**
     * @covers sankar\ST\Converter\VariableConverter::convert
     * @dataProvider Provider
     */
    public function testThatVariableIsConverted($smarty,$twig)
    {
        $this->assertSame($twig,
            $this->converter->convert($this->getFileMock(), $smarty)
        );
       
    }

    public function Provider()
    {
        return array(
                array( 
                    '{$var}','{{ var }}'
                    ),
                array(
                    '{$contacts.fax}','{{ contacts.fax }}'
                    ),
                array(
                    '{$contacts[0]}','{{ contacts[0] }}'
                    ),
                array(
                    '{$contacts[2][0]}','{{ contacts[2][0] }}'
                    ),
                array(
                    '{$person->name}','{{ person.name }}'
                    )
            );
    }

    /**
     * @covers sankar\ST\Converter\Variableconverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('variable', $this->converter->getName());
    }

    /**
     * @covers sankar\ST\Converter\Variableconverter::getDescription
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
