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

    protected $name = 'oxmailto';
    protected $convertedName = 'mailto';
    protected $description = "Convert smarty {oxmailto} to twig function {{ mailto() }}";
    protected $priority = 100;

    protected $mandatoryFields = ['address'];
}
