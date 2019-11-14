<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Tests\Acceptance;

use toTwig\Tests\FileConversionTestCase;

class AcceptanceTest extends FileConversionTestCase
{

    public function setUp()
    {
        $this->templateDirectory = dirname(__FILE__) . '/';
        $this->templateNames = ['basket', 'order', 'payment', 'thankyou', 'user'];
        parent::setUp();
    }
}
