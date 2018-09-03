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
        $pattern = '/\[\{oxid_include_dynamic\b\s*([^{}]+)?\}\]/';

        return preg_replace_callback($pattern, function ($matches) {
            $match = $matches[1];
            $attributes = $this->attributes($match);
            $argumentsString = $this->value($attributes['file']);

            $extraParameters = $this->extractAdditionalParametersArray($attributes);

            if (!empty($extraParameters)) {
                $argumentsString .= ", { " . implode(", ", $extraParameters) . " }";
            }

            return "{{ oxid_include_dynamic($argumentsString) }}";
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
            // Skip already handled attributes
            if ($name === 'file') continue;

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