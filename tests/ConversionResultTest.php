<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace sankar\ST\Tests;

use toTwig\ConversionResult;
use PHPUnit\Framework\TestCase;

/**
 * Class ConversionResultTest
 */
class ConversionResultTest extends TestCase
{

    /**
     * @covers \toTwig\ConversionResult::__construct
     */
    public function testConstruct()
    {
        $conversionResult = new ConversionResult();

        $this->assertNull($conversionResult->getOriginalTemplate());
        $this->assertNull($conversionResult->getConvertedTemplate());
        $this->assertNull($conversionResult->getDiff());
        $this->assertEmpty($conversionResult->getAppliedConverters());
        $this->assertFalse($conversionResult->hasAppliedConverters());
    }

    /**
     * @covers \toTwig\ConversionResult::setConvertedTemplate
     * @covers \toTwig\ConversionResult::getConvertedTemplate
     */
    public function testConvertedTemplate()
    {
        $conversionResult = new ConversionResult();
        $conversionResult->setConvertedTemplate('{{ varA }}');

        $this->assertEquals('{{ varA }}', $conversionResult->getConvertedTemplate());
    }

    /**
     * @covers \toTwig\ConversionResult::addAppliedConverter
     * @covers \toTwig\ConversionResult::getAppliedConverters
     * @covers \toTwig\ConversionResult::hasAppliedConverters
     */
    public function testAppliedConverters()
    {
        $conversionResult = new ConversionResult();

        // Only variable converter
        $conversionResult->addAppliedConverter('variable');

        $this->assertEquals(['variable'], $conversionResult->getAppliedConverters());
        $this->assertTrue($conversionResult->hasAppliedConverters());

        // Variable and foreach converter
        $conversionResult->addAppliedConverter('foreach');

        $this->assertEquals(['variable', 'foreach'], $conversionResult->getAppliedConverters());
        $this->assertTrue($conversionResult->hasAppliedConverters());
    }

    /**
     * @covers \toTwig\ConversionResult::setDiff
     * @covers \toTwig\ConversionResult::getDiff
     */
    public function testDiff()
    {
        $conversionResult = new ConversionResult();
        $conversionResult->setDiff('<diff></diff>');

        $this->assertEquals('<diff></diff>', $conversionResult->getDiff());
    }

    /**
     * @covers \toTwig\ConversionResult::setOriginalTemplate
     * @covers \toTwig\ConversionResult::getOriginalTemplate
     */
    public function testOriginalTemplate()
    {
        $conversionResult = new ConversionResult();
        $conversionResult->setOriginalTemplate('[{$varA}]');

        $this->assertEquals('[{$varA}]', $conversionResult->getOriginalTemplate());
    }
}
