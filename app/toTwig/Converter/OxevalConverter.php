<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class OxevalConverter
 *
 * @package toTwig\Converter
 */
class OxevalConverter extends ConverterAbstract
{
    protected string $name = 'oxeval';
    protected string $description = 'Converts oxeval function into Twig\'s template_from_string';
    protected int $priority = 1000;

    public function convert(string $content): string
    {
        $pattern = $this->getOpeningTagPattern('oxeval');

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                $attr = $this->getAttributes($matches);
                $attr['var'] = $this->sanitizeValue($attr['var']);
                $string = '{{ include(template_from_string(:var)) }}';
                $string = $this->replaceNamedArguments($string, $attr);

                return str_replace($matches[0], $string, $matches[0]);
            },
            $content
        );
    }
}
