<?php

namespace sankar\ST\Tests\Converter;

use toTwig\Converter\OxmailtoConverter;

/**
 * Class OxmailtoConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxmailtoConverterTest extends AbstractConverterTest
{
    /** @var OxmailtoConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new OxmailtoConverter();
    }

    /**
     * @covers \toTwig\Converter\OxmailtoConverter::convert
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
            // Basic usage
            [
                "[{oxmailto address='me@example.com'}]",
                "{{ oxmailto('me@example.com') }}"
            ],
            // Values converting
            [
                "[{oxmailto address=\$oxcmp_shop->oxshops__oxinfoemail->value}]",
                "{{ oxmailto(oxcmp_shop.oxshops__oxinfoemail.value) }}"
            ],
            // With parameters
            [
                "[{oxmailto address=\$oxcmp_shop->oxshops__oxinfoemail->value encode=\"javascript\"}]",
                "{{ oxmailto(oxcmp_shop.oxshops__oxinfoemail.value, { encode: \"javascript\" }) }}"
            ],
            // Nested quotes
            [
                "[{oxmailto address='me@example.com' subject='Subject of email' extra=\"class='email'\"}]",
                "{{ oxmailto('me@example.com', { subject: 'Subject of email', extra: \"class='email'\" }) }}"
            ],
            // With spaces
            [
                "[{ oxmailto address='me@example.com' }]",
                "{{ oxmailto('me@example.com') }}"
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
