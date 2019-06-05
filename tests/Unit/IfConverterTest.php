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
use toTwig\Converter\IfConverter;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class IfConverterTest extends TestCase
{

    /** @var IfConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new IfConverter();
    }

    /**
     * @covers       \toTwig\Converter\IfConverter::convert
     * @dataProvider provider
     */
    public function testThatIfIsConverted($smarty, $twig)
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
                // Test an if statement (no else or elseif)
                "[{if !\$foo or \$foo->bar or \$foo|bar:foo[\"hello\"]}]\nfoo\n[{/if}]",
                "{% if not foo or foo.bar or foo|bar(foo[\"hello\"]) %}\nfoo\n{% endif %}"
            ],
            [
                // Test an if with an else and a single logical operation.
                "[{if \$foo}]\nbar\n[{else}]\nfoo\n[{/if}]",
                "{% if foo %}\nbar\n{% else %}\nfoo\n{% endif %}"
            ],
            [
                // Test an if with an else and an elseif and two logical operations
                "[{if \$foo and \$awesome->string|banana:\"foo\"}]\nbar\n[{elseif \$awesome->sauce[1] and \$blue and \"hello\"}]\nfoo\n[{/if}]",
                "{% if foo and awesome.string|banana(\"foo\") %}\nbar\n{% elseif awesome.sauce[1] and blue and \"hello\" %}\nfoo\n{% endif %}"
            ],
            [
                // Test an if with an elseif and else clause.
                "[{if \$foo|bar:3 or !\$foo[3]}]\nbar\n[{elseif \$awesome->sauce[1] and blue and \"hello\"}]\nfoo\n[{else}]\nbar\n[{/if}]",
                "{% if foo|bar(3) or not foo[3] %}\nbar\n{% elseif awesome.sauce[1] and \"blue\" and \"hello\" %}\nfoo\n{% else %}\nbar\n{% endif %}"
            ],
            [
                // Test an if statement with parenthesis.
                "[{if (\$foo and \$bar) or \$foo and (\$bar or (\$foo and \$bar))}]\nbar\n[{else}]\nfoo\n[{/if}]",
                "{% if (foo and bar) or foo and (bar or (foo and bar)) %}\nbar\n{% else %}\nfoo\n{% endif %}"
            ],
            [
                // Test an elseif statement with parenthesis.
                "[{if \$foo}]\nbar\n[{elseif (\$foo and \$bar) or \$foo and (\$bar or (\$foo and \$bar))}]\nfoo\n[{/if}]",
                "{% if foo %}\nbar\n{% elseif (foo and bar) or foo and (bar or (foo and bar)) %}\nfoo\n{% endif %}"
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\IfConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('if', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\IfConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
