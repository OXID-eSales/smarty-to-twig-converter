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
    /**
     * @param \SplFileInfo $file
     * @param string $content
     *
     * @return string
     */
    public function convert(\SplFileInfo $file, $content)
    {
        $content = $this->replace($content);

        return $content;
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
        return 'assign_adv';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return "Convert OXID {assign_adv} to twig {% set foo = oxassign('foo') %}";
    }

    /**
     * @param $content
     *
     * @return string
     */
    private function replace($content)
    {
        $pattern = '/\[\{assign_adv\b\s*([^{}]+)?\}\]/';
        $string = '{% set :key = oxassign(:value) %}';

        return preg_replace_callback($pattern, function ($matches) use ($string) {

            $match = $matches[1];
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
