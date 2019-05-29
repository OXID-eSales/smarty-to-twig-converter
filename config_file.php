<?php

$finder = \toTwig\Finder\DefaultFinder::create()
    ->in('examples');

$sourceConverter = new \toTwig\SourceConverter\FileConverter();
$sourceConverter
    ->setFinder($finder)
    ->setOutputExtension('.html.twig');

return \toTwig\Config\Config::create()
    ->setSourceConverter($sourceConverter);
