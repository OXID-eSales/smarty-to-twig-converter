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
class VariableConverter extends ConverterAbstract
{
    protected $name = 'variable';
    protected $description = 'Convert smarty variable {$var.name} to twig {{ $var.name }}';
    protected $priority = 100;

    public function convert(\SplFileInfo $file, $content)
    {
        $content = $this->replace($content);

        return $content;
    }

    private function replace($content)
    {
        $pattern = '/\[\{([^{}]+)?\}\]/';
        return preg_replace_callback($pattern, function ($matches) {

            $match = $matches[1];
            $search = $matches[0];

            $match = $this->convertExpression($match);

            $search = str_replace($search, '{{ ' . $match . ' }}', $search);

            return $search;

        }, $content);
    }
}
