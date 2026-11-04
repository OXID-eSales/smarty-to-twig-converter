<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Tests\Unit;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\OxifcontentConverter;

/**
 * Class OxifcontentConverterTest
 */
class OxifcontentConverterTest extends TestCase
{

    /** @var OxifcontentConverter */
    protected $converter;

    public function setUp(): void
    {
        $this->converter = new OxifcontentConverter();
    }

    /**
     * @covers       \toTwig\Converter\OxifcontentConverter::convert
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
                "[{oxifcontent ident=\"TOBASKET\" object=\"aObject\"}]\nfoo\n[{/oxifcontent}]",
                "{% ifcontent ident \"TOBASKET\" set aObject %}\nfoo\n{% endifcontent %}"
            ],
            // Values converting
            [
                "[{oxifcontent ident=\$x object=\"y\"}]\nfoo\n[{/oxifcontent}]",
                "{% ifcontent ident x set y %}\nfoo\n{% endifcontent %}"
            ],
            // Assignment
            [
                "[{oxifcontent ident=\"TOBASKET\" object=\"aObject\" assign=\$var}]\nfoo\n[{/oxifcontent}]",
                "{% set var %}{% ifcontent ident \"TOBASKET\" set aObject %}\nfoo\n{% endifcontent %}{% endset %}"
            ],
            // With spaces
            [
                "[{ oxifcontent ident=\"TOBASKET\" object=\"aObject\" }]\nfoo\n[{ /oxifcontent }]",
                "{% ifcontent ident \"TOBASKET\" set aObject %}\nfoo\n{% endifcontent %}"
            ],
            // Without an object attribute
            [
                "[{oxifcontent ident=\"TOBASKET\"}]\nfoo\n[{/oxifcontent}]",
                "{% ifcontent ident \"TOBASKET\" %}\nfoo\n{% endifcontent %}"
            ],
            // Neither ident nor oxid (e.g. html escaped attributes in database content):
            // the opening tag is kept as is instead of raising an error
            [
                "[{oxifcontent ident=&quot;oxagb&quot; object=&quot;oCont&quot;}]\nfoo\n[{/oxifcontent}]",
                "[{oxifcontent ident=&quot;oxagb&quot; object=&quot;oCont&quot;}]\nfoo\n{% endifcontent %}"
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
}
