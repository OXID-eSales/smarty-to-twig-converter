<?php

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

/**
 * Class OxgetseourlConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxgetseourlConverter extends ConverterAbstract
{
    protected $name = 'oxgetseourl';
    protected $description = "Convert smarty {oxgetseourl} to twig function {{ oxgetseourl() }}";
    protected $priority = 100;

    /**
     * @param \SplFileInfo $file
     * @param string $content
     *
     * @return null|string|string[]
     */
    public function convert(\SplFileInfo $file, $content)
    {
        // [{oxgetseourl other stuff}]
        $pattern = '/\[\{\s*oxgetseourl\s*([^{}]+)?\}\]/';

        return preg_replace_callback($pattern, function ($matches) {
            $match = $matches[1];
            $attributes = $this->attributes($match);
            $argumentsString = $this->convertArrayToAssocTwigArray($attributes, []);

            return "{{ oxgetseourl($argumentsString) }}";
        }, $content);
    }
}