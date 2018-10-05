<?php

namespace toTwig\Converter;

use toTwig\AbstractSingleTagConverter;

/**
 * Class OxevalConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxevalConverter extends AbstractSingleTagConverter
{

    protected $name = 'oxeval';
    protected $description = "Convert smarty {oxeval} to twig function {{ oxeval() }}";
    protected $priority = 100;
}
