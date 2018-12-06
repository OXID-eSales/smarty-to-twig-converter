<?php

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

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
     * @param \SplFileInfo $file
     * @param string       $content
     *
     * @return string
     */
    public function convert(\SplFileInfo $file, string $content): string
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
        // [{/block}]
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
        // [{block other stuff}]
        $pattern = $this->getOpeningTagPattern('block');

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                $match = $matches[1];

                $attr = $this->attributes($match);
                if (isset($attr['name'])) {
                    $name = $this->value($attr['name']);
                } else {
                    $name = $this->value(array_shift($attr));
                }

                $block = sprintf("{%% block %s %%}", trim($name, '"'));

                if (isset($attr['prepend'])) {
                    $block .= "{{ parent() }}";
                }

                return $block;
            },
            $content
        );
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function replaceExtends(string $content): string
    {
        // [{extends other stuff}]
        $pattern = $this->getOpeningTagPattern('extends');

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                $match = $matches[1];

                $attr = $this->attributes($match);

                $file = $this->value(reset($attr));
                $file = str_replace(".tpl", ".html.twig", $file);

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
        // [{$smarty.block.parent}]
        $pattern = $this->getOpeningTagPattern('$smarty.block.parent');

        return preg_replace($pattern, "{{ parent() }}", $content);
    }
}
