<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class OxscriptConverter
 */
class OxscriptConverter extends AbstractSingleTagConverter
{
    protected string $name = 'oxscript';
    protected string $description = "Convert smarty {oxscript} to twig function {{ script() }}";
    protected int $priority = 100;
    protected ?string $convertedName = 'script';

    public function convert(string $content): string
    {
        $pattern = $this->getOpeningTagPattern($this->name);

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                $match = isset($matches[1]) ? $matches[1] : '';
                $attributes = $this->extractAttributes($match);
                $attributes['dynamic'] = '__oxid_include_dynamic';

                $arguments = [];
                foreach ($this->mandatoryFields as $mandatoryField) {
                    $arguments[] = $this->sanitizeValue($attributes[$mandatoryField]);
                }

                if ($this->convertArrayToAssocTwigArray($attributes, $this->mandatoryFields)) {
                    $arguments[] = $this->convertArrayToAssocTwigArray($attributes, $this->mandatoryFields);
                }

                return sprintf("{{ %s(%s) }}", $this->convertedName, implode(", ", $arguments));
            },
            $content
        );
    }
}
