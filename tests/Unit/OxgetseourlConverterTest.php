<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Tests\Unit;

use toTwig\Converter\OxgetseourlConverter;

/**
 * Class OxgetseourlConverterTest
 */
class OxgetseourlConverterTest extends FileConversionUnitTestCase
{

    /** @var OxgetseourlConverter */
    protected $converter;

    public function setUp(): void
    {
        $this->converter = new OxgetseourlConverter();
        $this->templateNames = ['oxgetseourl'];
        parent::setUp();
    }

    /**
     * @covers       \toTwig\Converter\OxgetseourlConverter::convert
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
            // Example from OXID
            [
                "[{oxgetseourl ident=\$oViewConf->getSelfLink()|cat:\"cl=basket\"}]",
                "{{ seo_url({ ident: oViewConf.getSelfLink()|cat(\"cl=basket\") }) }}"
            ],
            // With spaces
            [
                "[{ oxgetseourl ident=\"basket\"}]",
                "{{ seo_url({ ident: \"basket\" }) }}"
            ]
        ];
    }

    /**
     * @covers \toTwig\Converter\OxgetseourlConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('oxgetseourl', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\OxgetseourlConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
