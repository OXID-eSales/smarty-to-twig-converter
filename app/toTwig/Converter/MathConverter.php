<?php
/**
 * Created by PhpStorm.
 * User: jskoczek
 * Date: 31/08/18
 * Time: 14:01
 */

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

class MathConverter extends ConverterAbstract
{

    /**
     * @param \SplFileInfo $file
     * @param string $content
     * @return string
     */
    public function convert(\SplFileInfo $file, $content)
    {
        return $this->replace($content);
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return 1000;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'math';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Convert smarty math to twig';
    }

    private function replace($content)
    {
        // [{math equation="x + y" x=1 y=2}]
        $pattern = '/\[\{\s*math\b\s*([^{}]+)?\s*\}\]/';
        return preg_replace_callback($pattern, function($matches) {

            $match = $this->getMatch($matches);
            $attr = $this->attributes($match);
            $vars = $attr;
            unset($vars['equation']);
            unset($vars['format']);
            if(isset($attr['format'])) {
                $vars['format_string'] = $attr['format'];
            }
            $replace['assign'] = $this->getAssignAttribute($attr);
            $string = $this->getStringForPregReplace($replace);
            $equationTemplate = $this->translateEquationTemplate($attr, $vars);
            $formattedEquation = $this->mathEquationSprintf($equationTemplate, $vars);
            $replace['equation'] = $formattedEquation;
            $string = $this->vsprintf($string, $replace);

            // Replace more than one space to single space
            $string = preg_replace('!\s+!', ' ', $string);
            return str_replace($matches[0], $string, $matches[0]);
        }, $content);
    }

    /**
     * @param $matches
     * @return string
     */
    private function getMatch($matches)
    {
        if(!isset($matches[1]) && $matches[0]) {
            $match = $matches[0];
        } else {
            $match = $matches[1];
        }
        return $match;
    }

    /**
     * Get string with pattern to be replaced by preg_replace.
     *
     * @param $replace
     * @return string
     */
    private function getStringForPregReplace($replace)
    {
        if($replace['assign']) {
            $string = '{% set :assign = :equation %}';
        } else {
            $string = '{{ :equation }}';
        }
        return $string;
    }

    /**
     * @param $attr
     * @param $vars
     * @return mixed|null|string|string[]
     */
    private function translateEquationTemplate($attr, $vars)
    {
        $equationTemplate = $this->variable($attr['equation']);
        $equationTemplate = $this->mathAllVariationsOfRoundSprintf($equationTemplate, $vars);
        $equationTemplate = $this->mathAbsSprintf($equationTemplate, $vars);
        $equationTemplate = $this->mathPowSprintf($equationTemplate, $vars);
        $equationTemplate = $this->mathRandSprintf($equationTemplate, $vars);
        if(isset($attr['format'])) {
            $equationTemplate = $this->mathFormatSprintf($equationTemplate, $vars);
        }
        return $equationTemplate;
    }

    /**
     * Replace named args in string
     *
     * @param  string $string
     * @param  array $args
     * @return string Formated string
     */
    protected function mathEquationSprintf($string, $args)
    {
        $pattern = '/([a-zA-Z0-9]+)/';
        return preg_replace_callback($pattern, function($matches) use ($args) {
            if(isset($args[$matches[1]])) {
                $replace = $this->value($args[$matches[1]]);
                return str_replace($matches[0], $replace, $matches[0]);
            } else {
                return $matches[0];
            }
        }, $string);
    }

    /**
     * Replace variations of round. Order in which functions are called is important.
     *
     * @param $equationTemplate
     * @param $vars
     * @return string Formated string
     */
    protected function mathAllVariationsOfRoundSprintf($equationTemplate, $vars)
    {
        $equationTemplate = $this->mathRoundSprintf($equationTemplate, $vars);
        $equationTemplate = $this->mathCeilSprintf($equationTemplate, $vars);
        $equationTemplate = $this->mathFloorSprintf($equationTemplate, $vars);
        return $equationTemplate;
    }

