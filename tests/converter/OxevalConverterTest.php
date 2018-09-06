<?php

namespace sankar\ST\Tests\Converter;

use toTwig\Converter\OxevalConverter;

/**
 * Class OxevalConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxevalConverterTest extends AbstractConverterTest
{
    /** @var OxevalConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new OxevalConverter();
    }

    /**
     * @covers \toTwig\Converter\OxevalConverter::convert
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
            // Base usage
            [
                "[{oxeval var=\$variable}]",
                "{{ oxeval({ var: variable }) }}"
            ],
            // With additional parameters
            [
                "[{oxeval var=\$variable force=true}]",
                "{{ oxeval({ var: variable, force: true }) }}"
            ],
            // With spaces
            [
                "[{ oxeval var=\$variable }]",
                "{{ oxeval({ var: variable }) }}"
            ],
        ];
    }

    /**
     * @covers \toTwig\Converter\OxevalConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('oxeval', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\OxevalConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}