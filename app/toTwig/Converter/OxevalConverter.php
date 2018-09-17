<?php
/**
 * Created by PhpStorm.
 * User: jskoczek
 * Date: 17/09/18
 * Time: 16:09
 */

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

class OxevalConverter extends ConverterAbstract
{
    protected $name = 'OxevalConverter';
    protected $description = 'Converts oxeval function into Twig\'s template_from_string';
    protected $priority = 1000;

    /**
     * @param \SplFileInfo $file
     * @param string $content
     * @return mixed|string
     */
    public function convert(\SplFileInfo $file, $content)
    {
        $return = $this->replace($content);
        return $return;
    }

    /**
     * @param $content
     * @return null|string|string[]
     */
    private function replace($content)
    {
        //[{oxeval var="foo"}]
        $pattern = $this->getOpeningTagPattern('oxeval');
        return preg_replace_callback($pattern, function($matches) {

            $match = $matches[1];
            $attr = $this->attributes($match);

            var_dump($attr);

            $attr['var'] = $this->variable($attr['var']);
            $string = '{{ include(template_from_string(:var)) }}';
            $string = $this->vsprintf($string, $attr);
            // Replace more than one space to single space
            $string = preg_replace('!\s+!', ' ', $string);

            return str_replace($matches[0], $string, $matches[0]);

        }, $content);
    }

}