<?php

namespace sankar\ST\Tests\Converter;

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
            [
                "[{oxeval var=\$variable}]",
                "{{ oxeval({ var: variable }) }}"
            ],
            [
                "[{oxeval var=\$variable force=true}]",
                "{{ oxeval({ var: variable, force: true }) }}"
            ]
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