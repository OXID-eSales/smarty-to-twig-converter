<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class OxmultilangConverter
 */
class OxmultilangConverter extends AbstractSingleTagConverter
{

    protected $name = 'oxmultilang';
    protected $description = "Convert smarty {oxmultilang} to twig {{ translate }}";
    protected $priority = 100;
    protected $convertedName = 'translate';
}
