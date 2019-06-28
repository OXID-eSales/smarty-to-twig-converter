<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace sankar\ST\Tests\Unit;

use toTwig\Converter\InsertTrackerConverter;

class InsertTrackerConverterTest extends FileConversionUnitTestCase
{

    /** @var InsertTrackerConverter */
    protected $converter;

    public function setUp()
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
