<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Tests\Acceptance;

use toTwig\Tests\FileConversionTestCase;

class AcceptanceTest extends FileConversionTestCase
{

    public function setUp(): void
    {
        $this->templateDirectory = dirname(__FILE__) . '/';
        $this->templateNames = ['basket', 'comment', 'order', 'payment', 'thankyou', 'user', 'recommlist'];
        parent::setUp();
    }
}
