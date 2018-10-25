<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) Sankar suda <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class AssignConverter extends ConverterAbstract
{

    protected $name = 'assign';
    protected $description = "Convert smarty {assign} to twig {% set foo = 'foo' %}";
    protected $priority = 100;

    /**
     * @param \SplFileInfo $file
     * @param string       $content
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
        // [{assign other stuff}]
        $pattern = $this->getOpeningTagPattern('assign');
        $string = '{% set :key = :value %}';

        return preg_replace_callback(
            $pattern,
            function ($matches) use ($string) {
                $match = $matches[1];
                $attr = $this->attributes($match);

                if (isset($attr['var'])) {
                    $key = $attr['var'];
                }

                if (isset($attr['value'])) {
                    $value = $attr['value'];
                }

                // Short-hand {assign "name" "Bob"}
                if (!isset($key)) {
                    $key = reset($attr);
                }

                if (!isset($value)) {
                    $value = next($attr);
                }

                $key = $this->variable($key);
                $value = $this->value($value);

                $string = $this->vsprintf($string, ['key' => $key, 'value' => $value]);
                // Replace more than one space to single space
                $string = preg_replace('!\s+!', ' ', $string);

                return str_replace($matches[0], $string, $matches[0]);
            },
            $content
        );
    }
}
