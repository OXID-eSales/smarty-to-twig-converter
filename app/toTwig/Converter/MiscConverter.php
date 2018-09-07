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
class MiscConverter extends ConverterAbstract
{
    protected $name = 'misc';
    protected $description = 'Convert smarty general tags like {ldelim} {rdelim} {literal} {strip}';
    protected $priority = 52;

    // Lookup tables for performing some token
    // replacements not addressed in the grammar.
    private $replacements;

    public function __construct()
    {
        $this->replacements = [
            $this->getOpeningTagPattern('ldelim') => '',
            $this->getOpeningTagPattern('rdelim') => '',
            $this->getOpeningTagPattern('literal') => '{# literal #}',
            $this->getClosingTagPattern('literal') => '{# /literal #}',
            $this->getOpeningTagPattern('strip') => '{% spaceless %}',
            $this->getClosingTagPattern('strip') => '{% endspaceless %}',
        ];
    }

    public function convert(\SplFileInfo $file, $content)
    {
        foreach ($this->replacements as $k => $v) {
            $content = preg_replace($k, $v, $content);
        }

        return $content;
    }
}
