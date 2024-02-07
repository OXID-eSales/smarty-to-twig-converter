<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class TConverter
 */
class TConverter extends ConverterAbstract {
    protected string $name        = 'translation';
    protected string $description = 'Convert smarty {t} to twig {trans}';
    protected int    $priority    = 20;

    // Lookup tables for performing some token
    // replacements not addressed in the grammar.

    /**
     * Function converts smarty {section} tags to twig {for}
     */
    public function convert(string $content): string {
        $content = str_replace('[{t}]', '{{ \'', $content);
        $content = str_replace('[{/t}]', '\'|trans }}', $content);

        return $content;
    }
}
