<?php

namespace sankar\ST\Tests\Unit;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\OxmailtoConverter;

/**
 * Class OxmailtoConverterTest
 */
class OxmailtoConverterTest extends TestCase
{

    /** @var OxmailtoConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new OxmailtoConverter();
    }

    /**
     * @covers       \toTwig\Converter\OxmailtoConverter::convert
     *
     * @dataProvider provider
     *
     * @param $smarty
     * @param $twig
     */
    public function testThatAssignIsConverted($smarty, $twig)
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
                "[{oxmailto address='me@example.com'}]",
                "{{ mailto('me@example.com') }}"
            ],
            // Values converting
            [
                "[{oxmailto address=\$oxcmp_shop->oxshops__oxinfoemail->value}]",
                "{{ mailto(oxcmp_shop.oxshops__oxinfoemail.value) }}"
            ],
            // With parameters
            [
                "[{oxmailto address=\$oxcmp_shop->oxshops__oxinfoemail->value encode=\"javascript\"}]",
                "{{ mailto(oxcmp_shop.oxshops__oxinfoemail.value, { encode: \"javascript\" }) }}"
            ],
            // Nested quotes
            [
                "[{oxmailto address='me@example.com' subject='Subject of email' extra=\"class='email'\"}]",
                "{{ mailto('me@example.com', { subject: 'Subject of email', extra: \"class='email'\" }) }}"
            ],
            // With spaces
            [
                "[{ oxmailto address='me@example.com' }]",
                "{{ mailto('me@example.com') }}"
            ],
        ];
    }

    /**
     * @covers \toTwig\Converter\OxmailtoConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('oxmailto', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\OxmailtoConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
