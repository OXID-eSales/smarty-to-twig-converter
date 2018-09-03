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
        $pattern = '/\[\{oxgetseourl\b\s*([^{}]+)?\}\]/';

        return preg_replace_callback($pattern, function ($matches) {
            $match = $matches[1];
            $attributes = $this->attributes($match);
            $extraParameters = $this->extractAdditionalParametersArray($attributes);
            $argumentsString = "{ " . implode(", ", $extraParameters) . " }";

            return "{{ oxgetseourl($argumentsString) }}";
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