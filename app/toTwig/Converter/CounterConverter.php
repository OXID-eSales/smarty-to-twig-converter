<?php
/**
 * Created by PhpStorm.
 * User: jskoczek
 * Date: 29/08/18
 * Time: 12:34
 */

namespace toTwig\Converter;

/**
 * Class CounterConverter
 */
class CounterConverter extends ConverterAbstract
{

    protected $name = 'counter';
    protected $description = 'Convert smarty Counter to twig';
    protected $priority = 1000;

    /**
     * @param string $content
     *
     * @return string
     */
    public function convert(string $content): string
    {
        /**
         * $pattern is supposed to detect structure like this:
         * [{counter name="foo"}]
         **/
        // [{counter other stuff}]
        $pattern = $this->getOpeningTagPattern('counter');
        $string = '{% set :name = ( :name | default(:start) ) :direction :skip %}:print:assign';

        return preg_replace_callback(
            $pattern,
            function ($matches) use ($string) {
                /**
                 * $matches contains an array of strings.
                 *
                 * $matches[0] contains a string with full matched tag i.e.'[{counter name="foo"}]'
                 * $matches[1] should contain a string with all attributes passed to a tag i.e.'name="foo"'
                 */
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

    /**
     * @param array $attr
     *
     * @return string
     */
    private function getNameAttribute(array $attr): string
    {
        if (!isset($attr['name'])) {
            $name = 'defaultCounter'; //default name in Smarty
        } else {
            $name = $this->sanitizeVariableName($attr['name']);
        }

        return $name;
    }

    /**
     * @param array $attr
     *
     * @return string
     */
    private function getDirectionAttribute(array $attr): string
    {
        if (isset($attr['direction']) && ($attr['direction'] == 'down' || $attr['direction'] == '"down"')) {
            $direction = '-';
        } else {
            $direction = '+';
        }

        return $direction;
    }

    /**
     * @param array $attr
     *
     * @return int
     */
    private function getSkipAttribute(array $attr): int
    {
        if (!isset($attr['skip'])) {
            $skip = 1; //default interval in Smarty
        } else {
            $skip = (int) $attr['skip'];
        }

        return $skip;
    }

    /**
     * @param array  $attr
     * @param string $direction
     * @param int    $skip
     *
     * @return int
     */
    private function getStartAttribute(array $attr, string $direction, int $skip): int
    {
        if (!isset($attr['start'])) {
            $start = 0; //default initial number in Smarty is 1, but since we increment after 1st call, we want this to be 0
        } else {
            if ($direction == '+') { //if counter is to be incremented we need to subtract incrementation size from start attribute.
                $start = (int) $attr['start'] - $skip;
            } else {
                $start = (int) $attr['start'] + $skip;
            }
        }

        return $start;
    }

    /**
     * @param array  $attr
     * @param string $name
     *
     * @return string
     */
    private function getPrintAttribute(array $attr, string $name): string
    {
        if (isset($attr['print']) && $attr['print'] == 'true') {
            $print = ' {{ ' . $name . ' }}';
        } else {
            $print = '';
        }

        return $print;
    }

    /**
     * @param array  $attr
     * @param string $name
     *
     * @return string
     */
    private function getAssignAttribute(array $attr, string $name): string
    {
        $assign = '';
        if (isset($attr['assign'])) {
            $assign = ' {% set ' . $this->sanitizeVariableName($attr['assign']) . ' = ' . $name . ' %}';
        }

        return $assign;
    }
}
