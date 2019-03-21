<?php

namespace toTwig\Converter;

/**
 * Class OxhasrightsConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
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
        /**
         * $pattern is supposed to detect structure like this:
         * [{/oxhasrights}]
         **/
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
        /**
         * $pattern is supposed to detect structure like this:
         * [{oxhasrights type=$type}]
         **/
        $pattern = $this->getOpeningTagPattern('oxhasrights');

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                /**
                 * $matches contains an array of strings.
                 *
                 * $matches[0] contains a string with full matched tag i.e.'[{oxhasrights type=$type}]'
                 * $matches[1] should contain a string with all attributes passed to a tag i.e.'type=$type'
                 */
                $string = "{% hasrights {:type :field :right :object :readonly :ident} %}";
                $replace = [];
                $attr = $this->getAttributes($matches);

                $replace['type'] = $this->getArrayElementToReplace($attr, 'type');
                $replace['field'] = $this->getArrayElementToReplace($attr, 'field');
                $replace['right'] = $this->getArrayElementToReplace($attr, 'right');
                $replace['object'] = $this->getArrayElementToReplace($attr, 'object');
                $replace['readonly'] = $this->getArrayElementToReplace($attr, 'readonly');
                $replace['ident'] = $this->getArrayElementToReplace($attr, 'ident');

                $string = $this->replaceNamedArguments($string, $replace);

                return str_replace($matches[0], $string, $matches[0]);
            },
            $content
        );
    }

    /**
     * Returns element of array to replace template string
     *
     * @param array  $attr
     * @param string $attributeName
     *
     * @return string
     */
    private function getArrayElementToReplace(array $attr, string $attributeName): string
    {
        if (isset($attr[$attributeName])) {
            $format = "\"%s\": \"%s\",";
            $variable = $this->sanitizeVariableName($attr[$attributeName]);
            $return = sprintf($format, $attributeName, $variable);
        } else {
            $return = '';
        }

        return $return;
    }
}
