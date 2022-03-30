<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class DefunConverter
 *
 * @package toTwig\Converter
 */
class DefunConverter extends ConverterAbstract
{
    protected string $name = 'defun';
    protected string $description = "Convert Smarty2 defun to macro";
    protected int $priority = 50;

    //list of arguments passed to macro
    private ?string $arguments = null;

    //string to replace smarty call to macro
    private string $twigCallToMacro = '{% import _self as self %}{{ self.:macroName(:arguments) }}';
    private ?string $macroName = null;


    public function convert(string $content): string
    {
        $content = $this->replaceOpeningTag($content);
        $content = $this->replaceCallToMacro($content);
        $content = $this->replaceClosingTag($content);

        return $content;
    }

    private function replaceOpeningTag(string $content): string
    {
        $pattern = $this->getOpeningTagPattern('defun');
        $string = '{% macro :macroName(:parameters) %}';

        return preg_replace_callback(
            $pattern,
            function ($matches) use ($string) {
                $attr = $this->getAttributes($matches);
                $this->macroName = $this->sanitizeVariableName($attr['name']);
                $parameters = $this->getParameters($attr);
                $this->setArguments($attr);
                $string = $this->replaceNamedArguments(
                    $string,
                    ['macroName' => $this->macroName, 'parameters' => $parameters]
                );

                return str_replace($matches[0], $string, $matches[0]);
            },
            $content
        );
    }

    private function replaceCallToMacro(string $content): string
    {
        $pattern = $this->getOpeningTagPattern('fun');

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                $attr = $this->getAttributes($matches);
                $macroName = $this->sanitizeVariableName($attr['name']);

                // we have to use local macro variables as arguments passed to the nested macro
                $parameters = $this->getParameters($attr);
                $string = $this->replaceNamedArguments(
                    $this->twigCallToMacro,
                    ['macroName' => $macroName, 'arguments' => $parameters]
                );

                return str_replace($matches[0], $string, $matches[0]);
            },
            $content
        );
    }

    private function replaceClosingTag(string $content): string
    {
        $pattern = $this->getClosingTagPattern('defun');
        //smarty calls defined macro automatically
        $string = "{% endmacro %}" . $this->twigCallToMacro;

        return preg_replace_callback(
            $pattern,
            function ($matches) use ($string) {
                $string = $this->replaceNamedArguments(
                    $string,
                    ['macroName' => $this->macroName, 'arguments' => $this->arguments]
                );

                return str_replace($matches[0], $string, $matches[0]);
            },
            $content
        );
    }

    /**
     * Extract parameters from attributes. Defun uses all attributes, except name as parameters.
     */
    private function getParameters(array $attr): string
    {
        $parameters = '';
        foreach ($attr as $parameterName => $variableName) {
            if ($parameterName == 'name') {
                continue;
            }
            $parameters = $this->concatVariablesNames($parameters, $parameterName);
        }

        return $parameters;
    }

    /**
     * Similar to parameters, but we use variables defined outside macros
     */
    private function setArguments(array $attr): void
    {
        $arguments = '';
        foreach ($attr as $parameterName => $variableName) {
            if ($parameterName == 'name' || $parameterName == 'defun') {
                continue;
            }
            $arguments = $this->concatVariablesNames($arguments, $variableName);
        }

        $this->arguments = $arguments;
    }

    private function concatVariablesNames(string $variable, string $variableName): string
    {
        if ($variable == '') {
            $variable .= $variableName;
        } else {
            $variable .= ', ' . $variableName;
        }

        return $variable;
    }
}
