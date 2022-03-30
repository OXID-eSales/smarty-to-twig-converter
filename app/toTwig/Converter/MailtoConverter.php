<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class MailtoConverter
 */
class MailtoConverter extends AbstractSingleTagConverter
{
    protected string $name = 'mailto';
    protected string $description = "Convert smarty {mailto} to twig function {{ mailto() }}";
    protected int $priority = 100;

    protected array $mandatoryFields = ['address'];
}
