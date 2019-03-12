<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) sankar suda <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace sankar\ST\Tests\Unit;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\VariableConverter;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class VariableConverterTest extends TestCase
{

    /** @var VariableConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new VariableConverter();
    }

    /**
     * @covers       \toTwig\Converter\VariableConverter::convert
     * @dataProvider Provider
     */
    public function testThatVariableIsConverted($smarty, $twig)
    {
        $this->assertSame(
            $twig,
            $this->converter->convert($smarty)
        );
    }

    public function Provider()
    {
        return [
            [
                "[{\$var}]",
                "{{ var }}"
            ],
            [
                "[{\$contacts.fax}]",
                "{{ contacts.fax }}"
            ],
            [
                "[{\$contacts[0]}]",
                "{{ contacts[0] }}"
            ],
            [
                "[{\$contacts[2][0]}]",
                "{{ contacts[2][0] }}"
            ],
            [
                "[{\$person->name}]",
                "{{ person.name }}"
            ],
            [
                "[{\$oViewConf->getImageUrl(\$sLangImg)}]",
                "{{ oViewConf.getImageUrl(sLangImg) }}"
            ],
            [
                "[{\$_cur->link|oxaddparams:\$oView->getDynUrlParams()}]",
                "{{ _cur.link|add_url_parameters(oView.getDynUrlParams()) }}"
            ],
            [
                "[{(\$a && \$b) || \$c}]",
                "{{ (a and b) or c }}"
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\VariableConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('variable', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\VariableConverter::getDescription
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
