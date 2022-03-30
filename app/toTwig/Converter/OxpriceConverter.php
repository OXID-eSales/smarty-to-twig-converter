<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class OxpriceConverter
 */
class OxpriceConverter extends AbstractSingleTagConverter
{
    protected string $name = 'oxprice';
    protected string $description = "Convert smarty {oxprice} to twig function {{ format_price() }}";
    protected int $priority = 100;
    protected ?string $convertedName = 'format_price';
    protected array $mandatoryFields = ['price'];
}
