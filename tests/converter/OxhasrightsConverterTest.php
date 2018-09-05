<?php

namespace sankar\ST\Tests\Converter;

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
     * @covers \toTwig\Converter\OxhasrightsConverter::convert
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
                "[{oxhasrights ident=\"TOBASKET\"}]\nfoo\n[{/oxhasrights}]",
                "{% oxhasrights \"TOBASKET\" %}\nfoo\n{% endoxhasrights %}"
            ],
            // Nested blocks
            [
                "[{oxhasrights ident=\"TOBASKET\"}]\nfoo\n[{oxhasrights ident=\"NESTED\"}]xxx\n[{/oxhasrights}][{/oxhasrights}]",
                "{% oxhasrights \"TOBASKET\" %}\nfoo\n{% oxhasrights \"NESTED\" %}xxx\n{% endoxhasrights %}{% endoxhasrights %}"
            ],
            // With spaces
            [
                "[{ oxhasrights ident=\"IDENT\" }] ... [{ /oxhasrights }]",
                "{% oxhasrights \"IDENT\" %} ... {% endoxhasrights %}"
            ],
        ];
    }

    /**
     * @covers \toTwig\Converter\ForConverter::getName
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

    /**
     * @return \SplFileInfo
     */
    private function getFileMock()
    {
        /** @var \SplFileInfo $mock */
        $mock = $this->getMockBuilder('\SplFileInfo')
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }
}
