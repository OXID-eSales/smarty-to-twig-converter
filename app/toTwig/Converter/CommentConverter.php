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
    protected $description = 'Convert smarty comments {* *} to twig {# #}';
    protected $priority = 52;

    /**
     * @param string $content
     *
     * @return string
     */
    public function convert(string $content): string
    {
        return str_replace(['[{*', '*}]'], ['{#', '#}'], $content);
    }
}
