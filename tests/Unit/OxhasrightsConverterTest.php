<?php

namespace sankar\ST\Tests\Unit;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\OxhasrightsConverter;

/**
 * Class OxhasrightsConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxhasrightsConverterTest extends TestCase
{

    /** @var OxhasrightsConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new OxhasrightsConverter();
    }

    /**
     * @covers       \toTwig\Converter\OxhasrightsConverter::convert
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
            //basic usage
            [
                '[{oxhasrights object=$edit readonly=$readonly}]',
                '{% hasrights { "object": "edit", "readonly": "readonly", } %}'
            ],
            //all arguments
            [
                '[{oxhasrights type=$type field=field right=right object=object readonly=$readonly ident=ident}]',
                '{% hasrights {"type": "type", "field": "field", "right": "right", "object": "object", "readonly": "readonly", "ident": "ident",} %}'
            ],
            //spaces in tags
            [
                '[{ oxhasrights type=$type }]',
                '{% hasrights {"type": "type", } %}'
            ],
            //random order of arguments
            [
                '[{oxhasrights ident=$ident readonly=$readonly object=$object right=$right field=$field type=$type }]',
                '{% hasrights {"type": "type", "field": "field", "right": "right", "object": "object", "readonly": "readonly", "ident": "ident",} %}'
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\OxhasrightsConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('oxhasrights', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\OxhasrightsConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
