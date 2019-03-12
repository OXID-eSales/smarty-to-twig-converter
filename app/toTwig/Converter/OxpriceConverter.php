<?php

namespace toTwig\Converter;

/**
 * Class OxpriceConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxpriceConverter extends AbstractSingleTagConverter
{

    protected $name = 'oxprice';
    protected $description = "Convert smarty {oxprice} to twig function {{ format_price() }}";
    protected $priority = 100;
    protected $convertedName = 'format_price';
    protected $mandatoryFields = ['price'];
}
