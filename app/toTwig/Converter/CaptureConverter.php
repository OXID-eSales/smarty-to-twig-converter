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

    public $smartyAppend = '[{capture append="';
    public $smartyCapture = '[{capture name="';
    public $smartyEndCapture = '[{/capture}]';
    public $smartyClosingTag = '"}]';
    public $twigSet = '{% set ';
    public $twigEndSet = '{% endset %}';
    public $twigLogicClosingTag = ' %}';

    /**
     * Returns the priority of the converter.
     *
     * The default priority is 0 and higher priorities are executed first.
     */
    public function getPriority()
    {
        return 0;
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
        $strippedOpeningTag = str_replace([$this->smartyCapture, $this->smartyClosingTag], [$this->twigSet, $this->twigLogicClosingTag], $content);
        $return = str_replace($this->smartyEndCapture, $this->twigEndSet, $strippedOpeningTag);
        return $return;
    }

    /**
     * @param $content
     * @return bool
     */
    public function detectAppend($content)
    {
        $return = false;
        if(strpos($content, $this->smartyAppend)) {
            $return = true;
        }
        return $return;
    }

    /**
     * @param $content
     * @return string
     * @throws \Exception
     */
    public function convertAppend($content)
    {
        $escapedOpeningTag = str_replace('[', '\[', $this->smartyAppend);
        $escapedClosingTag = str_replace('[', '\[', $this->smartyClosingTag);
        $regex = '~' . $escapedOpeningTag . '([^{]*)' . $escapedClosingTag . '~i';
        preg_match($regex, $content, $match);
        if(isset($match[1])) {
            $contentVariableName = $match[1];
        } else {
            throw new \Exception('It seems that template has errors');
        }
        $strippedOpeningTag = str_replace([$this->smartyAppend, $this->smartyClosingTag], [$this->twigSet, $this->twigLogicClosingTag . '{{ ' . $contentVariableName . ' }}'], $content);
        $strippedClosingTag = str_replace($this->smartyEndCapture, $this->twigEndSet, $strippedOpeningTag);
        return $strippedClosingTag;
    }

}