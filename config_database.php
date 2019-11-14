<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

$sourceConverter = new \toTwig\SourceConverter\DatabaseConverter('mysql://root:123@localhost/oxideshop');

$sourceConverter->setColumns(['oxactions.OXLONGDESC', 'oxcontents.OXCONTENT']);

return \toTwig\Config\Config::create()
    ->setSourceConverter($sourceConverter);
