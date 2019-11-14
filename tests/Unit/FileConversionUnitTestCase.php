<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Tests\Unit;

use toTwig\Tests\FileConversionTestCase;

/**
 * Class FileConversionUnitTestCase
 *
 * @package toTwig\Tests\Unit
 */
class FileConversionUnitTestCase extends FileConversionTestCase
{
    public function setUp()
    {
        $this->templateDirectory = dirname(__FILE__) . '/UnitTestTemplates/';
        parent::setUp();
    }
}
