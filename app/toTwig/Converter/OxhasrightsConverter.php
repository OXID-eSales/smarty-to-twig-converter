<?php

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

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
     * @param \SplFileInfo $file
     * @param string       $content
     *
     * @return string
     */
    public function convert(\SplFileInfo $file, string $content): string
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
        // [{/oxhasrights}]
        $search = $this->getClosingTagPattern('oxhasrights');
        $replace = "{% endoxhasrights %}";

        return preg_replace($search, $replace, $content);
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function replaceOxhasrights(string $content): string
    {
        // [{oxhasrights other stuff}]
        $pattern = $this->getOpeningTagPattern('oxhasrights');

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                $string = "{% hasrights {:type :field :right :object :readonly :ident} %}";
                $replace = [];

                $match = $matches[1];
                $attr = $this->attributes($match);

                $replace['type'] = $this->getArrayElementToReplace($attr, 'type');
                $replace['field'] = $this->getArrayElementToReplace($attr, 'field');
                $replace['right'] = $this->getArrayElementToReplace($attr, 'right');
                $replace['object'] = $this->getArrayElementToReplace($attr, 'object');
                $replace['readonly'] = $this->getArrayElementToReplace($attr, 'readonly');
                $replace['ident'] = $this->getArrayElementToReplace($attr, 'ident');

                $string = $this->vsprintf($string, $replace);

                // Replace more than one space to single space
                $string = preg_replace('!\s+!', ' ', $string);

                return str_replace($matches[0], $string, $matches[0]);
            },
            $content
        );
    }

    /**
     * Returns element of array to replace template string
     *
     * @param array $attr
     * @param string $attributeName
     * @return string
     */
    private function getArrayElementToReplace(array $attr, string $attributeName): string
    {
        if(isset($attr[$attributeName])) {
            $format = "\"%s\": \"%s\",";
            $variable = $this->variable($attr[$attributeName]);
            $return = sprintf($format, $attributeName, $variable);
        } else {
            $return = '';
        }
        return $return;
    }
}
