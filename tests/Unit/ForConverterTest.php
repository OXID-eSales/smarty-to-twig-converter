<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) sankar suda <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace toTwig\Tests\Unit;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\ForConverter;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class ForConverterTest extends TestCase
{

    /** @var ForConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new ForConverter();
    }

    /**
     * @covers       \toTwig\Converter\ForConverter::convert
     * @dataProvider provider
     */
    public function testThatForIsConverted($smarty, $twig)
    {
        // Test the above cases
        $this->assertSame(
            $twig,
            $this->converter->convert($smarty)
        );
    }

    public function provider()
    {
        return [
            [
                "[{foreach \$myColors as \$color}]
                    foo
                [{/foreach}]",
                "{% for color in myColors %}
                    foo
                {% endfor %}"
            ],
            [
                "[{foreach \$contact as \$key => \$value}]
                    foo
                [{/foreach}]",
                "{% for key, value in contact %}
                    foo
                {% endfor %}"
            ],
            [
                "[{foreach name=outer item=contact from=\$contacts}]
                    foo
                [{/foreach}]",
                "{% for contact in contacts %}
                    foo
                {% endfor %}"
            ],
            [
                "[{foreach key=key item=item from=\$contact}]
                    foo
                [{foreachelse}]
                    bar
                [{/foreach}]",
                "{% for key, item in contact %}
                    foo
                {% else %}
                    bar
                {% endfor %}"
            ],
            [
                "[{foreach from=\$Errors.basket item=oEr key=key}]
                [{/foreach}]",
                "{% for key, oEr in Errors.basket %}
                {% endfor %}"
            ],
            [
                "[{foreach from=\$articleList key=iProdNr item='product' name='compareArticles'}]
                    [{\$smarty.foreach.compareArticles.first}]
                [{/foreach}]",
                "{% for iProdNr, product in articleList %}
                    [{\$loop.first}]
                {% endfor %}"
            ],
            [
                "[{foreach from=\$articleList key=iProdNr item='product' name='compareArticles'}]
                    [{\$smarty.foreach.compareArticles.first}]
                [{/foreach}]",
                "{% for iProdNr, product in articleList %}
                    [{\$loop.first}]
                {% endfor %}"
            ],
            [
                "[{foreach from=\$articleList key=iProdNr item='product' name='compareArticles'}]
                    [{\$smarty.foreach.compareArticles.last}]
                [{/foreach}]",
                "{% for iProdNr, product in articleList %}
                    [{\$loop.last}]
                {% endfor %}"
            ],
            [
                "[{foreach from=\$articleList key=iProdNr item='product' name='compareArticles'}]
                    [{\$smarty.foreach.compareArticles.index}]
                [{/foreach}]",
                "{% for iProdNr, product in articleList %}
                    [{\$loop.index0}]
                {% endfor %}"
            ],
            [
                "[{foreach from=\$articleList key=iProdNr item='product' name='compareArticles'}]
                    [{\$smarty.foreach.compareArticles.iteration}]
                [{/foreach}]",
                "{% for iProdNr, product in articleList %}
                    [{\$loop.index}]
                {% endfor %}"
            ],
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
