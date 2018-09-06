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

    // [{capture name="foo"}]
    private $pattern = '/\[{\s*capture([^{]*)\s*}]/';

    /**
     * Returns the priority of the converter.
     *
     * The default priority is 0 and higher priorities are executed first.
     */
    public function getPriority()
    {
        return 1000;
    }

    /**
     * Returns the name of the converter.
     *
     * The name must be all lowercase and without any spaces.
     *
     * @return string The name of the converter
     */
    public function getName()
    {
        return 'CaptureConverter';
    }

    /**
     * Returns the description of the converter.
     *
     * A short one-line description of what the converter does.
     *
     * @return string The description of the converter
     */
    public function getDescription()
    {
        return 'Converts Smarty Capture into Twig set';
    }

    /**
     * @param \SplFileInfo $file
     * @param string $content
     * @return mixed|string
     * @throws \Exception
     */
    public function convert(\SplFileInfo $file, $content)
    {
        if($this->detectAppend($content)) {
            $return = $this->convertAppend($content);
        } else {
            $return = $this->convertCapture($content);
        }
        return $return;
    }

    /**
     * @param $content
     * @return mixed
     */
    public function convertCapture($content)
    {
        $string = '{% set :name %}';
        $strippedOpeningTag = preg_replace_callback($this->pattern, function($matches) use ($string) {

            $match = $matches[1];
            $attr = $this->attributes($match);

            $attr['name'] = $this->variable($attr['name']);

            $string = $this->vsprintf($string, $attr);
            // Replace more than one space to single space
            $string = preg_replace('!\s+!', ' ', $string);

            return str_replace($matches[0], $string, $matches[0]);

        }, $content);

        return $this->stripClosingTag($strippedOpeningTag);
    }

    /**
     * @param $content
     * @return bool
     */
    public function detectAppend($content)
    {
        $return = false;
        if(strpos($content, '[{capture append="')) {
            $return = true;
        }
        return $return;
    }

    /**
     * @param $content
     * @return string
     */
    public function convertAppend($content)
    {
        $string = '{% set :append %}{{ :append }}';
        $strippedOpeningTag = preg_replace_callback($this->pattern, function($matches) use ($string) {

            $match = $matches[1];
            $attr = $this->attributes($match);

            $attr['append'] = $this->variable($attr['append']);

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