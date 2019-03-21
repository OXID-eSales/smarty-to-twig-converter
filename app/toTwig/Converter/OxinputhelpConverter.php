<?php

namespace toTwig\Converter;

/**
 * Class OxinputhelpConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxinputhelpConverter extends ConverterAbstract
{

    protected $name = 'oxinputhelp';
    protected $description = "Convert smarty {oxinputhelp} to twig include with specified file path and parameters";
    protected $priority = 100;

    /**
     * @param string $content
     *
     * @return mixed|string
     */
    public function convert(string $content): string
    {
        /**
         * $pattern is supposed to detect structure like this:
         * [{oxinputhelp ident="foo"}]
         **/
        $pattern = $this->getOpeningTagPattern('oxinputhelp');

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                /**
                 * $matches contains an array of strings.
                 *
                 * $matches[0] contains a string with full matched tag i.e.'[{oxinputhelp ident="foo"}]'
                 * $matches[1] should contain a string with all attributes passed to a tag i.e.'ident="foo"'
                 */
                $attr = $this->getAttributes($matches);
                if (isset($attr['ident'])) {
                    $attr['ident'] = $this->sanitizeValue($attr['ident']);
                }

                $pattern = '{% include "inputhelp.html.twig" with {\'sHelpId\': help_id(:ident), \'sHelpText\': help_text(:ident)} %}';
                $string = $this->replaceNamedArguments($pattern, $attr);

                return str_replace($matches[0], $string, $matches[0]);
            },
            $content
        );
    }
}
