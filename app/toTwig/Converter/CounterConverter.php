<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class CounterConverter
 */
class CounterConverter extends ConverterAbstract
{
    protected string $name = 'counter';
    protected string $description = 'Convert smarty Counter to twig';
    protected int $priority = 1000;

    public function convert(string $content): string
    {
        $pattern = $this->getOpeningTagPattern('counter');
        $string = '{% set :name = ( :name | default(:start) ) :direction :skip %}:print:assign';

        return preg_replace_callback(
            $pattern,
            function ($matches) use ($string) {
                $attr = $this->getAttributes($matches);

                $replace['name'] = $this->getNameAttribute($attr);
                $replace['direction'] = $this->getDirectionAttribute($attr);
                $replace['skip'] = $this->getSkipAttribute($attr);
                $replace['start'] = $this->getStartAttribute($attr, $replace['direction'], $replace['skip']);
                $replace['print'] = $this->getPrintAttribute($attr, $replace['name']);
                $replace['assign'] = $this->getAssignAttribute($attr, $replace['name']);

                $string = $this->replaceNamedArguments($string, $replace);

                return str_replace($matches[0], $string, $matches[0]);
            },
            $content
        );
    }

    private function getNameAttribute(array $attr): string
    {
        if (!isset($attr['name'])) {
            $name = 'defaultCounter'; //default name in Smarty
        } else {
            $name = $this->sanitizeVariableName($attr['name']);
        }

        return $name;
    }

    private function getDirectionAttribute(array $attr): string
    {
        if (isset($attr['direction']) && ($attr['direction'] == 'down' || $attr['direction'] == '"down"')) {
            $direction = '-';
        } else {
            $direction = '+';
        }

        return $direction;
    }

    private function getSkipAttribute(array $attr): int
    {
        if (!isset($attr['skip'])) {
            $skip = 1; //default interval in Smarty
        } else {
            $skip = (int) $attr['skip'];
        }

        return $skip;
    }

    private function getStartAttribute(array $attr, string $direction, int $skip): int
    {
        if (!isset($attr['start'])) {
            //default initial number in Smarty is 1, but since we increment after 1st call, we want this to be 0
            $start = 0;
        } else {
            //if counter is to be incremented we need to subtract incrementation size from start attribute.
            if ($direction == '+') {
                $start = (int) $attr['start'] - $skip;
            } else {
                $start = (int) $attr['start'] + $skip;
            }
        }

        return $start;
    }

    private function getPrintAttribute(array $attr, string $name): string
    {
        if (isset($attr['print']) && $attr['print'] == 'true') {
            $print = ' {{ ' . $name . ' }}';
        } else {
            $print = '';
        }

        return $print;
    }

    private function getAssignAttribute(array $attr, string $name): string
    {
        $assign = '';
        if (isset($attr['assign'])) {
            $assign = ' {% set ' . $this->sanitizeVariableName($attr['assign']) . ' = ' . $name . ' %}';
        }

        return $assign;
    }
}
