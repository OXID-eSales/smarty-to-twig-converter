<?php

namespace sankar\ST\Tests\Converter;

use toTwig\Converter\OxstyleConverter;

/**
 * Class OxstyleConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxstyleConverterTest extends AbstractConverterTest
{
    /** @var OxstyleConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new OxstyleConverter();
    }

    /**
     * @covers \toTwig\Converter\OxstyleConverter::convert
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
            // OXID examples
            [
                "[{oxstyle include=\"css/libs/chosen/chosen.min.css\"}]",
                "{{ oxstyle({ include: \"css/libs/chosen/chosen.min.css\" }) }}"
            ],
            [
                "[{oxstyle}]",
                "{{ oxstyle() }}"
            ],
            [
                "[{oxstyle widget=\$oView->getClassName()}]",
                "{{ oxstyle({ widget: oView.getClassName() }) }}"
            ],
            [
                "[{oxstyle include=\"css/ie8.css\" if=\"IE 8\"}]",
                "{{ oxstyle({ include: \"css/ie8.css\", if: \"IE 8\" }) }}"
            ],
            // With spaces
            [
                "[{ oxstyle include=\"css/libs/chosen/chosen.min.css\" }]",
                "{{ oxstyle({ include: \"css/libs/chosen/chosen.min.css\" }) }}"
            ],
        ];
    }

    /**
     * @covers \toTwig\Converter\OxstyleConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('oxstyle', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\OxstyleConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
