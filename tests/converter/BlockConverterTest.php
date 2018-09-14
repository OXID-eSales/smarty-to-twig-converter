<?php

namespace sankar\ST\Tests\Converter;

use toTwig\Converter\BlockConverter;

/**
 * Class BlockConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class BlockConverterTest extends AbstractConverterTest
{
    /** @var BlockConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new BlockConverter();
    }

    /**
     * @covers \toTwig\Converter\BlockConverter::convert
     *
     * @dataProvider Provider
     *
     * @param $smarty
     * @param $twig
     */
    public function testThatForIsConverted($smarty, $twig)
    {
        // Test the above cases
        $this->assertSame($twig,
            $this->converter->convert($this->getFileMock(), $smarty)
        );
    }

    /**
     * @return array
     */
    public function Provider()
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
                "[{extends file=\"parent.tpl\"}]\n[{block name=\"title\"}]\nYou will see now - [{\$smarty.block.parent}] - here\n[{/block}]",
                "{% extends \"parent.html.twig\" %}\n{% block title %}\nYou will see now - {{ parent() }} - here\n{% endblock %}"
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
