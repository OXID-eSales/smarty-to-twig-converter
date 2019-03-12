<?php

namespace toTwig\Converter;

/**
 * Class OxidIncludeWidgetConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxidIncludeWidgetConverter extends AbstractSingleTagConverter
{

    protected $name = 'oxid_include_widget';
    protected $description = 'Convert smarty {oxid_include_widget} to twig {{ include_widget() }}';
    protected $priority = 100;
    protected $convertedName = 'include_widget';
}
