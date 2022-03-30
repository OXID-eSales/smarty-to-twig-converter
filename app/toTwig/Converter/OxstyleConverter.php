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
    protected string $name = 'oxstyle';
    protected ?string $convertedName = 'style';
    protected string $description = "Convert smarty {oxstyle} to twig function {{ style() }}";
    protected int $priority = 100;
}
