<?php

namespace toTwig\Converter;

/**
 * Class AssignAdvConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
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
        /**
         * $pattern is supposed to detect structure like this:
         * [{assign_adv parameters}]
         **/
        $pattern = $this->getOpeningTagPattern('assign_adv');
        $string = '{% set :key = assign_advanced(:value) %}';

        return preg_replace_callback(
            $pattern,
            function ($matches) use ($string) {
                /**
                 * $matches contains an array of strings.
                 *
                 * $matches[0] contains a string with full matched tag i.e.'[{assign_adv var="name" value="Bob"}]'
                 * $matches[1] should contain a string with all attributes passed to a tag i.e.'var="name" value="Bob"'
                 */
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
