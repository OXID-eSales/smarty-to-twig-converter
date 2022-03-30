<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class OxidIncludeWidgetConverter
 */
class OxidIncludeWidgetConverter extends AbstractSingleTagConverter
{
    protected string $name = 'oxid_include_widget';
    protected string $description = 'Convert smarty {oxid_include_widget} to twig {{ include_widget() }}';
    protected int $priority = 100;
    protected ?string $convertedName = 'include_widget';
}
