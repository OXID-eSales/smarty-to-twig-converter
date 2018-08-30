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

            // Collect values into array
            $valuesArray = [];
            if (isset($attributes['values'])) {
                $values = trim($attributes['values'], "\"");
                unset($attributes['values']);
                $delimiter = isset($attributes['delimiter']) ? trim($attributes['delimiter'], "\"") : ",";
                unset($attributes['delimiter']);
                foreach (explode($delimiter, $values) as $value) {
                    $valuesArray[] = "\"$value\"";
                }
            }

            // Extract assignment variable
            $assignVar = null;
            if (isset($attributes['assign'])) {
                $assignVar = $this->variable($attributes['assign']);
                unset($attributes['assign']);
            }

            // Collect additional parameters into array
            $extraParameters = [];
            foreach ($attributes as $name => $value) {
                $extraParameters[] = $this->variable($name) . ": " . $this->value($value);
            }

            // Collect oxcycle arguments
            $argumentsString = "";
            if (!empty($extraParameters) || !empty($valuesArray)) {
                $arguments = [];
                $arguments[] = "[" . implode(", ", $valuesArray) . "]";
                if (!empty($extraParameters)) {
                    $arguments[] = "{ " . implode(", ", $extraParameters) . " }";
                }

                $argumentsString = implode(", ", $arguments);
            }

            // Different approaches for syntax with and without assignment
            if ($assignVar) {
                $twigTag = "{% set $assignVar = oxcycle($argumentsString) %}";
            } else {
                $twigTag = "{{ oxcycle($argumentsString) }}";
            }

            return $twigTag;
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
