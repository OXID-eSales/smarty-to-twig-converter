<?php

namespace toTwig\Converter;

/**
 * Class OxidIncludeDynamicConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxidIncludeDynamicConverter extends AbstractSingleTagConverter
{
    protected $name = 'oxid_include_dynamic';
    protected $description = "Convert smarty {oxid_include_dynamic} to twig function {{ oxid_include_dynamic() }}";
    protected $priority = 100;

    protected $mandatoryFields = ['file'];
}