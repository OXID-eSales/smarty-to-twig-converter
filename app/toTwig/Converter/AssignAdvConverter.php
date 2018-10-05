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
    protected $description = "Convert OXID {assign_adv} to twig {% set foo = oxassign('foo') %}";
    protected $priority = 100;

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
     * @param string $content
     *
     * @return string
     */
    private function replace($content)
    {
        // [{assign_adv other stuff}]
        $pattern = $this->getOpeningTagPattern('assign_adv');
        $string = '{% set :key = oxassign(:value) %}';

        return preg_replace_callback($pattern, function($matches) use ($string) {

            $key = $this->variable($attr['var']);
            $value = $this->value($attr['value']);

            $string = $this->vsprintf($string, ['key' => $key, 'value' => $value]);
            // Replace more than one space to single space
            $string = preg_replace('!\s+!', ' ', $string);

            return str_replace($matches[0], $string, $matches[0]);
        }, $content);
    }
}
