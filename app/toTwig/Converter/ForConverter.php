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
class ForConverter extends ConverterAbstract
{

    protected $name = 'for';
    protected $description = 'Convert foreach/foreachelse to twig';
    protected $priority = 50;

    // Lookup tables for performing some token
    // replacements not addressed in the grammar.
    private $replacements = array(
        'smarty\.foreach.*\.index'     => 'loop.index0',
        'smarty\.foreach.*\.iteration' => 'loop.index'
    );

    /**
     * @param \SplFileInfo $file
     * @param string       $content
     *
     * @return string
     */
    public function convert(\SplFileInfo $file, string $content): string
    {
        $content = $this->replaceFor($content);
        $content = $this->replaceEndForEach($content);
        $content = $this->replaceForEachElse($content);

        foreach ($this->replacements as $k => $v) {
            $content = preg_replace('/' . $k . '/', $v, $content);
        }

        return $content;
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function replaceEndForEach(string $content): string
    {
        // [{/foreach}]
        $search = $this->getClosingTagPattern('foreach');
        $replace = "{% endfor %}";

        return preg_replace($search, $replace, $content);
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function replaceForEachElse(string $content): string
    {
        // [{foreachelse other stuff}]
        $search = $this->getOpeningTagPattern('foreachelse');
        $replace = "{% else %}";

        return preg_replace($search, $replace, $content);
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function replaceFor(string $content): string
    {
        // [{foreach other stuff}]
        $pattern = $this->getOpeningTagPattern('foreach');
        $string = '{% for :key :item in :from %}';

        return preg_replace_callback(
            $pattern,
            function ($matches) use ($string) {
                $match = $matches[1];
                $search = $matches[0];
                $replace = [];

                // {foreach $users as $user}
                if (preg_match("/(.*)(?:\bas\b)(.*)/i", $match, $mcs)) {
                    // {foreach $users as $k => $val}
                    if (preg_match("/(.*)\=\>(.*)/", $mcs[2], $match)) {
                        if (!isset($replace['key'])) {
                            $replace['key'] = '';
                        }
                        $replace['key'] .= $this->variable($match[1]) . ',';
                        $mcs[2] = $match[2];
                    }
                    $replace['item'] = $this->variable($mcs[2]);
                    $replace['from'] = $mcs[1];
                } else {
                    $attr = $this->attributes($match);

                    if (isset($attr['key'])) {
                        $replace['key'] = $attr['key'] . ',';
                    }

                    $replace['item'] = $this->variable($attr['item']);
                    $replace['from'] = $attr['from'];
                }

                $replace['from'] = $this->value($replace['from']);

                $string = $this->vsprintf($string, $replace);
                // Replace more than one space to single space
                $string = preg_replace('!\s+!', ' ', $string);

                return str_replace($search, $string, $search);
            },
            $content
        );
    }
}
