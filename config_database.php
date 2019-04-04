<?php

$sourceConverter = new \toTwig\SourceConverter\DatabaseConverter('mysql://root:123@localhost/oxideshop');

$sourceConverter->setColumns(['oxactions.OXLONGDESC', 'oxcontents.OXCONTENT']);

return \toTwig\Config\Config::create()
    ->setSourceConverter($sourceConverter);