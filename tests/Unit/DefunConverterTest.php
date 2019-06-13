<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace sankar\ST\Tests\Unit;

use toTwig\Converter\DefunConverter;
use PHPUnit\Framework\TestCase;

class DefunConverterTest extends TestCase
{

    /** @var DefunConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new DefunConverter();
    }

    /**
     * @covers       \toTwig\Converter\DefunConverter::convert
     * @dataProvider provider
     *
     * @param $smarty
     * @param $twig
     */
    public function testThatIncludeIsConverted($smarty, $twig)
    {
        $this->assertSame($twig, $this->converter->convert($smarty));
    }

    public function provider()
    {
        return [
            [
                '[{defun name="foo" list=bar}]',
                '{% macro foo(list) %}'
            ],
            [
                '[{/defun}]',
                '{% endmacro %}{% import _self as self %}{{ self.() }}'
            ],
            [
                '[{defun name="foo" var=bar}]hello[{/defun}]',
                '{% macro foo(var) %}hello{% endmacro %}{% import _self as self %}{{ self.foo(bar) }}'
            ],
            [
                '[{defun name="foo" var=bar otherVar=otherBar}]
                    hello
                [{/defun}]',
                '{% macro foo(var, otherVar) %}
                    hello
                {% endmacro %}{% import _self as self %}{{ self.foo(bar, otherBar) }}'
            ],
            [
                '[{defun name="foo" var=bar otherVar=otherBar}]
                    [{fun name="nestedMacro" var=bar otherVar=otherBar}]
                [{/defun}]',
                '{% macro foo(var, otherVar) %}
                    {% import _self as self %}{{ self.nestedMacro(var, otherVar) }}
                {% endmacro %}{% import _self as self %}{{ self.foo(bar, otherBar) }}'
            ]
        ];
    }
}
