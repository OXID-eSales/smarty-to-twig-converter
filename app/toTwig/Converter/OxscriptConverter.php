<?php

namespace toTwig\Converter;

/**
 * Class OxscriptConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxscriptConverter extends AbstractSingleTagConverter
{

    protected $name = 'oxscript';
    protected $description = "Convert smarty {oxscript} to twig function {{ script() }}";
    protected $priority = 100;
    protected $convertedName = 'script';

    /**
     * @param string $content
     *
     * @return null|string|string[]
     */
    public function convert(string $content): string
    {
        /**
         * $pattern is supposed to detect structure like this:
         * [{oxscript include="foo.js" priority=10}]
         **/
        $pattern = $this->getOpeningTagPattern($this->name);

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                /**
                 * $matches contains an array of strings.
                 *
                 * $matches[0] contains a string with full matched tag i.e.
                 * '[{oxscript include="foo.js" priority=10}]'
                 *
                 * $matches[1] should contain a string with all attributes passed to a tag i.e.
                 * 'include="foo.js" priority=10'
                 */
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
