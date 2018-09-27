<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

use toTwig\AbstractSingleTagConverter;

/**
 * Class OxgetseourlConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxgetseourlConverter extends AbstractSingleTagConverter
{
    protected $name = 'oxgetseourl';
    protected $convertedName = 'seo_url';
    protected $description = "Convert smarty {oxgetseourl} to twig function {{ seo_url() }}";
    protected $priority = 100;
}