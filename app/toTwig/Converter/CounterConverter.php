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

        return preg_replace_callback($pattern, function ($matches) use ($string) {

            if(!isset($matches[1]) && $matches[0]){
                $match = $matches[0];
            } else {
                $match = $matches[1];
            }

            $attr = $this->attributes($match);
            if(!isset($attr['name'])) {
                $attr['name'] = 'default'; //default name in Smarty
            } else {
                $attr['name'] = $this->variable($attr['name']);
            }
            if(!isset($attr['start'])) {
                $attr['start'] = 0; //default initial number in Smarty is 1, but since we increment after 1st call, we want this to be 0
            } else {
                $attr['start'] = (int)$attr['start'] - 1;
            }
            if(!isset($attr['skip'])) {
                $attr['skip'] = 1; //default interval in Smarty
            } else {
                $attr['skip'] = (int)$attr['skip'];
            }
            if(isset($attr['print']) && $attr['print'] == 'true') {
                $attr['print'] = ' {{ ' . $attr['name'] . ' }}';
            } else {
                $attr['print'] = '';
            }
            if(isset($attr['direction']) && $attr['direction'] == 'down') {
                $attr['direction'] = '-';
            } else {
                $attr['direction'] = '+';
            }
            if(isset($attr['assign'])) {
                $attr['assign'] = ' {% set ' . $this->variable($attr['assign']) . ' = ' . $attr['name'] . ' %}';
            }

            $replace = $attr;


            $string = $this->vsprintf($string, $replace);

            // Replace more than one space to single space
            $string = preg_replace('!\s+!', ' ', $string);

            return str_replace($matches[0], $string, $matches[0]);

        }, $content);
    }
}
