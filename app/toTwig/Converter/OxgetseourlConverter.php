<?php

namespace toTwig\Converter;

use toTwig\AbstractSingleTagConverter;

/**
 * Class OxgetseourlConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxgetseourlConverter extends AbstractSingleTagConverter
{
    protected $name = 'oxgetseourl';
    protected $description = "Convert smarty {oxgetseourl} to twig function {{ oxgetseourl() }}";
    protected $priority = 100;
}