<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class OxmailtoConverter
 */
class OxmailtoConverter extends AbstractSingleTagConverter
{
    protected string $name = 'oxmailto';
    protected ?string $convertedName = 'mailto';
    protected string $description = "Convert smarty {oxmailto} to twig function {{ mailto() }}";
    protected int $priority = 100;

    protected array $mandatoryFields = ['address'];
}
