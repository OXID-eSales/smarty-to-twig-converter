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

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class VariableConverter extends ConverterAbstract
{

    protected $name = 'variable';
    protected $description = 'Convert smarty variable {$var.name} to twig {{ $var.name }}';
    protected $priority = 10;

    /**
     * @param string $content
     *
     * @return string
     */
    public function convert(string $content): string
    {
        /**
         * $pattern is supposed to detect structure like this:
         * [{$foo}]
         **/
        $pattern = '/\[\{([^{}]+)?\}\]/';

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                /**
                 * $matches contains an array of strings.
                 *
                 * $matches[0] contains a string with full matched tag i.e.'[{$foo}]'
                 * $matches[1] should contain a string with all attributes passed to a tag i.e.'$foo'
                 */
                $search = $matches[0];
                $match = $this->convertExpression($matches[1]);
                $search = str_replace($search, '{{ ' . $match . ' }}', $search);

                return $search;
            },
            $content
        );
    }
}
