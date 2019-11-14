<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class OxstyleConverter
 */
class OxstyleConverter extends AbstractSingleTagConverter
{

    protected $name = 'oxstyle';
    protected $convertedName = 'style';
    protected $description = "Convert smarty {oxstyle} to twig function {{ style() }}";
    protected $priority = 100;
}
