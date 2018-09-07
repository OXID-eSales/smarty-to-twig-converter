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
 * @author sankar <sankar.suda@gmail.com>
 */
class IfConverter extends ConverterAbstract
{
    protected $name = 'if';
    protected $description = 'Convert smarty if/else/elseif to twig';
    protected $priority = 50;

    public function convert(\SplFileInfo $file, $content)
    {
        // Replace {if }
        $content = $this->replaceIf($content);
        // Replace {elseif }
        $content = $this->replaceElseIf($content);
        // Replace {else}
        $content = preg_replace('#\[\{/if\s*\}\]#', "{% endif %}", $content);
        // Replace {/if}
        $content = preg_replace('#\[\{else\s*\}\]#', "{% else %}", $content);

        return $content;
    }

    private function replaceIf($content)
    {
        $pattern = "#\[\{if\b\s*([^{}]+)?\}\]#i";
        $string = '{%% if %s %%}';

        return $this->replace($pattern, $content, $string);
    }

    private function replaceElseIf($content)
    {
        $pattern = "#\[\{elseif\b\s*([^{}]+)?\}\]#i";
        $string = '{%% elseif %s %%}';

        return $this->replace($pattern, $content, $string);
    }


    private function replace($pattern, $content, $string)
    {
        return preg_replace_callback($pattern, function ($matches) use ($string) {

            $match = $matches[1];
            $search = $matches[0];

            $match = $this->convertExpression($match);

            $string = sprintf($string, $match);

            return str_replace($search, $string, $search);

        }, $content);
    }
}
