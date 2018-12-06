<?php

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

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
     * @param \SplFileInfo $file
     * @param string $content
     *
     * @return string
     */
    public function convert(\SplFileInfo $file, string $content): string
    {
        $content = $this->replace($content);

        return $content;
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function replace(string $content): string
    {
        // [{assign_adv other stuff}]
        $pattern = $this->getOpeningTagPattern('assign_adv');
        $string = '{% set :key = assign_advanced(:value) %}';

        return preg_replace_callback($pattern, function($matches) use ($string) {

            if (!isset($matches[1]) && $matches[0]) {
                $match = $matches[0];
            } else {
                $match = $matches[1];
            }
            $attr = $this->attributes($match);
            $key = $this->variable($attr['var']);
            $value = $this->value($attr['value']);

            $string = $this->vsprintf($string, ['key' => $key, 'value' => $value]);
            // Replace more than one space to single space
            $string = preg_replace('!\s+!', ' ', $string);

            return str_replace($matches[0], $string, $matches[0]);
        }, $content);
    }
}
