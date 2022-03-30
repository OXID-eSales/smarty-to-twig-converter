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
 * @author sankar <sankar.suda@gmail.com>
 */
class IfConverter extends ConverterAbstract
{
    protected string $name = 'if';
    protected string $description = 'Convert smarty if/else/elseif to twig';
    protected int $priority = 50;

    public function convert(string $content): string
    {
        $content = $this->replaceIf($content);
        $content = $this->replaceElseIf($content);
        // Replace smarty {/if} to its twig analogue
        $content = preg_replace($this->getClosingTagPattern('if'), "{% endif %}", $content);
        // Replace smarty {else} to its twig analogue
        $content = preg_replace($this->getOpeningTagPattern('else'), "{% else %}", $content);

        return $content;
    }

    /**
     * Replace smarty "if" tag to its twig analogue
     */
    private function replaceIf(string $content): string
    {
        $pattern = $this->getOpeningTagPattern('if');
        $string = '{%% if %s %%}';

        return $this->replace($pattern, $content, $string);
    }

    /**
     * Replace smarty "elseif" tag to its twig analogue
     */
    private function replaceElseIf(string $content): string
    {
        $pattern = $this->getOpeningTagPattern('elseif');
        $string = '{%% elseif %s %%}';

        return $this->replace($pattern, $content, $string);
    }

    /**
     * Helper for replacing starting tag patterns with additional checks and
     * converting of the arguments coming with those tags
     */
    private function replace(string $pattern, string $content, string $string): string
    {
        return preg_replace_callback(
            $pattern,
            function ($matches) use ($string) {
                $match = $matches[1] ?? '';
                $search = $matches[0];
                $match = $this->convertExpression($match);
                $string = sprintf($string, $match);

                return str_replace($search, $string, $search);
            },
            $content
        );
    }
}
