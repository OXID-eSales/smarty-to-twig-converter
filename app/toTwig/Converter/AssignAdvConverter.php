<?php

namespace toTwig\Converter;

/**
 * Class AssignAdvConverter
 */
class AssignAdvConverter extends ConverterAbstract
{

    protected $name = 'assign_adv';
    protected $description = "Convert OXID {assign_adv} to twig {% set foo = assign_advanced('foo') %}";
    protected $priority = 100;

    /**
     * @param string $content
     *
     * @return string
     */
    public function convert(string $content): string
    {
        $pattern = $this->getOpeningTagPattern('assign_adv');
        $string = '{% set :key = assign_advanced(:value) %}';

        return preg_replace_callback(
            $pattern,
            function ($matches) use ($string) {
                $attr = $this->getAttributes($matches);
                $key = $this->sanitizeVariableName($attr['var']);
                $value = $this->sanitizeValue($attr['value']);
                $string = $this->replaceNamedArguments($string, ['key' => $key, 'value' => $value]);

                return str_replace($matches[0], $string, $matches[0]);
            },
            $content
        );
    }
}
