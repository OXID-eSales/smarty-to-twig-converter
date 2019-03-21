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
 * @author sankara <sankar.suda@gmail.com>
 */
class IncludeConverter extends ConverterAbstract
{

    protected $name = 'include';
    protected $description = 'Convert smarty include to twig include';
    protected $priority = 100;

    protected $pattern;
    protected $string = '{% include :template :with :vars %}';
    protected $attrName = 'file';

    /**
     * IncludeConverter constructor.
     */
    public function __construct()
    {
        $this->pattern = $this->getOpeningTagPattern('include');
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

        return preg_replace_callback(
            $pattern,
            function ($matches) use ($string) {
                $attr = $this->getAttributes($matches);
                $replace = [];
                $replace['template'] = $this->convertFileExtension($attr[$this->attrName]);
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
