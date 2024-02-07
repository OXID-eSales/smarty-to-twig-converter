<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class OpenCloseTagConverter
 */
class SmartyCleaningConverter extends ConverterAbstract {
    protected string $name        = 'different cleaning';
    protected string $description = '';
    protected int    $priority    = 1;


    public function convert(string $content): string {
        $content = str_replace('::', '.', $content);
        $content = preg_replace('/(->)\S/', '.$1', $content);

        $content = str_replace('$', '', $content);

        return $content;
    }
}
