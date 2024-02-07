<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class OpenCloseTagConverter
 */
class OpenCloseTagConverter extends ConverterAbstract {
    protected string $name        = 'curly bracets replace';
    protected string $description = 'Convert smarty {} to smarty [{}]';
    protected int    $priority    = 3000;


    public function convert(string $content): string {
        $content = str_replace('{', '[{', $content);
        $content = str_replace('}', '}]', $content);

        return $content;
    }
}
