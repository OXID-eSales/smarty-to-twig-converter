<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Tests\Unit;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\OxcontentConverter;

/**
 * Class OxcontentConverterTest
 */
class OxcontentConverterTest extends TestCase
{
    protected OxcontentConverter $converter;

    public function setUp(): void
    {
        $this->converter = new OxcontentConverter();
    }

    /**
     * @covers \toTwig\Converter\OxcontentConverter::convert
     *
     * @dataProvider provider
     */
    public function testThatAssignIsConverted(string $smarty, string $twig): void
    {
        // Test the above cases
        $this->assertSame(
            $twig,
            $this->converter->convert($smarty)
        );
    }

    public function provider(): array
    {
        return [
            // Base usage
            [
                "[{oxcontent ident='oxregisteremail'}]",
                "{% include_content 'oxregisteremail' %}"
            ],
            // As assignment
            [
                "[{oxcontent ident='oxregisteremail' assign=\$var}]",
                "{% set var = content('oxregisteremail') %}"
            ],
        ];
    }

    /**
     * @covers \toTwig\Converter\OxcontentConverter::getName
     */
    public function testThatHaveExpectedName(): void
    {
        $this->assertEquals('oxcontent', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\OxcontentConverter::getDescription
     */
    public function testThatHaveDescription(): void
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
