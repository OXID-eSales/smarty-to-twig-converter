<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace sankar\ST\Tests\Unit;

use sankar\ST\Tests\FileConversionTestCase;

/**
 * Class FileConversionUnitTestCase
 *
 * @package sankar\ST\Tests\Unit
 */
class FileConversionUnitTestCase extends FileConversionTestCase
{
    public function setUp()
    {
        $this->templateDirectory = dirname(__FILE__) . '/UnitTestTemplates/';
        parent::setUp();
    }
}
