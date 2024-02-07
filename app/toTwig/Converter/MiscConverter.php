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
class MiscConverter extends ConverterAbstract
{
    protected string $name = 'misc';
    protected string $description = 'Convert smarty general tags like {ldelim} {rdelim} {literal} {strip}';
    protected int $priority = 52;

    // Lookup tables for performing some token
    // replacements not addressed in the grammar.
    private array $replacements;

    /**
     * MiscConverter constructor.
     */
    public function __construct()
    {
        $this->replacements = [
            $this->getOpeningTagPattern('ldelim') => '',
            $this->getOpeningTagPattern('rdelim') => '',
            $this->getOpeningTagPattern('literal') => '{# literal #}',
            $this->getClosingTagPattern('literal') => '{# /literal #}',
            $this->getOpeningTagPattern('strip') => '{% apply spaceless %}',
            $this->getClosingTagPattern('strip') => '{% endapply %}',
        ];
    }

    public function convert(string $content): string
    {
        foreach ($this->replacements as $k => $v) {
            $content = preg_replace($k, $v, $content);
        }

        return $content;
    }
}
