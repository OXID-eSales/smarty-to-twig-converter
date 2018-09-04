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
    /**
     * @param \SplFileInfo $file
     * @param string $content
     *
     * @return null|string|string[]
     */
    public function convert(\SplFileInfo $file, $content)
    {
        $pattern = '/\[\{oxid_include_widget\b\s*([^{}]+)?\}\]/';

        return preg_replace_callback($pattern, function ($matches) {
            $match = $matches[1];
            $attributes = $this->attributes($match);
            $extraParameters = $this->extractAdditionalParametersArray($attributes);

            $argumentsString = "";
            if (!empty($extraParameters)) {
                $argumentsString = "{ " . implode(", ", $extraParameters) . " }";
            }

            return "{{ oxid_include_widget($argumentsString) }}";
        }, $content);
    }

    /**
     * @param $attributes
     *
     * @return array
     */
    private function extractAdditionalParametersArray($attributes)
    {
        $extraParameters = [];
        foreach ($attributes as $name => $value) {
            $extraParameters[] = $this->variable($name) . ": " . $this->value($value);
        }

        return $extraParameters;
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
        return 'oxid_include_widget';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return "Convert smarty {oxid_include_widget} to twig function {{ oxid_include_widget() }}";
    }
}