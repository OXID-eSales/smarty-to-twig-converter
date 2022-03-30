<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class OxifcontentConverter
 */
class OxifcontentConverter extends ConverterAbstract
{
    protected string $name = 'oxifcontent';
    protected string $description = 'Convert oxifcontent to twig';
    protected int $priority = 50;

    public function convert(string $content): string
    {
        $assignVar = null;
        $openingPattern = $this->getOpeningTagPattern('oxifcontent');
        if (preg_match($openingPattern, $content, $matches)) {
            $attributes = $this->getAttributes($matches);
            if (isset($attributes['assign'])) {
                $assignVar = $this->sanitizeVariableName($attributes['assign']);
            }
        }

        $content = $this->replaceOxifcontent($openingPattern, $content);
        $content = $this->replaceEndOxifcontent($content);

        return ($assignVar ? "{% set $assignVar %}" : '') . $content . ($assignVar ? "{% endset %}" : '');
    }

    private function replaceEndOxifcontent(string $content): string
    {
        $search = $this->getClosingTagPattern('oxifcontent');
        $replace = "{% endifcontent %}";

        return preg_replace($search, $replace, $content);
    }

    private function replaceOxifcontent(string $pattern, string $content): string
    {
        return preg_replace_callback(
            $pattern,
            function ($matches) {
                $attributes = $this->getAttributes($matches);
                $key = isset($attributes['ident']) ? 'ident' : 'oxid';
                $value = ($key == 'ident') ? $attributes['ident'] : $attributes['oxid'];
                $set = $attributes['object'] ? (' set ' . $this->rawString($attributes['object'])) : '';

                return sprintf("{%% ifcontent %s %s%s %%}", $key, $this->sanitizeValue($value), $set);
            },
            $content
        );
    }
}
