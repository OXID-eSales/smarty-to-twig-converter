<?php

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

/**
 * Class OxevalConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxevalConverter extends ConverterAbstract
{
    /**
     * @param \SplFileInfo $file
     * @param string $content
     *
     * @return null|string|string[]
     */
    public function convert(\SplFileInfo $file, $content)
    {
        $pattern = '/\[\{oxeval\b\s*([^{}]+)?\}\]/';
        return preg_replace_callback($pattern, function ($matches) {
            $match = $matches[1];
            $attributes = $this->attributes($match);

            $extraParameters = $this->extractAdditionalParametersArray($attributes);

            $argumentsString = "{ " . implode(", ", $extraParameters) . " }";

            return "{{ oxeval($argumentsString) }}";
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
        return 'oxeval';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return "Convert smarty {oxeval} to twig function {{ oxeval() }}";
    }
}