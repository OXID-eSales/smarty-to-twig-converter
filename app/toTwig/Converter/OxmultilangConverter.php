<?php

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

/**
 * Class OxmultilangConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxmultilangConverter extends ConverterAbstract
{
    /**
     * @param \SplFileInfo $file
     * @param string $content
     *
     * @return null|string|string[]
     */
    public function convert(\SplFileInfo $file, $content)
    {
        // [{oxmultilang ident="A_IDENT"}]
        $pattern = '/\[\{\s*oxmultilang\s*([^{}]+)?\}\]/';

        return preg_replace_callback($pattern, function ($matches) {
            $match = $matches[1];
            $attributes = $this->attributes($match);
            $argumentsString = $this->convertArrayToAssocTwigArray($attributes, []);

            return "{{ oxmultilang($argumentsString) }}";
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
        return 'oxmultilang';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return "Convert smarty {oxmultilang} to twig function {{ oxmultilang() }}";
    }
}