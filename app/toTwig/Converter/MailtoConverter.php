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

    protected $name = 'mailto';
    protected $description = "Convert smarty {mailto} to twig function {{ mailto() }}";
    protected $priority = 100;

    protected $mandatoryFields = ['address'];
}
