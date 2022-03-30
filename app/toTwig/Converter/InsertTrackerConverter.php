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
 */
class InsertTrackerConverter extends ConverterAbstract
{
    protected string $name = 'oxid_tracker';
    protected string $description = 'Convert insert oxid_tracker to twig insert_tracker';
    protected int $priority = 110;

    protected string $pattern;
    protected string $string = '{{ insert_tracker(:vars) }}';
    protected string $attrName = 'name';

    /**
     * InsertTrackerConverter constructor.
     */
    public function __construct()
    {
        $this->pattern = $this->getOpeningTagPattern('insert');
    }

    public function convert(string $content): string
    {
        $pattern = $this->pattern;
        $string = $this->string;
        $name = $this->name;

        return preg_replace_callback(
            $pattern,
            function ($matches) use ($string, $name) {
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
