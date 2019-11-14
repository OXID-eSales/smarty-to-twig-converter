<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace sankar\ST\Tests;

class AcceptanceTest extends FileConversionTestCase
{

    public function setUp()
    {
        $this->templateDirectory = dirname(__FILE__) . '/';
        $this->templateNames = ['basket', 'order', 'payment', 'thankyou', 'user'];
        parent::setUp();
    }
}
