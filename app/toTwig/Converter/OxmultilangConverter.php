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
    protected string $name = 'oxmultilang';
    protected string $description = "Convert smarty {oxmultilang} to twig {{ translate }}";
    protected int $priority = 100;
    protected ?string $convertedName = 'translate';
}
