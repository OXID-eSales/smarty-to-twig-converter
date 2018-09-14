<?php

namespace toTwig\Converter;

use toTwig\AbstractSingleTagConverter;

/**
 * Class OxinputhelpConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxinputhelpConverter extends AbstractSingleTagConverter
{
    protected $name = 'oxinputhelp';
    protected $description = "Convert smarty {oxinputhelp} to twig function {{ oxinputhelp() }}";
    protected $priority = 100;

    protected $mandatoryFields = ['ident'];
}