<?php
/**
 * Created by PhpStorm.
 * User: jskoczek
 * Date: 31/08/18
 * Time: 14:54
 */

namespace sankar\ST\Tests\Converter;

use toTwig\Converter\MathConverter;
use PHPUnit\Framework\TestCase;

class MathConverterTest extends TestCase
{
    /** @var MathConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new MathConverter();
    }

    public function Provider()
    {
        return [
            [
                "[{math equation=\"x + y\" x=1 y=2}]",
                "{{ 1 + 2 }}"
            ],
            [
                "[{math equation=\"x + y\" x=\$a y=\$b}]",
                "{{ a + b }}"
            ],
            [
                "[{math equation=\"x - y\" x=1 y=2}]",
                "{{ 1 - 2 }}"
            ],
            [
                "[{math equation=\"x / y\" x=1 y=2}]",
                "{{ 1 / 2 }}"
            ],
            [
                "[{math equation=\"x * y\" x=1 y=2}]",
                "{{ 1 * 2 }}"
            ],
            [
                "[{math equation=\"abs(x)\" x=-1}]",
                "{{ (-1) | abs }}"
            ],
            [
                "[{math equation=\"ceil(x)\" x=3.4}]",
                "{{ (3.4) | round(0, 'ceil') }}"
            ],
            [
                "[{math equation=\"exp(x)\" x=3.4}]",
                "{{ exp(3.4) }}"
            ],
            [
                "[{math equation=\"floor(x)\" x=3.4}]",
                "{{ (3.4) | round(0, 'floor') }}"
            ],
            [
                "[{math equation=\"log(x,y)\" x=3 y=10}]",
                "{{ log(3,10) }}"
            ],
            [
                "[{math equation=\"log10(x)\" x=3.4}]",
                "{{ log10(3.4) }}"
            ],
            [
                "[{math equation=\"max([x,y])\" x=6 y=2}]",
                "{{ max([6,2]) }}"
            ],
            [
                "[{math equation=\"min([x,y])\" x=6 y=2}]",
                "{{ min([6,2]) }}"
            ],
            [
                "[{math equation=\"pi()\"}]",
                "{{ pi() }}"
            ],
            [
                "[{math equation=\"pow(x,y)\" x=2 y=3}]",
                "{{ pow(2,3) }}"
            ],
            [
                "[{math equation=\"sqrt(x)\" x=3.4}]",
                "{{ sqrt(3.4) }}"
            ],
            [
                "[{math equation=\"rand(x, y)\" x=2 y=3}]",
                "{{ random([2, 3]) }}"
            ],
            [
                "[{math equation=\"round(x)\" x=3.4}]",
                "{{ (3.4) | round }}"
            ],
            [
                "[{math equation=\"cos(x)\" x=3.4}]",
                "{{ cos(3.4) }}"
            ],
            [
                "[{math equation=\"sin(x)\" x=3.4}]",
                "{{ sin(3.4) }}"
            ],
            [
                "[{math equation=\"tan(x)\" x=3.4}]",
                "{{ tan(3.4) }}"
            ],
            [
                "[{math equation=\"x + y\" x=1 y=2 assign=\"foo\"}]",
                "{% set foo = 1 + 2 %}"
            ],
            [
                "[{math equation=\"x + y\" x=1 y=2 format=\"%.2f\"}]",
                "{{ 1 + 2 | format(\"%.2f\") }}"
            ],
            [
                "[{math equation=\"a + b - c * d / e\" a=1 b=2 c=3 d=4 e=5 format=\"%.2f\"}]",
                "{{ 1 + 2 - 3 * 4 / 5 | format(\"%.2f\") }}"
            ],
            [
                "[{math equation=\"abs(sin(a) + cos(b) + tan(c) + max([c,d]) + min([c,d]) + pi() + exp(e) + log(e,f) + log10(f) + sqrt(g) + pow(f,g) + rand(g,h))\" a=1 b=2 c=3 d=4 e=5 f=6 g=7 h=8 format=\"%.2f\"}]",
                "{{ (sin(1) + cos(2) + tan(3) + max([3,4]) + min([3,4]) + pi() + exp(5) + log(5,6) + log10(6) + sqrt(7) + pow(6,7) + random([7,8])) | abs | format(\"%.2f\") }}"
            ],
            [
                "[{math equation=\"round(sin(a) + cos(b) + tan(c) + max([c,d]) + min([c,d]) + pi() + exp(e) + log(e,f) + log10(f) + sqrt(g) + pow(f,g) + rand(g,h))\" a=1 b=2 c=3 d=4 e=5 f=6 g=7 h=8 format=\"%.2f\"}]",
                "{{ (sin(1) + cos(2) + tan(3) + max([3,4]) + min([3,4]) + pi() + exp(5) + log(5,6) + log10(6) + sqrt(7) + pow(6,7) + random([7,8])) | round | format(\"%.2f\") }}"
            ],
            [
                "[{math equation=\"ceil(sin(a) + cos(b) + tan(c) + max([c,d]) + min([c,d]) + pi() + exp(e) + log(e,f) + log10(f) + sqrt(g) + pow(f,g) + rand(g,h))\" a=1 b=2 c=3 d=4 e=5 f=6 g=7 h=8 format=\"%.2f\"}]",
                "{{ (sin(1) + cos(2) + tan(3) + max([3,4]) + min([3,4]) + pi() + exp(5) + log(5,6) + log10(6) + sqrt(7) + pow(6,7) + random([7,8])) | round(0, 'ceil') | format(\"%.2f\") }}"
            ],
            [
                "[{math equation=\"floor(sin(a) + cos(b) + tan(c) + max([c,d]) + min([c,d]) + pi() + exp(e) + log(e,f) + log10(f) + sqrt(g) + pow(f,g) + rand(g,h))\" a=1 b=2 c=3 d=4 e=5 f=6 g=7 h=8 format=\"%.2f\"}]",
                "{{ (sin(1) + cos(2) + tan(3) + max([3,4]) + min([3,4]) + pi() + exp(5) + log(5,6) + log10(6) + sqrt(7) + pow(6,7) + random([7,8])) | round(0, 'floor') | format(\"%.2f\") }}"
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\MAthConverter::convert
     * @dataProvider Provider
     *
     * @param $smarty
     * @param $twig
     */
    public function testThatMAthIsConverted($smarty, $twig)
    {
        // Test the above cases
        /** @var \SplFileInfo $fileMock */
        $fileMock = $this->getFileMock();
        $this->assertSame($twig, $this->converter->convert($fileMock, $smarty));
    }

    /**
     * @covers \toTwig\Converter\MathConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('math', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\MathConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }

    private function getFileMock()
    {
        return $this->getMockBuilder('\SplFileInfo')->disableOriginalConstructor()->getMock();
    }
}
