<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) Sankar suda <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace toTwig\Converter;

/**
 * Class InsertTrackerConverter
 *
 * @package toTwig\Converter
 * @author  Jędrzej Skoczek
 */
class NewBasketItemConverter extends ConverterAbstract
{

    protected $name = 'oxid_newbasketitem';
    protected $description = 'Convert insert oxid_newbasketitem to twig new_basket_item';
    protected $priority = 110;

    protected $pattern;
    protected $string = '{{ insert_new_basket_item(:vars) }}';
    protected $attrName = 'name';

    /**
     * NewBasketItemConverter constructor.
     */
    public function __construct()
    {
        /**
         * $pattern is supposed to detect structure like this:
         * [{insert name="foo" tpl="foo.tpl" type="message"}]
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
                 * $matches[0] contains a string with full matched tag i.e.
                 * '[{insert name="foo" tpl="foo.tpl" type="message"}]'
                 * $matches[1] should contain a string with all attributes passed to a tag i.e.
                 * 'name="foo" tpl="foo.tpl" type="message"'
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

                    $vars = [];
                    foreach ($attr as $key => $value) {
                        $valueWithConvertedFileExtension = $this->convertFileExtension($this->sanitizeValue($value));
                        $vars[] = $this->sanitizeVariableName($key) . ": " . $valueWithConvertedFileExtension;
                    }

                    $replace['vars'] = '{' . implode(', ', $vars) . '}';
                }

                $string = $this->replaceNamedArguments($string, $replace);

                return str_replace($matches[0], $string, $matches[0]);
            },
            $content
        );
    }
}
