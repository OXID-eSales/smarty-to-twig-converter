<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Tests\Unit;

use toTwig\Converter\GetRequestVariablesConverter;
use PHPUnit\Framework\TestCase;

class GetRequestVariablesConverterTest extends TestCase
{

    /** @var GetRequestVariablesConverter */
    protected $converter;

    public function setUp(): void
    {
        $this->converter = new GetRequestVariablesConverter();
    }

    /**
     * @covers       \toTwig\Converter\AssignConverter::convert
     *
     * @dataProvider provider
     *
     * @param $smarty
     * @param $twig
     */
    public function testThatAssignIsConverted($smarty, $twig)
    {
        // Test the above cases
        $this->assertSame(
            $twig,
            $this->converter->convert($smarty)
        );
    }

    /**
     * @return array
     */
    public function provider()
    {
        return [
            [
                '[{$smarty.cookies.foo}]',
                '[{get_global_cookie("foo")}]'
            ],
            [
                '[{ $smarty.cookies.foo }]',
                '[{ get_global_cookie("foo") }]'
            ],
            [
                '[{if $oView->isEnabled() && $smarty.cookies.foo != \'1\'}]',
                '[{if $oView->isEnabled() && get_global_cookie("foo") != \'1\'}]'
            ],
            [
                '[{$smarty.get.foo}]',
                '[{get_global_get("foo")}]'
            ],
            [
                '[{if $oView->isEnabled() && $smarty.get.foo != \'1\'}]',
                '[{if $oView->isEnabled() && get_global_get("foo") != \'1\'}]'
            ],
            [
                '[{$smarty.get.foo $smarty.cookies.foo}]',
                '[{get_global_get("foo") get_global_cookie("foo")}]'
            ],
            [
                '[{$smarty.cookies.foo $smarty.get.foo}]',
                '[{get_global_cookie("foo") get_global_get("foo")}]'
            ]
        ];
    }
}
