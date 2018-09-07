<?php

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

/**
 * Class OxidIncludeWidgetConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxidIncludeWidgetConverter extends ConverterAbstract
{
    protected $name = 'oxid_include_widget';
    protected $description = "Convert smarty {oxid_include_widget} to twig function {{ oxid_include_widget() }}";
    protected $priority = 100;

    /**
     * @param \SplFileInfo $file
     * @param string $content
     *
     * @return null|string|string[]
     */
    public function convert(\SplFileInfo $file, $content)
    {
        // [{oxid_include_widget other stuff}]
        $pattern = $this->getOpeningTagPattern('oxid_include_widget');

        return preg_replace_callback($pattern, function ($matches) {
            $match = $matches[1];
            $attributes = $this->attributes($match);
            $argumentsString = $this->convertArrayToAssocTwigArray($attributes, []);

            return "{{ oxid_include_widget($argumentsString) }}";
        }, $content);
    }
}