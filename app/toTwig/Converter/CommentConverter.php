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
class CommentConverter extends ConverterAbstract
{
    protected $name = 'comment';
    protected $description = 'Convert smarty comments [{* *}] to twig {# #}';
    protected $priority = 2000;

    /**
     * @param string $content
     *
     * @return string
     */
    public function convert(string $content): string
    {
        $pattern = '#\[{\*(.*)\*}]#isU';

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                $string = "{# :comment #}";
                $string = $this->replaceNamedArguments($string, ['comment' => $matches[1]]);

                return str_replace($matches[0], $string, $matches[0]);
            },
            $content
        );
    }
}
