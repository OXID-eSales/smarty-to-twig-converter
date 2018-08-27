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

    // Lookup tables for performing some token
    // replacements not addressed in the grammar.
    private $replacements = array(
        '\{ldelim\}' => '',
        '\{rdelim\}' => '',
        '\{literal\}' => '{# literal #}',
        '\{\\/literal\}' => '{# /literal #}'
    );

    public function convert(\SplFileInfo $file, $content)
    {
        foreach ($this->replacements as $k => $v) {
            $content = preg_replace('/' . $k . '/', $v, $content);
        }

        return $content;
    }

    public function getPriority()
    {
        return 52;
    }

    public function getName()
    {
        return 'misc';
    }

    public function getDescription()
    {
        return 'Convert smarty general tags like {ldelim} {rdelim} {literal}';
    }
}
