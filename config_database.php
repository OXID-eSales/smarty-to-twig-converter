<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

use OxidEsales\Eshop\Core\DatabaseProvider;

require "/var/www/source/bootstrap.php";

$db = DatabaseProvider::getDb();
$db->setFetchMode($db::FETCH_MODE_ASSOC);

$sourceConverter = new \toTwig\SourceConverter\DatabaseConverter($db->getPublicConnection());

$sourceConverter->setColumns([
    'oxactions.OXLONGDESC',
    'oxcontents.OXCONTENT'
]);

return \toTwig\Config\Config::create()
    ->setSourceConverter($sourceConverter);
