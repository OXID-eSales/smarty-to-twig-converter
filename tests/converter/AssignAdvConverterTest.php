<?php

namespace sankar\ST\Tests\Converter;

use PHPUnit\Framework\TestCase;
use toTwig\Converter\AssignAdvConverter;

/**
 * Class AssignAdvConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class AssignAdvConverterTest extends TestCase
{
    /** @var AssignAdvConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new AssignAdvConverter();
    }

    /**
     * @covers \toTwig\Converter\AssignAdvConverter::convert
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
            // Few examples from assign (compatibility)
            [
                "[{assign_adv var=\"name\" value=\"Bob\"}]",
                "{% set name = oxassign(\"Bob\") %}"
            ],
            [
                "[{assign_adv var=\"name\" value=\$bob}]",
                "{% set name = oxassign(bob) %}"
            ],
            [
                "[{assign_adv var=\"where\" value=\$oView->getListFilter()}]",
                "{% set where = oxassign(oView.getListFilter()) %}"
            ],
            [
                "[{assign_adv var=\"template_title\" value=\"MY_WISH_LIST\"|oxmultilangassign}]",
                "{% set template_title = oxassign(\"MY_WISH_LIST\"|oxmultilangassign) %}"
            ],
            // Example for assign_dev function
            [
                "[{assign_adv var=\"invite_array\" value=\"array('0' => '\$sender_name', '1' => '\$shop_name')\"}]",
                "{% set invite_array = oxassign(\"array('0' => '\$sender_name', '1' => '\$shop_name')\") %}"
            ],
            // With spaces
            [
                "[{ assign_adv var=\"name\" value=\"Bob\" }]",
                "{% set name = oxassign(\"Bob\") %}"
            ],
        ];
    }

    /**
     * @covers \toTwig\Converter\AssignAdvConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('assign_adv', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\AssignAdvConverter::getDescription
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
