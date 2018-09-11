<?php

namespace toTwig\Converter;

use toTwig\AbstractSingleTagConverter;

/**
 * Class MailtoConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class MailtoConverter extends AbstractSingleTagConverter
{
    protected $name = 'mailto';
    protected $description = "Convert smarty {mailto} to twig function {{ mailto() }}";
    protected $priority = 100;

    protected $mandatoryFields = ['address'];
}
