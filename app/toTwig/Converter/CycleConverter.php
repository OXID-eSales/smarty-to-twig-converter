<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class CycleConverter
 */
class CycleConverter extends ConverterAbstract
{
    protected string $name = 'cycle';
    protected string $description = "Convert smarty {cycle} to twig function {{ smarty_cycle() }}";
    protected int $priority = 100;

    public function convert(string $content): string
    {
        $pattern = $this->getOpeningTagPattern('cycle');

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                // If short form [{cycle}] - attributes are empty
                $match = isset($matches[1]) ? $matches[1] : "";
                $attributes = $this->extractAttributes($match);

                // Different approaches for syntax with and without assignment
                if ($assignVar = $this->extractAssignVariableName($attributes)) {
                    unset($attributes['print']);
                }

                $valuesArray = $this->extractValuesArray($attributes);
                $extraParameters = $this->extractAdditionalParametersArray($attributes);
                $argumentsString = $this->composeArgumentsString($valuesArray, $extraParameters);

                return $this->getTag($argumentsString, $assignVar);
            },
            $content
        );
    }

    private function extractValuesArray(array $attributes): array
    {
        $valuesArray = [];
        if (isset($attributes['values'])) {
            $values = trim($attributes['values'], "\"'");
            $delimiter = isset($attributes['delimiter']) ? trim($attributes['delimiter'], "\"'") : ",";

            foreach (explode($delimiter, $values) as $value) {
                $valuesArray[] = "\"" . $value . "\"";
            }
        }

        return $valuesArray;
    }

    private function extractAssignVariableName(array $attributes): ?string
    {
        $assignVar = null;
        if (isset($attributes['assign'])) {
            $assignVar = $this->sanitizeVariableName($attributes['assign']);
        }

        return $assignVar;
    }

    private function extractAdditionalParametersArray(array $attributes): array
    {
        $extraParameters = [];
        foreach ($attributes as $name => $value) {
            // Skip already handled attributes
            if (in_array($name, ['values', 'delimiter', 'assign'])) {
                continue;
            }

            $extraParameters[] = $this->sanitizeVariableName($name) . ": " . $this->sanitizeValue($value);
        }

        return $extraParameters;
    }

    private function composeArgumentsString(array $valuesArray, array $extraParameters): string
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
     * In Twig we have to use different syntax, when we want to use assignment in cycle
     */
    private function getTag(string $argumentsString, ?string $assignVar = null): string
    {
        if ($assignVar) {
            $twigTag = "{% set $assignVar = smarty_cycle($argumentsString) %}";
        } else {
            $twigTag = "{{ smarty_cycle($argumentsString) }}";
        }

        return $twigTag;
    }
}
