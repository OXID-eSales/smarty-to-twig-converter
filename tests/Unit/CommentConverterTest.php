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

use toTwig\Converter\CommentConverter;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class CommentConverterTest extends FileConversionUnitTestCase
{
    /** @var CommentConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new CommentConverter();
        $this->templateNames = ['comment'];
        parent::setUp();
    }

    protected function getCommandParameters(string $templateName): array
    {
        return ['--path' => $this->getSmartyTemplatePath($templateName), '--converters' => 'comment'];
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
                "[{*foo\nbar*}]",
                "{# foo\nbar #}"
            ],
            [
                '[{* foo *}] bar [{* baz *}]',
                '{# foo #} bar {# baz #}'
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
