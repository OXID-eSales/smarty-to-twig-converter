<?php

namespace sankar\ST\Tests\Converter;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\OxmultilangConverter;

/**
 * Class OxmultilangConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxmultilangConverterTest extends TestCase
{
    /** @var OxmultilangConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new OxmultilangConverter();
    }

    /**
     * @covers \toTwig\Converter\OxmultilangConverter::convert
     *
     * @dataProvider Provider
     *
     * @param $smarty
     * @param $twig
     */
    public function testThatAssignIsConverted($smarty, $twig)
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
                "[{oxmultilang ident=\"ERROR_404\"}]",
                "{{ oxmultilang({ ident: \"ERROR_404\" }) }}"
            ],
            [
                "[{oxmultilang noerror=true ident=\$menuitem->getAttribute('name')|default:\$menuitem->getAttribute('id')}]",
                "{{ oxmultilang({ noerror: true, ident: menuitem.getAttribute('name')|default(menuitem.getAttribute('id')) }) }}"
            ],
        ];
    }

    /**
     * @covers \toTwig\Converter\OxmultilangConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('oxmultilang', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\OxmultilangConverter::getDescription
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
