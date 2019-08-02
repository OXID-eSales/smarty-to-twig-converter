<?php

namespace sankar\ST\Tests\Unit;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\MailtoConverter;

/**
 * Class MailtoConverterTest
 */
class MailtoConverterTest extends TestCase
{

    /** @var MailtoConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new MailtoConverter();
    }

    /**
     * @covers       \toTwig\Converter\AssignConverter::convert
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
            [
                "[{mailto address='me@example.com'}]",
                "{{ mailto('me@example.com') }}"
            ],
            [
                "[{mailto address='me@example.com' text='send me some mail'}]",
                "{{ mailto('me@example.com', { text: 'send me some mail' }) }}"
            ],
            [
                "[{mailto address='me@example.com' extra='class='email''}]",
                "{{ mailto('me@example.com', { extra: 'class='email'' }) }}"
            ],
            [
                "[{mailto address='me@example.com' subject='Subject of email' extra='class='email''}]",
                "{{ mailto('me@example.com', { subject: 'Subject of email', extra: 'class='email'' }) }}"
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\AssignConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('mailto', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\AssignConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
