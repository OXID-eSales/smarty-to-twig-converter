<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class OpenCloseTagConverter
 */
class AssignmentConverter extends ConverterAbstract {
    protected string $name        = 'assignment by =';
    protected string $description = 'Convert smarty {=} to twig {% set %}';
    protected int    $priority    = 20;


    public function convert(string $content): string {
        $content = preg_replace('/\[\{\$(.*? = .*?)\}]/', '{% set $1 %}', $content);

        return $content;
    }
}
