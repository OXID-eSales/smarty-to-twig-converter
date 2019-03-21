<?php

namespace toTwig\Converter;

/**
 * Class CycleConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class CycleConverter extends ConverterAbstract
{

    protected $name = 'cycle';
    protected $description = "Convert smarty {cycle} to twig function {{ smarty_cycle() }}";
    protected $priority = 100;

    /**
     * @param string $content
     *
     * @return null|string|string[]
     */
    public function convert(string $content): string
    {
        /**
         * $pattern is supposed to detect structure like this:
         * [{cycle values="foo, bar"}]
         **/
        $pattern = $this->getOpeningTagPattern('cycle');

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                /**
                 * $matches contains an array of strings.
                 *
                 * $matches[0] contains a string with full matched tag i.e.'[{cycle values="foo, bar"}]'
                 * $matches[1] should contain a string with all attributes passed to a tag i.e.'values="foo, bar"'
                 */
                // If short form [{cycle}] - attributes are empty
                $match = isset($matches[1]) ? $matches[1] : "";
                $attributes = $this->extractAttributes($match);

                // Different approaches for syntax with and without assignment
                $assignVar = $this->extractAssignVariableName($attributes);

                if ($assignVar) {
                    unset($attributes['print']);
                }

                $valuesArray = $this->extractValuesArray($attributes);
                $extraParameters = $this->extractAdditionalParametersArray($attributes);
                $argumentsString = $this->composeArgumentsString($valuesArray, $extraParameters);

                // Different approaches for syntax with and without assignment
                if ($assignVar) {
                    $twigTag = "{% set $assignVar = smarty_cycle($argumentsString) %}";
                } else {
                    $twigTag = "{{ smarty_cycle($argumentsString) }}";
                }

                return $twigTag;
            },
            $content
        );
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    private function extractValuesArray(array $attributes): array
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
     * @param array $attributes
     *
     * @return string
     */
    private function extractAssignVariableName(array $attributes): ?string
    {
        $assignVar = null;
        if (isset($attributes['assign'])) {
            $assignVar = $this->sanitizeVariableName($attributes['assign']);
        }

        return $assignVar;
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
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

    /**
     * @param array $valuesArray
     * @param array $extraParameters
     *
     * @return string
     */
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
}
