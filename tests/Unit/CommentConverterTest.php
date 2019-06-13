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
use toTwig\Converter\CommentConverter;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class CommentConverterTest extends TestCase
{

    /** @var CommentConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new CommentConverter();
    }

    /**
     * @covers       \toTwig\Converter\CommentConverter::convert
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
                '[{* foo *}]',
                '{# foo #}'
            ],
            [
                '[{*foo*}]',
                '{# foo #}'
            ],
            [
                '[{block name="dd_layout_page_header_icon_menu_languages"}]
                    [{* Language Dropdown*}]
                    [{oxid_include_widget cl="oxwLanguageList" lang=$oViewConf->getActLanguageId() _parent=$oView->getClassName() nocookie=1 _navurlparams=$oViewConf->getNavUrlParams() anid=$oViewConf->getActArticleId()}]
                 [{/block}]',
                '[{block name="dd_layout_page_header_icon_menu_languages"}]
                    {# Language Dropdown #}
                    [{oxid_include_widget cl="oxwLanguageList" lang=$oViewConf->getActLanguageId() _parent=$oView->getClassName() nocookie=1 _navurlparams=$oViewConf->getNavUrlParams() anid=$oViewConf->getActArticleId()}]
                 [{/block}]'
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\CommentConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('comment', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\CommentConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
