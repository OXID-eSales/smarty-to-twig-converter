<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Tests\Unit;

use toTwig\Converter\InsertTrackerConverter;

class InsertTrackerConverterTest extends FileConversionUnitTestCase
{

    /** @var InsertTrackerConverter */
    protected $converter;

    public function setUp(): void
    {
        $this->converter = new InsertTrackerConverter();
        $this->templateNames = ['insert-tracker'];
        parent::setUp();
    }

    /**
     * @covers \toTwig\Converter\InsertTrackerConverter::getName
     */
    public function testThatHaveExpectedName()
    {
        $this->assertEquals('oxid_tracker', $this->converter->getName());
    }

    /**
     * @covers \toTwig\Converter\InsertTrackerConverter::getDescription
     */
    public function testThatHaveDescription()
    {
        $this->assertNotEmpty($this->converter->getDescription());
    }
}
