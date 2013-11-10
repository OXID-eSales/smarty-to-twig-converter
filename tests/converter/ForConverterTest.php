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
use sankar\ST\Converter\ForConverter;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class ForConverterTest extends \PHPUnit_Framework_TestCase
{
    protected $converter;

    public function setUp()
    {
        $this->converter = new ForConverter();
    }
    /**
     * @covers sankar\ST\Converter\ForConverter::convert
     * @dataProvider Provider
     */
    public function testThatForIsConverted($smarty,$twig)
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
                        '{foreach $myColors as $color}\nfoo{/foreach}',
                        '{% for color in myColors %}\nfoo\n{% endfor %}'
                    ),
                array(
                        '{foreach $contact as $key => $value}\nfoo{/foreach}',
                        '{% for key,value in contact %}\nfoo{% endfor %}'
                    ),
                array( 
                        '{foreach name=outer item=contact from=$contacts}\nfoo{/foreach}',
                        '{% for contact in contacts %}\nfoo{% endfor %}'
                    ), 
                array(
                        '{foreach key=key item=item from=$contact}\nfoo\n{foreachelse}bar{/foreach}',
                        '{% for key,item in contact %}\nfoo\n{% else %}bar{% endfor %}'
                    ),
            );
    }

    /**
     * @covers sankar\ST\Converter\ForConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('for', $this->converter->getName());
    }

    /**
     * @covers sankar\ST\Converter\ForConverter::getDescription
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
