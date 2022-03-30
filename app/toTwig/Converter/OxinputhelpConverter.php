<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class OxinputhelpConverter
 */
class OxinputhelpConverter extends ConverterAbstract
{
    protected string $name = 'oxinputhelp';
    protected string $description = "Convert smarty {oxinputhelp} to twig include with specified file path and parameters";
    protected int $priority = 100;

    public function convert(string $content): string
    {
        $pattern = $this->getOpeningTagPattern('oxinputhelp');

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                $attr = $this->getAttributes($matches);
                if (isset($attr['ident'])) {
                    $attr['ident'] = $this->sanitizeValue($attr['ident']);
                }

                $pattern = '{% include "inputhelp.html.twig" with '
                           . '{\'sHelpId\': help_id(:ident), \'sHelpText\': help_text(:ident)} '
                           . '%}';
                $string = $this->replaceNamedArguments($pattern, $attr);

                return str_replace($matches[0], $string, $matches[0]);
            },
            $content
        );
    }
}
