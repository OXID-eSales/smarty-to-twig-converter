<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class OpenCloseTagConverter
 */
class SprintfConverter extends ConverterAbstract {
    protected string $name        = 'sprintf to format';
    protected string $description = 'Convert sprintf() to ""|format()';
    protected int    $priority    = 20;


    public function convert(string $content): string {
        $content = preg_replace('/sprintf\((.*?),(.*?)\)/', '$1|format($2)', $content);

        return $content;
    }
}
