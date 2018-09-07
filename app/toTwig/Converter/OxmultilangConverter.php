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
    protected $name = 'oxmultilang';
    protected $description = "Convert smarty {oxmultilang} to twig function {{ oxmultilang() }}";
    protected $priority = 100;

    /**
     * @param \SplFileInfo $file
     * @param string $content
     *
     * @return null|string|string[]
     */
    public function convert(\SplFileInfo $file, $content)
    {
        // [{oxmultilang ident="A_IDENT"}]
        $pattern = $this->getOpeningTagPattern('oxmultilang');

        return preg_replace_callback($pattern, function ($matches) {
            $match = $matches[1];
            $attributes = $this->attributes($match);
            $argumentsString = $this->convertArrayToAssocTwigArray($attributes, []);

            return "{{ oxmultilang($argumentsString) }}";
        }, $content);
    }
}