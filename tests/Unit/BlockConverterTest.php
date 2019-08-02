<?php

namespace sankar\ST\Tests\Unit;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\BlockConverter;

/**
 * Class BlockConverterTest
 */
class BlockConverterTest extends TestCase
{

    /** @var BlockConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new BlockConverter();
    }

    /**
     * @covers       \toTwig\Converter\BlockConverter::convert
     *
     * @dataProvider provider
     *
     * @param $smarty
     * @param $twig
     */
    public function testThatForIsConverted($smarty, $twig)
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
            // Basic usage
            [
                "[{block name=\"title\"}]Default Title[{/block}]",
                "{% block title %}Default Title{% endblock %}"
            ],
            // Short-hand
            [
                "[{block \"title\"}]Default Title[{/block}]",
                "{% block title %}Default Title{% endblock %}"
            ],
            // Prepend
            [
                "[{block name=\"title\" prepend}]\nPage Title\n[{/block}]",
                "{% block title %}{{ parent() }}\nPage Title\n{% endblock %}"
            ],
            // Extends
            [
                "[{extends file=\"parent.tpl\"}]",
                "{% extends \"parent.html.twig\" %}"
            ],
            // $smarty.block.parent
            [
                "[{\$smarty.block.parent}]",
                "{{ parent() }}"
            ],
            // Extends with parent call
            [
                "[{extends file=\"parent.tpl\"}]
                [{block name=\"title\"}]
                    You will see now - [{\$smarty.block.parent}] - here
                [{/block}]",
                "{% extends \"parent.html.twig\" %}
                {% block title %}
                    You will see now - {{ parent() }} - here
                {% endblock %}"
            ],
        ];
    }

    /**
     * @covers \toTwig\Converter\BlockConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('block', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\BlockConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
