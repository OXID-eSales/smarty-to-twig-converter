<?php

namespace sankar\ST\Tests\Unit;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\OxevalConverter;

/**
 * Class OxevalConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxevalConverterTest extends TestCase
{

    /** @var OxevalConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new OxevalConverter();
    }

    /**
     * @covers       \toTwig\Converter\OxevalConverter::convert
     *
     * @dataProvider provider
     *
     * @param $smarty
     * @param $twig
     */
    public function testThatAssignIsConverted($smarty, $twig)
    {
        // Test the above cases
        $this->assertSame($twig, $this->converter->convert($smarty));
    }

    /**
     * @return array
     */
    public function provider()
    {
        return [
            // Base usage
            [
                "[{oxeval var=\$variable}]",
                "{{ include(template_from_string(variable)) }}"
            ],
            // With spaces
            [
                "[{ oxeval var=\$variable }]",
                "{{ include(template_from_string(variable)) }}"
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
