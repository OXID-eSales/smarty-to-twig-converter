<?php

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

/**
 * Class OxidIncludeDynamicConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxidIncludeDynamicConverter extends ConverterAbstract
{
    /**
     * @param \SplFileInfo $file
     * @param string $content
     *
     * @return null|string|string[]
     */
    public function convert(\SplFileInfo $file, $content)
    {
        // [{oxid_include_dynamic other stuff}]
        $pattern = '/\[\{\s*oxid_include_dynamic\s*([^{}]+)?\}\]/';

        return preg_replace_callback($pattern, function ($matches) {
            $match = $matches[1];
            $attributes = $this->attributes($match);
            $argumentsString = $this->value($attributes['file']);

            if ($twigArray = $this->convertArrayToAssocTwigArray($attributes, ['file'])) {
                $argumentsString .= ", " . $twigArray;
            }

            return "{{ oxid_include_dynamic($argumentsString) }}";
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
        return 'oxid_include_dynamic';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return "Convert smarty {oxid_include_dynamic} to twig function {{ oxid_include_dynamic() }}";
    }
}