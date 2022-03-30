<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class BlockConverter
 */
class BlockConverter extends ConverterAbstract
{
    protected string $name = 'block';
    protected string $description = 'Convert block to twig';
    protected int $priority = 50;

    public function convert(string $content): string
    {
        $content = $this->replaceBlock($content);
        $content = $this->replaceEndBlock($content);
        $content = $this->replaceExtends($content);
        $content = $this->replaceParent($content);

        return $content;
    }

    private function replaceEndBlock(string $content): string
    {
        $search = $this->getClosingTagPattern('block');
        $replace = "{% endblock %}";

        return preg_replace($search, $replace, $content);
    }

    private function replaceBlock(string $content): string
    {
        $pattern = $this->getOpeningTagPattern('block');

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                $attr = $this->getAttributes($matches);
                $name = $this->getSanitizedName($attr);
                $block = $this->getBlockOpeningTag($name, $attr);

                return $block;
            },
            $content
        );
    }

    private function getSanitizedName(array $attr): string
    {
        if (isset($attr['name'])) {
            $name = $this->sanitizeValue($attr['name']);
        } else {
            $name = $this->sanitizeValue(array_shift($attr));
        }

        return $name;
    }

    private function getBlockOpeningTag(string $name, array $attr): string
    {
        $block = sprintf("{%% block %s %%}", trim($name, '"'));
        if (isset($attr['prepend'])) {
            $block .= "{{ parent() }}";
        }

        return $block;
    }

    private function replaceExtends(string $content): string
    {
        $pattern = $this->getOpeningTagPattern('extends');

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                $attr = $this->getAttributes($matches);
                $file = $this->sanitizeValue(reset($attr));
                $file = $this->convertFileExtension($file);

                return sprintf("{%% extends %s %%}", $file);
            },
            $content
        );
    }

    private function replaceParent(string $content): string
    {
        $pattern = $this->getOpeningTagPattern('$smarty.block.parent');

        return preg_replace($pattern, "{{ parent() }}", $content);
    }
}
