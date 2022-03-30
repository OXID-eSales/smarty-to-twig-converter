<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class OxgetseourlConverter
 */
class OxgetseourlConverter extends AbstractSingleTagConverter
{
    protected string $name = 'oxgetseourl';
    protected ?string $convertedName = 'seo_url';
    protected string $description = "Convert smarty {oxgetseourl} to twig function {{ seo_url() }}";
    protected int $priority = 100;
}
