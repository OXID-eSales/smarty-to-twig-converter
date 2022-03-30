<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Tests\Unit;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\OxevalConverter;

/**
 * Class OxevalConverterTest
 */
class OxevalConverterTest extends TestCase
{

    /** @var OxevalConverter */
    protected $converter;

    public function setUp(): void
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
