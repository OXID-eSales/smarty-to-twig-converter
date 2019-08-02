<?php

namespace toTwig\Converter;

/**
 * Class OxmultilangConverter
 */
class OxmultilangConverter extends AbstractSingleTagConverter
{

    protected $name = 'oxmultilang';
    protected $description = "Convert smarty {oxmultilang} to twig {{ translate }}";
    protected $priority = 100;
    protected $convertedName = 'translate';
}
