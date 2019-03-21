<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class InsertTrackerConverter
 *
 * @package toTwig\Converter
 * @author  Jędrzej Skoczek
 */
class InsertTrackerConverter extends ConverterAbstract
{

    protected $name = 'oxid_tracker';
    protected $description = 'Convert insert oxid_tracker to twig insert_tracker';
    protected $priority = 110;

    protected $pattern;
    protected $string = '{{ insert_tracker(:vars) }}';
    protected $attrName = 'name';

    /**
     * InsertTrackerConverter constructor.
     */
    public function __construct()
    {
        /**
         * $pattern is supposed to detect structure like this:
         * [{insert name="foo" title="foo"}]
         **/
        $this->pattern = $this->getOpeningTagPattern('insert');
    }

    /**
     * @param string $content
     *
     * @return string
     */
    public function convert(string $content): string
    {
        $pattern = $this->pattern;
        $string = $this->string;
        $name = $this->name;

        return preg_replace_callback(
            $pattern,
            function ($matches) use ($string, $name) {
                /**
                 * $matches contains an array of strings.
                 *
                 * $matches[0] contains a string with full matched tag i.e.'[{insert name="foo" title="foo"}]'
                 * $matches[1] should contain a string with all attributes passed to a tag i.e.'name="foo" title="foo"'
                 */
                $attr = $this->getAttributes($matches);
                $replace = [];
                $templateName = $this->sanitizeVariableName($attr[$this->attrName]);
                if ($templateName != $name) {
                    return $matches[0];
                }
                $replace['template'] = $attr[$this->attrName];

                if (isset($attr['insert'])) {
                    unset($attr['insert']);
                }

                // If we have any other variables
                if (count($attr) > 1) {
                    $replace['with'] = 'with';
                    unset($attr[$this->attrName]); // We won't need in vars

                    $replace['vars'] = $this->getOptionalReplaceVariables($attr);
                }
                $string = $this->replaceNamedArguments($string, $replace);

                return str_replace($matches[0], $string, $matches[0]);
            },
            $content
        );
    }
}