    /**
     * Replace abs function in equation
     *
     * @param $string
     * @param $args
     * @return null|string|string[]
     */
    protected function mathAbsSprintf($string, $args)
    {
        // [{math equation="abs(x)" x=-1}]
        $pattern = '/abs\(\b\s*([^{}]+)?\)/';
        return preg_replace_callback($pattern, function($matches) use ($args) {
            if(isset($matches[1])) {
                return str_replace($matches[0], '(' . $matches[1] . ') | abs', $matches[0]);
            }
        }, $string);
    }

    /**
     * Replace ceil function in equation
     *
     * @param $string
     * @param $args
     * @return null|string|string[]
     */
    protected function mathCeilSprintf($string, $args)
    {
        // [{math equation="ceil(x)" x=3.4}]
        $pattern = '/ceil\(\b\s*([^{}]+)?\)/';
        return preg_replace_callback($pattern, function($matches) use ($args) {
            if(isset($matches[1])) {
                return str_replace($matches[0], '(' . $matches[1] . ') | round(0, \'ceil\')', $matches[0]);
            }
        }, $string);
    }

    /**
     * Replace floor function in equation
     *
     * @param $string
     * @param $args
     * @return null|string|string[]
     */
    protected function mathFloorSprintf($string, $args)
    {
        // [{math equation="floor(x)" x=3.4}]
        $pattern = '/floor\(\b\s*([^{}]+)?\)/';
        return preg_replace_callback($pattern, function($matches) use ($args) {
            if(isset($matches[1])) {
                return str_replace($matches[0], '(' . $matches[1] . ') | round(0, \'floor\')', $matches[0]);
            }
        }, $string);
    }

    /**
     * Replace pow function in equation
     *
     * @param $string
     * @param $args
     * @return null|string|string[]
     */
    protected function mathPowSprintf($string, $args)
    {
        // [{math equation="pow(x,y)" x=2 y=3}]
        $pattern = '/pow\(\b\s*([^{)}]+)?\)/';
        return preg_replace_callback($pattern, function($matches) use ($args) {
            if(isset($matches[1])) {
                return str_replace($matches[0], 'pow(' . $matches[1] . ')', $matches[0]);
            }
        }, $string);
    }

    /**
     * Replace rand function in equation
     *
     * @param $string
     * @param $args
     * @return null|string|string[]
     */
    protected function mathRandSprintf($string, $args)
    {
        //[{math equation="rand(x, y)" x=2 y=3}]
        $pattern = '/rand\(\b\s*([^{)}]+)?\)/';
        return preg_replace_callback($pattern, function($matches) use ($args) {
            if(isset($matches[1])) {
                return str_replace($matches[0], 'random([' . $matches[1] . '])', $matches[0]);
            }
        }, $string);
    }

    /**
     * Replace round function in equation
     *
     * @param $string
     * @param $args
     * @return null|string|string[]
     */
    protected function mathRoundSprintf($string, $args)
    {
        //[{math equation="round(x)" x=3.4}]
        $pattern = '/round\(\b\s*([^{}]+)?\)/';
        return preg_replace_callback($pattern, function($matches) use ($args) {
            if(isset($matches[1])) {
                return str_replace($matches[0], '(' . $matches[1] . ') | round', $matches[0]);
            }
        }, $string);
    }

    /**
     * Replace format function in equation
     *
     * @param $string
     * @param $args
     * @return null|string|string[]
     */
    protected function mathFormatSprintf($string, $args)
    {
        // [{math equation="x + y" x=1 y=2 format="%.2f"}]
        $pattern = '/format\(\b\s*([^{}]+)?\)/';
        $string .= ' | format(format_string)';
        return preg_replace_callback($pattern, function($matches) use ($args) {
            if(isset($matches[1])) {
                return str_replace($matches[1], $args[$matches[1]], $matches[0]);
            }
        }, $string);
    }

    /**
     * @param $attr
     * @param $name
     * @return string
     */
    private function getAssignAttribute($attr)
    {
        $assign = '';
        if(isset($attr['assign'])) {
            $assign = $this->variable($attr['assign']);
        }
        return $assign;
    }

}