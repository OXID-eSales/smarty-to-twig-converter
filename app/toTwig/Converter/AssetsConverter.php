<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class InsertConverter
 */
class AssetsConverter extends IncludeConverter
{
    protected string $name = 'assets';
    protected string $description = 'Convert smarty asset to twig asses';


    public function convert(string $content): string {
        $content = preg_replace('/\[\{assets name=(.*?) type=(.*?)\}\]/', '{{ assets($1, $2) }}', $content);

        return $content;
    }
}
