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

use toTwig\ConverterAbstract;

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
        // [{insert other stuff}]
        $this->pattern = $this->getOpeningTagPattern('insert');
    }

    /**
     * @param \SplFileInfo $file
     * @param string       $content
     *
     * @return string
     */
    public function convert(\SplFileInfo $file, string $content): string
    {
        return $this->replace($content);
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function replace(string $content): string
    {
        $pattern = $this->pattern;
        $string = $this->string;
        $name = $this->name;

        return preg_replace_callback(
            $pattern,
            function ($matches) use ($string, $name) {
                $match = $matches[1];
                $attr = $this->attributes($match);

                $replace = array();
                $templateName = $this->variable($attr[$this->attrName]);
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

                    $vars = array();
                    foreach ($attr as $key => $value) {
                        $valueWithConvertedFileExtension = $this->convertFileExtension($this->value($value));
                        $vars[] = $this->variable($key) . ": " . $valueWithConvertedFileExtension;
                    }

                    $replace['vars'] = '{' . implode(', ', $vars) . '}';
                }

                $string = $this->vsprintf($string, $replace);

                // Replace more than one space to single space
                $string = preg_replace('!\s+!', ' ', $string);

                return str_replace($matches[0], $string, $matches[0]);
            },
            $content
        );
    }
}
