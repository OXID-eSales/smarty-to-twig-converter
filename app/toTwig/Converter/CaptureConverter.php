<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class CaptureConverter
 */
class CaptureConverter extends ConverterAbstract
{

    protected $name = 'capture';
    protected $description = 'Converts Smarty Capture into Twig';
    protected $priority = 100;

    /**
     * @param string $content
     *
     * @return mixed|string
     */
    public function convert(string $content): string
    {
        $pattern = $this->getOpeningTagPattern('capture');
        $strippedOpeningTag = preg_replace_callback(
            $pattern,
            function ($matches) {
                $attr = $this->getAttributes($matches);
                if (isset($attr['name'])) {
                    $attr['name'] = $this->sanitizeVariableName($attr['name']);
                    $string = '{% capture name = ":name" %}';
                } elseif (isset($attr['append'])) {
                    $attr['append'] = $this->sanitizeVariableName($attr['append']);
                    $string = '{% capture append = ":append" %}';
                } elseif (isset($attr['assign'])) {
                    $attr['assign'] = $this->sanitizeVariableName($attr['assign']);
                    $string = '{% capture assign = ":assign" %}';
                } else {
                    return $matches[0];
                }

                $string = $this->replaceNamedArguments($string, $attr);

                return str_replace($matches[0], $string, $matches[0]);
            },
            $content
        );

        return $this->stripClosingTag($strippedOpeningTag);
    }

    /**
     * @param string $strippedOpeningTag
     *
     * @return string
     */
    private function stripClosingTag(string $strippedOpeningTag): string
    {
        // [{/capture}]
        $pattern = '/\[{\s*\/capture([^{]*)\s*}]/';
        $string = '{% endcapture %}';

        return preg_replace_callback(
            $pattern,
            function ($matches) use ($string) {
                $attr = $this->getAttributes($matches);
                $string = $this->replaceNamedArguments($string, $attr);

                return str_replace($matches[0], $string, $matches[0]);
            },
            $strippedOpeningTag
        );
    }
}
