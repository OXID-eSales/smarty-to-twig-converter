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

    /**
     * @return int
     */
    public function getPriority()
    {
        return 100;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'oxgetseourl';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return "Convert smarty {oxgetseourl} to twig function {{ oxgetseourl() }}";
    }
}