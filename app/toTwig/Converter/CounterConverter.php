<?php
/**
 * Created by PhpStorm.
 * User: jskoczek
 * Date: 29/08/18
 * Time: 12:34
 */

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

class CounterConverter extends ConverterAbstract
{
    protected $name = 'counter';
    protected $description = 'Convert smarty Counter to twig';
    protected $priority = 1000;

    public function convert(\SplFileInfo $file, $content)
    {
        return $this->replace($content);
    }

    private function replace($content)
    {
        // [{counter other stuff}]
        $pattern = $this->getOpeningTagPattern('counter');
        $string = '{% set :name = ( :name | default(:start) ) :direction :skip %}:print:assign';

        return preg_replace_callback($pattern, function($matches) use ($string) {

            if(!isset($matches[1]) && $matches[0]) {
                $match = $matches[0];
            } else {
                $match = $matches[1];
            }

            $attr = $this->attributes($match);

            $replace['name'] = $this->getNameAttribute($attr);
            $replace['direction'] = $this->getDirectionAttribute($attr);
            $replace['skip'] = $this->getSkipAttribute($attr);
            $replace['start'] = $this->getStartAttribute($attr, $replace['direction'], $replace['skip']);
            $replace['print'] = $this->getPrintAttribute($attr, $replace['name']);
            $replace['assign'] = $this->getAssignAttribute($attr, $replace['name']);

            $string = $this->vsprintf($string, $replace);

            // Replace more than one space to single space
            $string = preg_replace('!\s+!', ' ', $string);

            return str_replace($matches[0], $string, $matches[0]);

        }, $content);
    }

    private function getNameAttribute($attr)
    {
        if(!isset($attr['name'])) {
            $name = 'defaultCounter'; //default name in Smarty
        } else {
            $name = $this->variable($attr['name']);
        }
        return $name;
    }

    private function getDirectionAttribute($attr)
    {
        if(isset($attr['direction']) && ($attr['direction'] == 'down' || $attr['direction'] == '"down"')) {
            $direction = '-';
        } else {
            $direction = '+';
        }
        return $direction;
    }

    private function getSkipAttribute($attr)
    {
        if(!isset($attr['skip'])) {
            $skip = 1; //default interval in Smarty
        } else {
            $skip = (int)$attr['skip'];
        }
        return $skip;
    }

    private function getStartAttribute($attr, $direction, $skip)
    {
        if(!isset($attr['start'])) {
            $start = 0; //default initial number in Smarty is 1, but since we increment after 1st call, we want this to be 0
        } else {
            if($direction == '+') { //if counter is to be incremented we need to subtract incrementation size from start attribute.
                $start = (int)$attr['start'] - (int)$skip;
            } else {
                $start = (int)$attr['start'] + (int)$skip;
            }
        }
        return $start;
    }

    private function getPrintAttribute($attr, $name)
    {
        if(isset($attr['print']) && $attr['print'] == 'true') {
            $print = ' {{ ' . $name . ' }}';
        } else {
            $print = '';
        }
        return $print;
    }

    private function getAssignAttribute($attr, $name)
    {
        $assign = '';
        if(isset($attr['assign'])) {
            $assign = ' {% set ' . $this->variable($attr['assign']) . ' = ' . $name . ' %}';
        }
        return $assign;
    }
}
