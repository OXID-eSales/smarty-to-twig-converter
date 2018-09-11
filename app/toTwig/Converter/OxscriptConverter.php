<?php

namespace toTwig\Converter;

use toTwig\AbstractSingleTagConverter;

/**
 * Class OxscriptConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxscriptConverter extends AbstractSingleTagConverter
{
    protected $name = 'oxscript';
    protected $description = "Convert smarty {oxscript} to twig function {{ oxscript() }}";
    protected $priority = 100;
}
