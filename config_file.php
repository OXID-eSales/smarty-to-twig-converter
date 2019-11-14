<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

$finder = \toTwig\Finder\DefaultFinder::create()
    ->in('examples');

$sourceConverter = new \toTwig\SourceConverter\FileConverter();
$sourceConverter
    ->setFinder($finder)
    ->setOutputExtension('.html.twig');

return \toTwig\Config\Config::create()
    ->setSourceConverter($sourceConverter);
