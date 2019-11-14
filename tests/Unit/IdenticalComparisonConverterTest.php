<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Tests\Unit;

use toTwig\Converter\IdenticalComparisonConverter;

class IdenticalComparisonConverterTest extends FileConversionUnitTestCase
{

    /** @var IdenticalComparisonConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new IdenticalComparisonConverter();
        $this->templateNames = ['identical-comparison'];
        parent::setUp();
    }

    /**
     * @covers       \toTwig\Converter\IdenticalComparison::convert
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
                '1 === 1',
                '1 is same as(1)'
            ],
            [
                '1===1',
                '1 is same as(1)'
            ],
            [
                '1 === "foo"',
                '1 is same as("foo")'
            ],
            [
                '1==="foo"',
                '1 is same as("foo")'
            ],
            [
                '"foo" === "bar"',
                '"foo" is same as("bar")'
            ],
            [
                '"foo"==="bar"',
                '"foo" is same as("bar")'
            ],
            [
                '{% if "foo" === "bar" %}',
                '{% if "foo" is same as("bar") %}'
            ],
            [
                '{% if "foo"==="bar" %}',
                '{% if "foo" is same as("bar") %}'
            ],
            [
                '{% if "foo" === "bar" %}',
                '{% if "foo" is same as("bar") %}'
            ],
            [
                '{% if "foo"==="bar" %}',
                '{% if "foo" is same as("bar") %}'
            ],
            [
                '{% if "foo"==="foo bar" %}',
                '{% if "foo" is same as("foo bar") %}'
            ],
            [
                '{% if $oView->isConfirmAGBError() === 1 %}',
                '{% if $oView->isConfirmAGBError() is same as(1) %}'
            ],
            [
                '{%if $oView->isConfirmAGBError() === 1%}',
                '{%if $oView->isConfirmAGBError() is same as(1)%}'
            ],
            [
                '{% block checkout_order_errors %}
                    {% if oView.isConfirmAGBError() === 1 %}
                    {% include "message/error.html.twig" with {statusMessage: "READ_AND_CONFIRM_TERMS"|translate} %}
                {% endblock %}',
                '{% block checkout_order_errors %}
                    {% if oView.isConfirmAGBError() is same as(1) %}
                    {% include "message/error.html.twig" with {statusMessage: "READ_AND_CONFIRM_TERMS"|translate} %}
                {% endblock %}'
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\IdenticalComparison::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('identical_comparison_converter', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\IdenticalComparison::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
