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

use toTwig\Converter\ForConverter;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class ForConverterTest extends AbstractConverterTest
{
    /** @var ForConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new ForConverter();
    }

    /**
     * @covers       \toTwig\Converter\ForConverter::convert
     * @dataProvider Provider
     */
    public function testThatForIsConverted($smarty, $twig)
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
                "[{foreach \$myColors as \$color}]\nfoo\n[{/foreach}]",
                "{% for color in myColors %}\nfoo\n{% endfor %}"
            ],
            [
                "[{foreach \$contact as \$key => \$value}]\nfoo\n[{/foreach}]",
                "{% for key, value in contact %}\nfoo\n{% endfor %}"
            ],
            [
                "[{foreach name=outer item=contact from=\$contacts}]\nfoo\n[{/foreach}]",
                "{% for contact in contacts %}\nfoo\n{% endfor %}"
            ],
            [
                "[{foreach key=key item=item from=\$contact}]\nfoo\n[{foreachelse}]\nbar\n[{/foreach}]",
                "{% for key, item in contact %}\nfoo\n{% else %}\nbar\n{% endfor %}"
            ],
            [
                "[{foreach from=\$Errors.basket item=oEr key=key}]\n[{/foreach}]",
                "{% for key, oEr in Errors.basket %}\n{% endfor %}"
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\ForConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('for', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\ForConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
