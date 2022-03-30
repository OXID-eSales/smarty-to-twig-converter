<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class OxhasrightsConverter
 */
class OxhasrightsConverter extends ConverterAbstract
{
    protected string $name = 'oxhasrights';
    protected string $description = 'Convert oxhasrights to twig';
    protected int $priority = 50;

    public function convert(string $content): string
    {
        $content = $this->replaceOxhasrights($content);
        $content = $this->replaceEndOxhasrights($content);

        return $content;
    }

    private function replaceEndOxhasrights(string $content): string
    {
        $search = $this->getClosingTagPattern('oxhasrights');
        $replace = "{% endhasrights %}";

        return preg_replace($search, $replace, $content);
    }

    private function replaceOxhasrights(string $content): string
    {
        $pattern = $this->getOpeningTagPattern('oxhasrights');

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                $attributes = $this->getAttributes($matches);

                $attributes = array_filter($attributes, function ($key) {
                    return in_array($key, ['type', 'field', 'right', 'object', 'readonly', 'ident']);
                }, ARRAY_FILTER_USE_KEY);

                return "{% hasrights " . $this->convertArrayToAssocTwigArray($attributes, []) . " %}";
            },
            $content
        );
    }
}
