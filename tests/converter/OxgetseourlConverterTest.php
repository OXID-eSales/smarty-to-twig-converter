<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace sankar\ST\Tests\Converter;

use toTwig\Converter\OxgetseourlConverter;

/**
 * Class OxgetseourlConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxgetseourlConverterTest extends AbstractConverterTest
{
    /** @var OxgetseourlConverter */
    protected $converter;

    public function setUp()
    {
        $this->converter = new OxgetseourlConverter();
    }

    /**
     * @covers \toTwig\Converter\OxgetseourlConverter::convert
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
            // Example from OXID
            [
                "[{oxgetseourl ident=\$oViewConf->getSelfLink()|cat:\"cl=basket\"}]",
                "{{ seo_url({ ident: oViewConf.getSelfLink()|cat(\"cl=basket\") }) }}"
            ],
            // Example from OXID
            [
                "[{oxgetseourl ident=\$oViewConf->getSelfLink()|cat:\"cl=account\" params=\"anid=`\$oDetailsProduct->oxarticles__oxnid->value`\"|cat:\"&amp;sourcecl=\"|cat:\$oViewConf->getTopActiveClassName()|cat:\$oViewConf->getNavUrlParams()}]",
                "{{ seo_url({ ident: oViewConf.getSelfLink()|cat(\"cl=account\"), params: \"anid=`\$oDetailsProduct.oxarticles__oxnid.value`\"|cat(\"&amp;sourcecl=\")|cat(oViewConf.getTopActiveClassName())|cat(oViewConf.getNavUrlParams()) }) }}"
            ],
            // With spaces
            [
                "[{ oxgetseourl ident=\"basket\"}]",
                "{{ seo_url({ ident: \"basket\" }) }}"
            ],
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