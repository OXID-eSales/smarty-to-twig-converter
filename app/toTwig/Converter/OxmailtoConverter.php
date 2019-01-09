<?php

namespace toTwig\Converter;

use toTwig\AbstractSingleTagConverter;

/**
 * Class OxmailtoConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxmailtoConverter extends AbstractSingleTagConverter
{

    protected $name = 'oxmailto';
    protected $convertedName = 'mailto';
    protected $description = "Convert smarty {oxmailto} to twig function {{ mailto() }}";
    protected $priority = 100;

    protected $mandatoryFields = ['address'];
}
