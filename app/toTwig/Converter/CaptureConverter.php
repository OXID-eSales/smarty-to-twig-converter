<?php
/**
 * Created by PhpStorm.
 * User: jskoczek
 * Date: 24/08/18
 * Time: 16:09
 */

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

/**
 * Class CaptureConverter
 */
class CaptureConverter extends ConverterAbstract
{

    protected $name = 'capture';
    protected $description = 'Converts Smarty Capture into Twig set';
    protected $priority = 100;

    /**
     * @param \SplFileInfo $file
     * @param string       $content
     *
     * @return mixed|string
     */
    public function convert(\SplFileInfo $file, string $content): string
    {
        $return = $this->replace($content);

        return $return;
    }

    /**
     * @param string $content
     *
     * @return null|string|string[]
     */
    private function replace(string $content): string
    {
        $pattern = $this->getOpeningTagPattern('capture');
        $strippedOpeningTag = preg_replace_callback(
            $pattern,
            function ($matches) {

                $match = $matches[1];
                $attr = $this->attributes($match);
                if (isset($attr['name'])) {
                    $attr['name'] = $this->variable($attr['name']);
                }

                $string = '{% set :name %}';
                if (isset($attr['append'])) {
                    $attr['append'] = $this->variable($attr['append']);
                    if (!isset($attr['name'])) {
                        $attr['name'] = $attr['append'];
                    }
                    $string .= '{{ :append }}';
                }

                $string = $this->vsprintf($string, $attr);
                // Replace more than one space to single space
                $string = preg_replace('!\s+!', ' ', $string);

                return str_replace($matches[0], $string, $matches[0]);
            },
            $content
        );

        return $this->stripClosingTag($strippedOpeningTag);
    }

    /**
     * @param string $strippedOpeningTag
     *
     * @return string
     */
    private function stripClosingTag(string $strippedOpeningTag): string
    {
        // [{/capture}]
        $pattern = '/\[{\s*\/capture([^{]*)\s*}]/';
        $string = '{% endset %}';

        return preg_replace_callback(
            $pattern,
            function ($matches) use ($string) {
                $match = $matches[1];
                $attr = $this->attributes($match);

                $string = $this->vsprintf($string, $attr);
                // Replace more than one space to single space
                $string = preg_replace('!\s+!', ' ', $string);

                return str_replace($matches[0], $string, $matches[0]);
            },
            $strippedOpeningTag
        );
    }
}
