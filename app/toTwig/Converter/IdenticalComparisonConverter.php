<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class IdenticalComparisonConverter
 *
 * @package toTwig\Converter
 * @author  Jędrzej Skoczek
 */
class IdenticalComparisonConverter extends ConverterAbstract
{

    protected $name = 'identical_comparison_converter';
    protected $description = 'Convert php "===" to twig "is same as()"';
    protected $priority = 49;

    /**
     * @param string $content
     *
     * @return string
     */
    public function convert(string $content): string
    {
        /**
         * for {% if "foo"==="foo bar" %}
         *  $match[0] should contain left side of comparison with '===' sign i.e '=== "foo bar"'
         *  $match[1] should only contain value, without twig closing bracket or following spaces i.e. "foo bar"
         *
         * regex is designed to capture string between:
         *  comparison '===',
         *  twig logic operators (and, or, not)
         *  and twig closing tag '%}'
         */
        $pattern = '/\s*\=\=\=+\s*((?:(?!\%\}|\s\%\}|\sand|\sor|\snot).)+)?/';

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                $search = str_replace($matches[0], ' is same as(' . $matches[1] . ')', $matches[0]);

                return $search;
            },
            $content
        );
    }
}
