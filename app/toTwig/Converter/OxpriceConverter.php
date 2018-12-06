<?php

namespace toTwig\Converter;

use toTwig\AbstractSingleTagConverter;

/**
 * Class OxpriceConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxpriceConverter extends AbstractSingleTagConverter
{

    protected $name = 'oxprice';
    protected $description = "Convert smarty {oxprice} to twig function {{ oxprice() }}";
    protected $priority = 100;
    protected $convertedName = 'format_price';

    protected $mandatoryFields = ['price'];
}
