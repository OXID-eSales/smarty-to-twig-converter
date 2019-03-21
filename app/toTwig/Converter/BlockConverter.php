<?php

namespace toTwig\Converter;

/**
 * Class BlockConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class BlockConverter extends ConverterAbstract
{

    protected $name = 'block';
    protected $description = 'Convert block to twig';
    protected $priority = 50;

    /**
     * @param string $content
     *
     * @return string
     */
    public function convert(string $content): string
    {
        $content = $this->replaceBlock($content);
        $content = $this->replaceEndBlock($content);
        $content = $this->replaceExtends($content);
        $content = $this->replaceParent($content);

        return $content;
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function replaceEndBlock(string $content): string
    {
        /**
         * $pattern is supposed to detect structure like this:
         * [{/block}]
         */
        $search = $this->getClosingTagPattern('block');
        $replace = "{% endblock %}";

        return preg_replace($search, $replace, $content);
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function replaceBlock(string $content): string
    {
        /**
         * $pattern is supposed to detect structure like this:
         * [{block name="title"}]
         */
        $pattern = $this->getOpeningTagPattern('block');

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                /**
                 * $matches contains an array of strings.
                 *
                 * $matches[0] contains a string with full matched tag i.e.'[{block name="title"}]'
                 * $matches[1] should contain a string with all attributes passed to a tag i.e.'name="title"'
                 */
                $attr = $this->getAttributes($matches);
                $name = $this->getSanitizedName($attr);
                $block = $this->getBlockOpeningTag($name, $attr);

                return $block;
            },
            $content
        );
    }

    /**
     * @param array $attr
     *
     * @return string
     */
    private function getSanitizedName(array $attr): string
    {
        if (isset($attr['name'])) {
            $name = $this->sanitizeValue($attr['name']);
        } else {
            $name = $this->sanitizeValue(array_shift($attr));
        }

        return $name;
    }

    /**
     * @param string $name
     * @param array  $attr
     *
     * @return string
     */
    private function getBlockOpeningTag(string $name, array $attr): string
    {
        $block = sprintf("{%% block %s %%}", trim($name, '"'));
        if (isset($attr['prepend'])) {
            $block .= "{{ parent() }}";
        }

        return $block;
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function replaceExtends(string $content): string
    {
        /**
         * $pattern is supposed to detect structure like this:
         * [{extends file="foo.tpl"}]
         */
        $pattern = $this->getOpeningTagPattern('extends');

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                /**
                 * $matches contains an array of strings.
                 *
                 * $matches[0] contains a string with full matched tag i.e.'[{extends file="foo.tpl"}]'
                 * $matches[1] should contain a string with all attributes passed to a tag i.e.'file="foo.tpl"
                 */
                $attr = $this->getAttributes($matches);
                $file = $this->sanitizeValue(reset($attr));
                $file = $this->convertFileExtension($file);

                return sprintf("{%% extends %s %%}", $file);
            },
            $content
        );
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function replaceParent(string $content): string
    {
        /**
         * $pattern is supposed to detect structure like this:
         * [{$smarty.block.parent}]
         */
        $pattern = $this->getOpeningTagPattern('$smarty.block.parent');

        return preg_replace($pattern, "{{ parent() }}", $content);
    }
}
