<?php

namespace toTwig\Converter;

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