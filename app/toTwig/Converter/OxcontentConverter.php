<?php

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

/**
 * Class OxcontentConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxcontentConverter extends ConverterAbstract
{
    /**
     * @param \SplFileInfo $file
     * @param string $content
     *
     * @return null|string|string[]
     */
    public function convert(\SplFileInfo $file, $content)
    {
        $pattern = '/\[\{oxcontent\b\s*([^{}]+)?\}\]/';

        return preg_replace_callback($pattern, function ($matches) {
            // If short form [{cycle}] - attributes are empty
            $match = isset($matches[1]) ? $matches[1] : "";
            $attributes = $this->attributes($match);

            $extraParameters = $this->extractAdditionalParametersArray($attributes);

            $argumentsString = "{ " . implode(", ", $extraParameters) . " }";

            // Different approaches for syntax with and without assignment
            $assignVar = $this->extractAssignVariableName($attributes);
            if ($assignVar) {
                $twigTag = "{% set $assignVar = oxcontent($argumentsString) %}";
            } else {
                $twigTag = "{{ oxcontent($argumentsString) }}";
            }

            return $twigTag;
        }, $content);
    }

    /**
     * @param $attributes
     *
     * @return string
     */
    private function extractAssignVariableName($attributes)
    {
        $assignVar = null;
        if (isset($attributes['assign'])) {
            $assignVar = $this->variable($attributes['assign']);
        }

        return $assignVar;
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
            if ($name == 'assign') continue;

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
        return 'oxcontent';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return "Convert smarty {oxcontent} to twig function {{ oxcontent() }}";
    }
}