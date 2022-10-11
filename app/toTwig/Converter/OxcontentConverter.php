<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class OxcontentConverter
 */
class OxcontentConverter extends ConverterAbstract
{
    protected string $name = 'oxcontent';
    protected string $description = "Convert smarty {oxcontent} to twig tag {% include_content %}";
    protected int $priority = 100;

    public function convert(string $content): string
    {
        $pattern = $this->getOpeningTagPattern('oxcontent');

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                $attributes = $this->getAttributes($matches);

                $value = $this->rawString($this->sanitizeValue($attributes['ident']));

                // Different approaches for syntax with and without assignment
                $assignVar = $this->extractAssignVariableName($attributes);
                if ($assignVar) {
                    $twigTag = "{% set $assignVar = content('$value') %}";
                } else {
                    $twigTag = "{% include_content '$value' %}";
                }

                return $twigTag;
            },
            $content
        );
    }

    private function extractAssignVariableName(array $attributes): ?string
    {
        $assignVar = null;
        if (isset($attributes['assign'])) {
            $assignVar = $this->sanitizeVariableName($attributes['assign']);
        }

        return $assignVar;
    }
}
