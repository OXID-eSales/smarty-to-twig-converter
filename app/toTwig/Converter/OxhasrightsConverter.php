<?php

namespace toTwig\Converter;

/**
 * Class OxhasrightsConverter
 */
class OxhasrightsConverter extends ConverterAbstract
{

    protected $name = 'oxhasrights';
    protected $description = 'Convert oxhasrights to twig';
    protected $priority = 50;

    /**
     * @param string $content
     *
     * @return string
     */
    public function convert(string $content): string
    {
        $content = $this->replaceOxhasrights($content);
        $content = $this->replaceEndOxhasrights($content);

        return $content;
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function replaceEndOxhasrights(string $content): string
    {
        $search = $this->getClosingTagPattern('oxhasrights');
        $replace = "{% endhasrights %}";

        return preg_replace($search, $replace, $content);
    }

    /**
     * @param string $content
     *
     * @return string
     */
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
