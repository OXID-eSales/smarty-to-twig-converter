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
class ForConverter extends ConverterAbstract
{
    protected string $name = 'for';
    protected string $description = 'Convert foreach/foreachelse to twig';
    protected int $priority = 50;

    // Lookup tables for performing some token
    // replacements not addressed in the grammar.
    private array $replacements = [
        'smarty\.foreach.*\.index' => 'loop.index0',
        'smarty\.foreach.*\.iteration' => 'loop.index',
        'smarty\.foreach.*\.first' => 'loop.first',
        'smarty\.foreach.*\.last' => 'loop.last',
    ];

    public function convert(string $content): string
    {
        $content = $this->replaceFor($content);
        $content = $this->replaceEndForEach($content);
        $content = $this->replaceForEachElse($content);

        foreach ($this->replacements as $k => $v) {
            $content = preg_replace('/' . $k . '/', $v, $content);
        }

        return $content;
    }

    private function replaceEndForEach(string $content): string
    {
        $search = $this->getClosingTagPattern('foreach');
        $replace = "{% endfor %}";

        return preg_replace($search, $replace, $content);
    }

    private function replaceForEachElse(string $content): string
    {
        $search = $this->getOpeningTagPattern('foreachelse');
        $replace = "{% else %}";

        return preg_replace($search, $replace, $content);
    }

    private function replaceFor(string $content): string
    {
        $pattern = $this->getOpeningTagPattern('foreach');
        $string = '{% for :key :item in :from %}';

        return preg_replace_callback(
            $pattern,
            function ($matches) use ($string) {
                $match = $matches[1];
                $search = $matches[0];

                if (preg_match("/(.*)(?:\bas\b)(.*)/i", $match, $mcs)) {
                    $replace = $this->getReplaceArgumentsForSmarty3($mcs);
                } else {
                    $replace = $this->getReplaceArgumentsForSmarty2($matches);
                }
                $replace['from'] = $this->sanitizeValue($replace['from']);
                $string = $this->replaceNamedArguments($string, $replace);

                return str_replace($search, $string, $search);
            },
            $content
        );
    }

    /**
     * Returns array of replace arguments for foreach function in smarty 3
     * For example:
     * {foreach $arrayVar as $itemVar}
     * or
     * {foreach $arrayVar as $keyVar=>$itemVar}
     */
    private function getReplaceArgumentsForSmarty3(array $mcs): array
    {
        $replace = [];
        /**
         * $pattern is supposed to detect key variable and value variable in structure like this:
         * [{foreach $arrayVar as $keyVar=>$itemVar}]
         **/
        if (preg_match("/(.*)\=\>(.*)/", $mcs[2], $match)) {
            if (!isset($replace['key'])) {
                $replace['key'] = '';
            }
            $replace['key'] .= $this->sanitizeVariableName($match[1]) . ',';
            $mcs[2] = $match[2];
        }
        $replace['item'] = $this->sanitizeVariableName($mcs[2]);
        $replace['from'] = $mcs[1];

        return $replace;
    }

    /**
     * Returns array of replace arguments for foreach function in smarty 2
     * For example:
     * {foreach from=$myArray key="myKey" item="myItem"}
     */
    private function getReplaceArgumentsForSmarty2(array $matches): array
    {
        $attr = $this->getAttributes($matches);

        if (isset($attr['key'])) {
            $replace['key'] = $this->sanitizeVariableName($attr['key']) . ',';
        }

        $replace['item'] = $this->sanitizeVariableName($attr['item']);
        $replace['from'] = $attr['from'];

        return $replace;
    }
}
