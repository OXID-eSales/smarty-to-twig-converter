<?php

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

/**
 * Class CycleConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class CycleConverter extends ConverterAbstract
{
    /**
     * @param \SplFileInfo $file
     * @param string $content
     *
     * @return null|string|string[]
     */
    public function convert(\SplFileInfo $file, $content)
    {
        // Smarty cycle tag will be converted to custom Twig function oxcycle

        $pattern = '/\[\{cycle\b\s*([^{}]+)?\}\]/';

        return preg_replace_callback($pattern, function ($matches) {
            // If short form [{cycle}] - attributes are empty
            $match = isset($matches[1]) ? $matches[1] : "";
            $attributes = $this->attributes($match);

            $valuesArray = $this->extractValuesArray($attributes);
            $extraParameters = $this->extractAdditionalParametersArray($attributes);

            $argumentsString = $this->composeArgumentsString($valuesArray, $extraParameters);

            // Different approaches for syntax with and without assignment
            $assignVar = $this->extractAssignVariableName($attributes);
            if ($assignVar) {
                $twigTag = "{% set $assignVar = oxcycle($argumentsString) %}";
            } else {
                $twigTag = "{{ oxcycle($argumentsString) }}";
            }

            return $twigTag;
        }, $content);
    }

    /**
     * @param $attributes
     *
     * @return array
     */
    private function extractValuesArray($attributes)
    {
        $valuesArray = [];
        if (isset($attributes['values'])) {
            $values = trim($attributes['values'], "\"");
            $delimiter = isset($attributes['delimiter']) ? trim($attributes['delimiter'], "\"") : ",";

            foreach (explode($delimiter, $values) as $value) {
                $valuesArray[] = "\"$value\"";
            }
        }
        return $valuesArray;
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
    function extractAdditionalParametersArray($attributes)
    {
        $extraParameters = [];
        foreach ($attributes as $name => $value) {
            // Skip already handled attributes
            if (in_array($name, ['values', 'delimiter', 'assign'])) continue;

            $extraParameters[] = $this->variable($name) . ": " . $this->value($value);
        }

        return $extraParameters;
    }

    /**
     * @param $valuesArray
     * @param $extraParameters
     *
     * @return string
     */
    function composeArgumentsString($valuesArray, $extraParameters)
    {
        $argumentsString = "";
        if (!empty($valuesArray) || !empty($extraParameters)) {
            $arguments = [];
            $arguments[] = "[" . implode(", ", $valuesArray) . "]";
            if (!empty($extraParameters)) {
                $arguments[] = "{ " . implode(", ", $extraParameters) . " }";
            }

            $argumentsString = implode(", ", $arguments);
        }

        return $argumentsString;
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
        return 'cycle';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return "Convert smarty {cycle} to twig function {{ oxcycle() }}";
    }
}
