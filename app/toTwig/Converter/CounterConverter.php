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
    public function convert(\SplFileInfo $file, $content)
    {
        return $this->replace($content);
    }

    public function getPriority()
    {
        return 0;
    }

    public function getName()
    {
        return 'counter';
    }

    public function getDescription()
    {
        return 'Convert smarty Counter to twig';
    }

    private function replace($content)
    {
        $pattern = '/\[\{counter\b\s*([^{}]+)?\}\]/';
        $string = '{% set :name = ( :name | default(:start) ) :direction :skip %}:print:assign';

        return preg_replace_callback($pattern, function($matches) use ($string) {

            if(!isset($matches[1]) && $matches[0]) {
                $match = $matches[0];
            } else {
                $match = $matches[1];
            }

            $attr = $this->attributes($match);

            $replace['name'] = $this->getNameAttribute($attr);
            $replace['start'] = $this->getStartAttribute($attr);
            $replace['skip'] = $this->getSkipAttribute($attr);
            $replace['print'] = $this->getPrintAttribute($attr, $replace['name']);
            $replace['direction'] = $this->getDirectionAttribute($attr);
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
            $name = 'default'; //default name in Smarty
        } else {
            $name = $this->variable($attr['name']);
        }
        return $name;
    }

    private function getStartAttribute($attr)
    {
        if(!isset($attr['start'])) {
            $start = 0; //default initial number in Smarty is 1, but since we increment after 1st call, we want this to be 0
        } else {
            $start = (int)$attr['start'] - 1;
        }
        return $start;
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

    private function getPrintAttribute($attr, $name)
    {
        if(isset($attr['print']) && $attr['print'] == 'true') {
            $print = ' {{ ' . $name . ' }}';
        } else {
            $print = '';
        }
        return $print;
    }

    private function getDirectionAttribute($attr)
    {
        if(isset($attr['direction']) && $attr['direction'] == 'down') {
            $direction = '-';
        } else {
            $direction = '+';
        }
        return $direction;
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
