<?php
/**
 * Created by PhpStorm.
 * User: jskoczek
 * Date: 24/08/18
 * Time: 16:09
 */

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

class CaptureConverter extends ConverterAbstract
{
    protected $name = 'CaptureConverter';
    protected $description = 'Converts Smarty Capture into Twig set';

    /**
        return 1000;
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
        // [{capture name="foo"}]
        $pattern = '/\[{\s*capture([^{]*)\s*}]/';
        $strippedOpeningTag = preg_replace_callback($pattern, function($matches) {

            $match = $matches[1];
            $attr = $this->attributes($match);

            $attr['name'] = $this->variable($attr['name']);

            $string = '{% set :name %}';
            if(isset($attr['append'])) {
                $attr['append'] = $this->variable($attr['append']);
                $string .= '{{ :append }}';
            }

            $string = $this->vsprintf($string, $attr);
            // Replace more than one space to single space
            $string = preg_replace('!\s+!', ' ', $string);

            return str_replace($matches[0], $string, $matches[0]);

        }, $content);

        return $this->stripClosingTag($strippedOpeningTag);
    }

    private function stripClosingTag($strippedOpeningTag)
    {
        // [{/capture}]
        $pattern = '/\[{\s*\/capture([^{]*)\s*}]/';
        $string = '{% endset %}';

        return preg_replace_callback($pattern, function($matches) use ($string) {

            $match = $matches[1];
            $attr = $this->attributes($match);

            $string = $this->vsprintf($string, $attr);
            // Replace more than one space to single space
            $string = preg_replace('!\s+!', ' ', $string);

            return str_replace($matches[0], $string, $matches[0]);

        }, $strippedOpeningTag);
    }

}