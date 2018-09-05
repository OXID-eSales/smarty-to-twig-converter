<?php

namespace sankar\ST\Tests\Converter;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\OxifcontentConverter;

/**
 * Class OxifcontentConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxifcontentConverterTest extends TestCase
{
    /** @var OxifcontentConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new OxifcontentConverter();
    }

    /**
     * @covers \toTwig\Converter\OxifcontentConverter::convert
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
            [
                "[{oxifcontent ident=\"TOBASKET\" object=\"aObject\"}]\nfoo\n[{/oxifcontent}]",
                "{% oxifcontent { ident: \"TOBASKET\", object: \"aObject\" } %}\nfoo\n{% endoxifcontent %}"
            ],
            [
                "[{oxifcontent ident=\$x object=\$y}]\nfoo\n[{/oxifcontent}]",
                "{% oxifcontent { ident: x, object: y } %}\nfoo\n{% endoxifcontent %}"
            ],
            [
                "[{oxifcontent ident=\$x object=\$y}]\nfoo\n[{oxifcontent ident=\$x2 object=\$y2}]bar[{/oxifcontent}][{/oxifcontent}]",
                "{% oxifcontent { ident: x, object: y } %}\nfoo\n{% oxifcontent { ident: x2, object: y2 } %}bar{% endoxifcontent %}{% endoxifcontent %}"
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\OxifcontentConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('oxifcontent', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\OxifcontentConverter::getDescription
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
